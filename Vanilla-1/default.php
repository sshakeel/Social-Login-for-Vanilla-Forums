<?php 
/*
Extension Name:  Social Login
Extension Url: http://vanillaforums.org/
Description: LoginRadius enables Social Login Plugin for Vanilla Forums.
Version: 1.1.0
Author: LoginRadius Team
Author Url: http://www.loginradius.com
*/

$Context->Dictionary['socialloginSettings'] = 'sociallogin settings';

$Context->Dictionary['ErrCreateTable'] = 'Could not create Attachments database table!';

$Context->Dictionary['ErrCreateConfig'] = 'Could not save Attachments settings to configuration file!';

$Context->Dictionary['ExtensionOptions'] = 'Extension Options';

$Context->Dictionary['RememberToSetsocialloginPermissions'] = 'Remember to set sociallogin Permissions for you and your users. You can do it at the <a href="'.GetUrl($Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=sociallogin').'">Social Login Settings</a> page.';	

$Context->Dictionary['ErrUserNotFound'] = 'Confirmation link send to your Email. Check your email';

$Context->Dictionary['ErrPermissionNotFound'] = 'User have no Permission to sign in';

require_once("LoginRadius.php");

require_once("sociallogin_function.php");

$Head->AddStyleSheet('extensions/SocialLogin/login.css', 'screen', 100, '');
	if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
	  $http = "https://";
	 }else{
	   $http = "http://";
	 }
	 $loc =((isset($_SERVER['REQUEST_URI']))? urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']): urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'])); 
	 $AppID= trim($Context->Configuration['API_KEY']) ;
	

$Head->AddScript('//hub.loginradius.com/include/js/LoginRadius.js');
$Head->AddString('<script type="text/javascript">var options={}; options.login=true; LoginRadius_SocialLogin.util.ready(function () { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "";$ui.apikey = "'.$AppID.'";$ui.callback="'.$loc.'"; $ui.lrinterfacecontainer ="interfacecontainerdiv"; LoginRadius_SocialLogin.init(options); });</script>');
$Context->Configuration['PERMISSION_MANAGE_SOCIALLOGIN'] = '0';

//add definations.

if (!array_key_exists('SOCIALLOGIN_VERSION', $Configuration)) {

   AddConfigurationSetting($Context, 'SOCIALLOGIN_VERSION', '1.1.0');
   
   AddConfigurationSetting($Context, 'Sociallogin_email', 'yes');
   
   AddConfigurationSetting($Context, 'API_KEY', '0');
   
   AddConfigurationSetting($Context, 'API_Secret','0');
   
   AddConfigurationSetting($Context, 'Title', 'Please Login With');
   
   AddConfigurationSetting($Context, 'Sociallogin_API', 'CURL');
   
   AddConfigurationSetting($Context, 'Sociallogin_Linking', 'no');
   
   $Errors = 0;
   
   $NotifiCreate = "
		CREATE TABLE  `" . $Context->Configuration['DATABASE_TABLE_PREFIX'] . "sociallogin` (
		
		`UserID` int(11) NOT NULL,
		`socialloginID` varchar(50) NOT NULL,
		`ProviderName` varchar(20) NOT NULL default 'sociallogin',
		`random`  varchar(50) NULL,
	    `verified`  int(11) NOT NULL
		
     )";

    if (!mysql_query($NotifiCreate, $Context->Database->Connection)) $Errors = 1;
	
    if ($Errors == 0) {
	
	  // Add the db structure to the database configuration file
  	  $Structure = "// Social Login Table Structure
	  \$DatabaseTables['sociallogin'] = 'sociallogin';
	  \$DatabaseColumns['sociallogin']['UserID'] = 'UserID';
	  \$DatabaseColumns['sociallogin']['socialloginID'] = 'socialloginID';
	  \$DatabaseColumns['sociallogin']['ProviderName'] = 'ProviderName';
	  \$DatabaseColumns['sociallogin']['random'] = 'random';
	  \$DatabaseColumns['sociallogin']['verified'] = 'verified';

		";
		
	  if (!AppendToConfigurationFile($Configuration['APPLICATION_PATH'].'conf/database.php', $Structure))
	   
		  $Errors = 1;	
		  
      } 
   } 
   else {
   
     class sociallogin {
	 
	   var $socialloginID;
	   
	   var $UserID;
	   
	   var $ProviderName;
	   
	   var $random;
	   
	   var $verified;
	   
		// Constructor
	   function sociallogin() {
	   
	     $this->Clear();
		 
	   }

	   function Clear() {
	   
		 $this->socialloginID = 0;
		 
		 $this->UserID = 0;
		 
		 $this->ProviderName = 0;
		 
		 $this->random = 0;
		 
		 $this->verified = 0;
		 
	    }
	  }
    }
	
