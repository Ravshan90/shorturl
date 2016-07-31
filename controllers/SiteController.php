<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\ShortUrl;

class SiteController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * @return string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionIndex()
    {
        $model = new ShortUrl();
        //save url
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                //$model->checkUrl($model['long_url']);
                $model->setAttributes([
                    'short_code' => $model->generateShortCode(),
                    'time_create' => date('Y-m-d')
                ]);
                $model->save();
                return $this->refresh();
            }
        }
        //get all urls
        $query = ShortUrl::find();
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);
        $short_urls = $query->addOrderBy('id DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'short_urls' => $short_urls,
            'model' => $model,
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * @param $code
     * @return \yii\web\Response
     * @throws \yii\web\HttpException
     * @throws \yii\web\NotAcceptableHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionForward($code)
    {
        $url = ShortUrl::validateShortCode($code);
        return $this->redirect($url['long_url']);
    }
}