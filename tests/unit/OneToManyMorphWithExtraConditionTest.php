<?php

namespace tests\unit;

use app\fixtures\AddressFixture;
use app\fixtures\CompanyFixture;
use app\fixtures\UserFixture;
use app\models\Address;
use app\models\Company;
use app\models\User;
use Faker\Factory;

/**
 * Class OneToManyMorphWithExtraConditionTest
 * @package tests\unit
 */
class OneToManyMorphWithExtraConditionTest extends \Codeception\Test\Unit
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
            'address' => AddressFixture::class,
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
        $userBillingAddresses = Address::find()
            ->andWhere(
                [
                    'addressable_id' => $user->id,
                    'addressable_type' => User::class,
                    'type' => Address::TYPE_BILLING
                ]
            )
            ->all();
        $userBillingAddressesTest = $user->getBillingAddresses()->all();
        $this->assertEquals($userBillingAddresses, $userBillingAddressesTest);


        $company = Company::findOne($this->validCompanies['company0']['id']);
        $companyShippingAddresses = Address::find()
            ->andWhere(
                [
                    'addressable_id' => $company->id,
                    'addressable_type' => Company::class,
                    'type' => Address::TYPE_SHIPPING
                ]
            )
            ->all();
        $companyShippingAddressesTest = $company->getShippingAddresses()->all();
        $this->assertEquals($companyShippingAddresses, $companyShippingAddressesTest);
    }

    /**
     * Test Link
     *
     */
    public function testLink()
    {
        $user = User::findOne($this->validUsers['user0']['id']);
        $count = $user->getShippingAddresses()->count();
        $address = new Address();
        $address->addressable_id = $user->id;
        $address->city = $this->faker->city;
        $address->street = $this->faker->streetAddress;
        $this->assertNull($user->link('shippingAddresses', $address));
        $this->tester->seeInDatabase(
            'address', [
                'addressable_id' => $user->id,
                'addressable_type' => User::class,
                'type' => 'shipping'
            ]
        );
        $this->assertEquals($count + 1, $user->getShippingAddresses()->count());

        $company = Company::findOne($this->validCompanies['company0']['id']);
        $count = $company->getBillingAddresses()->count();
        $address = new Address();
        $address->addressable_id = $company->id;
        $address->city = $this->faker->city;
        $address->street = $this->faker->streetAddress;
        $this->assertNull($company->link('billingAddresses', $address));
        $this->tester->seeInDatabase(
            'address', [
                'addressable_id' => $company->id,
                'addressable_type' => Company::class,
                'type' => 'billing'
            ]
        );
        $this->assertEquals($count + 1, $company->getBillingAddresses()->count());
    }

    /**
     * Test Unlink
     *
     */
    public function testUnlink()
    {
        foreach ($this->validUsers as $item) {
            if (($user = User::findOne($item['id'])) && ($address = $user->getShippingAddresses()->one())) {
                $count = $user->getShippingAddresses()->count();
                $this->assertNull($user->unlink('shippingAddresses', $address, true));
                $this->tester->dontSeeInDatabase(
                    'address', [
                    'id' => $address->id
                    ]
                );
                $this->assertEquals($count ? $count - 1 : $count, $user->getShippingAddresses()->count());
                break;
            }
        }

        foreach ($this->validCompanies as $item) {
            if (($company = Company::findOne($item['id'])) && ($address = $company->getBillingAddresses()->one())) {
                $count = $company->getBillingAddresses()->count();
                $this->assertNull($company->unlink('billingAddresses', $address, true));
                $this->tester->dontSeeInDatabase(
                    'address', [
                        'id' => $address->id
                    ]
                );
                $this->assertEquals($count ? $count - 1 : $count, $company->getBillingAddresses()->count());
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
        $this->assertNull($user->unlinkAll('billingAddresses', true));
        $this->tester->dontSeeInDatabase(
            'address', [
                'addressable_id' => $user->id,
                'addressable_type' => User::class,
                'type' => 'billing'
            ]
        );
        $this->assertEquals(0, $user->getBillingAddresses()->count());

        $this->assertNull($user->unlinkAll('shippingAddresses', true));
        $this->tester->dontSeeInDatabase(
            'address', [
                'addressable_id' => $user->id,
                'addressable_type' => User::class,
                'type' => 'shipping'
            ]
        );
        $this->assertEquals(0, $user->getShippingAddresses()->count());

        $company = Company::findOne($this->validCompanies['company4']['id']);
        $this->assertNull($company->unlinkAll('billingAddresses', true));
        $this->tester->dontSeeInDatabase(
            'address', [
                'addressable_id' => $company->id,
                'addressable_type' => Company::class,
                'type' => 'billing'
            ]
        );
        $this->assertEquals(0, $user->getBillingAddresses()->count());

        $this->assertNull($company->unlinkAll('shippingAddresses', true));
        $this->tester->dontSeeInDatabase(
            'address', [
                'addressable_id' => $company->id,
                'addressable_type' => Company::class,
                'type' => 'shipping'
            ]
        );
        $this->assertEquals(0, $user->getShippingAddresses()->count());
    }
}
