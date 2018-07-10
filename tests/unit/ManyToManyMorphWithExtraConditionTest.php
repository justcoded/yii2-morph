<?php

namespace tests\unit;

use app\fixtures\AllFixture;
use app\models\Company;
use app\models\Media;
use app\models\User;
use Faker\Factory;

/**
 * Class ManyToManyMorphWithExtraConditionTest
 * @package tests\unit
 */
class ManyToManyMorphWithExtraConditionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Faker\Factory
     */
    protected $faker;

    /**
     * @var array
     */
    private $validUsers;

    /**
     * @var array
     */
    private $validCompanies;

    /**
     * _fixtures
     *
     * @return array
     */
    public function _fixtures()
    {
        $all = new AllFixture();
        return $all->depends;
    }

    /**
     * _before
     *
     */
    public function _before()
    {
        $this->faker = Factory::create();
        $this->validUsers = $this->tester->grabFixture('user')->data;
        $this->validCompanies = $this->tester->grabFixture('company')->data;
    }

    /**
     * Test Get Data
     *
     */
    public function testGetData()
    {
        $user = User::findOne($this->validUsers['user0']['id']);

        $user->getThumbnail()->all();
        expect(1)->equals(1);

        $user->getGallery()->all();
        expect(1)->equals(1);

        $company = Company::findOne($this->validCompanies['company0']['id']);

        $company->getThumbnail()->all();
        expect(1)->equals(1);

        $company->getGallery()->all();
        expect(1)->equals(1);
    }

    /**
     * Test Link
     *
     */
    public function testLink()
    {
        $user = User::findOne($this->validUsers['user0']['id']);
        $count = $user->getGallery()->count();
        $media = new Media();
        $media->type = $this->faker->randomElement(['image', 'video', 'file']);
        $media->file = 'tmp/' . $this->faker->md5 . '.' . $this->faker->fileExtension;
        $media->thumb = $this->faker->imageUrl($this->faker->numberBetween(600, 1200), $this->faker->numberBetween(400, 800));

        $this->assertTrue($media->save());
        $this->assertNull($user->link('gallery', $media, ['media_id' => $media->id]));
        $this->tester->seeInDatabase('mediable', [
            'media_id' => $media->id,
            'mediable_id' => $user->id,
            'mediable_type' => User::class,
            'type' => 'gallery'
        ]);
        $this->assertEquals($count + 1, $user->getGallery()->count());

        $company = Company::findOne($this->validCompanies['company0']['id']);
        $count = $company->getGallery()->count();
        $media = new Media();
        $media->type = $this->faker->randomElement(['image', 'video', 'file']);
        $media->file = 'tmp/' . $this->faker->md5 . '.' . $this->faker->fileExtension;
        $media->thumb = $this->faker->imageUrl($this->faker->numberBetween(600, 1200), $this->faker->numberBetween(400, 800));
        $this->assertTrue($media->save());
        $this->assertNull($company->link('thumbnail', $media, ['media_id' => $media->id]));
        $this->tester->seeInDatabase('mediable', [
            'media_id' => $media->id,
            'mediable_id' => $company->id,
            'mediable_type' => Company::class,
            'type' => 'thumbnail'
        ]);
        $this->assertEquals($count + 1, $company->getThumbnail()->count());
    }

    /**
     * Test Unlink
     *
     */
    public function testUnlink()
    {
        foreach ($this->validUsers as $item) {
            if (($user = User::findOne($item['id'])) && ($media = $user->getThumbnail()->one())) {
                $count = $user->getThumbnail()->count();
                $this->assertNull($user->unlink('thumbnail', $media, true));
                $this->tester->dontSeeInDatabase('mediable', [
                    'media_id' => $media->id,
                    'mediable_id' => $user->id,
                    'mediable_type' => User::class,
                    'type' => 'thumbnail'
                ]);
                $this->assertEquals($count ? $count - 1 : $count, $user->getThumbnail()->count());
                break;
            }
        }

        foreach ($this->validCompanies as $item) {
            if (($company = Company::findOne($item['id'])) && ($media = $company->getGallery()->one())) {
                $count = $user->getGallery()->count();
                $this->assertNull($company->unlink('gallery', $media, true));
                $this->tester->dontSeeInDatabase('mediable', [
                    'media_id' => $media->id,
                    'mediable_id' => $company->id,
                    'mediable_type' => Company::class,
                    'type' => 'gallery'
                ]);
                $this->assertEquals($count ? $count - 1 : $count, $company->getGallery()->count());
                break;
            }
        }
    }

    /**
     * Test UnlinkAll
     *
     */
    public function testUnlinkAll()
    {
        $user = User::findOne($this->validUsers['user4']['id']);

        $this->assertNull($user->unlinkAll('gallery', true));
        $this->tester->dontSeeInDatabase('mediable', [
            'mediable_id' => $user->id,
            'mediable_type' => User::class,
            'type' => 'gallery'
        ]);
        $this->assertEquals(0, $user->getGallery()->count());

        $this->assertNull($user->unlinkAll('thumbnail', true));
        $this->tester->dontSeeInDatabase('mediable', [
            'mediable_id' => $user->id,
            'mediable_type' => User::class,
            'type' => 'thumbnail'
        ]);
        $this->assertEquals(0, $user->getThumbnail()->count());

        $company = Company::findOne($this->validCompanies['company4']['id']);

        $this->assertNull($company->unlinkAll('gallery', true));
        $this->tester->dontSeeInDatabase('mediable', [
            'mediable_id' => $company->id,
            'mediable_type' => Company::class,
            'type' => 'gallery'
        ]);
        $this->assertEquals(0, $company->getGallery()->count());

        $this->assertNull($company->unlinkAll('thumbnail', true));
        $this->tester->dontSeeInDatabase('mediable', [
            'mediable_id' => $company->id,
            'mediable_type' => Company::class,
            'type' => 'gallery'
        ]);
        $this->assertEquals(0, $company->getThumbnail()->count());
    }
}
