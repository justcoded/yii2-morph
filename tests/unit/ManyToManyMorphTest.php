<?php

namespace tests\unit;

use app\fixtures\CompanyFixture;
use app\fixtures\UserFixture;
use app\fixtures\TagFixture;
use app\fixtures\TaggableFixture;
use app\models\Company;
use app\models\Tag;
use app\models\User;

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
     * @var \Tightenco\Collect\Support\Collection
     */
    private $tags;

    /**
     * @var \Tightenco\Collect\Support\Collection
     */
    private $taggables;

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
            'tag' => TagFixture::class,
            'taggable' => TaggableFixture::class
        ];
    }

    /**
     * _before
     *
     */
    protected function _before()
    {
        $this->tester->setCommonProperties();
        $this->taggables = $this->tester->getFixtureData('taggable');
        $this->tags = $this->tester->getFixtureData('tag');
    }

    /**
     * Test Get Data
     *
     */
    public function testGetData()
    {
        $user = User::findOne($this->tester->users->first()['id']);
        $userTaggables = $this->taggables
            ->where('taggable_id', $user->id)
            ->where('taggable_type', User::class)
            ->keyBy('tag_id')
            ->keys();
        $userTags = $this->tags
            ->whereIn('id', $userTaggables)
            ->values()
            ->all();
        $userTagsTest = $user->getTags()->asArray()->all();
        $this->assertEquals($userTags, $userTagsTest);

        $company = Company::findOne($this->tester->companies->first()['id']);
        $companyTaggables = $this->taggables
            ->where('taggable_id', $company->id)
            ->where('taggable_type', Company::class)
            ->keyBy('tag_id')
            ->keys();
        $companyTags = $this->tags
            ->whereIn('id', $companyTaggables)
            ->values()
            ->all();
        $companyTagsTest = $company->getTags()->asArray()->all();
        $this->assertEquals($companyTags, $companyTagsTest);
    }

    /**
     * Test Link
     *
     */
    public function testLink()
    {
        $user = User::findOne($this->tester->users->first()['id']);
        $count = $user->getTags()->count();
        $tag = new Tag();
        $tag->name = $this->tester->faker->word;
        $this->assertTrue($tag->save());
        $this->assertNull($user->link('tags', $tag, ['tag_id' => $tag->id]));
        $this->tester->seeInDatabase(
            'taggable', [
                'tag_id' => $tag->id,
                'taggable_id' => $user->id,
                'taggable_type' => User::class,
            ]
        );
        $this->assertEquals($count + 1, $user->getTags()->count());

        $company = Company::findOne($this->tester->companies->first()['id']);
        $count = $company->getTags()->count();
        $tag = new Tag();
        $tag->name = $this->tester->faker->word;
        $this->assertTrue($tag->save());
        $this->assertNull($company->link('tags', $tag, ['tag_id' => $tag->id]));
        $this->tester->seeInDatabase(
            'taggable', [
                'tag_id' => $tag->id,
                'taggable_id' => $company->id,
                'taggable_type' => Company::class,
            ]
        );
        $this->assertEquals($count + 1, $company->getTags()->count());
    }

    /**
     * Test Unlink
     *
     */
    public function testUnlink()
    {
        foreach ($this->tester->users as $item) {
            if (($user = User::findOne($item['id'])) && ($tag = $user->getTags()->one())) {
                $count = $user->getTags()->count();
                $this->assertNull($user->unlink('tags', $tag, true));
                $this->tester->dontSeeInDatabase(
                    'taggable', [
                    'tag_id' => $tag->id,
                    'taggable_id' => $user->id,
                    'taggable_type' => User::class
                ]);
                $this->assertEquals($count ? $count - 1 : $count, $user->getTags()->count());
                break;
            }
        }

        foreach ($this->tester->companies as $item) {
            if (($company = Company::findOne($item['id'])) && ($tag = $company->getTags()->one())) {
                $count = $company->getTags()->count();
                $this->assertNull($company->unlink('tags', $tag, true));
                $this->tester->dontSeeInDatabase(
                    'taggable', [
                        'tag_id' => $tag->id,
                        'taggable_id' => $company->id,
                        'taggable_type' => Company::class
                    ]
                );
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
        $user = User::findOne($this->tester->users->firstWhere('id', '4')['id']);

        $this->assertNull($user->unlinkAll('tags', true));
        $this->tester->dontSeeInDatabase(
            'taggable', [
                'taggable_id' => $user->id,
                'taggable_type' => User::class,
            ]
        );
        $this->assertEquals(0, $user->getTags()->count());

        $company = Company::findOne($this->tester->companies->firstWhere('id', '4')['id']);

        $this->assertNull($company->unlinkAll('tags', true));
        $this->tester->dontSeeInDatabase(
            'taggable',
            [
                'taggable_id' => $company->id,
                'taggable_type' => Company::class,
            ]
        );
        $this->assertEquals(0, $company->getTags()->count());
    }
}
