<?php

namespace tests\unit;

use app\fixtures\AllFixture;
use app\fixtures\UserFixture;
use Yii;
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
	 * @var array
	 */
	private $validUsers;

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
		$this->validUsers = $this->tester->grabFixture('user')->data;
	}

	/**
	 * Test Post Morph
	 *
	 */
	public function testGetData()
	{
		$user = User::findOne($this->validUsers['user0']['id']);

		$user->getComments();
		expect(1)->equals(1);
	}
}
