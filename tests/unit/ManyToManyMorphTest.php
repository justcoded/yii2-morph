<?php

namespace tests\unit;

use app\fixtures\AllFixture;
use app\models\Address;
use app\models\Company;
use app\models\Media;
use app\models\Tag;
use app\models\User;
use Faker\Factory;

/**
 * Class ManyToManyMorphTest
 * @package tests\unit
 */
class ManyToManyMorphTest extends \Codeception\Test\Unit
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

        $user->getTags()->all();
        expect(1)->equals(1);

        $company = Company::findOne($this->validCompanies['company0']['id']);

        $company->getTags()->all();
        expect(1)->equals(1);
    }

    /**
     * Test Link
     *
     */
    public function testLink()
    {
        $user = User::findOne($this->validUsers['user0']['id']);
        $count = $user->getTags()->count();
        $tag = new Tag();
        $tag->name = $this->faker->word;
        $this->assertTrue($tag->save());
        $this->assertNull($user->link('tags', $tag, ['tag_id' => $tag->id]));
        $this->tester->seeInDatabase('taggable', [
            'tag_id' => $tag->id,
            'taggable_id' => $user->id,
            'taggable_type' => User::class,
        ]);
        $this->assertEquals($count + 1, $user->getTags()->count());

        $company = Company::findOne($this->validCompanies['company0']['id']);
        $count = $company->getTags()->count();
        $tag = new Tag();
        $tag->name = $this->faker->word;
        $this->assertTrue($tag->save());
        $this->assertNull($company->link('tags', $tag, ['tag_id' => $tag->id]));
        $this->tester->seeInDatabase('taggable', [
            'tag_id' => $tag->id,
            'taggable_id' => $company->id,
            'taggable_type' => Company::class,
        ]);
        $this->assertEquals($count + 1, $company->getTags()->count());
    }

    /**
     * Test Unlink
     *
     */
    public function testUnlink()
    {
        foreach ($this->validUsers as $item) {
            if (($user = User::findOne($item['id'])) && ($tag = $user->getTags()->one())) {
                $count = $user->getTags()->count();
                $this->assertNull($user->unlink('tags', $tag, true));
                $this->tester->dontSeeInDatabase('taggable', [
                    'tag_id' => $tag->id,
                    'taggable_id' => $user->id,
                    'taggable_type' => User::class
                ]);
                $this->assertEquals($count ? $count - 1 : $count, $user->getTags()->count());
                break;
            }
        }

        foreach ($this->validCompanies as $item) {
            if (($company = Company::findOne($item['id'])) && ($tag = $company->getTags()->one())) {
                $count = $company->getTags()->count();
                $this->assertNull($company->unlink('tags', $tag, true));
                $this->tester->dontSeeInDatabase('taggable', [
                    'tag_id' => $tag->id,
                    'taggable_id' => $company->id,
                    'taggable_type' => Company::class
                ]);
                $this->assertEquals($count ? $count - 1 : $count, $company->getTags()->count());
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

        $this->assertNull($user->unlinkAll('tags', true));
        $this->tester->dontSeeInDatabase('taggable', [
            'taggable_id' => $user->id,
            'taggable_type' => User::class,
        ]);
        $this->assertEquals(0, $user->getTags()->count());

        $company = Company::findOne($this->validCompanies['company4']['id']);

        $this->assertNull($company->unlinkAll('tags', true));
        $this->tester->dontSeeInDatabase('taggable', [
            'taggable_id' => $company->id,
            'taggable_type' => Company::class,
        ]);
        $this->assertEquals(0, $company->getTags()->count());
    }
}
