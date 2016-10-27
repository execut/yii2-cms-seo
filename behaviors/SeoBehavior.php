<?php

namespace infoweb\seo\behaviors;

use yii;
use yii\base\Exception;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use infoweb\seo\models\Seo;

class SeoBehavior extends Behavior
{
    public $titleAttribute = 'title';

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function afterInsert($event)
    {
        $post = Yii::$app->request->post();

        if (isset($post['SeoLang'])) {
            // Create the seo tag
            $seo = new Seo([
                'entity'    => $this->owner->className(),
                'entity_id' => $this->owner->id
            ]);

            foreach ($post['SeoLang'] as $language => $data) {
                foreach ($data as $attribute => $translation) {
                    $seo->translate($language)->$attribute = $translation;
                }
            }

            if (!$seo->save()) {
                return false;
            }
        }

        return true;
    }

    public function afterUpdate($event)
    {
        $post = Yii::$app->request->post();
        if (isset($post['SeoLang'])) {
            $attributes = [
                'entity'    => $this->owner->className(),
                'entity_id' => $this->owner->id
            ];
            if (!($seo = Seo::findOne($attributes))) {
                $seo = new Seo($attributes);
            }

            foreach ($post['SeoLang'] as $language => $data) {
                if (is_array($data)) {
                    foreach ($data as $attribute => $translation) {
                        $seo->translate($language)->$attribute = $translation;
                    }
                } else {
                    $attribute = $language;
                    $translation = $data;
                    $language = \yii::$app->language;
                    $seo->translate($language)->$attribute = $translation;
                }
            }

            if (!$seo->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeo()
    {
        return $this->owner->hasOne(Seo::className(), ['entity_id' => 'id'])->where(['entity' => $this->owner->className()]);
    }

    public function beforeDelete()
    {
        // Try to load and delete the attached 'Seo' entity
        if (isset($this->owner->seo)) {
            return $this->owner->seo->delete();
        } else {
            return false;
        }
    }

    /**
     * Returns an array of the non-empty seo tags that are attached to the page.
     *
     * @return  array
     */
    public function getSeoTags()
    {
        return array_filter($this->owner->seo->translate()->attributes);
    }

}