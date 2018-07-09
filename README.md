Yii2 Morph Relations - DEVELOPMENT IN PROGRESS!!!!
=========================

REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.0.
   

INSTALLATION
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



CONFIGURATION
-------------

### ENV support

Config files are the same for all environments. You don't need to create some "local" config files.
Instead you can accept different parameters from server environment with `env()` helper function. 

Server environment variables can be set through web server vhost configuration, .htaccess file, 
or .env file in project root (the simplest option).

To start using the project template copy .env-example as .env in the project root and setup it.

### Web
Copy .htaccess-example as .htaccess to enable pretty URLs support and cache/expire tokens required by 
Google PageSpeed Insights test.

Furthermore you should check such options inside .env:

```php
APP_ENV=dev
APP_DEBUG=true
APP_KEY=wUZvVVKJyHFGDB9qK_Lop4QE1vwb4bYU
```

*`APP_KEY` is used as cookie verification key. Unfortunately there are no post install composer script to generate it automatically*

### Database

You should update your .env file config:

```php
DB_HOST=127.0.0.1
DB_NAME=yii2_starter
DB_USER=root
DB_PASS=12345
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.

LAUNCH
-------

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

TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).
By default there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](http://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2_basic_tests` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run -- --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit -- --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit -- --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.