<?php
namespace app\models;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\NotAcceptableHttpException;
use linslin\yii2\curl;
/**
 * This is the model class for table "{{%short_urls}}".
 *
 * @property integer $id
 * @property string $long_url
 * @property string $short_code
 * @property string $time_create
 * @property integer $counter
 */
class ShortUrl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%short_urls}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['long_url'], 'required'],
            [['long_url'], 'url'],
            [['time_create'], 'safe'],
            [['short_code'], 'string', 'max' => 6],
            [['short_code'], 'unique']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'long_url' => 'Long Url',
            'short_code' => 'Short Code',
            'time_create' => 'Created Time',
        ];
    }
    /**
     * @return string
     */
    public function generateShortCode()
    {
        return substr(str_shuffle(ALLOWED_CHARS), 0, 6);
    }
    /**
     * @param $code
     * @return array|null|\yii\db\ActiveRecord
     * @throws HttpException
     * @throws NotAcceptableHttpException
     * @throws NotFoundHttpException
     */
    public static function validateShortCode($code)
    {
        if (!preg_match('|^[0-9a-zA-Z]{6,6}$|', $code)) {
            throw new HttpException(400, 'Please enter valid short code');
        }
        $url = ShortUrl::find()->where(['short_code' => $code])->one();
        if ($url === null) {
            throw new NotFoundHttpException('This short code not found:' . $code);
        }
        return $url;
    }
    /**
     * @param $url
     * @throws HttpException
     */
    public function checkUrl($url)
    {
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_SSL_VERIFYHOST, false);
        $curl->setOption(CURLOPT_FOLLOWLOCATION, true);
        $curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $curl->setOption(CURLOPT_AUTOREFERER, true);
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 10);
        $curl->setOption(CURLOPT_TIMEOUT, 10);
        $curl->setOption(CURLOPT_MAXREDIRS, 10);
        $curl->setOption(CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36');
        $curl->get($url);
        if ($curl->responseCode != 200)
            throw new HttpException(400, 'Something is wrong with your URL. Status code: ' . $curl->responseCode);
    }
}