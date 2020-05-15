<?php

namespace crestoff\yii2badlogin;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class LoginAttempt extends ActiveRecord
{
    public static function tableName()
    {
        return 'bad_login';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['timestampBehavior'] = [
            'class' => \yii\behaviors\TimestampBehavior::class,
            'value' => new \yii\db\Expression('NOW()'),
        ];

        return $behaviors;
    }

    public function rules()
    {
        return [
            [['key'], 'required'],
        ];
    }
}
