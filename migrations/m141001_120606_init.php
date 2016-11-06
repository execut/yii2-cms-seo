<?php

use yii\db\Schema;
use yii\db\Migration;

class m141001_120606_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        } else if ($this->db->driverName === 'pgsql') {
            $this->execute('CREATE TYPE seo_entity AS ENUM (\'page\')');
        }
        
        // Create 'seo' table
        $this->createTable('{{%seo}}', [
            'id'            => $this->primaryKey(),
            'entity'        => "seo_entity NOT NULL DEFAULT 'page'",
            'entity_id'     => $this->integer()->unsigned()->notNull(),
            'created_at'    => $this->integer()->unsigned()->notNull(),
            'updated_at'    => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('entity_entity_id', '{{%seo}}', ['entity', 'entity_id'], true);
        $this->createIndex('entity_id', '{{%seo}}', 'entity_id');

        // Create 'seo_lang' table
        $this->createTable('{{%seo_lang}}', [
            'seo_id'        => $this->integer()->notNull(),
            'language'      => $this->string(10)->notNull(),
            'title'         => $this->string()->notNull(),
            'description'   => Schema::TYPE_TEXT . ' NOT NULL',
            'keywords'      => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'    => $this->integer()->unsigned()->notNull(),
            'updated_at'    => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('seo_id_language', '{{%seo_lang}}', ['seo_id', 'language']);
        $this->createIndex('seo_language_i', '{{%seo_lang}}', 'language');
        $this->addForeignKey('FK_SEO_LANG_SEO_ID', '{{%seo_lang}}', 'seo_id', '{{%seo}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('seo_lang');
        $this->dropTable('seo');
    }
}
