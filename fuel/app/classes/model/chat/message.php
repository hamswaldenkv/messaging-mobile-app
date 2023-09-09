<?php

class Model_Chat_Message extends \Orm\Model_Soft
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"chat_thread_id" => array(
			"label" => "Chat thread id",
			"data_type" => "int",
		),
		"chat_message_id" => array(
			"label" => "Chat message id",
			"data_type" => "varchar",
		),
		"user_id" => array(
			"label" => "User id",
			"data_type" => "int",
		),
		"kind" => array(
			"label" => "Kind",
			"data_type" => "varchar",
		),
		"content" => array(
			"label" => "Content",
			"data_type" => "mediumtext",
		),
		"metafield" => array(
			"label" => "Metafield",
			"data_type" => "mediumtext",
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

	protected static $_table_name = 'chat_messages';

	protected static $_primary_key = array('id');

	protected static $_has_many = array();

	protected static $_many_many = array();

	protected static $_has_one = array();

	protected static $_belongs_to = array(
		'chat_thread', 'user'
	);
}
