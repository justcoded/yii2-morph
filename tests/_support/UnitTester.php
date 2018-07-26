<?php

use Faker\Factory;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

    /**
     * @var \Faker\Factory
     */
    public $faker;

    /**
     * @var \Tightenco\Collect\Support\Collection
     */
    public $users;

    /**
     * @var \Tightenco\Collect\Support\Collection
     */
    public $companies;

    /**
     * Get Fixture Data
     *
     * @param $name
     * @return \Tightenco\Collect\Support\Collection
     */
    public function getFixtureData($name)
    {
        return collect($this->grabFixture($name)->data)->values();
    }

    /**
     * Set Properties
     *
     */
    public function setCommonProperties()
    {
        $this->faker = Factory::create();
        $this->users = $this->getFixtureData('user');
        $this->companies = $this->getFixtureData('company');
    }
}
