<?php

/**
 * @package   	Virgil Login
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license
 */
namespace virgil\login\ucp;

class sociallogin_ucp_info
{
	function module ()
	{
		return array (
			'filename' => '\virgil\login\ucp\sociallogin_ucp_module',
			'title' => 'OA_SOCIAL_LOGIN_LINK_UCP',
			'version'   => '1.0.0',
			'modes' => array (
				'settings' => array (
					'title' => 'OA_SOCIAL_LOGIN_LINK_UCP',
					'auth' => 'acl_u_chgprofileinfo',
					'cat' => array ('UCP_PROFILE')
				)
			)
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
