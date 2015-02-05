<?php

/**
 * @package   	Virgil Pass
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license     BSD
 */
namespace virgil\pass\migrations\v10x;

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
					'VIRGIL_PASS_ACP'
				)
			),

			// Add Settings link to Virgil Auth group
			array (
				'module.add',
				array (
					'acp',
					'VIRGIL_PASS_ACP',
					array (
						'module_basename' => '\virgil\pass\acp\virgil_pass_acp_module',
						'modes' => array (
							'settings'
						)
					)
				)
			)
		);
	}
}
