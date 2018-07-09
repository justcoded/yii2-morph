<?php

namespace tests\unit;

use app\fixtures\AllFixture;
use Faker\Factory;
use Yii;
use app\models\User;
use app\models\Company;
use app\models\Comment;

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

		$user->getComments();
		expect(1)->equals(1);

        $company = Company::findOne($this->validCompanies['company0']['id']);

        $company->getComments();
        expect(1)->equals(1);
	}

    /**
     * Test Link
     *
     */
    public function testLink()
    {
        $user = User::findOne($this->validUsers['user0']['id']);
        $comment = new Comment();
        $comment->commentable_id = $user->id;
        $comment->body = $this->faker->text;
        $user->link('comments', $comment);
        expect(1)->equals(1);
    }

    /**
     * Test Unlink
     *
     */
    public function testUnlink()
    {
        foreach ($this->validUsers as $item) {
            if (($user = User::findOne($item['id'])) && ($comment = $user->getComments()->one())) {
                $user->unlink('comments', $comment, true);
                expect(1)->equals(1);
                break;
            }
        }

        foreach ($this->validCompanies as $item) {
            if (($company = Company::findOne($item['id'])) && ($comment = $user->getComments()->one())) {
                $company->unlink('comments', $comment, true);
                expect(1)->equals(1);
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

        $user->unlinkAll('comments',true);
        expect(1)->equals(1);

        $company = Company::findOne($this->validCompanies['company3']['id']);

        $company->unlinkAll('comments',true);
        expect(1)->equals(1);
    }
}
