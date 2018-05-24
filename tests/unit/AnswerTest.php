<?php

namespace tests\unit;

use Yii;
use app\fixtures\AnswerFixture;
use app\models\Answer;

/**
 * Class AnswerTest
 * @package tests\unit
 */
class AnswerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var array
     */
    private $validAnswer;

    /**
     * _fixtures
     *
     * @return array
     */
    public function _fixtures()
    {
        return [
            'answer' => [
                'class' => AnswerFixture::class,
                'dataFile' => dirname(__DIR__) . '/app/fixtures/data/answer.php'
            ],
        ];
    }

    /**
     * _before
     *
     */
    public function _before()
    {
        $this->validAnswer = reset($this->tester->grabFixture('answer')->data);
    }

    /**
     * Test Answer Morph
     *
     */
    public function testAnswerMorph()
    {
        $answer = Answer::findOne($this->validAnswer['id']);

        $answer->getTags();
    }
}
