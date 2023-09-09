<?php

namespace Fuel\Migrations;

class Create_events
{
	public function up()
	{
		\DBUtil::create_table('events', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'event_id' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'title' => array('constraint' => 75, 'null' => false, 'type' => 'varchar'),
			'description' => array('constraint' => 300, 'null' => true, 'type' => 'varchar'),
			'photo_url' => array('constraint' => 400, 'null' => true, 'type' => 'varchar'),
			'organisator_name' => array('constraint' => 100, 'null' => true, 'type' => 'varchar'),
			'venue_place' => array('constraint' => 100, 'null' => true, 'type' => 'varchar'),
			'venue_address' => array('constraint' => 300, 'null' => true, 'type' => 'varchar'),
			'venue_location_latitude' => array('null' => true, 'type' => 'double'),
			'venue_location_longitude' => array('null' => true, 'type' => 'double'),
			'is_live' => array('null' => true, 'type' => 'tinyint'),
			'is_free' => array('null' => true, 'type' => 'tinyint'),
			'price_value' => array('null' => true, 'type' => 'double'),
			'price_currency' => array('constraint' => 4, 'null' => true, 'type' => 'varchar'),
			'metafield' => array('null' => true, 'type' => 'mediumtext'),
			'count_participants' => array('constraint' => 11, 'null' => true, 'type' => 'int'),
			'status' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'start_at' => array('constraint' => 11, 'null' => true, 'type' => 'int'),
			'finish_at' => array('constraint' => 11, 'null' => true, 'type' => 'int'),
			'deleted_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('events');
	}
}