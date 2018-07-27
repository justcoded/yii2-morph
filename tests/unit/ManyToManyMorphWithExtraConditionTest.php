<?php

namespace tests\unit;

use app\fixtures\CompanyFixture;
use app\fixtures\UserFixture;
use app\fixtures\MediableFixture;
use app\fixtures\MediaFixture;
use app\models\Company;
use app\models\Media;
use app\models\User;

/**
 * Class ManyToManyMorphWithExtraConditionTest
 * @package tests\unit
 */
class ManyToManyMorphWithExtraConditionTest extends \Codeception\Test\Unit
{
    /**
     * @var \Tightenco\Collect\Support\Collection
     */
    private $media;

    /**
     * @var \Tightenco\Collect\Support\Collection
     */
    private $mediables;
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
            'media' => MediaFixture::class,
            'mediable' => MediableFixture::class
        ];
    }

    /**
     * _before
     *
     */
    protected function _before()
    {
        $this->tester->setCommonProperties();
        $this->mediables = $this->tester->getFixtureData('mediable');
        $this->media = $this->tester->getFixtureData('media');
    }

    /**
     * Test Get Data
     *
     */
    public function testGetData()
    {
        $user = User::findOne($this->tester->users->first()['id']);
        $userMediablesThumbnailsIds = $this->mediables
            ->where('mediable_id', $user->id)
            ->where('mediable_type', User::class)
            ->where('mediable.type', Media::TYPE_THUMBNAIL)
            ->keyBy('media_id')
            ->keys();
        $userMediaThumbnails = $this->media
            ->whereIn('id', $userMediablesThumbnailsIds)
            ->values()
            ->all();
        $userMediaThumbnailsTest = $user->getThumbnail()->asArray()->all();
        $this->assertEquals($userMediaThumbnails, $userMediaThumbnailsTest);

        $userMediablesGalleryIds = $this->mediables
            ->where('mediable_id', $user->id)
            ->where('mediable_type', User::class)
            ->where('mediable.type', Media::TYPE_GALLERY)
            ->keyBy('media_id')
            ->keys();
        $userMediaGallery = $this->media
            ->whereIn('id', $userMediablesGalleryIds)
            ->values()
            ->all();
        $userMediaGalleryTest = $user->getGallery()->asArray()->all();
        $this->assertEquals($userMediaGallery, $userMediaGalleryTest);

        $company = Company::findOne($this->tester->companies->first()['id']);

        $companyMediablesThumbnailsIds = $this->mediables
            ->where('mediable_id', $company->id)
            ->where('mediable_type', Company::class)
            ->where('mediable.type', Media::TYPE_THUMBNAIL)
            ->keyBy('media_id')
            ->keys();
        $companyMediaThumbnails = $this->media
            ->whereIn('id', $companyMediablesThumbnailsIds)
            ->values()
            ->all();
        $companyMediaThumbnailsTest = $company->getThumbnail()->asArray()->all();
        $this->assertEquals($companyMediaThumbnails, $companyMediaThumbnailsTest);

        $companyMediablesGalleryIds = $this->mediables
            ->where('mediable_id', $company->id)
            ->where('mediable_type', Company::class)
            ->where('mediable.type', Media::TYPE_THUMBNAIL)
            ->keyBy('media_id')
            ->keys();
        $companyMediaGallery = $this->media
            ->whereIn('id', $companyMediablesGalleryIds)
            ->values()
            ->all();
        $companyMediaGalleryTest = $company->getGallery()->asArray()->all();
        $this->assertEquals($companyMediaGallery, $companyMediaGalleryTest);
    }

    /**
     * Test Link
     *
     */
    public function testLink()
    {
        $user = User::findOne($this->tester->users->first()['id']);
        $count = $user->getGallery()->count();
        $media = new Media();
        $media->type = $this->tester->faker->randomElement(['image', 'video', 'file']);
        $media->file = 'tmp/' . $this->tester->faker->md5 . '.' . $this->tester->faker->fileExtension;
        $media->thumb = $this->tester->faker->imageUrl(
            $this->tester->faker->numberBetween(600, 1200), $this->tester->faker->numberBetween(400, 800)
        );

        $this->assertTrue($media->save());
        $this->assertNull($user->link('gallery', $media, ['media_id' => $media->id]));
        $this->tester->seeInDatabase(
            'mediable',
            [
                'media_id' => $media->id,
                'mediable_id' => $user->id,
                'mediable_type' => User::class,
                'type' => 'gallery'
            ]
        );
        $this->assertEquals($count + 1, $user->getGallery()->count());

        $userNeighbour = User::findOne($this->tester->users->firstWhere('id', '2')['id']);
        $this->tester->dontSeeInDatabase(
            'mediable',
            [
                'media_id' => $media->id,
                'mediable_id' => $userNeighbour->id,
                'mediable_type' => User::class,
                'type' => 'gallery'
            ]
        );

        $company = Company::findOne($this->tester->companies->first()['id']);
        $count = $company->getGallery()->count();
        $media = new Media();
        $media->type = $this->tester->faker->randomElement(['image', 'video', 'file']);
        $media->file = 'tmp/' . $this->tester->faker->md5 . '.' . $this->tester->faker->fileExtension;
        $media->thumb = $this->tester->faker->imageUrl(
            $this->tester->faker->numberBetween(600, 1200), $this->tester->faker->numberBetween(400, 800)
        );
        $this->assertTrue($media->save());
        $this->assertNull($company->link('thumbnail', $media, ['media_id' => $media->id]));
        $this->tester->seeInDatabase(
            'mediable', [
                'media_id' => $media->id,
                'mediable_id' => $company->id,
                'mediable_type' => Company::class,
                'type' => 'thumbnail'
            ]
        );
        $this->assertEquals($count + 1, $company->getThumbnail()->count());

        $companyNeighbour = User::findOne($this->tester->users->firstWhere('id', '2')['id']);
        $this->tester->dontSeeInDatabase(
            'mediable',
            [
                'media_id' => $media->id,
                'mediable_id' => $companyNeighbour->id,
                'mediable_type' => Company::class,
                'type' => 'thumbnail'
            ]
        );
    }

    /**
     * Test Unlink
     *
     */
    public function testUnlink()
    {
        $user = User::findOne($this->tester->users->firstWhere('id', '2')['id']);

        $media = new Media();
        $media->type = $this->tester->faker->randomElement(['image', 'video', 'file']);
        $media->file = 'tmp/' . $this->tester->faker->md5 . '.' . $this->tester->faker->fileExtension;
        $media->thumb = $this->tester->faker->imageUrl(
            $this->tester->faker->numberBetween(600, 1200), $this->tester->faker->numberBetween(400, 800)
        );

        $this->assertTrue($media->save());
        $this->assertNull($user->link('gallery', $media, ['media_id' => $media->id]));

        $count = $user->getGallery()->count();
        $this->assertNull($user->unlink('comments', $media, true));
        $this->tester->dontSeeInDatabase(
            'media', [
                'id' => $media->id
            ]
        );
        $this->tester->dontSeeInDatabase(
            'mediable', [
                'media_id' => $media->id
            ]
        );
        $this->assertEquals($count - 1, $user->getGallery()->count());

        $company = Company::findOne($this->tester->companies->firstWhere('id', '2')['id']);

        $media = new Media();
        $media->type = $this->tester->faker->randomElement(['image', 'video', 'file']);
        $media->file = 'tmp/' . $this->tester->faker->md5 . '.' . $this->tester->faker->fileExtension;
        $media->thumb = $this->tester->faker->imageUrl(
            $this->tester->faker->numberBetween(600, 1200), $this->tester->faker->numberBetween(400, 800)
        );

        $this->assertTrue($media->save());
        $this->assertNull($company->link('thumbnail', $media, ['media_id' => $media->id]));

        $count = $company->getThumbnail()->count();
        $this->assertNull($company->unlink('comments', $media, true));
        $this->tester->dontSeeInDatabase(
            'media', [
                'id' => $media->id
            ]
        );
        $this->tester->dontSeeInDatabase(
            'mediable', [
                'media_id' => $media->id
            ]
        );
        $this->assertEquals($count - 1, $company->getThumbnail()->count());
    }

    /**
     * Test UnlinkAll
     *
     */
    public function testUnlinkAll()
    {
        $user = User::findOne($this->tester->users->firstWhere('id', '4')['id']);

        $this->assertNull($user->unlinkAll('gallery', true));
        $this->tester->dontSeeInDatabase(
            'mediable',
            [
                'mediable_id' => $user->id,
                'mediable_type' => User::class,
                'type' => 'gallery'
            ]
        );
        $this->assertEquals(0, $user->getGallery()->count());

        $this->assertNull($user->unlinkAll('thumbnail', true));
        $this->tester->dontSeeInDatabase(
            'mediable',
            [
                'mediable_id' => $user->id,
                'mediable_type' => User::class,
                'type' => 'thumbnail'
            ]
        );
        $this->assertEquals(0, $user->getThumbnail()->count());

        $company = Company::findOne($this->tester->companies->firstWhere('id', '4')['id']);

        $this->assertNull($company->unlinkAll('gallery', true));
        $this->tester->dontSeeInDatabase(
            'mediable',
            [
                'mediable_id' => $company->id,
                'mediable_type' => Company::class,
                'type' => 'gallery'
            ]
        );
        $this->assertEquals(0, $company->getGallery()->count());

        $this->assertNull($company->unlinkAll('thumbnail', true));
        $this->tester->dontSeeInDatabase(
            'mediable',
            [
                'mediable_id' => $company->id,
                'mediable_type' => Company::class,
                'type' => 'gallery'
            ]
        );
        $this->assertEquals(0, $company->getThumbnail()->count());
    }
}
