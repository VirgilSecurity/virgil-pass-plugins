<?php if (!defined('APPLICATION')) exit();
session_start();
// Define the plugin:
$PluginInfo['VirgilPass'] = array(
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
     * Before page render event
     * @param $Sender
     */
    public function Base_Render_Before($Sender) {

        $Sender->AddCssFile('plugins/VirgilPass/login.css');
        $Sender->Head->AddString('<script src="' . C('Plugins.VirgilPass.sdkUrl') . '" type="text/javascript"></script>');

    }

    /**
     * Add Virgil Pass interface at entry controller Popup.
     */
    public function EntryController_SignIn_Handler($Sender, $Args) {

        if(C('Plugins.VirgilPass.disabled') == 'no') {

            $SignInHtml='<div style="margin-top:10px">
                            <p class="virgil-login">
                                <button data-virgil-ui="auth-btn" data-virgil-reference="' . C('Plugins.VirgilPass.redirectUrl') . '" type="button">Virgil Auth</button>
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
     * Handle before Signin request
     * @param $Sender
     * @param $Args
     */
    public function Base_BeforeSignInButton_Handler($Sender, $Args) {

        if(($token = GetIncomingValue('token')) !== false) {

            require 'core/virgil_auth_client.php';

            $virgil_auth_client = new virgil_auth_client(array(
                'auth_url' => C('Plugins.VirgilPass.authUrl')
            ));

            $error = false;
            if(!$virgil_auth_client->verify_token($token)) {
                $Sender->AddAsset('Messages', 'Virgil token validation failed.');
                $error = true;
            }

            if(($userInfo = $virgil_auth_client->get_user_info_by_token($token)) == false) {
                $Sender->AddAsset('Messages', 'Virgil user was not found.');
                $error = true;
            }

            if(!$error) {

                $UserDataAuth = Gdn::SQL()
                    ->Select('UserID')
                    ->From('User')
                    ->Where('Email', $userInfo['email'])
                    ->Get()->Result(DATASET_TYPE_ARRAY);

                $user = null;
                foreach ($UserDataAuth as $AuthUser) {
                    $UserID = GetValue('UserID', $AuthUser);
                }

                // If user was not found created it
                if(!$UserID) {

                    // Collect user data
                    $PasswordHash = new Gdn_PasswordHash();
                    $Data = array(
                        'Name'=>$userInfo['first_name'],
                        'Password'=> $PasswordHash->HashPassword(RandomString(8)),
                        'Email'=>$userInfo['email'],
                        'Photo'=> '',
                        'About'=> '',
                        'Gender' => 99,
                        'DateOfBirth'=> '',
                        'DateFirstVisit' =>   Gdn_Format::ToDateTime(),
                        'InsertIPAddress' =>Gdn::Request()->IPAddress(),
                        'LastIPAddress' =>Gdn::Request()->IPAddress(),
                        'DateInserted' => Gdn_Format::ToDateTime(strtotime('-1 day'))
                    );

                    Gdn::SQL()->Options('Ignore', TRUE)->Insert('User', $Data);

                    $UserDataw = Gdn::SQL()
                        ->Select('UserID')
                        ->From('User')
                        ->Where('Email',  $userInfo['email'])
                        ->Get()->Result(DATASET_TYPE_ARRAY);
                    foreach ($UserDataw as $UpdateUser) {
                        $UserID = GetValue('UserID', $UpdateUser);
                    }
                }

                Gdn::Session()->Start($UserID);
                $_SESSION['lrdata_store']=$UserID;

                Redirect('/');
            }
        }
    }

    public function ProfileController_AfterAddSideMenu_Handler($Sender) {


    }

    /*
    * Add to dashboard side menu.
    */
    public function Base_GetAppSettingsMenuItems_Handler($Sender) {

        $Menu = $Sender->EventArguments['SideMenu'];
        $Menu->AddItem('Add-ons', T('Addons'), FALSE, array('class' => 'Addons'));
        $Menu->AddLink('Add-ons', T('Virgil Pass'), 'dashboard/settings/VirgilPass', 'Garden.Settings.Manage');
    }

    /**
     * Admin Configuration option.
     */
    public function SettingsController_VirgilPass_Create($Sender, $Args) {

        $Sender->Permission('Garden.Settings.Manage');
        if ($Sender->Form->IsPostBack()) {

            SaveToConfig(array(
                'Plugins.VirgilPass.redirectUrl' =>$Sender->Form->GetFormValue('redirectUrl'),
                'Plugins.VirgilPass.sdkUrl' =>$Sender->Form->GetFormValue('sdkUrl'),
                'Plugins.VirgilPass.authUrl' =>$Sender->Form->GetFormValue('authUrl'),
                'Plugins.VirgilPass.disabled' => $Sender->Form->GetFormValue('disabled')
            ));

            $Sender->InformMessage(T("Your settings have been saved."));

        }
        else {

            $Sender->Form->SetFormValue('redirectUrl', C('Plugins.VirgilPass.redirectUrl'));
            $Sender->Form->SetFormValue('sdkUrl', C('Plugins.VirgilPass.sdkUrl'));
            $Sender->Form->SetFormValue('authUrl', C('Plugins.VirgilPass.authUrl'));
            $Sender->Form->SetFormValue('disabled', C('Plugins.VirgilPass.disabled'));
        }

        $Sender->AddSideMenu();
        $Sender->SetData('Title', T('Virgil Pass Plugin Settings'));
        $Sender->Render('Settings', '', 'plugins/VirgilPass');
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

        $http     = ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://");
        $location = $http . $_SERVER["HTTP_HOST"];

        SaveToConfig('Plugins.VirgilPass.disabled', 'no');
        SaveToConfig('Plugins.VirgilPass.redirectUrl', $location . '?token={{virgilToken}}');
        SaveToConfig('Plugins.VirgilPass.sdkUrl', 'https://auth-demo.virgilsecurity.com/js/sdk.js');
        SaveToConfig('Plugins.VirgilPass.authUrl', 'https://auth.virgilsecurity.com');
    }
}