<?php

namespace app\controllers;

use Yii;
use app\filters\TestForm;

class TestController extends BaseController
{
    /**
     * @var TestForm
     */
    private $testForm;

    /*
    *Initialize the common code here
    */
    public function init()
    {
        $this->testForm = new TestForm();
    }

    public function actionIndex()
    {
        $result = $this->testForm->list();

        return ['code' => $result['result'] ? 200 : 0, 'message' => $result['message'], 'data' => $result['data']];
    }

    public function actionCreate()
    {
        return ['code' => 200, 'message' => 'ok', 'data' => []];
    }

    public function actionShow($id)
    {
        return ['code' => 200, 'message' => 'ok', 'data' => ['id' => $id]];
    }

    public function actionUpdate($id)
    {
        return ['code' => 200, 'message' => 'ok', 'data' => []];
    }

    public function actionDelete()
    {
        return ['code' => 200, 'message' => 'ok', 'data' => []];
    }
}
