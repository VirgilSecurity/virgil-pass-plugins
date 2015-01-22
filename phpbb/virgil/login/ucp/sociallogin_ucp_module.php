<?php

/**
 * @package   	Virgil Login
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license
 */
namespace virgil\login\ucp;

if (! defined ('IN_PHPBB'))
{
	exit ();
}

// Social Link Service
class sociallogin_ucp_module
{
	// Add Social Link to UCP \ Profile \ Social link.
	public function main ($id, $mode)
	{
		global $config, $db, $user, $auth, $template, $phpbb_root_path, $phpEx, $request, $phpbb_dispatcher;

		// User must be logged in and not a bot
		if (is_object ($user) && empty ($user->data ['isbot']) && (! empty ($user->data ['user_id']) && $user->data ['user_id'] != ANONYMOUS))
		{
			// Only display this in the UCP.
			if (! empty ($user->page ['page_name']) && strpos ($user->page ['page_name'], 'ucp') !== false)
			{
				// Initialize module.
				$sociallogin = new \virgil\login\acp\sociallogin_acp_module ();

				// Retrieve user_token.
				if (($user_token = $sociallogin->get_user_token_for_user_id ($user->data ['user_id'])) !== false)
				{
					$template->assign_var ('OA_SOCIAL_LINK_USER_TOKEN', $user_token);
				}

				// We have a login token.
				if (request_var ('oa_social_login_login_token', '') == '')
				{
					// Forge callback uri.
					$callback_uri = $sociallogin->get_current_url ();
					$callback_uri .= ((strpos ($callback_uri, '?') === false) ? '?' : '&');
					$callback_uri .= ('oa_social_login_login_token=' . $sociallogin->create_login_token_for_user_id ($user->data ['user_id']));
				}

				// Assign callback uri.
				$template->assign_var ('OA_SOCIAL_LINK_CALLBACK_URI', $callback_uri);
			}
		}

		// Set desired template
		$this->tpl_name = 'sociallogin_ucp_social_link';
		$this->page_title = 'OA_SOCIAL_LOGIN_LINK_UCP';
	}
}
