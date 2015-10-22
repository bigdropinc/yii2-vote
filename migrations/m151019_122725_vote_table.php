<?php

use yii\db\Migration;

class m151019_122725_vote_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%vote}}', [
            'user_id' => $this->bigInteger()->notNull(),
            'model_id' => $this->bigInteger()->notNull(),
            'model' => $this->string(32)->notNull(),
            'type' => $this->smallInteger(1)->notNull(),
            'ip' => $this->string(15)->defaultValue(null),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ], $tableOptions);
        $this->addPrimaryKey('pk-vote-user_id-model_id-model-type','{{%vote}}',['user_id','model_id','model','type']);
    }

    public function down()
    {
        return $this->dropTable('{{%vote}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
