<?php

namespace Fuel\Migrations;

class Create_user_devices
{
	public function up()
	{
		\DBUtil::create_table('user_devices', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'user_device_id' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'user_id' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'name' => array('constraint' => 45, 'null' => false, 'type' => 'varchar'),
			'unique_id' => array('constraint' => 300, 'null' => false, 'type' => 'varchar'),
			'device_id' => array('constraint' => 300, 'null' => false, 'type' => 'varchar'),
			'brand' => array('constraint' => 100, 'null' => true, 'type' => 'varchar'),
			'model' => array('constraint' => 100, 'null' => true, 'type' => 'varchar'),
			'platform' => array('constraint' => 100, 'null' => true, 'type' => 'varchar'),
			'os_version' => array('constraint' => 100, 'null' => true, 'type' => 'varchar'),
			'firebase_token' => array('null' => true, 'type' => 'mediumtext'),
			'status' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'deleted_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('user_devices');
	}
}