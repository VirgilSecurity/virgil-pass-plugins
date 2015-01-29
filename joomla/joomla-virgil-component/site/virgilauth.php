<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('VirgilAuth');
$controller->execute(JFactory::getApplication()->input->getCmd('task', 'login'));
$controller->redirect();
