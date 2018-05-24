<?php

namespace tests\unit;

use Yii;
use app\fixtures\TagFixture;
use app\models\Tag;

/**
 * Class TagTest
 * @package tests\unit
 */
class TagTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var array
     */
    private $validTag;

    /**
     * _fixtures
     *
     * @return array
     */
    public function _fixtures()
    {
        return [
            'tag' => [
                'class' => TagFixture::class,
                'dataFile' => dirname(__DIR__) . '/app/fixtures/data/tag.php'
            ],
        ];
    }

    /**
     * _before
     *
     */
    public function _before()
    {
        $this->validTag = reset($this->tester->grabFixture('tag')->data);
    }

    /**
     * Test Tag Morph
     *
     */
    public function testTagMorph()
    {
        $tag = Tag::findOne($this->validTag['id']);

        $tag->getAnswers();

        $tag->getQuestions();
    }
}
