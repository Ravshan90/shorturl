<?php

use yii\db\Migration;


/**
 * Handles the creation for table `short_urls`.
 */
class m160731_114540_create_short_urls_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%short_urls}}', [
            'id' => $this->primaryKey(),
            'long_url' => $this->text()->notNull(),
            'short_code' => $this->string(6)->notNull(),
            'time_create' => $this->date()->notNull()
        ], $tableOptions);
    }
	

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('short_urls');
    }
}
