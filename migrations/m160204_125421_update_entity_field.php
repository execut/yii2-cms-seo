<?php

use yii\db\Migration;
use yii\db\Schema;

class m160204_125421_update_entity_field extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('seo', 'entity', $this->string());
        $this->execute(<<<SQL
ALTER TABLE seo ALTER COLUMN entity DROP DEFAULT;
SQL
);
        if ($this->db->driverName === 'pgsql') {
            $this->execute(<<<SQL
DROP TYPE seo_entity;
SQL
            );
        }

        $this->execute(<<<SQL
ALTER TABLE seo ALTER COLUMN entity SET DEFAULT 'page'
SQL
        );
    }

    public function safeDown()
    {
        if ($this->db->driverName === 'pgsql') {
            $this->execute(<<<SQL
CREATE TYPE seo_entity AS ENUM ('page');
SQL
            );
            $this->dropColumn('seo', 'entity');
            $entityDefinition = "seo_entity NOT NULL DEFAULT 'page'";
            $this->addColumn('seo', 'entity', $entityDefinition);
        } else {
            $entityDefinition = "ENUM('page') NOT NULL DEFAULT 'page'";
            $this->alterColumn('seo', 'entity', $entityDefinition);
        }
    }
}
