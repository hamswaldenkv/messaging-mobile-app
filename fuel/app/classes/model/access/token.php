<?php

class Model_Access_Token extends \Orm\Model_Soft
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"access_token_id" => array(
			"label" => "Access token id",
			"data_type" => "varchar",
		),
		"user_id" => array(
			"label" => "User id",
			"data_type" => "int",
		),
		"type" => array(
			"label" => "Type",
			"data_type" => "varchar",
		),
		"scope" => array(
			"label" => "Scope",
			"data_type" => "varchar",
		),
		"access_token" => array(
			"label" => "Access token",
			"data_type" => "mediumtext",
		),
		"refresh_token" => array(
			"label" => "Refresh token",
			"data_type" => "mediumtext",
		),
		"expires_at" => array(
			"label" => "Expires at",
			"data_type" => "int",
		),
		"refreshed_at" => array(
			"label" => "Refreshed at",
			"data_type" => "int",
		),
		"status" => array(
			"label" => "Status",
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

	protected static $_table_name = 'access_tokens';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
	);

	protected static $_many_many = array(
	);

	protected static $_has_one = array(
	);

	protected static $_belongs_to = array(
	);

}
