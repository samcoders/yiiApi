<?php

namespace app\filters;

use Yii;
use yii\base\Model;
use app\services\TestServices;

class TestForm extends Model
{
    const LIST_SCENARIOS = 'list';
    const LOGIN_SCENARIOS = 'login';

    public $username;
    public $password;
    public $pageSize;
    public $currentPage;

    /**
     * @var TestServices
     */
    private $testService;

    public function init()
    {
        $this->testService = new TestServices();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            [['pageSize', 'currentPage'], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            self::LOGIN_SCENARIOS => ['username', 'password'],
            self::LIST_SCENARIOS => ['pageSize', 'currentPage'],
        ];
    }

    public function list()
    {
        $this->scenario = self::LIST_SCENARIOS;
        $this->load(['TestForm' => Yii::$app->request->get()]);
        if (!$this->validate()) {
            return ['result' => false, 'message' => current($this->getFirstErrors()), 'data' => ''];
        }

        $data = $this->testService->list();
        return ['result' => true, 'message' => 'success', 'data' => $data];
    }
}