<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'user_id' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'username' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'password' => array('constraint' => 150, 'null' => false, 'type' => 'varchar'),
			'email_address' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'email_validated' => array('null' => false, 'type' => 'tinyint'),
			'name' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'first_name' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'last_name' => array('constraint' => 75, 'null' => true, 'type' => 'varchar'),
			'photo_url' => array('constraint' => 200, 'null' => true, 'type' => 'varchar'),
			'birth_date' => array('null' => true, 'type' => 'date'),
			'country_code' => array('constraint' => 3, 'null' => false, 'type' => 'varchar'),
			'metafield' => array('null' => true, 'type' => 'mediumtext'),
			'account_state' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'last_login' => array('constraint' => 11, 'null' => true, 'type' => 'int'),
			'deleted_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}