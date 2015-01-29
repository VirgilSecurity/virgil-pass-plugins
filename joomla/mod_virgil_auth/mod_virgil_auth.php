<?php

/*------------------------------------------------------------------------
# mod_oauth - XIPAT Open Authentication Module - Need com_oauth to work
# version: 1.0
# ------------------------------------------------------------------------
# author    Duong Tien Dung - www.xipat.com
# copyright Copyright (C) 2013 www.xipat.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.xipat.com
# Technical Support:  http://www.xipat.com/support.html
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die;

if (!defined('DS')) {
    define('DS', "/");
}
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$params->def('greeting', 1);


$type = ModVirgilAuthHelper::getType();
$return = ModVirgilAuthHelper::getReturnURL($params, $type);
$user = JFactory::getUser();
$siteUrl = JURI::root();

require JModuleHelper::getLayoutPath('mod_virgil_auth', $params->get('layout', 'default'));