$Login_Index = $Context->ObjectFactory->NewContextObject($Context, 'socialloginManager');

//verify mail id.
if(isset($_REQUEST['SL_VERIFY_EMAIL'])){

  $Login_Index->verify_email($Context);
  
}

if (in_array(

  		$Context->SelfUrl,
		array(
			"people.php"
				))) {
				
  $USE_API= $Context->Configuration['Sociallogin_API'];
  
  $error="";
  
  $AppID= trim($Context->Configuration['API_KEY']) ;
  
  $Secret=trim( $Context->Configuration['API_Secret']) ;
  
  $Title=$Context->Configuration['Title'] ;
  
  //when user is not authenticated.
  if(empty($Secret) || empty($AppID)){
  	$error = "<p style='color:red'>Your LoginRadius API key/secret is empty, please correct it or contact LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
  }
 if(empty($Secret) || !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $Secret)) {
			$error = "<p style='color:red'>Your LoginRadius API secret is not valid, please correct it or contact LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
	}
 
   if($Context->Session->UserID==0) {
      //embed the iframe.
	  
      $ifr = $Context->ObjectFactory->CreateControl($Context, "iframe");
	  
      $Page->AddRenderControl($ifr, $Configuration["CONTROL_POSITION_BODY_ITEM"]);
	  
    
  }
}

class iframe {

  function Render() {
  
?>
    <div style='margin-left:20px;'>		<?php
	
    global	$Title,$error,$Login_Index,$Context,$flag;
	
	 echo "<p >".$Title."</p>";
	 
	if($error!='') {
	
	echo $error;
	
	return;
	
	}
	if($flag==1){
	
	  $Login_Index->Render_ValidPostBack($Context);
	  
	}
   
    echo '<div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>'; 
	
			?>
	</div>
			<?php
	}
}

//Retrieve user profile data.
$emailrequired=$Context->Configuration['Sociallogin_email'] ;

$Secret= trim($Context->Configuration['API_Secret']) ;

$obj = new LoginRadius();

$userprofile = $obj->loginradius_get_data($Secret); 

$lrdata=array();

