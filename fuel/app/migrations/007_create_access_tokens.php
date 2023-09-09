<?php

namespace Fuel\Migrations;

class Create_access_tokens
{
	public function up()
	{
		\DBUtil::create_table('access_tokens', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'access_token_id' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'user_id' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'type' => array('constraint' => 45, 'null' => false, 'type' => 'varchar'),
			'scope' => array('constraint' => 300, 'null' => false, 'type' => 'varchar'),
			'access_token' => array('null' => false, 'type' => 'mediumtext'),
			'refresh_token' => array('null' => true, 'type' => 'mediumtext'),
			'expires_at' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'refreshed_at' => array('constraint' => 11, 'null' => true, 'type' => 'int'),
			'status' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'deleted_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('access_tokens');
	}
}