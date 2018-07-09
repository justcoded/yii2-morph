<?php

namespace tests\unit;

use app\fixtures\AllFixture;
use app\models\Address;
use app\models\Tag;
use Yii;
use Faker\Factory;
use app\models\User;
use app\models\Company;
use app\models\Media;

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

        $user->getTags();
        expect(1)->equals(1);

        $company = Company::findOne($this->validCompanies['company0']['id']);

        $company->getTags();
        expect(1)->equals(1);
    }

    /**
     * Test Get Data With Extra Conditions
     *
     */
    public function testGetDataWithExtraConditions()
    {
        $user = User::findOne($this->validUsers['user0']['id']);

        $user->getBillingAddresses();
        expect(1)->equals(1);

        $user->getThumbnail();
        expect(1)->equals(1);

        $user->getGallery();
        expect(1)->equals(1);

        $company = Company::findOne($this->validCompanies['company0']['id']);

        $company->getShippingAddresses();
        expect(1)->equals(1);

        $company->getThumbnail();
        expect(1)->equals(1);

        $company->getGallery();
        expect(1)->equals(1);
    }

    /**
     * Test Link
     *
     */
    public function testLink()
    {
        $user = User::findOne($this->validUsers['user0']['id']);

        $media = new Media();
        $media->type = $this->faker->randomElement(['image', 'video', 'file']);
        $media->file = 'tmp/' . $this->faker->md5 . '.' . $this->faker->fileExtension;
        $media->thumb = $this->faker->imageUrl($this->faker->numberBetween(600, 1200), $this->faker->numberBetween(400, 800));
        $media->save();
        $user->link('gallery', $media, ['media_id' => $media->id]);
        expect(1)->equals(1);

        $tag = new Tag();
        $tag->name = $this->faker->word;
        $tag->save();
        $user->link('tags', $tag, ['tag_id' => $tag->id]);
        expect(1)->equals(1);

        $address = new Address();
        $address->addressable_id = $user->id;
        $address->city = $this->faker->city;
        $address->street = $this->faker->streetAddress;
        $user->link('shippingAddresses', $address);
        expect(1)->equals(1);

        $company = Company::findOne($this->validCompanies['company0']['id']);

        $media = new Media();
        $media->type = $this->faker->randomElement(['image', 'video', 'file']);
        $media->file = 'tmp/' . $this->faker->md5 . '.' . $this->faker->fileExtension;
        $media->thumb = $this->faker->imageUrl($this->faker->numberBetween(600, 1200), $this->faker->numberBetween(400, 800));
        $media->save();
        $company->link('thumbnail', $media, ['media_id' => $media->id]);
        expect(1)->equals(1);

        $tag = new Tag();
        $tag->name = $this->faker->word;
        $tag->save();
        $company->link('tags', $tag, ['tag_id' => $tag->id]);
        expect(1)->equals(1);

        $address = new Address();
        $address->addressable_id = $company->id;
        $address->city = $this->faker->city;
        $address->street = $this->faker->streetAddress;
        $company->link('billingAddresses', $address);
        expect(1)->equals(1);
    }

    /**
     * Test Unlink
     *
     */
    public function testUnlink()
    {
        foreach ($this->validUsers as $item) {
            if (($user = User::findOne($item['id'])) && ($media = $user->getThumbnail()->one())) {
                $user->unlink('thumbnail', $media, true);
                expect(1)->equals(1);
                break;
            }
        }

        foreach ($this->validCompanies as $item) {
            if (($company = Company::findOne($item['id'])) && ($media = $company->getGallery()->one())) {
                $company->unlink('gallery', $media, true);
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
        $user = User::findOne($this->validUsers['user4']['id']);

        $user->unlinkAll('gallery',true);
        expect(1)->equals(1);

        $user->unlinkAll('tags',true);
        expect(1)->equals(1);

        $user->unlinkAll('thumbnail',true);
        expect(1)->equals(1);

        $user->unlinkAll('billingAddresses',true);
        expect(1)->equals(1);

        $user->unlinkAll('shippingAddresses',true);
        expect(1)->equals(1);

        $company = Company::findOne($this->validCompanies['company4']['id']);

        $company->unlinkAll('gallery',true);
        expect(1)->equals(1);

        $company->unlinkAll('tags',true);
        expect(1)->equals(1);

        $company->unlinkAll('thumbnail',true);
        expect(1)->equals(1);

        $company->unlinkAll('billingAddresses',true);
        expect(1)->equals(1);

        $company->unlinkAll('shippingAddresses',true);
        expect(1)->equals(1);
    }
}
