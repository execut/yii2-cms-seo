<?php

use yii\db\Migration;
use yii\db\Schema;

class m160204_125421_update_entity_field extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('seo', 'entity');
        $this->addColumn('seo', 'entity', $this->string()->notNull());
    }

    public function safeDown()
    {
        echo "m160204_125421_update_entity_field cannot be reverted.\n";

        return false;
    }
}
