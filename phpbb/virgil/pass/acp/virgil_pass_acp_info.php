<?php

/**
 * @package   	Virgil Pass
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license     BSD
 */
namespace virgil\pass\acp;

class virgil_pass_acp_info
{
	function module ()
	{
		return array (
			'filename' => '\virgil\pass\acp\virgil_pass_acp_module',
			'title' => 'VIRGIL_PASS_ACP',
			'modes' => array (
				'settings' => array (
					'title' => 'VIRGIL_PASS_ACP_SETTINGS',
					'auth' => 'acl_a_board',
					'cat' => array ()
				)
			)
		);
	}
}
