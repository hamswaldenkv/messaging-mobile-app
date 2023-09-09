<?php

namespace Fuel\Migrations;

class Create_event_participants
{
	public function up()
	{
		\DBUtil::create_table('event_participants', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'event_id' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'user_id' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'event_participant_id' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'status' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'deleted_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('event_participants');
	}
}