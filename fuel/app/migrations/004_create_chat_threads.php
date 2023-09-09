<?php

namespace Fuel\Migrations;

class Create_chat_threads
{
	public function up()
	{
		\DBUtil::create_table('chat_threads', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'creator_user_id' => array('constraint' => 11, 'null' => true, 'type' => 'int'),
			'event_id' => array('constraint' => 11, 'null' => true, 'type' => 'int'),
			'chat_thread_id' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'kind' => array('constraint' => 45, 'null' => false, 'type' => 'varchar'),
			'last_message' => array('constraint' => 200, 'null' => false, 'type' => 'varchar'),
			'user_ids' => array('null' => false, 'type' => 'mediumtext'),
			'metafield' => array('null' => false, 'type' => 'mediumtext'),
			'status' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'deleted_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('chat_threads');
	}
}