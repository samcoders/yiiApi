<?php

namespace app\models;

use yii\db\ActiveRecord;

class CostCompany extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cost_departments';
    }
}