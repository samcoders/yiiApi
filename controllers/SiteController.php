<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\CostCompany;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use yii\filters\AccessControl;
use Elasticsearch\ClientBuilder;

use PhpOffice\PhpSpreadsheet\Reader;

class SiteController extends Controller
{
    private $client;


    public function init()
    {
        $this->client = ClientBuilder::create()->build();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionTestCreate()
    {
        $params = [
            'index' => 'movies',
            'type' => 'movie',
            'id' => 1,
            'body' => [
                'title' => 'The Godfather',
                'director' => 'Francis Ford Coppola',
                'year' => 1972,
            ],
        ];
        $response = $this->client->index($params);

        return json_encode($response);
    }

    public function actionTestList()
    {
        $start = microtime(true);

        $params = [
            'index' => 'products',
            'type' => 'product',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['ids' => ['values' => [17844, 21211]]],
                            ['match' => ['channel_product_name' => '笺']],
                        ],
                        'filter' => [
                            ['term' => ['card_value' => 82]],
                        ],
                    ],
                ],
            ],
        ];
        $response = $this->client->search($params);

        return json_encode($response);
    }

    public function actionTestInfo()
    {
        $params = [
            'index' => 'movies',
            'type' => 'movie',
            'id' => 1,
        ];
        $response = $this->client->getSource($params);

        return json_encode($response);
    }

    public function actionTestUpdate()
    {
        $params = [
            'index' => 'movies',
            'type' => 'movie',
            'id' => 1,
            'body' => [
                'title' => 'The Godfather',
                'director' => 'Francis Ford Coppola',
                'year' => 1972,
                'genres' => ['Crime', 'Drama'],
            ],
        ];
        $response = $this->client->index($params);

        return json_encode($response);
    }

    public function actionTestDelete()
    {
        $params = [
            'index' => 'movies',
            'type' => 'movie',
            'id' => 1,
        ];
        $response = $this->client->delete($params);

        return json_encode($response);
    }

    public function actionTestBulk()
    {
        /* ini_set('memory_limit', '2048M');

         $start = 300000;
         $end = 350000;
         for ($i = $start; $i < $end; $i++) {
             $params['body'][] = [
                 'index' => [
                     '_index' => 'products',
                     '_type' => 'product',
                     '_id' => $i,
                 ],
             ];

             $channelId = mt_rand(0, 999);
             $score = mt_rand(0, 100);

             $params['body'][] = [
                 'channel_id' => $channelId,
                 'product_id' => $i,
                 'product_name' => $this->getChar(15),
                 'product_name_en' => $this->getEn(),
                 'channel_product_name' => $this->getChar(15),
                 'channel_product_name_en' => $this->getEn(),
                 'is_score' => mt_rand(0, 1),
                 'score' => $score,
                 'card_value' => $score,
                 'price' => $score,
                 'delivery_rule_id' => $score,
                 'third_delivery_rule_id' => $score,
                 'product_url' => 'http://' . $this->getEn(),
                 'video_url' => 'http://' . $this->getEn(),
                 'video_image' => 'http://' . $this->getEn(),
                 'video_size' => $score,
                 'is_oversell' => mt_rand(0, 1),
                 'is_online' => mt_rand(0, 1),
                 'is_all_area' => mt_rand(0, 1),
                 'additional_info' => $this->getChar(15),
                 'purchasable' => $this->getChar(15),
                 'cat_id' => mt_rand(0, 199),
                 'second_cat_id' => mt_rand(0, 199),
                 'farmer_id' => mt_rand(0, 199),
             ];
         }

         $response = $this->client->bulk($params);
         return json_encode(['result' => 'ok']);*/
    }

    private function getChar($num)  // $num为生成汉字的数量
    {
        $b = '';
        for ($i = 0; $i < $num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0, 0xD0)) . chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }

    private function getEn()
    {
        $code = '';
        for ($i = 1; $i <= 4; $i++) {
            $code .= chr(rand(97, 122));
        }
        return $code;
    }

    public function actionExcel()
    {
        $result = CostCompany::find()->select(['id', 'code'])->asArray()->all();
        $result = array_column($result, 'id', 'code');

        $inputFileName = './sdb_b2c_analyst_config.xls';
        $reader = new Reader\Xls();
        $spreadsheet = $reader->load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $sql = 'INSERT INTO business_developments(code,name,department_id,business_key,business_id,is_cancel,updated_by,created_by)VALUES';

        foreach ($sheetData as $item) {
            if (!isset($result[$item['D']])) {
                echo $item['D'] . 'no exists';
                exit;
            }

            $code = $item['B'];
            $name = $item['C'];
            $department_id = $result[$item['D']];
            $business_key = 'YIMI';
            $business_id = 1;
            $is_cancel = 0;
            $updated_by = 'system';
            $created_by = 'system';
            $sql .= "('$code','$name',$department_id,'$business_key',$business_id,$is_cancel,'$updated_by','$created_by'),";
        }
        $sql = rtrim($sql, ',');
        print_r($sql);
        return '';
    }

    public function actionTest()
    {
        Yii::warning('测试');
        return ['code' => 20000, 'message' => 'ok', 'data' => []];
    }

}
