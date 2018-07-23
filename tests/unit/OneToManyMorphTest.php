<?php

namespace tests\unit;

use app\fixtures\AllFixture;
use app\fixtures\CommentFixture;
use app\fixtures\UserFixture;
use app\fixtures\CompanyFixture;
use app\models\Comment;
use app\models\Company;
use app\models\Media;
use app\models\User;
use Faker\Factory;
use yii\collection\Collection;

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
            'comment' => CommentFixture::class
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
        $userComments = Comment::find()
            ->andWhere(['commentable_id' => $user->id, 'commentable_type' => User::class])
            ->all();
        $userCommentsTest = $user->getComments()->all();
        $this->assertEquals($userComments, $userCommentsTest);

        $company = Company::findOne($this->validCompanies['company0']['id']);
        $companyComments = Comment::find()
            ->andWhere(['commentable_id' => $company->id, 'commentable_type' => Company::class])
            ->all();
        $companyCommentsTest = $company->getComments()->all();
        $this->assertEquals($companyComments, $companyCommentsTest);
    }

    /**
     * Test Link
     *
     */
    public function testLink()
    {
        $user = User::findOne($this->validUsers['user0']['id']);
        $count = $user->getComments()->count();
        $comment = new Comment();
        $comment->body = $this->faker->text;
        $this->assertNull($user->link('comments', $comment));
        $this->tester->seeInDatabase('comment', [
            'commentable_id' => $user->id,
            'commentable_type' => User::class,
        ]);
        $this->assertEquals($count + 1, $user->getComments()->count());

        $company = Company::findOne($this->validCompanies['company0']['id']);
        $count = $company->getComments()->count();
        $comment = new Comment();
        $comment->body = $this->faker->text;
        $this->assertNull($company->link('comments', $comment));
        $this->tester->seeInDatabase('comment', [
            'commentable_id' => $company->id,
            'commentable_type' => Company::class,
        ]);
        $this->assertEquals($count + 1, $company->getComments()->count());
    }

    /**
     * Test Unlink
     *
     */
    public function testUnlink()
    {
        foreach ($this->validUsers as $item) {
            if (($user = User::findOne($item['id'])) && ($comment = $user->getComments()->one())) {
                $count = $user->getComments()->count();
                $this->assertNull($user->unlink('comments', $comment, true));
                $this->tester->dontSeeInDatabase('comment', [
                    'id' => $comment->id
                ]);
                $this->assertEquals($count ? $count - 1 : $count, $user->getComments()->count());
                break;
            }
        }

        foreach ($this->validCompanies as $item) {
            if (($company = Company::findOne($item['id'])) && ($comment = $company->getComments()->one())) {
                $count = $company->getComments()->count();
                $this->assertNull($company->unlink('comments', $comment, true));
                $this->tester->dontSeeInDatabase('comment', [
                    'id' => $comment->id
                ]);
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
        $user = User::findOne($this->validUsers['user3']['id']);

        $this->assertNull($user->unlinkAll('comments', true));
        $this->tester->dontSeeInDatabase('comment', [
            'commentable_id' => $user->id,
            'commentable_type' => User::class,
        ]);
        $this->assertEquals(0, $user->getComments()->count());

        $company = Company::findOne($this->validCompanies['company3']['id']);

        $this->assertNull($this->assertNull($company->unlinkAll('comments', true)));
        $this->tester->dontSeeInDatabase('comment', [
            'commentable_id' => $company->id,
            'commentable_type' => Company::class,
        ]);
        $this->assertEquals(0, $company->getComments()->count());
    }
}
