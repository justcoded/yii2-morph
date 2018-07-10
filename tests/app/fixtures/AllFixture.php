<?php

namespace app\fixtures;

use yii\test\DbFixture;

/**
 * Class AnswerFixture
 *
 * @package justcoded\yii2\tests\app\fixtures
 */
class AllFixture extends DbFixture
{
    /**
     * Fixture dependencies
     *
     * @var array
     */
    public $depends = [
        'user' => UserFixture::class,
        'company' => CompanyFixture::class,
        'comment' => CommentFixture::class,
        'tag' => TagFixture::class,
        'taggable' => TaggableFixture::class,
        'address' => AddressFixture::class,
        'media' => MediaFixture::class,
        'mediable' => MediableFixture::class,
    ];
}
