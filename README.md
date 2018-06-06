Yii2 Morph Relations
=========================

... docs coming soon

Migrations: php yii migrate

Faker example: php yii fixture/generate tag --count=20

Fixture load example: php yii fixture/load Tag

Unit Tests: 
    ./vendor/bin/codecept build
    ./vendor/bin/codecept run unit
   

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist justcoded/yii2-morph "*"
```

or add

```
"justcoded/yii2-morph": "*"
```

to the require section of your `composer.json` file.


Configuration
------------
... coming soon


For Developers
------------

This package has auto-tests with all variations of morph relation behaviors. If you want to contribute, you should check them with unit tests.

To start development with tests:

1. Clone repository on your server
2. Copy `.env-example` into `.env` and update with DB credentials
3. Run `php yii migrate`
4. Run `php yii fixture/load All`

Models and relations in tests:

	User
			1:n		Comment 
	Company   
	
	--------
	
	User
			n:n		Tag   (via junction)	
	Company   
	
	--------
	
	User
			1:n(:type) 	Address   (via junction)
	Company   
	
	--------
	
	User
			n:n(:type) 	Media   (via junction)
	Company   

Regenerate fixtures data sequence:

	php yii fixture/generate user --count=5
	php yii fixture/generate company --count=5
	php yii fixture/load User
	php yii fixture/load Company

	php yii fixture/generate comment --count=10
	php yii fixture/load Comment
	
	php yii fixture/generate tag --count=5
	php yii fixture/load Tag
	php yii fixture/generate taggable --count=15
	php yii fixture/load Taggable
	
	php yii fixture/generate address --count=10
	php yii fixture/load Address

	php yii fixture/generate media --count=20
	php yii fixture/load Media
	php yii fixture/generate mediable --count=10
	php yii fixture/load Mediable
