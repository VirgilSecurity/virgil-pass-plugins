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
	 * Create the Virgil Login tables.
	 */
	public function update_schema ()
	{
        return array();
    }

	/**
	 * Drop the Virgil Login tables.
	 */
	public function revert_schema ()
	{
        return array();
	}
}
