<?php if (!defined('APPLICATION')) exit();
session_start();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/
// Define the plugin:

$PluginInfo['SocialLogin'] = array(
                                    'Name' => 'Social Login',
									'Description' => 'This plugin integrates Vanilla with SocialLogin. <b>You must register your application with SocialLogin for this plugin to work.</b>',
									'Version' => '1.1.2',
									'RequiredTheme' => FALSE,
									'RequiredPlugins' => FALSE,
									'MobileFriendly' => TRUE,
									'SettingsUrl' => '/dashboard/settings/SocialLogin',
									'SettingsPermission' => 'Garden.Settings.Manage',
									'HasLocale' => TRUE,
									'RegisterPermissions' => FALSE,
									'Author' => "LoginRadius Team",
									'AuthorEmail' => 'developers@loginradius.com',
									'AuthorUrl' => 'http://www.LoginRadius.com'
									);

require_once("LoginRadius.php");
class SocialLoginPlugin extends Gdn_Plugin {

  public function EntryController_SignIn_Handler($Sender, $Args) {
	$Sender->AddCssFile('plugins/SocialLogin/style.css');
	$AppID = trim(C('Plugins.SocialLogin.ApplicationID'));
	$Secret = trim(C('Plugins.SocialLogin.Secret')); 
	  if(empty($Secret) || empty($AppID)) {
	   $SignInHtml = "<p style='color:red'>Your LoginRadius API key/secret is empty, please correct it or contact LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
	}
	 elseif(empty($Secret) || !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $Secret)) {
	   $SignInHtml = "<p style='color:red'>Your LoginRadius API secret is not valid, please correct it or contact LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
	}
	else {
    $jsfiles='<script>$(function(){
loginradius_interface();					 
});</script>';
$SignInHtml=  $jsfiles.'<div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>';
}
	  // Add the Social Login  module to the controller.
	  	$FbMethod = array(
	  'Name' => 'SocialLogin',
	  'SignInHtml' => $SignInHtml
	  );
	
	$Sender->Data['Methods'][] = $FbMethod;
	}
	
  public function Base_Render_Before($Sender){
    $AppID = trim(C('Plugins.SocialLogin.ApplicationID'));
	if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
      $http = "https://";
     }else{
       $http = "http://";
      }
	  if (isset($_SERVER['REQUEST_URI'])) {
	    $loc = urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	 }
	else {
	  $loc = urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF']);
	}
    $js_files='<script src="//hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> 
	function loginradius_interface() { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "small";$ui.apikey = "'.$AppID.'";$ui.callback="'.$loc.'"; $ui.lrinterfacecontainer ="interfacecontainerdiv"; LoginRadius_SocialLogin.init(options); }
	var options={}; options.login=true; LoginRadius_SocialLogin.util.ready(loginradius_interface); </script><script src="//hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> var options={}; options.login=true; LoginRadius_SocialLogin.util.ready(function () { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "small";$ui.apikey = "'.$AppID.'";$ui.callback="'.$loc.'"; $ui.lrinterfacecontainer ="interfacecontainerbasediv"; LoginRadius_SocialLogin.init(options); }); </script>';
