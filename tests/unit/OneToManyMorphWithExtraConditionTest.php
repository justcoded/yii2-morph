<?php

namespace tests\unit;

use app\fixtures\AddressFixture;
use app\fixtures\CompanyFixture;
use app\fixtures\UserFixture;
use app\models\Address;
use app\models\Company;
use app\models\User;

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
     * @var \Tightenco\Collect\Support\Collection
     */
    private $addresses;

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
    protected function _before()
    {
        $this->tester->setCommonProperties();
        $this->addresses = $this->tester->getFixtureData('address');
    }

    /**
     * Test Get Data
     *
     */
    public function testGetData()
    {
        $user = User::findOne($this->tester->users->first()['id']);
        $userBillingAddresses = $this->addresses
            ->where('addressable_id', $user->id)
            ->where('addressable_type', User::class)
            ->where('type', Address::TYPE_BILLING)
            ->values()
            ->all();
        $userBillingAddressesTest = $user->getBillingAddresses()->asArray()->all();
        $this->assertEquals($userBillingAddresses, $userBillingAddressesTest);

        $company = Company::findOne($this->tester->companies->first()['id']);
        $companyShippingAddresses = $this->addresses
            ->where('addressable_id', $company->id)
            ->where('addressable_type', Company::class)
            ->where('type', Address::TYPE_SHIPPING)
            ->values()
            ->all();
        $companyShippingAddressesTest = $company->getShippingAddresses()->asArray()->all();
        $this->assertEquals($companyShippingAddresses, $companyShippingAddressesTest);
    }

    /**
     * Test Link
     */
    public function testLink()
    {
        $user = User::findOne($this->tester->users->first()['id']);
        $count = $user->getShippingAddresses()->count();
        $address = new Address();
        $address->addressable_id = $user->id;
        $address->city = $this->tester->faker->city;
        $address->street = $this->tester->faker->streetAddress;
        $this->assertNull($user->link('shippingAddresses', $address));
        $this->tester->seeInDatabase(
            'address', [
                'addressable_id' => $user->id,
                'addressable_type' => User::class,
                'type' => 'shipping'
            ]
        );
        $this->assertEquals($count + 1, $user->getShippingAddresses()->count());

        $company = Company::findOne($this->tester->companies->first()['id']);
        $count = $company->getBillingAddresses()->count();
        $address = new Address();
        $address->addressable_id = $company->id;
        $address->city = $this->tester->faker->city;
        $address->street = $this->tester->faker->streetAddress;
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
        foreach ($this->tester->users as $item) {
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

        foreach ($this->tester->companies as $item) {
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
        $user = User::findOne($this->tester->users->firstWhere('id', '4')['id']);
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

        $company = Company::findOne($this->tester->companies->firstWhere('id', '3')['id']);
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
