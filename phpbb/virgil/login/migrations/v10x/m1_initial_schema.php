<?php

/**
 * @package   	Virgil Login
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license
 */
namespace virgil\login\migrations\v10x;

/**
 * Migration stage 1: Initial schema changes to the database
 */
class m1_initial_schema extends \phpbb\db\migration\migration
{

	/**
	 * Create the Social Login tables.
	 */
	public function update_schema ()
	{
        return array (
			'add_tables' => array (
				$this->table_prefix . 'oasl_identity' => array (
					'COLUMNS' => array (
						'oasl_identity_id' => array (
							'INT:10',
							NULL,
							'auto_increment'
						),
						'oasl_user_id' => array (
							'INT:10',
							NULL
						),
						'identity_token' => array (
							'VCHAR:255',
							''
						),
						'identity_provider' => array (
							'VCHAR:255',
							''
						),
						'num_logins' => array (
							'INT:10',
							NULL
						),
						'date_added' => array (
							'INT:10',
							NULL
						),
						'date_updated' => array (
							'INT:10',
							NULL
						)
					),
					'PRIMARY_KEY' => 'oasl_identity_id',
					'KEYS' => array (
						'oaid' => array (
							'UNIQUE',
							'oasl_identity_id'
						)
					)
				),
				$this->table_prefix . 'oasl_login_token' => array (
					'COLUMNS' => array (
						'oasl_login_token_id' => array (
							'INT:10',
							NULL,
							'auto_increment'
						),
						'login_token' => array (
							'VCHAR:255',
							''
						),
						'user_id' => array (
							'INT:10',
							NULL
						),
						'date_creation' => array (
							'INT:10',
							NULL
						)
					),
					'PRIMARY_KEY' => 'oasl_login_token_id',
					'KEYS' => array (
						'oatok' => array (
							'UNIQUE',
							'oasl_login_token_id'
						)
					)
				),
				$this->table_prefix . 'oasl_user' => array (
					'COLUMNS' => array (
						'oasl_user_id' => array (
							'INT:10',
							NULL,
							'auto_increment'
						),
						'user_id' => array (
							'INT:10',
							NULL
						),
						'user_token' => array (
							'VCHAR:255',
							''
						),
						'date_added' => array (
							'INT:10',
							NULL
						)
					),
					'PRIMARY_KEY' => 'oasl_user_id',
					'KEYS' => array (
						'oauid' => array (
							'UNIQUE',
							'oasl_user_id'
						)
					)
				)
			)
		);
	}

	/**
	 * Drop the Social Login tables.
	 */
	public function revert_schema ()
	{
	}
}