$Sender->Head->AddString($js_files);
}
  public function Base_BeforeSignInButton_Handler($Sender, $Args) {
    $UserModel = new UserModel();
    $AppID = trim(C('Plugins.SocialLogin.ApplicationID'));
	$Secret = trim(C('Plugins.SocialLogin.Secret'));
	$subtitle= trim(C('Plugins.SocialLogin.SUBTITLE'));
    $EmailRequired = C('Plugins.SocialLogin.Title');
    echo "<h4>".$subtitle."</h4>"; 
	  if(empty($Secret) || empty($AppID)) {
	   echo "<p style='color:red'>Your LoginRadius API key/secret is empty, please correct it or contact LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
	}
	elseif(empty($Secret) || !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $Secret)) {
	  echo "<p style='color:red'>Your LoginRadius API secret is not valid, please correct it or contact LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
	}
	else { 
	  echo '<div class="interfacecontainerbasediv"></div>';
	}
	//Get the User Profile Data. and defines the roles of user. 
	$obj = new LoginRadius();
	$userprofile = $obj->loginradius_get_data($Secret);
	$lrdata=array(); 
	if ($obj->IsAuthenticated == TRUE) {
	  $lrdata['FullName'] =  (!empty($userprofile->FullName) ? $userprofile->FullName : "");
	  $lrdata['ProfileName'] = (!empty($userprofile->ProfileName) ? $userprofile->ProfileName :"");
	  $lrdata['fname']  = (!empty($userprofile->FirstName) ? $userprofile->FirstName : ""); 
	  $lrdata['lname']  = (!empty($userprofile->LastName) ? $userprofile->LastName : "");
	  $lrdata['id'] = (!empty($userprofile->ID) ? $userprofile->ID :"");
	  $lrdata['NickName'] =(!empty($userprofile->NickName) ? $userprofile->NickName:"");
	  $lrdata['Provider']    = (!empty($userprofile->Provider) ? $userprofile->Provider :"");
	  $lrdata['email']  = (sizeof($userprofile->Email) > 0 ? $userprofile->Email[0]->Value : "");
	  $lrdata['aboutme'] =(!empty($userprofile->About)?$userprofile->About:"");
	  $website = (!empty($userprofile->ProfileUrl)?$userprofile->ProfileUrl:"");
	  $lrdata['thumbnail']  =(!empty($userprofile->ThumbnailImageUrl) ? trim($userprofile->ThumbnailImageUrl):"");
	  $lrdata['dob']  = (!empty($userprofile->BirthDate)? $userprofile->BirthDate :"");
	  $lrdata['roles']= ((C('Garden.Registration.Method')=="Approval")?4:8);
      if (empty( $lrdata['thumbnail']) && $lrdata['Provider']  == 'facebook')  {
	    $thumbnail = "http://graph.facebook.com/" . $lrdata['id']. "/picture";
	  }
	  if (empty($lrdata['email']) && $EmailRequired == "No") {
	    switch(  $lrdata['Provider']  ){
	      case 'twitter':
	        $lrdata['email'] = $lrdata['id'].'@'.$lrdata['Provider'] .'.com';
	      break;
	      case 'linkedin':
	        $lrdata['email'] = $lrdata['id'].'@'.$lrdata['Provider'] .'.com';
	      break;
	      default:
	       $Email_id = substr($lrdata['id'],7);
	       $Email_id2 = str_replace("/","_",$Email_id);
	       $lrdata['email'] = str_replace(".","_",$Email_id2).'@'.$lrdata['Provider'].'.com';
          break;
	   }
   }
   //check the Provider id present in UserAuthentication table.
	$UserDataCheck = Gdn::SQL()
	->Select('UserID')
	->From('UserAuthentication')
	->Where('ForeignUserKey',$lrdata['id'])
	->Get()->Result(DATASET_TYPE_ARRAY);
	foreach ($UserDataCheck as $UpdateUser) {
	$UserID = GetValue('UserID', $UpdateUser);
	}
	//check retrieved User id is also present in User Table.
	$UserDataAuth = Gdn::SQL()
	->Select('UserID')
	->From('User')
	->Where('UserID', $UserID)
	->Get()->Result(DATASET_TYPE_ARRAY);
	foreach ($UserDataAuth as $AuthUser) {
	  $UserID = GetValue('UserID', $AuthUser);
	}
	//when email not empty,then check the user is present in User table.
    if(!empty($lrdata['email'])) {	
	$UserDataCheckEmail = Gdn::SQL()
	->Select('UserID')
	->From('User')
	->Where('Email', $lrdata['email'])
	->Get()->Result(DATASET_TYPE_ARRAY);
	foreach ($UserDataCheckEmail as $UpdateUser) {
	  $UserIDEmail = GetValue('UserID', $UpdateUser);
	}
   }
   //when userId present then provide Login.
	if (!empty($UserID)) {
      Gdn::Session()->Start($UserID);
	  $loc=$this->RedirectUrl();
	  Redirect($loc);
	}
	if (!empty($UserIDEmail) && empty($UserID)) {
	  Gdn::Session()->Start($UserIDEmail);
	  $loc=$this->RedirectUrl();
	  Redirect($loc);
	}
	//popup box open when email required and emailis empty.
	if (empty( $lrdata['email']) && $EmailRequired == "Yes" && empty($UserID)) {
	  $_SESSION['lrdata']=$lrdata;
	  $msg="please enter your email address to proceed";
	  $this->popup($msg) ;
	 }
	}
	
	if (isset($_REQUEST['LoginRadiusRedSliderClick']))  {
	  $lrdata=$_SESSION['lrdata'];
	  $lrdata['email']= $_REQUEST['email'];
	  $lrdata['roles']=C('Garden.Registration.ConfirmEmailRole');
	  $r = $this->ValidateEmail($lrdata['email']) ;
	  if ($r == FALSE) {
	    $msg="<p style ='color:red;'>please enter your correct email address to proceed</p>";
	    $this->popup($msg);
	    return false;
	   }
	$UserEmail = Gdn::SQL()
	->Select('UserID')
	->From('User')
	->Where('Email',$lrdata['email'])
	->Get()->Result(DATASET_TYPE_ARRAY);
	foreach ($UserEmail as $UpdateUser) {
	  $UserID = GetValue('UserID', $UpdateUser);
	}
	if(!empty($UserID)) {
	 $_SESSION['lrdata']=$lrdata;
	  $msg = "<p style ='color:red;'>This Email is already registered or invalid please choose another one to proceed</p>";
	  $this->popup($msg);
	  return false;
	  }
  }
  else if (isset($_POST['cancel'])) {
	    unset($_SESSION['lrdata']);
}
	if (!empty( $lrdata['fname']) && !empty( $lrdata['lname'] )) {
	  $lrdata['username'] = $lrdata['fname']. ' ' . $lrdata['lname'] ;
	} 
	elseif (!empty($lrdata['FullName'])) {
	  $lrdata['username']= $lrdata['FullName'];
	} 
	elseif (!empty($lrdata['ProfileName'])) {
	  $lrdata['username'] = $lrdata['ProfileName'];
	}   
	elseif (!empty($lrdata['NickName'] )) {
	  $lrdata['username'] = $lrdata['NickName'] ;

	} 
	elseif (!empty($lrdata['email'])) {
	  $user_name = explode('@', $lrdata['email']);
	  $lrdata['username']  = $user_name[0];
	} 
	else {
	  $lrdata['username'] =  $lrdata['id'] ;
	}

	$UserData = Gdn::SQL()
	->Select('UserID')
	->From('UserAuthentication')
	->Where('ForeignUserKey',$lrdata['id'] )
	->Get();
	if (($UserData->NumRows()==0)&& !empty($lrdata['email']))  {
	  unset($_SESSION['lrdata']);
	  $result = Gdn::SQL()
	  ->Select('UserID')
      ->From('User')
	  ->Like('Name',$lrdata['username'].'%')
	  ->Get(); 
	  $MatchCount=$result->NumRows();
	  if($MatchCount>0)  {
	    for($i=0;$i<$MatchCount;$i++){
     	  $index++;
	    }
	    $lrdata['username']=$lrdata['username'].$index;
	   }
	$User = Gdn::Session()->User;
    $ConfirmationCode = RandomString(8);
	$PasswordHash = new Gdn_PasswordHash();
	$PasswordHashed = $PasswordHash->HashPassword($Password);
	$Data = array(
	'Name'=>$lrdata['username'],
	'Password'=> $PasswordHashed,
	'Email'=>$lrdata['email'],
	'Photo'=>$lrdata['thumbnail'],
	'About'=>$lrdata['aboutme'],
	'DateOfBirth'=>Gdn_Format::ToDate($lrdata['dob'] ),
	'DateFirstVisit' =>   Gdn_Format::ToDateTime(),
	'InsertIPAddress' =>Gdn::Request()->IPAddress(),
	'LastIPAddress' =>Gdn::Request()->IPAddress(),
	'DateInserted' => Gdn_Format::ToDateTime(strtotime('-1 day'))
	);
    if ( C('Garden.Registration.ConfirmEmail')==1) {
      $lrdata['roles']=C('Garden.Registration.ConfirmEmailRole');
      TouchValue('Attributes', $Data, array());
      $Data['Attributes']['EmailKey'] = $ConfirmationCode;
	  }
	$Data['Attributes'] = serialize($Data['Attributes']);
	Gdn::SQL()->Options('Ignore', TRUE)->Insert('User', $Data);
	
	$UserDataw = Gdn::SQL()
	->Select('UserID')
	->From('User')
	->Where('Email',  $lrdata['email'])
	->Get()->Result(DATASET_TYPE_ARRAY);
	foreach ($UserDataw as $UpdateUser) {
	  $UserID = GetValue('UserID', $UpdateUser);
	 }
	$DataAuth = array(
	'ForeignUserKey'=>$lrdata['id'],
	'ProviderKey'=>$lrdata['Provider'],
	'UserID'=>$UserID			
	);
	Gdn::SQL()->Options('Ignore', TRUE)->Insert('UserAuthentication', $DataAuth);
	if(	empty( $lrdata['roles']))
    $lrdata['roles']=8;
    $DataRole = array(
	'UserID'=>$UserID,
	'RoleID'=> $lrdata['roles']
	);
	
	Gdn::SQL()->Options('Ignore', TRUE)->Insert('UserRole', $DataRole);
	$Datatoken = array(
	'Token'=>$token,
	'ProviderKey'=>'SocialLogin',
	'TokenSecret'=>$Secret,
	'TokenType'=>"access",
	'Authorized' => FALSE,
	'Lifetime' => 60 * 5
	);
	
	Gdn::SQL()->Options('Ignore', TRUE)->Insert('UserAuthenticationToken', $Datatoken);
	$DataAuthentication = array(
	'AuthenticationKey'=>"SocialLogin",
	'AuthenticationSchemeAlias'=> "SocialLogin",
	'AssociationSecret'=>"-----",
	'AssociationHashMethod' => "-------"
	);
	Gdn::SQL()->Options('Ignore', TRUE)->Insert('UserAuthenticationProvider', $DataAuthentication);
	}
	
	else  {
	  $UserDataw = Gdn::SQL()
	  ->Select('UserID')
	  ->From('UserAuthentication')
	  ->Where('ForeignUserKey',  $lrdata['id'] )
	  ->Get()->Result(DATASET_TYPE_ARRAY);
	  foreach ($UserDataw as $UpdateUser) {
	   $UserID = GetValue('UserID', $UpdateUser);
	  }
     }
	 if(!empty( $lrdata['email'])&& $UserID >0) {
	 $Session=Gdn::Session();
	   Gdn::Session()->Start($UserID);
	   $loc=$this->RedirectUrl();
	  Redirect($loc);
	  }	
   }
  public function ValidateEmail($Value, $Field = '') {
    $Result = PHPMailer::ValidateAddress($Value);
    $Result = (bool)$Result;
    return  $Result;
   }


  public function SettingsController_SocialLogin_Create($Sender, $Args) {
    $Sender->Permission('Garden.Settings.Manage');
    if ($Sender->Form->IsPostBack()) {
      $Settings = array(
     'Plugins.SocialLogin.ApplicationID' => $Sender->Form->GetFormValue('ApplicationID'),
     'Plugins.SocialLogin.Secret' => $Sender->Form->GetFormValue('Secret'),
     'Plugins.SocialLogin.Title' => $Sender->Form->GetFormValue('Title'),
	 'Plugins.SocialLogin.SUBTITLE' =>$Sender->Form->GetFormValue('SUBTITLE'),
	  'Plugins.SocialLogin.USE_API' =>$Sender->Form->GetFormValue('USE_API'),
	    'Plugins.SocialLogin.LOGIN_SETTING' =>$Sender->Form->GetFormValue('LOGIN_SETTING'),
	   'Plugins.SocialLogin.LOGIN_SETTING1' =>$Sender->Form->GetFormValue('LOGIN_SETTING1'));
	   
	 
     SaveToConfig($Settings);
     $Sender->InformMessage(T("Your settings have been saved."));
    }
    else {
	  $r=((C('Plugins.SocialLogin.LOGIN_SETTING1')=='')?'LOGIN_SETTING1':C('Plugins.SocialLogin.LOGIN_SETTING1'));
      $Sender->Form->SetFormValue('ApplicationID', C('Plugins.SocialLogin.ApplicationID'));
      $Sender->Form->SetFormValue('Secret', C('Plugins.SocialLogin.Secret'));
      $Sender->Form->SetFormValue('Title', C('Plugins.SocialLogin.Title'));
	   $Sender->Form->SetFormValue('SUBTITLE', C('Plugins.SocialLogin.SUBTITLE'));
	   $Sender->Form->SetFormValue('USE_API', C('Plugins.SocialLogin.USE_API'));
	   $Sender->Form->SetFormValue('LOGIN_SETTING1', $r);
	   $Sender->Form->SetFormValue('LOGIN_SETTING', C('Plugins.SocialLogin.LOGIN_SETTING'));
     }
    $Sender->AddSideMenu();
    $Sender->SetData('Title', T('SocialLogin Settings'));
    $Sender->Render('Settings', '', 'plugins/SocialLogin');
   }

