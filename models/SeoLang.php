<?php

namespace infoweb\seo\models;

use infoweb\cms\helpers\ArrayHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "seo_lang".
 *
 * @property string $seo_id
 * @property string $language
 * @property string $title
 * @property string $description
 */
class SeoLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['language'], 'required'],
            // Only required for existing records
            [['seo_id'], 'required', 'when' => function($model) {
                return !$model->isNewRecord;
            }],
            // Trim
            [['title', 'description', 'keywords'], 'trim'],
            [['seo_id'], 'integer'],
            [['language'], 'string', 'max' => 10],
            [['title'], 'string', 'max' => 255],
            [['seo_id', 'language'], 'unique', 'targetAttribute' => ['seo_id', 'language'], 'message' => Yii::t('infoweb/seo', 'The combination of Seo ID and Language has already been taken.')]
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seo_id' => Yii::t('infoweb/seo', 'Seo ID'),
            'language' => Yii::t('app', 'Language'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'keywords' => Yii::t('infoweb/seo', 'Keywords'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::className(), ['id' => 'seo_id']);
    }
}