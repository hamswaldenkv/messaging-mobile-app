<?php

class Model_User extends \Orm\Model_Soft
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"user_id" => array(
			"label" => "User id",
			"data_type" => "varchar",
		),
		"username" => array(
			"label" => "Username",
			"data_type" => "varchar",
		),
		"password" => array(
			"label" => "Password",
			"data_type" => "varchar",
		),
		"email_address" => array(
			"label" => "Email address",
			"data_type" => "varchar",
		),
		"email_validated" => array(
			"label" => "Email validated",
			"data_type" => "tinyint",
		),
		"name" => array(
			"label" => "Name",
			"data_type" => "varchar",
		),
		"first_name" => array(
			"label" => "First name",
			"data_type" => "varchar",
		),
		"last_name" => array(
			"label" => "Last name",
			"data_type" => "varchar",
		),
		"photo_url" => array(
			"label" => "Photo url",
			"data_type" => "varchar",
		),
		"birth_date" => array(
			"label" => "Birth date",
			"data_type" => "tinyint",
		),
		"country_code" => array(
			"label" => "Country code",
			"data_type" => "varchar",
		),
		"metafield" => array(
			"label" => "Metafield",
			"data_type" => "mediumtext",
		),
		"account_state" => array(
			"label" => "Account state",
			"data_type" => "int",
		),
		"last_login" => array(
			"label" => "Last login",
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

	protected static $_table_name = 'users';

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
