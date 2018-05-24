<?php

namespace tests\unit;

use Yii;
use app\fixtures\PostFixture;
use app\models\Post;

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
                'class' => PostFixture::class,
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
        $post = Post::findOne($this->validPost['id']);

        $post->getComments();
    }
}
