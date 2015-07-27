<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class VirgilAuthController extends JControllerLegacy
{
    const VIRGIL_BASE_URL = 'https://auth-stg.virgilsecurity.com/api/v1';

    public function login() {
        $httpClient = JHttpFactory::getHttp();

        $response = $httpClient->get(self::VIRGIL_BASE_URL . '/token/' . JFactory::getApplication()->input->get('token') . '/info');
        if($response && $response->code == 200) {
            $response = json_decode($response->body);

            if($response->email) {
                $user = $this->_findUser($response->email);

                if($user->id) {
                    $this->_initSession($user);

                    JFactory::getApplication()->enqueueMessage(JText::_('You are successfully logged in'), 'Success');
                } else {
                    // If registration is disabled - Redirect to login page.
                    if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
                        $this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));

                        return false;
                    }

                    $user = $this->_registerUser($response);
                    if($user) {
                        $this->_initSession($user);

                        JFactory::getApplication()->enqueueMessage(JText::_('You are successfully logged in'), 'Success');
                    } else {
                        JFactory::getApplication()->enqueueMessage(JText::_('User registration failed'), 'error');
                    }
                }
            } else {
                JFactory::getApplication()->enqueueMessage(JText::_('Unable to register user.'), 'error');
            }

        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('Authentication failed'), 'error');
        }

        $this->setRedirect(JRoute::_('/', false));
    }

    private function _findUser($email) {
        $db = JFactory::getDbo();

        $sql = "SELECT * FROM #__users WHERE email = " . $db->quote($email);
        $db->setQuery($sql);

        return $db->loadObject();
    }

    private function _initSession($user) {
        $session = JFactory::getSession();
        $session->set('user', new JUser($user->id));

        return JFactory::getUser();    }

    private function _registerUser($response) {
        $instance = JUser::getInstance();

        jimport('joomla.application.component.helper');

        $defaultUserGroup = 2;

        jimport('joomla.user.helper');
        $salt	  = JUserHelper::genRandomPassword(32);
        $password = JUserHelper::genRandomPassword(8);
        $password_clear = $password;

        $encrypted = JUserHelper::getCryptedPassword($password_clear, $salt);
        $password  = $encrypted . ':' . $salt;

        $instance->set('id'             , 0);
        $instance->set('name'           , $response->first_name . ' ' . $response->last_name);
        $instance->set('username'       , $response->email);
        $instance->set('password'       , $password);
        $instance->set('password_clear' , $password_clear);
        $instance->set('email'          , $response->email);
        $instance->set('groups'         , array($defaultUserGroup));
        $instance->set('sendEmail'      , true);

        $instance->save();

        return $this->_findUser($response->email);
    }
}
