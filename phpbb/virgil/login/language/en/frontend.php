<?php
/**
 * @package   	Virgil Login
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license
 */

if (! defined ('IN_PHPBB'))
{
	exit ();
}

if (empty ($lang) || ! is_array ($lang))
{
	$lang = array ();
}

// Social Login Frontend.
$lang = array_merge ($lang, array (
	'OA_SOCIAL_LOGIN_LINK_UCP' => 'Link social network accounts',
	'OA_SOCIAL_LOGIN_LINK' => 'Link social network accounts',
	'OA_SOCIAL_LOGIN_LINK_NETWORKS' => 'Social Networks',
	'OA_SOCIAL_LOGIN_LINK_DESC1' => 'On this page you can connect your social network accounts to your forum account.',
	'OA_SOCIAL_LOGIN_LINK_DESC2' => 'After having connected a social network account you can also use it to login to the forum.',
	'OA_SOCIAL_LOGIN_LINK_ACTION' => 'Click on the icon of social network to link/unlink.',
	'OA_SOCIAL_LOGIN_ENABLE_SOCIAL_NETWORK' => 'You have to enable at least one social network',
	'OA_SOCIAL_LOGIN_ENTER_CREDENTIALS' => 'You have to setup your API credentials',
	'OA_SOCIAL_LOGIN_SOCIAL_LINK' => 'Social Link Service',
	'OA_SOCIAL_LOGIN_ACCOUNT_ALREADY_LINKED' => 'This social network account is already linked to another forum user.',
	'OA_SOCIAL_LOGIN_ACCOUNT_INACTIVE_OTHER' => 'The account has been created. However, the forum settings require account activation.<br />An activation key has been sent to your email address.',
	'OA_SOCIAL_LOGIN_ACCOUNT_INACTIVE_ADMIN' => 'The account has been created. However, the forum settings require account activation by an administrator.<br />An email has been sent to the administrators and you will be informed by email once your account has been activated.'
));
