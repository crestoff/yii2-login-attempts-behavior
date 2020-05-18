<?php

namespace crestoff\yii2badlogin;

use Yii;
use Exception;
use yii\base\Model;
use yii\base\Behavior;
use yii\db\Expression;
use yii\helpers\Inflector;

class BadLoginBehavior extends Behavior
{
    public $attempts = 3;
    public $duration = 300;
    public $durationUnit = 'second';
    public $disableDuration = 900;
    public $disableDurationUnit = 'second';
    public $clearOnHandle = true;
    public $clearDuration = 30;
    public $clearDurationUnit = 'day';
    public $usernameAttribute = 'email';
    public $passwordAttribute = 'password';
    public $message = 'Sorry, you have exceeded the password attempts.';

    private $_attempt;
    private $_safeUnits = [
        'second',
        'minute',
        'day',
        'week',
        'month',
        'year',
    ];

    /**
     * @return string[]
     */
    public function events()
    {
        return [
            Model::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            Model::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    public function beforeValidate()
    {
        if ($this->clearOnHandle) {
            BadLogin::deleteAll(
                ['<', 'bad_login.reset_at', $this->intervalExpression($this->clearDuration, $this->clearDurationUnit, "-")]
            );
        }

        if ($this->_attempt = BadLogin::find()->where(['username' => $this->username])->andWhere(['>', 'reset_at', new Expression('UNIX_TIMESTAMP(NOW())')])->one()) {
            if ($this->_attempt->amount >= $this->attempts) {
                $this->owner->addError($this->usernameAttribute, $this->message);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function afterValidate()
    {
        if ($this->owner->hasErrors($this->passwordAttribute)) {
            if (!$this->_attempt) {
                $this->_attempt = new BadLogin;
                $this->_attempt->username = $this->username;
            }

            $this->_attempt->amount++;

            if ($this->_attempt->amount >= $this->attempts) {
                $this->_attempt->reset_at = $this->intervalExpression($this->disableDuration, $this->disableDurationUnit);
            } else {
                $this->_attempt->reset_at = $this->intervalExpression($this->duration, $this->durationUnit);
            }

            $this->_attempt->save();
        }
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->owner->{$this->usernameAttribute};
    }

    /**
     * @param $length
     * @param string $unit
     * @return Expression
     * @throws Exception
     */
    private function intervalExpression($length, $unit = 'second', $sign = "+")
    {
        $unit = Inflector::singularize(strtolower($unit));

        if (!in_array($unit, $this->_safeUnits)) {
            $safe = join(', ', $this->_safeUnits);
            throw new Exception("$unit is not an allowed unit. Safe units are: [$safe]");
        }

        if (Yii::$app->db->driverName === 'pgsql')
            $interval = "'$length $unit'";
        else
            $interval = "$length $unit";

        return new Expression("UNIX_TIMESTAMP(NOW() $sign INTERVAL $interval)");
    }
}
