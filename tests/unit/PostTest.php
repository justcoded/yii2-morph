<?php

namespace tests\unit;

use Yii;
use app\fixtures\UserFixture;
use app\models\User;

/**
 * Class PostTest
 * @package tests\unit
 */
class PostTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var array
     */
    private $validPost;

    /**
     * _fixtures
     *
     * @return array
     */
    public function _fixtures()
    {
        return [
            'post' => [
				'class' => UserFixture::class,
				'dataFile' => dirname(__DIR__) . '/app/fixtures/data/post.php'
            ],
        ];
    }

    /**
     * _before
     *
     */
    public function _before()
    {
        $this->validPost = reset($this->tester->grabFixture('post')->data);
    }

    /**
     * Test Post Morph
     *
     */
    public function testPostMorph()
    {
        $post = User::findOne($this->validPost['id']);

        $post->getComments();
    }
}
