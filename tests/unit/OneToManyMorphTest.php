<?php

namespace tests\unit;

use app\fixtures\CommentFixture;
use app\fixtures\UserFixture;
use app\fixtures\CompanyFixture;
use app\models\Comment;
use app\models\Company;
use app\models\User;

/**
 * Class OneToManyMorphTest
 * @package tests\unit
 */
class OneToManyMorphTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Tightenco\Collect\Support\Collection
     */
    private $comments;

    /**
     * _fixtures
     *ß
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => UserFixture::class,
            'company' => CompanyFixture::class,
            'comment' => CommentFixture::class
        ];
    }

    /**
     * _before
     *
     */
    protected function _before()
    {
        $this->tester->setCommonProperties();
        $this->comments = $this->tester->getFixtureData('comment');
    }

    /**
     * Test Get Data
     *
     */
    public function testGetData()
    {
        $user = User::findOne($this->tester->users->first()['id']);
        $userComments = $this->comments
            ->where('commentable_id', $user->id)
            ->where('commentable_type', User::class)
            ->values()
            ->all();
        $userCommentsTest = $user->getComments()->asArray()->all();
        $this->assertEquals($userComments, $userCommentsTest);

        $company = Company::findOne($this->tester->companies->first()['id']);
        $companyComments = $this->comments
            ->where('commentable_id', $company->id)
            ->where('commentable_type', Company::class)
            ->values()
            ->all();
        $companyCommentsTest = $company->getComments()->asArray()->all();
        $this->assertEquals($companyComments, $companyCommentsTest);
    }

    /**
     * Test Link
     *
     */
    public function testLink()
    {
        $user = User::findOne($this->tester->users->first()['id']);
        $count = $user->getComments()->count();
        $comment = new Comment();
        $comment->body = $this->tester->faker->text;
        $this->assertNull($user->link('comments', $comment));
        $this->tester->seeInDatabase(
            'comment', [
                'commentable_id' => $user->id,
                'commentable_type' => User::class
            ]
        );
        $this->assertEquals($count + 1, $user->getComments()->count());

        $company = Company::findOne($this->tester->companies->first()['id']);
        $count = $company->getComments()->count();
        $comment = new Comment();
        $comment->body = $this->tester->faker->text;
        $this->assertNull($company->link('comments', $comment));
        $this->tester->seeInDatabase(
            'comment', [
                'commentable_id' => $company->id,
                'commentable_type' => Company::class
            ]
        );
        $this->assertEquals($count + 1, $company->getComments()->count());
    }

    /**
     * Test Unlink
     *
     */
    public function testUnlink()
    {
        foreach ($this->tester->users as $item) {
            if (($user = User::findOne($item['id'])) && ($comment = $user->getComments()->one())) {
                $count = $user->getComments()->count();
                $this->assertNull($user->unlink('comments', $comment, true));
                $this->tester->dontSeeInDatabase(
                    'comment', [
                        'id' => $comment->id
                    ]
                );
                $this->assertEquals($count ? $count - 1 : $count, $user->getComments()->count());
                break;
            }
        }

        foreach ($this->tester->companies as $item) {
            if (($company = Company::findOne($item['id'])) && ($comment = $company->getComments()->one())) {
                $count = $company->getComments()->count();
                $this->assertNull($company->unlink('comments', $comment, true));
                $this->tester->dontSeeInDatabase(
                    'comment', [
                        'id' => $comment->id
                    ]
                );
                $this->assertEquals($count ? $count - 1 : $count, $company->getComments()->count());
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
        $user = User::findOne($this->tester->users->firstWhere('id', '3')['id']);

        $this->assertNull($user->unlinkAll('comments', true));
        $this->tester->dontSeeInDatabase(
            'comment', [
                'commentable_id' => $user->id,
                'commentable_type' => User::class
            ]
        );
        $this->assertEquals(0, $user->getComments()->count());

        $company = Company::findOne($this->tester->companies->firstWhere('id', '3')['id']);

        $this->assertNull($this->assertNull($company->unlinkAll('comments', true)));
        $this->tester->dontSeeInDatabase(
            'comment',
            [
                'commentable_id' => $company->id,
                'commentable_type' => Company::class
            ]
        );
        $this->assertEquals(0, $company->getComments()->count());
    }
}
