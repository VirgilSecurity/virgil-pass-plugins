<?php

/**
 * @package   	Virgil Pass
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license     BSD
 */
namespace virgil\pass\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	// @var \phpbb\config\config
	protected $config;

	// @var \phpbb\config\db_text
	protected $config_text;

	// @var \phpbb\controller\helper
	protected $controller_helper;

	// @var \phpbb\request\request
	protected $request;

	// @var \phpbb\template\template
	protected $template;

	// @var \phpbb\user
	protected $user;

	/**
	 * Constructor
	 */
	public function __construct (\phpbb\config\config $config,\phpbb\config\db_text $config_text,\phpbb\controller\helper $controller_helper,\phpbb\request\request $request,\phpbb\template\template $template,\phpbb\user $user)
	{
		$this->config = $config;
		$this->config_text = $config_text;
		$this->controller_helper = $controller_helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
	}


	/**
	 * Assign functions defined in this class to event listeners in the core
	 */
	static public function getSubscribedEvents ()
	{
		return array (
			'core.page_header' => 'setup',
			'core.user_setup' => 'add_language'
		);
	}

	/**
	 * Add Virgil Pass language file.
	 */
	public function add_language ($event)
	{
		// Read language settings.
		$lang_set_ext = $event['lang_set_ext'];

		// Add frontend language strings.
		$lang_set_ext[] = array(
			'ext_name' => 'virgil/pass',
			'lang_set' => 'frontend'
		);

		// Add backend language strings.
		$lang_set_ext[] = array(
			'ext_name' => 'virgil/pass',
			'lang_set' => 'backend'
		);

		// Set language settings.
		$event['lang_set_ext'] = $lang_set_ext;
	}


	/**
	 * Setup Virgil Pass.
	 */
	public function setup ($event)
	{
        // The plugin must be enabled and the API settings must be filled out
		if (empty($this->config ['virgil_pass_page_disable']))
		{
			// First check for a callback
			$this->check_callback ();

            // Setup template placeholders
			$this->template->assign_var ('VIRGIL_PASS_REDIRECT_URL', $this->config ['virgil_pass_redirect_url']);
			$this->template->assign_var ('VIRGIL_PASS_SDK_URL', $this->config ['virgil_pass_sdk_url']);
			$this->template->assign_var ('VIRGIL_PASS_AUTH_URL', $this->config ['virgil_pass_auth_url']);
			$this->template->assign_var ('VIRGIL_PASS_DISABLED', $this->config ['virgil_pass_disable']);

            // User must not be logged in
			if ( empty ($this->user->data['user_id']) || $this->user->data['user_id'] == ANONYMOUS)
			{
				// Embed on the login page
				if ($this->request->variable ('mode', '') == 'login')
				{
                    // Can be changed in the virgil auth settings.
					if (empty ($this->config ['virgil_pass_page_disable']))
					{
                        // Trigger icons.
						$this->template->assign_var ('VIRGIL_PASS_EMBED', 1);
					}
				}
			}
		}
	}

	/**
	 * Hook used for the callback handler.
	 */
	public function check_callback ()
	{
        // Handle request after Virgil Login
		if ($this->request->variable ('token', ''))
		{
            $virgillogin = new \virgil\pass\acp\virgil_pass_acp_module ();
            $virgillogin->handle_callback ();
		}

        // Handle request to the profile page
        if($this->request->variable ('mode', '') == 'reg_details')
        {
            $userprofile = new \virgil\pass\ucp\virgil_pass_ucp_module();
            $userprofile->handle_callback ();
        }
	}
}
