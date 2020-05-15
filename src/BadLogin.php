<?php

namespace crestoff\yii2badlogin;

use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class BadLogin extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bad_login';
    }

    /**
     * @return mixed
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['timestampBehavior'] = [
            'class' => TimestampBehavior::class,
            'value' => new Expression('UNIX_TIMESTAMP(NOW())'),
        ];

        return $behaviors;
    }

    /**
     * @return array[]
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
        ];
    }
}
