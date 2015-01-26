<?php

/**
 * @package   	Virgil Login
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license
 */
namespace virgil\login\acp;

class virgil_login_acp_info
{
	function module ()
	{
		return array (
			'filename' => '\virgil\login\acp\virgil_login_acp_module',
			'title' => 'VIRGIL_LOGIN_ACP',
			'modes' => array (
				'settings' => array (
					'title' => 'VIRGIL_LOGIN_ACP_SETTINGS',
					'auth' => 'acl_a_board',
					'cat' => array ()
				)
			)
		);
	}
}