/**
*
* @param Gdn_Controller $Sender
* @param array $Args
*/

public function RedirectUrl() {
  $LOGIN_SETTING1 = C('Plugins.SocialLogin.LOGIN_SETTING1'); 
  $LOGIN_SETTING= C('Plugins.SocialLogin.LOGIN_SETTING'); 
  if ($LOGIN_SETTING1=="LOGIN_SETTING1") {
    $loc = "/";
  }
  else if ($LOGIN_SETTING1=="LOGIN_SETTING2") {
    $loc = 'profile.php';
  }
  else {
    $loc = $LOGIN_SETTING;
  }
  return $loc;
}

  public function popup($msg)  { ?> 
    <link rel="stylesheet" href="plugins/SocialLogin/login.css" type="text/css" />
    <div id="fade" class="LoginRadius_overlay">
    <div id="popupouter">
    <div id="popupinner">
    <div id="textmatter"><?php
    if ($msg) {
      echo "<b>" . $msg . "</b>";
    }?></div>
    <form id="wp_login_form"  method="post"  action="">
    Email <input type="text" name="email" id="email" class="inputtxt" />
    <input type="submit" id="LoginRadiusRedSliderClick" name="LoginRadiusRedSliderClick" value="Submit" class="inputbutton">
    <input type="submit" value="Cancel" class="inputbutton" onClick="history.back()" />
    </form></div></div></div>
<?php }


  public function Structure() {
    Gdn::SQL()->Replace('UserAuthenticationProvider',
    array('AuthenticationSchemeAlias' => 'SocialLogin', 'URL' => '...', 'AssociationSecret' => '...', 'AssociationHashMethod' => '...'),
    array('AuthenticationKey' => 'SocialLogin'), TRUE);
  }
  public function OnDisable() {}
 
}