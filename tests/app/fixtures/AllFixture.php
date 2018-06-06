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
		UserFixture::class,
		CompanyFixture::class,
		CommentFixture::class,
		TagFixture::class,
		TaggableFixture::class,
		AddressFixture::class,
		MediaFixture::class,
		MediableFixture::class,
	];
}
