<?php

namespace app\controllers;

class TestController extends BaseController
{
    /*
    *Initialize the common code here
    */
    public function init()
    {

    }

    public function actionTest()
    {
        return ['code' => '200', 'message' => 'test', 'data' => []];
    }
}
