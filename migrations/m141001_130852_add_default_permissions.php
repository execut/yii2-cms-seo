<?php

use yii\db\Schema;
use yii\db\Migration;

class m141001_130852_add_default_permissions extends Migration
{
    public function safeUp()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'showSeoModule',
            'type'          => 2,
            'description'   => 'Show SEO module icon in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        // Create the auth item relation
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showSeoModule'
        ]);
    }

    public function safeDown()
    {
        // Delete the auth item relation
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showSeoModule'
        ]);
        
        // Delete the auth items
        $this->delete('{{%auth_item}}', [
            'name'          => 'showSeoModule',
            'type'          => 2,
        ]);
    }
}
