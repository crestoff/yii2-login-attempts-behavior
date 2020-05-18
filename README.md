Yii2 Bad Login Behavior
========================

Store login failures, and disable after multiple failures.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run
```sh
composer require crestoff/yii2badlogin
```
or add
```json
"crestoff/yii2badlogin" : "*"
```
to the require section of your `composer.json` file.

Usage
=====
Run the following migration

    php yii migrate --migrationPath="vendor/crestoff/badlogin/src/migrations" --interactive=0

Add the behavior to your login model.

```php
public function behaviors()
{
    $behaviors = parent::behaviors();

    $behaviors[] = [
        'class' => '\crestoff\badlogin\LoginAttemptBehavior',

        'attempts' => 3,
        'duration' => 300,
        'durationUnit' => 'second',
        'disableDuration' => 900,
        'clearOnHandle' => true,
        'clearDuration' => 30,
        'clearDurationUnit' => 'day',
        'disableDurationUnit' => 'second',
        'usernameAttribute' => 'email',
        'passwordAttribute' => 'password',
        'message' => 'Login is disabled',
    ];

    return $behaviors;
}
```
