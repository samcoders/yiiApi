<?php

namespace app\filters;

use Yii;
use yii\base\Model;

class TestForm extends Model
{
    const LOGIN_SCENARIOS = 'login';

    public $username;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            self::LOGIN_SCENARIOS => ['username', 'password'],
        ];
    }
}