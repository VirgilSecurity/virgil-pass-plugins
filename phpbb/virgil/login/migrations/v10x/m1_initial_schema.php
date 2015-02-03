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
     * Extend phpbb users table
     */
    public function update_schema ()
    {
        return array (
            'add_columns' => array (
                $this->table_prefix . 'users' => array (
                    'virgil_implemented' => array (
                        'INT:1',
                        NULL
                    )
                )
            )
        );
    }

    /**
     * Drop plugin changes
     */
    public function revert_schema ()
    {
        return array(
            'drop_columns'        => array(
                $this->table_prefix . 'users' => array(
                    'virgil_implemented',
                )
            )
        );
    }
}
