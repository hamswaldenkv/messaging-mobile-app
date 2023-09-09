<?php

class Model_Event extends \Orm\Model_Soft
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"event_id" => array(
			"label" => "Event id",
			"data_type" => "varchar",
		),
		"title" => array(
			"label" => "Title",
			"data_type" => "varchar",
		),
		"description" => array(
			"label" => "Description",
			"data_type" => "varchar",
		),
		"photo_url" => array(
			"label" => "Photo url",
			"data_type" => "varchar",
		),
		"organisator_name" => array(
			"label" => "Organisator name",
			"data_type" => "varchar",
		),
		"venue_place" => array(
			"label" => "Venue place",
			"data_type" => "varchar",
		),
		"venue_address" => array(
			"label" => "Venue address",
			"data_type" => "varchar",
		),
		"venue_location_latitude" => array(
			"label" => "Venue location latitude",
			"data_type" => "double",
		),
		"venue_location_longitude" => array(
			"label" => "Venue location longitude",
			"data_type" => "double",
		),
		"is_live" => array(
			"label" => "Is live",
			"data_type" => "tinyint",
		),
		"is_free" => array(
			"label" => "Is free",
			"data_type" => "tinyint",
		),
		"price_value" => array(
			"label" => "Price value",
			"data_type" => "double",
		),
		"price_currency" => array(
			"label" => "Price currency",
			"data_type" => "varchar",
		),
		"metafield" => array(
			"label" => "Metafield",
			"data_type" => "mediumtext",
		),
		"count_participants" => array(
			"label" => "Count participants",
			"data_type" => "int",
		),
		"status" => array(
			"label" => "Status",
			"data_type" => "int",
		),
		"start_at" => array(
			"label" => "Start at",
			"data_type" => "int",
		),
		"finish_at" => array(
			"label" => "Finish at",
			"data_type" => "int",
		),
		"deleted_at" => array(
			"label" => "Deleted at",
			"data_type" => "int",
		),
		"created_at" => array(
			"label" => "Created at",
			"data_type" => "int",
		),
		"updated_at" => array(
			"label" => "Updated at",
			"data_type" => "int",
		),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'property' => 'created_at',
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'property' => 'updated_at',
			'mysql_timestamp' => false,
		),
	);

	protected static $_soft_delete = array(
		'mysql_timestamp' => false,
		'deleted_field' => 'deleted_at',
	);

	protected static $_table_name = 'events';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
		'participants'	=>	[
			'model_to' 	=> 'Model_Event_Participant',
			'key_from'	=>	'id',
			'key_to'	=>	'event_id'
		]
	);

	protected static $_many_many = array();

	protected static $_has_one = array();

	protected static $_belongs_to = array();
}
