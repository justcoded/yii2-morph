<?php

namespace tests\unit;

use app\fixtures\CompanyFixture;
use app\fixtures\UserFixture;
use app\fixtures\MediableFixture;
use app\fixtures\MediaFixture;
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
        return [
            'user' => UserFixture::class,
            'company' => CompanyFixture::class,
            'media' => MediaFixture::class,
            'mediable' => MediableFixture::class
        ];
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

        $userMediaThumbnails = Media::find()
            ->leftJoin('mediable', 'mediable.media_id = media.id')
            ->andWhere(
                [
                    'mediable_id' => $user->id,
                    'mediable_type' => User::class,
                    'mediable.type' => Media::TYPE_THUMBNAIL
                ]
            )
            ->all();
        $userMediaThumbnailsTest = $user->getThumbnail()->all();
        $this->assertEquals($userMediaThumbnails, $userMediaThumbnailsTest);

        $userMediaGallery = Media::find()
            ->leftJoin('mediable', 'mediable.media_id = media.id')
            ->andWhere(
                [
                    'mediable_id' => $user->id,
                    'mediable_type' => User::class,
                    'mediable.type' => Media::TYPE_GALLERY
                ]
            )
            ->all();
        $userMediaGalleryTest = $user->getGallery()->all();
        $this->assertEquals($userMediaGallery, $userMediaGalleryTest);

        $company = Company::findOne($this->validCompanies['company0']['id']);

        $companyMediaThumbnails = Media::find()
            ->leftJoin('mediable', 'mediable.media_id = media.id')
            ->andWhere(
                [
                    'mediable_id' => $company->id,
                    'mediable_type' => Company::class,
                    'mediable.type' => Media::TYPE_THUMBNAIL
                ]
            )
            ->all();
        $companyMediaThumbnailsTest = $company->getThumbnail()->all();
        $this->assertEquals($companyMediaThumbnails, $companyMediaThumbnailsTest);

        $company = Company::findOne($this->validCompanies['company0']['id']);

        $companyMediaGallery = Media::find()
            ->leftJoin('mediable', 'mediable.media_id = media.id')
            ->andWhere(
                [
                    'mediable_id' => $company->id,
                    'mediable_type' => Company::class,
                    'mediable.type' => Media::TYPE_GALLERY
                ]
            )
            ->all();
        $companyMediaGalleryTest = $company->getThumbnail()->all();
        $this->assertEquals($companyMediaGallery, $companyMediaGalleryTest);
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
