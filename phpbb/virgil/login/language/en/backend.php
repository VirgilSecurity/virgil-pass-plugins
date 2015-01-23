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

// Virgil Login Backend.
$lang = array_merge ($lang, array (
    'VIRGIL_LOGIN_DO_ENABLE' => 'Enable Virgil Login?',
    'VIRGIL_LOGIN_DO_ENABLE_DESC' => 'Allows you to temporarily disable Virgil Login without having to remove it.',
    'VIRGIL_LOGIN_DO_ENABLE_YES' => 'Enable',
    'VIRGIL_LOGIN_DO_ENABLE_NO' => 'Disable',
    'VIRGIL_LOGIN_DEFAULT' => 'Default',
    'VIRGIL_LOGIN_SETTINGS' => 'Settings',
    'VIRGIL_LOGIN_REDIRECT_URL' => 'Redirect URL:',
    'VIRGIL_LOGIN_REDIRECT_URL_DESC' => 'URL where the system is redirected after successful authentication',
    'VIRGIL_LOGIN_SDK_URL' => 'Virgil SDK URL:',
    'VIRGIL_LOGIN_SDK_URL_DESC' => 'Virgil JavaScript SDK URL',
    'VIRGIL_LOGIN_AUTH_URL' => 'Virgil Auth URL:',
    'VIRGIL_LOGIN_AUTH_URL_DESC' => 'Virgil Authentication service URL',
    'VIRGIL_LOGIN_ACP' => 'Virgil Login',
    'VIRGIL_LOGIN_ACP_SETTINGS' => 'Settings',
    'VIRGIL_LOGIN_SETTNGS_UPDATED' => 'Virgil settings successfully updated.',
    'VIRGIL_LOGIN_PAGE_CAPTION' => 'Login with Virgil'
));
