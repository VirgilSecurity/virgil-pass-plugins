<?php

/**
 * @package   	Virgil Pass
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license     BSD
 */
namespace virgil\pass\migrations\v10x;

/**
 * Migration stage 2: Initial data changes to the database
 */
class m2_initial_data extends \phpbb\db\migration\migration
{
	public function update_data ()
	{
        global $config;

        // Save configuration.
        $config->set ('virgil_pass_disable', 0);
        $config->set ('virgil_pass_redirect_url', $config ['server_protocol'] . $config ['server_name'] . '/ucp.php?mode=login&token={{virgilToken}}');
        $config->set ('virgil_pass_sdk_url',  'https://auth-demo.virgilsecurity.com/js/sdk.js');
        $config->set ('virgil_pass_auth_url', 'https://auth-stg.virgilsecurity.com/api/v1');

		return array ();
	}

    public function revert_data ()
    {

        global $config;

        $config->delete ('virgil_pass_disable');
        $config->delete ('virgil_pass_redirect_url');
        $config->delete ('virgil_pass_sdk_url');
        $config->delete ('virgil_pass_auth_url');
    }
}