if ($obj->IsAuthenticated == TRUE) {

  $lrdata['FullName'] = (!empty($userprofile->FullName) ? $userprofile->FullName : "");
  
  $lrdata['ProfileName'] = (!empty($userprofile->ProfileName) ? $userprofile->ProfileName :"");
  
  $lrdata['fname']  = (!empty($userprofile->FirstName) ? $userprofile->FirstName : ""); 
  
  $lrdata['lname']  = (!empty($userprofile->LastName) ? $userprofile->LastName : "");
  
  $lrdata['id'] = (!empty($userprofile->ID) ? $userprofile->ID :"");
  
  $lrdata['Provider']    = (!empty($userprofile->Provider) ? $userprofile->Provider :"");
  
  $lrdata['email']  = (sizeof($userprofile->Email) > 0 ? $userprofile->Email[0]->Value : "");
  
  $lrdata['thumbnail']  =(!empty($userprofile->ThumbnailImageUrl) ? trim($userprofile->ThumbnailImageUrl):"");
  
  $lrdata['dob']  = (!empty($userprofile->BirthDate)? $userprofile->BirthDate :"");
  
  $lrdata['RoleId']=$Context->Configuration['DEFAULT_ROLE'];
  
  $lrdata['rndm']='';
 
  $lrdata['vrfied']=1;
 

  if (empty( $lrdata['thumbnail']) && $lrdata['Provider']  == 'facebook') {
  
    $lrdata['thumbnail']= "http://graph.facebook.com/" .  $lrdata['id']  . "/picture";
	
   }
   
   //Create the dummyemail.
  if (empty($lrdata['email']) && $emailrequired == "no") {
  
    switch(  $lrdata['Provider'] ) {
	
      case 'twitter':
	  
        $lrdata['email'] = $lrdata['id'].'@'.$lrdata['Provider'].'.com';
		
      break;
	  
      case 'linkedin':
	  
        $lrdata['email'] = $lrdata['id'].'@'.$lrdata['Provider'].'.com';
		
      break;
	  
      default:
	  
        $Email_id = substr($lrdata['id'],7);
		
        $Email_id2 = str_replace("/","_",$Email_id);
		
        $lrdata['email'] = str_replace(".","_",$Email_id2).'@'. $lrdata['Provider'] .'.com';
		
        break;
		
       }
     }
	 
	//Put the value in username.
  if (!empty( $lrdata['fname']) && !empty( $lrdata['lname'] )) {
	
    $lrdata['username'] = $lrdata['fname']. ' ' . $lrdata['lname'] ;
	  
  } 
	
  elseif (!empty($lrdata['FullName'])) {
	
    $lrdata['username']= $lrdata['FullName'];
	  
  } 
	
  elseif (!empty($lrdata['ProfileName'])) {
	
    $lrdata['username'] = $lrdata['ProfileName'];
	  
  }   
	
  elseif (!empty($email)) {
	
    $user_name = explode('@',  $lrdata['email']);
	  
    $lrdata['username'] = $user_name[0];
	  
  }
	 
  else {
	
    $lrdata['username'] = $lrdata['id'] ;
	  
  }
	
  $Login_Index = $Context->ObjectFactory->NewContextObject($Context, 'socialloginManager');
  
  $UserID=$Login_Index->retrieveUserID($Context,$lrdata);
  
  $Login_control = $Context->ObjectFactory->NewContextObject($Context, 'Control');
  
	//When user-id present.
  if(!empty($UserID)) {
  
    $verify_Role=$Login_Index->check_Role($Context,$UserID);
	
	$verify=$Login_Index->Check_verify($Context,$UserID,$lrdata['Provider']);
	
	  //Check user have permission to sign-in.
	  if($verify_Role!=1){
	  
	    $Context->WarningCollector->Add($Context->GetDefinition('ErrPermissionNotFound'));
		
	    return $Context->WarningCollector->Count();
		
	  }
	  
	   //Check user is verified.
	  if($verify!=1) {
	  
	    $Context->WarningCollector->Add($Context->GetDefinition('ErrUserNotFound'));
	  
	    return $Context->WarningCollector->Count();
	  
	  }
	  //After verification redirection is happen.
	  
    $Login_Index->updateprofile($Context,$lrdata,$UserID);
	
	$Context->Authenticator->AssignSessionUserID($UserID);
	
	$redirect=$Login_Index->RedirectUrl($Context);
	
    Redirect($redirect);
	
  }
	 //When user-id empty and email is present.
	 
  if (empty($UserID)&& (!empty($lrdata['email']))) {
  
    $UserID=$Login_Index->retrieveemail($Context,$lrdata);
	
	if(!empty($UserID)) {
	
	  $checkUserID=$Login_Index->checkprofile($Context,$lrdata,$UserID);
	  
		 //Provide Social-Linking.
	  if(empty($checkUserID) and ($Context->Configuration['Sociallogin_Linking']=='yes')) {
	  
	    $Login_Index->SocialLink($Context,$lrdata,$UserID);
		
	   }
	   
      $verify_Role=$Login_Index->check_Role($Context,$UserID);
	  
	  $verify=$Login_Index->Check_verify($Context,$UserID,'');
	  
	  if($verify_Role!=1){
	  
	    $Context->WarningCollector->Add($Context->GetDefinition('ErrPermissionNotFound'));
		
	    return $Context->WarningCollector->Count();
	  
	   }
	   
	  if($verify!=1) {
		
	    $Context->WarningCollector->Add($Context->GetDefinition('ErrUserNotFound'));
		
	    return $Context->WarningCollector->Count();
		
	  }
	  
	  $Login_Index->updateprofile($Context,$lrdata,$UserID);
	  
	  $Context->Authenticator->AssignSessionUserID($UserID);
	  
	  $redirect=$Login_Index->RedirectUrl($Context);
		
      Redirect($redirect);
	  
	   }
	   
 }
 
	  //If email required nad email is not present.
	  
    if (empty($lrdata['email']) && $emailrequired == "yes") {
      $_SESSION['lrdata']=$lrdata;
	
	  $msg="Please enter your email address to proceed";
	
      $Login_Index->popup($msg) ;
	
    } 
	  
 }
   
  //Accept the mail is from Pop-up.
  $Login_Index = $Context->ObjectFactory->NewContextObject($Context, 'socialloginManager');
  
  if (isset($_POST['LoginRadiusRedSliderClick']))  {
  
    $lrdata=$_SESSION['lrdata'];
	
    $lrdata['email'] = $_REQUEST['email'];
	
    $lrdata['rndm']=DefineVerificationKey()."/".$lrdata['id'];
	
    $lrdata['vrfied']=1;
	
    $EMAIL_VALIDATE=Validate($Context->GetDefinition('EmailLower'), 1,  $lrdata['email'], 200,
  '^([A-Z0-9+_-][A-Z0-9+_.-]{0,63})@(([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})|([A-Z0-9][A-Z0-9.-]{0,244}\.[A-Z]{2,10}))$',
	  $Context
	        );
			
	//Check email-validation.		
      if ($EMAIL_VALIDATE=='0') {
	
        $msg="<p style ='color:red;'>Please enter your correct email address to proceed further</p>";
		
	    $Login_Index->popup($msg);
		
	    return false;
		
     }
	 
	//If user's mail-id is already exist.
    $UserID=$Login_Index->retrieveemail($Context,$lrdata);
	
    if(!empty($UserID)) {
	
	  $_SESSION['lrdata']=$lrdata;
	  
	  $lrdata['random']=DefineVerificationKey();
	  
	  $yes= $Login_Index->check_that($Context,$lrdata,$UserID);
	  
	  if($yes==0) {
	  
	    $msg = "<p style ='color:red;'>The email you entered is already registered or invalid. Please enter another email address.</p>";
		
	    $Login_Index->popup($msg);
		
	    return false;
		
	  }

	  else {
	  
	  $lrdata['vrfied']='0';
	  
	  $lrdata['rndm']=$lrdata['random']."/".$lrdata['id'];
	  
	  $Login_Index->insertSocialloginID($Context,$lrdata,$UserID);
	  
	  }
	 
	 $Login_Index->SendMail($Context,$lrdata,$UserID);
	 
	  $msg = "The email you entered is already registered. Check your email.";
	  
	  $Login_Index->popup_verify($msg,$Context);
	  
	  return false;
	  
     }
	 
  }
  
  //Wnen cancel Pop-up.
  else if (isset($_POST['cancel'])) {
  
   unset($_SESSION['lrdata']);
   
  }
  
  $Login_Index = $Context->ObjectFactory->NewContextObject($Context, 'socialloginManager');
  
  if(!empty($lrdata['email'])) {
  
    unset($_SESSION['lrdata']);
   
    $UserID=$Login_Index->insertNewUser($Context,$lrdata);
	
    $Login_Index->insertSocialloginID($Context,$lrdata,$UserID);
	
  }
  //Allow immediate access.
  if(!empty($lrdata['email'])) {
  
    if($Context->Configuration['ALLOW_IMMEDIATE_ACCESS']){
	
      $Context->Session->Start($Context, $Context->Authenticator, $UserID);
	  
      $Context->WarningCollector->Iif();
	  
      $Context->Authenticator->AssignSessionUserID($UserID);
	  
      $redirect=$Login_Index->RedirectUrl($Context);
	  
      Redirect($redirect);
	  
   }
   
  else { 
  
$flag=1;

    }
	
 }
 
