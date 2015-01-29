<?php

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
