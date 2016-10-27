<?php

use yii\db\Migration;

class m161026_102353_addTextAndHeaderColumnsToSeoLang extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('seo_lang', 'header', $this->string(1000));
        $this->addColumn('seo_lang', 'text', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('seo_lang', 'header');
        $this->dropColumn('seo_lang', 'text');
    }
}