//Check permission to manage sociallogin settings.
if ($Context->SelfUrl == "settings.php" && $Context->Session->User->Permission('PERMISSION_MANAGE_SOCIALLOGIN'))  {

  /*
  * Insert the Admin settings Options.
  *
  */
  
  class socialloginForm extends PostBackControl {
  
    var $ConfigurationManager;
	
    function socialloginForm(&$Context) {
	
	  $this->Name = 'socialloginForm';
	  
	  $this->ValidActions = array('sociallogin', 'Processsociallogin');
	  
	  $this->Constructor($Context);
	  
      if (!$Context->Session->User->Permission('PERMISSION_MANAGE_SOCIALLOGIN')) {
	  
	    $this->PostBackAction = 0;
		
	  } 
	  elseif( $this->PostBackAction ) {
	  
	   $SettingsFile = $this->Context->Configuration['APPLICATION_PATH'].'conf/settings.php';
	   
	   $this->ConfigurationManager = $this->Context->ObjectFactory->NewContextObject($this->Context, 'ConfigurationManager');
	   
	     if ($this->PostBackAction == 'Processsociallogin') {
		 
	       $this->ConfigurationManager->GetSettingsFromForm($SettingsFile);
		   
		  // Checkboxes aren't posted back if unchecked, so make sure that they are saved properly
		   $this->DelegateParameters['ConfigurationManager'] = &$this->ConfigurationManager;
		   
			$this->CallDelegate('DefineCheckboxes');

			  // And save everything
			  if ($this->ConfigurationManager->SaveSettingsToFile($SettingsFile)) {
			  
			    header('location: '.GetUrl($this->Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=sociallogin&Success=1'));
				
			  } 
			  
			  else {
			  
		    	$this->PostBackAction = 'sociallogin';
				
		      }
          } 
	  }
			
  $this->CallDelegate('Constructor');
  
  }
  
/*
* Display all the admin settings.
*/

  function Render() {
  
    if ($this->IsPostBack) {
	
	  $this->CallDelegate('PreRender');
	  
	  $this->PostBackParams->Clear();
	  
	  if ($this->PostBackAction == "sociallogin") {
	  
	    $this->PostBackParams->Set('PostBackAction', 'Processsociallogin');
		
		echo '
		<div id="Form" class="Account socialloginSettings">';
		
		  if (ForceIncomingInt('Success', 0)){ 
		  
		    echo '<div id="Success">'.$this->Context->GetDefinition('ChangesSaved').'</div>';
						
		}
			echo '
					<fieldset>
						<legend>'.$this->Context->GetDefinition("socialloginSettings").'</legend>
						'.$this->Get_Warnings().'
						'.$this->Get_PostBackForm('frmsociallogin').'
						
						<ul>
							<li>
								<label for="API_KEY">API Key</label>
								<input type="text" name="API_KEY" id="API_KEY"   value="'.$this->ConfigurationManager->GetSetting('API_KEY').'" style="width: 100%;" />
							</li>
							<li>
								<label for="API_Secret">API Secret</label>
								<input type="text" name="API_Secret" id="API_Secret"   value="'.$this->ConfigurationManager->GetSetting('API_Secret').'" style="width: 100%;" />
							</li>
							
							
							<li>
								<label for="Title">Title</label>
								<input type="text" name="Title" id="Title"  value="'.$this->ConfigurationManager->GetSetting('Title').'"  style="width: 100%;" />
							</li>
							<li>
						
									<label for="Sociallogin_email">Email Required</label>
							<input type="Radio" name="Sociallogin_email" id="Sociallogin_email" value="yes"' ?><?php if($this->ConfigurationManager->GetSetting('Sociallogin_email')=="yes") echo"checked";  echo '/>YES<br/>
							<input type="Radio" name="Sociallogin_email" id="Sociallogin_email" value="no" '?> <?php if($this->ConfigurationManager->GetSetting('Sociallogin_email')=="no") echo"checked";  echo '/>NO
							</li>
						
							<li>
									<label for="Sociallogin_API">Select API Credentials</label>
								<input type="Radio" name="Sociallogin_API" id="Sociallogin_API" value="CURL"'?><?php if($this->ConfigurationManager->GetSetting('Sociallogin_API')=="CURL") echo"checked";  echo '/>CURL<br/>
							<input type="Radio" name="Sociallogin_API" id="Sociallogin_API" value="FSOCKOPEN"'?><?php if($this->ConfigurationManager->GetSetting('Sociallogin_API')=="FSOCKOPEN") echo"checked";  echo '/>FSOCKOPEN
							</li>
							<li>
									<label for="Sociallogin_Linking">Social Linking</label>
								<input type="Radio" name="Sociallogin_Linking" id="Sociallogin_Linking" value="yes"'?><?php if($this->ConfigurationManager->GetSetting('Sociallogin_Linking')=="yes") echo"checked";  echo '/>YES<br/>
							<input type="Radio" name="Sociallogin_Linking" id="Sociallogin_Linking" value="no"'?><?php if($this->ConfigurationManager->GetSetting('Sociallogin_Linking')=="no") echo"checked";  echo '/>NO
							</li>
						
						
						</ul>
						<div class="Submit">
							<input type="submit" name="btnSave" value="'.$this->Context->GetDefinition('Save').'" class="Button SubmitButton" />
							<a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
						</div>
					';
					}
				$this->CallDelegate('PostRender'); 
				
			}
		}
}

		
$socialloginForm = $Context->ObjectFactory->NewContextObject($Context, 'socialloginForm');

$Page->AddRenderControl($socialloginForm, $Configuration["CONTROL_POSITION_BODY_ITEM"] + 1);

$ExtensionOptions = $Context->GetDefinition("ExtensionOptions");

$Panel->AddList($ExtensionOptions, 20);

$Panel->AddListItem($ExtensionOptions, $Context->GetDefinition("socialloginSettings"), GetUrl($Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=sociallogin'));
	
}

//Turn off the social login Notice.
if ($Context->SelfUrl == 'index.php' && !array_key_exists('sociallogin_NOTICE', $Configuration)) {

  if ($Context->Session->User && $Context->Session->User->Permission('PERMISSION_MANAGE_EXTENSIONS')) {
  
    $HideNotice = ForceIncomingBool('TurnOffsocialloginSettingsNotice', 0);
	
	if ($HideNotice) {
	
	  AddConfigurationSetting($Context, 'sociallogin_NOTICE', '1');
	  
	}
	
	 else {
	 
	  $NoticeCollector->AddNotice('<span><a href="'.GetUrl($Configuration, 'index.php', '', '', '', '', 'TurnOffsocialloginSettingsNotice=1').'">'.$Context->GetDefinition('RemoveThisNotice').'</a></span>
			'.$Context->GetDefinition('RememberToSetsocialloginPermissions'));
     }
  }	
		   
}


?>