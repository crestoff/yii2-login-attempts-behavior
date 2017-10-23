<?php

namespace ethercreative\loginattempts;

use Yii;
use yii\base\Model;

use ethercreative\loginattempts\LoginAttempt;

class LoginAttemptBehavior extends \yii\base\Behavior
{
    public $attempts = 3;

    public $duration = 300;

    public $disableDuration = 900;

    public $usernameAttribute = 'email';

    public $passwordAttribute = 'password';

    public $message = 'You have exceeded the password attempts.';

    private $_attempt;

    public function events()
    {
        return [
            Model::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            Model::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    public function beforeValidate()
    {
        if ($this->_attempt = LoginAttempt::find()->where(['key' => $this->key])->andWhere(['>', 'reset_at', date('r')])->one())
        {
            if ($this->_attempt->amount >= $this->attempts)
            {
                $this->owner->addError($this->usernameAttribute, $this->message);
            }
        }
    }

    public function afterValidate()
    {
        if ($this->owner->hasErrors($this->passwordAttribute))
        {
            if (!$this->_attempt)
            {
                $this->_attempt = new LoginAttempt;
                $this->_attempt->key = $this->key;
            }

            $this->_attempt->amount += 1;

            if ($this->_attempt->amount >= $this->attempts)
                $this->_attempt->reset_at = date('r', strtotime('+' . $this->disableDuration . ' seconds'));
            else
                $this->_attempt->reset_at = date('r', strtotime('+' . $this->duration . ' seconds'));

            $this->_attempt->save();
        }
    }

    public function getKey()
    {
        return sha1($this->owner->{$this->usernameAttribute});
    }
}
