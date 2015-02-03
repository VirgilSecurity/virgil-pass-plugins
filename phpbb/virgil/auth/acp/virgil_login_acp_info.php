<?php

/**
 * @package   	Virgil Auth
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license
 */
namespace virgil\auth\acp;

class virgil_login_acp_info
{
	function module ()
	{
		return array (
			'filename' => '\virgil\auth\acp\virgil_login_acp_module',
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
