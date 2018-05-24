<?php

namespace tests\unit;

use Yii;
use app\fixtures\QuestionFixture;
use app\models\Question;

/**
 * Class QuestionTest
 * @package tests\unit
 */
class QuestionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var array
     */
    private $validQuestion;

    /**
     * _fixtures
     *
     * @return array
     */
    public function _fixtures()
    {
        return [
            'question' => [
                'class' => QuestionFixture::class,
                'dataFile' => dirname(__DIR__) . '/app/fixtures/data/question.php'
            ],
        ];
    }

    /**
     * _before
     *
     */
    public function _before()
    {
        $this->validQuestion = reset($this->tester->grabFixture('question')->data);
    }

    /**
     * Test Question Morph
     *
     */
    public function testQuestionMorph()
    {
        $question = Question::findOne($this->validQuestion['id']);

        $question->getTags();
    }
}
