<?php

/**
 * @package   	Virgil Auth
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license
 */
namespace virgil\auth\migrations\v10x;

/**
 * Migration stage 3: Initial module
 */
class m3_initial_module extends \phpbb\db\migration\migration
{
	public function update_data ()
	{
		return array (

			// Add Virgil Auth group to ACP \ Extensions
			array (
				'module.add',
				array (
					'acp',
					'ACP_CAT_DOT_MODS',
					'VIRGIL_LOGIN_ACP'
				)
			),

			// Add Settings link to Virgil Auth group
			array (
				'module.add',
				array (
					'acp',
					'VIRGIL_LOGIN_ACP',
					array (
						'module_basename' => '\virgil\auth\acp\virgil_login_acp_module',
						'modes' => array (
							'settings'
						)
					)
				)
			),

			// Add Virgil Link group to UCP \ Profile
			array (
				'module.add',
				array (
					'ucp',
					'UCP_PROFILE',
					'OA_SOCIAL_LOGIN_LINK_UCP'
				)
			),

			// Add Settings link to Virgil Link group
			array (
				'module.add',
				array (
					'ucp',
					'OA_SOCIAL_LOGIN_LINK_UCP',
					array (
						'module_basename' => '\virgil\auth\ucp\virgil_login_ucp_module',
						'modes' => array (
							'settings'
						)
					)
				)
			)
		);
	}
}
