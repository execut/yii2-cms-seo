<?php

namespace infoweb\seo\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use creocoder\translateable\TranslateableBehavior;
use infoweb\pages\models\Page;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "seo".
 *
 * @property string $id
 * @property string $entity
 * @property string $entity_id
 * @property string $created_at
 * @property string $updated_at
 */
class Seo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo';
    }

    public function behaviors()
    {
        return [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'title',
                    'description',
                    'keywords'
                ]
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function () {
                    return time();
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity', 'entity_id'], 'required'],
            [['entity'], 'string'],
            [['entity_id', 'created_at', 'updated_at'], 'integer'],
            [['entity', 'entity_id'], 'unique', 'targetAttribute' => ['entity' => 'entity', 'entity_id' => 'entity_id'], 'message' => Yii::t('infoweb/seo', 'The combination of Entity and Entity ID has already been taken.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity' => Yii::t('app', 'Entity'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(SeoLang::className(), ['seo_id' => 'id']);
    }

    public function getEntityModel()
    {
        return $this->hasOne($this->entity, ['id' => 'entity_id']);
    }

    public function getEntityTypeName()
    {
        return StringHelper::basename($this->entity);
    }
}