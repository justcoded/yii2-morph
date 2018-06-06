<?php

namespace tests\unit;

use Yii;
use app\fixtures\CompanyFixture;
use app\models\Company;

/**
 * Class VideoTest
 * @package tests\unit
 */
class VideoTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var array
     */
    private $validVideo;

    /**
     * _fixtures
     *
     * @return array
     */
    public function _fixtures()
    {
        return [
            'video' => [
				'class' => CompanyFixture::class,
				'dataFile' => dirname(__DIR__) . '/app/fixtures/data/video.php'
            ],
        ];
    }

    /**
     * _before
     *
     */
    public function _before()
    {
        $this->validVideo = reset($this->tester->grabFixture('video')->data);
    }

    /**
     * Test Video Morph
     *
     */
    public function testVideoMorph()
    {
        $video = Company::findOne($this->validVideo['id']);

        $video->getComments();
    }
}
