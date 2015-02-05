<?php
/**
 * @package   	Virgil Pass
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license     BSD
 */
if (! defined ('IN_PHPBB'))
{
	exit ();
}

if (empty ($lang) || ! is_array ($lang))
{
	$lang = array ();
}

// Virgil Auth Backend.
$lang = array_merge ($lang, array (
    'VIRGIL_PASS_DO_ENABLE' => 'Enable Virgil Pass?',
    'VIRGIL_PASS_DO_ENABLE_DESC' => 'Allows you to temporarily disable Virgil Pass without having to remove it.',
    'VIRGIL_PASS_DO_ENABLE_YES' => 'Enable',
    'VIRGIL_PASS_DO_ENABLE_NO' => 'Disable',
    'VIRGIL_PASS_DEFAULT' => 'Default',
    'VIRGIL_PASS_SETTINGS' => 'Settings',
    'VIRGIL_PASS_REDIRECT_URL' => 'Redirect URL:',
    'VIRGIL_PASS_REDIRECT_URL_DESC' => 'URL where the system is redirected after successful authentication',
    'VIRGIL_PASS_SDK_URL' => 'Virgil SDK URL:',
    'VIRGIL_PASS_SDK_URL_DESC' => 'Virgil JavaScript SDK URL',
    'VIRGIL_PASS_AUTH_URL' => 'Virgil Auth URL:',
    'VIRGIL_PASS_AUTH_URL_DESC' => 'Virgil Authentication service URL',
    'VIRGIL_PASS_ACP' => 'Virgil Pass',
    'VIRGIL_PASS_ACP_SETTINGS' => 'Settings',
    'VIRGIL_PASS_SETTNGS_UPDATED' => 'Virgil settings successfully updated.',
    'VIRGIL_PASS_PAGE_CAPTION' => 'Login with Virgil'
));
