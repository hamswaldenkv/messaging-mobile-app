<?php

namespace Fuel\Migrations;

class Create_chat_messages
{
	public function up()
	{
		\DBUtil::create_table('chat_messages', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'chat_thread_id' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'chat_message_id' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'user_id' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'kind' => array('constraint' => 45, 'null' => false, 'type' => 'varchar'),
			'content' => array('null' => false, 'type' => 'mediumtext'),
			'metafield' => array('null' => false, 'type' => 'mediumtext'),
			'status' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'deleted_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('chat_messages');
	}
}