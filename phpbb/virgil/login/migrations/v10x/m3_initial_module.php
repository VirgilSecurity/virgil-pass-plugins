<?php

/**
 * @package   	Virgil Login
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license
 */
namespace virgil\login\migrations\v10x;

/**
 * Migration stage 3: Initial module
 */
class m3_initial_module extends \phpbb\db\migration\migration
{
	public function update_data ()
	{
		return array (

			// Add Social Login group to ACP \ Extensions
			array (
				'module.add',
				array (
					'acp',
					'ACP_CAT_DOT_MODS',
					'VIRGIL_LOGIN_ACP'
				)
			),

			// Add Settings link to Social Login group
			array (
				'module.add',
				array (
					'acp',
					'VIRGIL_LOGIN_ACP',
					array (
						'module_basename' => '\virgil\login\acp\sociallogin_acp_module',
						'modes' => array (
							'settings'
						)
					)
				)
			),

			// Add Social Link group to UCP \ Profile
			array (
				'module.add',
				array (
					'ucp',
					'UCP_PROFILE',
					'OA_SOCIAL_LOGIN_LINK_UCP'
				)
			),

			// Add Settings link to Social Link group
			array (
				'module.add',
				array (
					'ucp',
					'OA_SOCIAL_LOGIN_LINK_UCP',
					array (
						'module_basename' => '\virgil\login\ucp\sociallogin_ucp_module',
						'modes' => array (
							'settings'
						)
					)
				)
			)
		);
	}
}
