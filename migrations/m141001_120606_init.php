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
        }

        if ($this->db->driverName === 'pgsql') {
            $this->execute(<<<SQL
CREATE TYPE seo_entity AS ENUM ('page');
SQL
);

            if ($this->db->createCommand('SELECT count(*) FROM pg_extension WHERE extname=\'bdr\'')->queryScalar()) {
                $i->execute('SET LOCAL default_sequenceam = \'bdr\';');
            }

            $entityDefinition = "seo_entity NOT NULL DEFAULT 'page'";
        } else {
            $entityDefinition = "ENUM('page') NOT NULL DEFAULT 'page'";
        }

        // Create 'seo' table
        $this->createTable('{{%seo}}', [
            'id'            => $this->primaryKey(),
            'entity'        => $entityDefinition,
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
            'description'   => $this->text()->notNull(),
            'keywords'      => $this->text()->notNull(),
            'created_at'    => $this->integer()->unsigned()->notNull(),
            'updated_at'    => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('seo_id_language', '{{%seo_lang}}', ['seo_id', 'language']);
        $this->createIndex('language', '{{%seo_lang}}', 'language');
        $this->addForeignKey('FK_SEO_LANG_SEO_ID', '{{%seo_lang}}', 'seo_id', '{{%seo}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('seo_lang');
        $this->dropTable('seo');
        if ($this->db->driverName === 'pgsql') {
            $this->execute(<<<SQL
DROP TYPE seo_entity;
SQL
            );
        }
    }
}
