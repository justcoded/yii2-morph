<?php

namespace tests\unit;

use Yii;
use app\fixtures\CommentFixture;
use app\models\Comment;

/**
 * Class CommentTest
 * @package tests\unit
 */
class CommentTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var array
     */
    private $validComment;

    /**
     * _fixtures
     *
     * @return array
     */
    public function _fixtures()
    {
        return [
            'comment' => [
                'class' => CommentFixture::class,
                'dataFile' => dirname(__DIR__) . '/app/fixtures/data/comment.php'
            ],
        ];
    }

    /**
     * _before
     *
     */
    public function _before()
    {
        $this->validComment = reset($this->tester->grabFixture('comment')->data);
    }

    /**
     * Test Comment Morph
     *
     */
    public function testCommentMorph()
    {
        $comment = Comment::findOne($this->validComment['id']);

        $comment->commentable();
    }
}
