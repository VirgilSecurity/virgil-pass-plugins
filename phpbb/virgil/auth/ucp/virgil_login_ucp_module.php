<?php

/**
 * @package   	Virgil Auth
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license
 */
namespace virgil\auth\ucp;

if (! defined ('IN_PHPBB'))
{
	exit ();
}

class virgil_login_ucp_module
{
    public function handle_callback ()
    {
        global $user, $template, $request;

        if($request->variable ('form_token', '') != '') {
            $this->set_virgil_implemented($user->data['user_id'], 0);
        }

        var_dump(1); exit();
        if($user->data['virgil_implemented']) {
            $template->assign_vars(array(
                'VIRGIL_IMPLEMENTED' => $user->data['virgil_implemented'],
                'IS_PROFILE' => true
            ));
        }
    }
    /**
     * Update user virgil_implemented status
     *
     * @param $user_id
     * @param int $implemented
     */
    protected function set_virgil_implemented($user_id, $implemented = 1)
    {
        global $db;
        $sql = 'UPDATE ' . USERS_TABLE . " SET virgil_implemented = " . $db->sql_escape ($implemented) . " WHERE user_id = " . intval ($user_id);
        $db->sql_query ($sql);
    }

}
