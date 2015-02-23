<?php if (!defined('APPLICATION')) exit();
session_start();
// Define the plugin:
$PluginInfo['SocialLogin'] = array(
    'Name' => 'Virgil Pass',
    'Description' => 'Virgil Pass Extension',
    'Version' => '1.0',
    'RequiredTheme' => FALSE,
    'RequiredPlugins' => FALSE,
    'MobileFriendly' => TRUE,
    'SettingsUrl' => '/dashboard/settings/VirgilPass',
    'SettingsPermission' => 'Garden.Settings.Manage',
    'HasLocale' => TRUE,
    'RegisterPermissions' => FALSE,
    'Author' => "VirgilSecurity",
    'AuthorEmail' => 'support@virgilsecurity.com',
    'AuthorUrl' => 'http://www.virgilsecurity.com/'
);

class VirgilPassPlugin extends Gdn_Plugin {

    /**
     * Add Social Login interface at entry controller Popup.
     */
    public function EntryController_SignIn_Handler($Sender, $Args) {

        if(C('Plugins.VirgilPass.disabled') == 'no') {
            $Sender->Head->AddString('<script src="' . C('Plugins.VirgilPass.sdkUrl') . '"></script>');
            $SignInHtml='<div style="margin-top:10px">
                            <p class="virgil-login">
                                <a href="https://auth-demo.virgilsecurity.com/uploads/virgil-chrome.zip">Download Virgil Extension</a>
                                <button data-virgil-ui="auth-btn" data-virgil-reference="http://virgil.phpbb.local/ucp.php?mode=login&amp;token={{virgilToken}}">Virgil Auth</button>
                            </p>
                        </div>';

            $VirgilPassMethod = array(
                'Name' => 'VirgilLogin',
                'SignInHtml' => $SignInHtml
            );

            $Sender->Data['Methods'][] = $VirgilPassMethod;
        }
    }

    /**
     * Plugin disable event. Clear plugin configuration options
     */
    public function onDisable() {

        RemoveFromConfig('Plugins.VirgilPass.disabled');
        RemoveFromConfig('Plugins.VirgilPass.redirectUrl');
        RemoveFromConfig('Plugins.VirgilPass.sdkUrl');
        RemoveFromConfig('Plugins.VirgilPass.authUrl');

    }

    /**
     * Plugin Setup Event. Setup default plugin options
     * @return bool|void
     */
    public function Setup() {

        SaveToConfig('Plugins.VirgilPass.disabled', 'no');
        SaveToConfig('Plugins.VirgilPass.redirectUrl', 'yes');
        SaveToConfig('Plugins.VirgilPass.sdkUrl', 'https://auth-demo.virgilsecurity.com/js/sdk.js');
        SaveToConfig('Plugins.VirgilPass.authUrl', 'https://auth.virgilsecurity.com');
    }
}