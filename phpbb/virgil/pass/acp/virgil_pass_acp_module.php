<?php
/**
 * @package   	Virgil Pass
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license     BSD
 */
namespace virgil\pass\acp;

class virgil_pass_acp_module
{

	// @var \phpbb\config\config
	protected $config;

	// @var \phpbb\config\db_text
	protected $config_text;

	// @var \phpbb\db\driver\driver_interface
	protected $db;

	// @var \phpbb\log\log
	protected $log;

	// @var \phpbb\request\request
	protected $request;

	// @var \phpbb\template\template
	protected $template;

	// @var \phpbb\user
	protected $user;

	// @var ContainerInterface
	protected $phpbb_container;

	// @var string
	protected $phpbb_root_path;

	// @var string
	protected $php_ext;

	// @var string
	public $u_action;


	/**
	 * Main Function
	 */
	public function main ($id, $mode)
	{
		// Default.
		return $this->admin_main ();
	}

	/**
	 * Admin Main Page
	 */
	public function admin_main ()
	{
		global $user, $template, $config, $request;

		// Add the language file.
		$user->add_lang_ext ('virgil/pass', 'backend');

		// Set up the page
		$this->tpl_name = 'settings';

		// Enable Virgil Pass?
		$virgil_pass_disable = ((isset ($config ['virgil_pass_disable']) && $config ['virgil_pass_disable'] == '1') ? '1' : '0');

		// Virgil Auth settings
        $virgil_pass_redirect_url = (isset ($config ['virgil_pass_redirect_url']) ? $config ['virgil_pass_redirect_url'] : $config ['server_protocol'] . $config ['server_name'] . '/ucp.php?mode=login&token={{virgilToken}}');
        $virgil_pass_sdk_url      = (isset ($config ['virgil_pass_sdk_url'])      ? $config ['virgil_pass_sdk_url']      : 'https://auth-demo.virgilsecurity.com/js/sdk.js');
        $virgil_pass_auth_url     = (isset ($config ['virgil_pass_auth_url'])     ? $config ['virgil_pass_auth_url']     : 'https://auth.virgilsecurity.com');

		// Triggers a form message.
		$virgil_pass_settings_saved = false;

		// Form submitted
		if (isset ($_POST ['submit']))
		{
            // Triggers a form message
            $virgil_pass_settings_saved = true;

			// Gather Settings parameters
            $virgil_pass_disable      = $request->variable ('virgil_pass_disable', '');
            $virgil_pass_redirect_url = $request->variable ('virgil_pass_redirect_url', '');
			$virgil_pass_sdk_url      = $request->variable ('virgil_pass_sdk_url', '');
			$virgil_pass_auth_url     = $request->variable ('virgil_pass_auth_url', '');

            $virgil_pass_redirect_url = !empty($virgil_pass_redirect_url) ? $virgil_pass_redirect_url : $config ['server_protocol'] . $config ['server_name'] . '/ucp.php?mode=login&token={{virgilToken}}';
            $virgil_pass_sdk_url      = !empty($virgil_pass_sdk_url)      ? $virgil_pass_sdk_url      : 'https://auth-demo.virgilsecurity.com/js/sdk.js';
            $virgil_pass_auth_url     = !empty($virgil_pass_auth_url)     ? $virgil_pass_auth_url     : 'https://auth.virgilsecurity.com';

            // Save configuration.
			$config->set ('virgil_pass_disable', $virgil_pass_disable);
			$config->set ('virgil_pass_redirect_url', $virgil_pass_redirect_url);
            $config->set ('virgil_pass_sdk_url', $virgil_pass_sdk_url);
            $config->set ('virgil_pass_auth_url', $virgil_pass_auth_url);

		}

		// Setup Vars
		$template->assign_vars (array (
			'U_ACTION' => $this->u_action,
			'CURRENT_SID' => $user->data ['session_id'],
            'VIRGIL_PASS_DISABLED' => $virgil_pass_disable,
            'VIRGIL_PASS_REDIRECT_URL' => $virgil_pass_redirect_url,
            'VIRGIL_PASS_SDK_URL' => $virgil_pass_sdk_url,
            'VIRGIL_PASS_AUTH_URL' => $virgil_pass_auth_url,
            'VIRGIL_PASS_SETTINGS_SAVED' => $virgil_pass_settings_saved
		));

		// Done
		return true;
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

    /**
     * Get the user_id for a given email address.
     */
    protected function get_user_id_by_email ($email)
    {
        global $db;

        // Read the user_id for this email address.
        $sql = "SELECT user_id FROM " . USERS_TABLE . " WHERE user_email  = '" . $db->sql_escape ($email) . "'";
        $query = $db->sql_query_limit ($sql, 1);
        $result = $db->sql_fetchrow ($query);
        $db->sql_freeresult ($query);

        // We have found an user_id.
        if (is_array ($result) && ! empty ($result ['user_id']))
        {
            return $result ['user_id'];
        }

        // Not found.
        return false;
    }

    /**
     * Generates a random hash of the given length
     */
    protected function generate_hash ($length)
    {
        $hash = '';

        for ($i = 0; $i < $length; $i++)
        {
            do
            {
                $char = chr (mt_rand (48, 122));
            }
            while (! preg_match ('/[a-zA-Z0-9]/', $char));
            $hash .= $char;
        }

        // Done
        return $hash;
    }

    /**
     * Get the default group_id for new users
     */
    function get_default_group_id ()
    {
        global $db;

        // Read the default group.
        $sql = "SELECT group_id FROM " . GROUPS_TABLE . " WHERE group_name = 'REGISTERED' AND group_type = " . GROUP_SPECIAL;
        $query = $db->sql_query ($sql);
        $result = $db->sql_fetchrow ($query);
        $db->sql_freeresult ($query);

        // Group found;
        if (is_array ($result) && isset ($result ['group_id']))
        {
            return $result ['group_id'];
        }

        // Not found
        return false;
    }

    /**
     * Login the current user with the give $user_id.
     */
    protected function do_login ($user_id, $check_admin = false)
    {
        global $auth, $db, $user;

        // Grab the list of admins to check if this user is an administrator.
        if ($check_admin === true)
        {
            $admin_user_ids = $auth->acl_get_list (false, 'a_user', false);
            $admin_user_ids = (! empty ($admin_user_ids [0] ['a_user'])) ? $admin_user_ids [0] ['a_user'] : array ();
            $is_admin = (in_array ($user_id, $admin_user_ids) ? true : false);

            // Store the old session id for later use.
            $old_session_id = $user->session_id;

            // This user is an administrator.
            if ($is_admin === true)
            {
                global $SID, $_SID;

                // Refresh the cookie.
                $cookie_expire = time () - 31536000;
                $user->set_cookie ('u', '', $cookie_expire);
                $user->set_cookie ('sid', '', $cookie_expire);

                // Refresh the session id.
                $SID = '?sid=';
                $user->session_id = $_SID = '';
            }
        }
        else
        {
            $is_admin = false;
        }

        // Log the user in.
        $result = $user->session_create ($user_id, $is_admin);

        // Session created successfully.
        if ($result === true)
        {
            // For admins we remove the old session entry because a new one has been created.
            if ($is_admin === true)
            {
                $sql = 'DELETE FROM ' . SESSIONS_TABLE . " WHERE session_id = '" . $db->sql_escape ($old_session_id) . "' AND session_user_id = " . intval ($user_id) . "";
                $db->sql_query ($sql);
            }

            // We re-init the auth array to get correct results on login/logout.
            $auth->acl ($user->data);

            // Done.
            return true;
        }

        // An error has occurred.
        return false;
    }

	/**
	 * Callback Handler.
	 */
	public function handle_callback ()
	{
		// Required global variables.
		global $user, $config, $template, $phpbb_root_path, $phpEx, $request, $phpbb_container;

		// Add language file.
		$user->add_lang_ext ('virgil/pass', 'frontend');

		// Read arguments.
		$token = trim($request->variable ('token', ''));

		// Make sure we need to call the callback handler.
		if (!empty($token))
		{
            // Make sure Virgil Auth is enabled.
			if (empty ($config ['virgil_pass_disable']))
			{

                $error = false;
                $virgil_auth_client = new \virgil\pass\client\virgil_auth_client(array(
                    'auth_url' => $config ['virgil_pass_auth_url']
                ));

                if(!$virgil_auth_client->verify_token($token))
                {
                    $template->assign_vars(array(
                        'VIRGIL_PASS_WARNING' => true,
                        'VIRGIL_PASS_VALIDATION_MESSAGE' => 'Virgil token validation failed.'
                    ));
                    $error = true;
                }

                if(($userInfo = $virgil_auth_client->get_user_info_by_token($token)) == false)
                {
                    $template->assign_vars(array(
                        'VIRGIL_PASS_WARNING' => true,
                        'VIRGIL_PASS_VALIDATION_MESSAGE' => 'Virgil user was not found.'
                    ));
                    $error = true;
                }

                if(!$error)
                {
                    // Check if user already exists
                    $user_id = $this->get_user_id_by_email ($userInfo['email']);

                    // No user has been linked to this token yet.
                    if (! is_numeric ($user_id))
                    {
                        // User functions
                        if (! function_exists ('user_add'))
                        {
                            require ($phpbb_root_path . 'includes/functions_user.' . $phpEx);
                        }

                        $password_manager = $phpbb_container->get('passwords.manager');

                        // Generate a random password.
                        $new_password = $this->generate_hash ($config ['min_pass_chars'] + rand (3, 5));

                        // Construct user name
                        if(!isset($userInfo['first_name']) && !isset($userInfo['last_name'])) {
                            list($userName, $userDomain) = explode('@', $userInfo['email']);
                        } else {
                            $userName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
                        }

                        // Setup user details.
                        $user_row = array (
                            'group_id' =>  $this->get_default_group_id (),
                            'user_type' => USER_NORMAL,
                            'user_actkey' => '',
                            'user_password' => $password_manager->hash($new_password),
                            'user_ip' => $user->ip,
                            'user_inactive_reason' => 0,
                            'user_inactive_time' => 0,
                            'user_lastvisit' => time (),
                            'user_lang' => ! empty ($config ['default_lang']) ? trim ($config ['default_lang']) : 'en',
                            'username' => $userName,
                            'user_email' => $userInfo['email']
                        );

                        // Register user.
                        $user_id = user_add ($user_row, false);

                        if( is_numeric($user_id))
                        {
                            // Log the user in
                            $this->do_login ($user_id);
                        }

                        // Update virgil implemented
                        $this->set_virgil_implemented($user_id, 1);

                        redirect ($phpbb_root_path . 'index.' . $phpEx);

                    } else
                    {

                        // Update virgil implemented
                        $this->set_virgil_implemented($user_id, 1);

                        // Log the user in
                        $this->do_login ($user_id);

                        redirect ($phpbb_root_path . 'index.' . $phpEx);
                    }
                }
            }
        }
	}
}