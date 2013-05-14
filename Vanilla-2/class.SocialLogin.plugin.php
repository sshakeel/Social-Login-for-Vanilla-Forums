<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

session_start();

// Define the plugin:
$PluginInfo['SocialLogin'] = array(
                                    'Name' => 'Social Login',
									'Description' => 'Let your users log in and comment via their accounts with popular ID providers such as Facebook, Google, Twitter, Yahoo, Vkontakte and over 21 more!.',
									'Version' => '2.0',
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
		$AppID = trim(C('Plugins.SocialLogin.Apikey'));
		$Secret = trim(C('Plugins.SocialLogin.Secretkey')); 
		$subtitle= trim(C('Plugins.SocialLogin.Sociallogintitle'));
		$subtitle=!empty($subtitle) ? $subtitle :'';
	  	if(empty($Secret) || empty($AppID)) {
	   		$SignInHtml = "<p style='color:red'>Your LoginRadius API key/secret is empty, please correct it or contact 
			LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
		}
	    elseif(empty($Secret) || !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $Secret)){
	   		$SignInHtml = "<p style='color:red'>Your LoginRadius API secret is not valid, please correct it or contact 
			LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
		}
		else {
    		$jsfiles='<script>$(function(){
			loginradius_interface();					 
			});</script>';
			$SignInHtml='<h4 style="margin:8px; color:#1e79a7; font-weight: bold;">'.$subtitle.'</h4><br/>' .$jsfiles.
			'<div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>';
		}
		/*
	    Add the Social Login  module to the controller.
	   */
	  	$SocialLoginMethod = array(
	    'Name' => 'SocialLogin',
	    'SignInHtml' => $SignInHtml
	    );
	    $Sender->Data['Methods'][] = $SocialLoginMethod;
	}
	
	public function Base_Render_Before($Sender){
		$Sender->AddCssFile('plugins/SocialLogin/socialloginandsocialshare.css');
    	$AppID = trim(C('Plugins.SocialLogin.Apikey'));
			if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
				$http = "https://";
     		}
	 		else{
       			$http = "http://";
     		}
	 		if (isset($_SERVER['REQUEST_URI'])) {
	   			$loc = urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	 		}
	 		else {
	   			$loc = urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF']);
	 		}
    	$js_files='<script src="http://hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> 
		function loginradius_interface() { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "small";
		$ui.apikey = "'.$AppID.'";$ui.callback="'.$loc.'"; 
		$ui.lrinterfacecontainer ="interfacecontainerdiv";  LoginRadius_SocialLogin.init(options); } 
		var options={};  options.login=true;  LoginRadius_SocialLogin.util.ready(loginradius_interface); </script>';
		$Sender->Head->AddString($js_files);
		$sh=$this->share();
		$Sender->Head->AddString($sh);
		$counter=$this->counter();
		$Sender->Head->AddString($counter);
		}
		
	public function Base_BeforeSignInButton_Handler($Sender, $Args) {
    	$UserModel = new UserModel();
    	$AppID = trim(C('Plugins.SocialLogin.Apikey'));
		$Secret = trim(C('Plugins.SocialLogin.Secretkey'));
		$subtitle= trim(C('Plugins.SocialLogin.Sociallogintitle'));
    	$EmailRequired = C('Plugins.SocialLogin.Email_required');
		$Account_linking= c('Plugins.SocialLogin.Account_linking');
		$subtitle= !empty($subtitle) ? $subtitle :'';
    	echo '<h4 style="margin:0 0 5px 0;">'.$subtitle.'</h4>';
			if(empty($Secret) || empty($AppID)) {
	   			echo "<p style='color:red'>Your LoginRadius API key/secret is empty, please correct it or contact 
				LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
			}
			elseif(empty($Secret) || !preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', 
			$Secret)) {
	  			echo "<p style='color:red'>Your LoginRadius API secret is not valid, please correct it or contact 
				LoginRadius support at <a href='http://www.LoginRadius.com' target='_blank'>www.loginradius.com</a></p>";
			}
			else { 
	 		 	echo '<div id="interfacecontainerdiv" class="interfacecontainerdiv"></div>';
			}
	    /*
	    Get the User Profile Data. and defines the roles of user. 
		*/
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
	  			$lrdata['roles']= (C('Garden.Registration.Method'));
	  				if($lrdata['roles']=='Approval')
	  					$lrdata['rolevalue']=4;
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
   		$_SESSION['lrdata_store']=$lrdata;
        /*
        check the Provider id present in UserAuthentication table.
		*/
		$UserDataCheck = Gdn::SQL()
		->Select('UserID')
		->From('UserAuthentication')
		->Where('ForeignUserKey',$lrdata['id'])
		->Get()->Result(DATASET_TYPE_ARRAY);
		foreach ($UserDataCheck as $UpdateUser) {
			$UserID = GetValue('UserID', $UpdateUser);
		}
	    /*
        check retrieved User id is also present in User Table.
		*/
		$UserDataAuth = Gdn::SQL()
		->Select('UserID')
		->From('User')
		->Where('UserID', $UserID)
		->Get()->Result(DATASET_TYPE_ARRAY);
		foreach ($UserDataAuth as $AuthUser) {
	  		$UserID = GetValue('UserID', $AuthUser);
		}
	    /*
	    when email not empty,then check the user is present in User table.
		*/
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
        /*
        when userId present then provide Login.
		*/
		if (!empty($UserID)) {
      		Gdn::Session()->Start($UserID);
	 		$loc=$this->RedirectUrl();
	  		Redirect($loc);
		}
	    /*
	    Auto Linking, user can link their traditional account to social login account.
	    */
     	$Link_UserID=Gdn::SQL()
		->Select('UserID')
		->From('UserAuthentication')
		->Where('UserID',$UserIDEmail)
		->Get()->Result(DATASET_TYPE_ARRAY);
		foreach ($link_UserID as $UpdateUser) {
	  		$Check_Link_UserID = GetValue('UserID', $UpdateUser);
		}
		if (!empty($UserIDEmail) && empty($UserID) && $Account_linking=="Yes" && empty($Check_Link_UserID))  {
			$DataAuth1 = array(
			'ForeignUserKey'=>$lrdata['id'],
			'ProviderKey'=>$lrdata['Provider'],
			'UserID'=>	$UserIDEmail	
			);
			Gdn::SQL()->Options('Ignore', TRUE)->Insert('UserAuthentication', $DataAuth1);	
	  		Gdn::Session()->Start($UserIDEmail);
	  		$loc=$this->RedirectUrl();
	  		Redirect($loc);
		}
		else if(!empty($UserIDEmail) && empty($UserID)) {
			Gdn::Session()->Start($UserIDEmail);
	  		$loc=$this->RedirectUrl();
	  		Redirect($loc);
		}

        /*
		popup box open when email required and emailis empty.
		*/
		
		if (empty( $lrdata['email']) && $EmailRequired == "Yes" && empty($UserID)) {
			$EmailTitle=trim(C('Plugins.SocialLogin.Emailtitle'));
			$EmailTitle=!empty($EmailTitle)? $EmailTitle :'';
	  		$_SESSION['lrdata']=$lrdata;
	  		$msg=$EmailTitle;
	  		$showinvitation="no";
	  		if($lrdata['roles']=='Invitation'){
	  			$showinvitation="yes";
	   			$msg='Please enter Invitation code:';
	  		}
	  	$this->popup($msg, $showinvitation) ;
	 	}
	
	
	
		if($lrdata['roles']=='Invitation'){
			$_SESSION['lrdata']=$lrdata;
	  		$showinvitation="yes";
	  		$msg='Please enter Invitation code:';
	  		$this->popup($msg, $showinvitation) ;
	  	return false;
	 	}
	  }
	
		if (isset($_REQUEST['LoginRadiusRedSliderClick'])) {
	  	$lrdata=$_SESSION['lrdata'];
	  	$lrdata['email']= $_REQUEST['email'];
	  	$flag=1;
		$Invitation_code=$_REQUEST['invitecode'];
	  	if($lrdata['roles']=='Invitation') {
	  		$Invitation= Gdn::SQL()
	  		->Select('Email')
	 		->From('Invitation')
	  		->Where('Code',$Invitation_code)
	 	 	->Where('AcceptedUserID',NULL)
	  		->Get()->Result(DATASET_TYPE_ARRAY);
	  		foreach($Invitation as $update) {
	  			$Invitation=GetValue('Email',$update);
	  		}
	  		if(!empty($Invitation)) { 
	  			$lrdata['email']=$Invitation;
	  			$lrdata['roles']=C('Garden.Registration.ConfirmEmailRole');
			}else {
	  			$msg='<p style ="color:red;"> Please enter valid invitation code </p>';
	    		$this->popup($msg,'yes');
	    		return false;
	  		}
	  	}
		else {
	  	$lrdata['roles']=C('Garden.Registration.ConfirmEmailRole');
	  	$EmailErrorMsg=trim(C('Plugins.SocialLogin.Emailerrortitle'));
	  	$EmailErrorMsg=!empty($EmailErrorMsg) ? $EmailErrorMsg : '';
	  	$r = $this->ValidateEmail($lrdata['email']) ;
	  		if ($r == FALSE) {
	    		$msg='<p style ="color:red;">'.$EmailErrorMsg.'</p>';
	    		$this->popup($msg,'no');
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
	  			$this->popup($msg,'no');
	  			return false;
	  		}
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
	   /*
	   Message the roles for email confirmation.
	   */
	   
		$SkipEmail=C('Plugins.SocialLogin.SkipEmail');
		if((!empty($lrdata['email'])&& $SkipEmail=='No') || $flag==1) {
			if ( C('Garden.Registration.ConfirmEmail')==1) {
      			$lrdata['rolevalue']=C('Garden.Registration.ConfirmEmailRole');
      			TouchValue('Attributes', $Data, array());
      			$Data['Attributes']['EmailKey'] = $ConfirmationCode;
	  		}
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
		if(	empty( $lrdata['rolevalue']))
    		$lrdata['rolevalue']=8;
    	$DataRole = array(
			'UserID'=>$UserID,
			'RoleID'=> $lrdata['rolevalue']
			);
		Gdn::SQL()->Options('Ignore', FALSE)->Insert('UserRole', $DataRole);
		$role=(C('Garden.Registration.Method'));
		if($role=='Invitation') {
			Gdn::SQL()
            ->Update('Invitation')
            ->Set('AcceptedUserID', $UserID)
            ->Where('Email', $lrdata['email'])
            ->Put();
		}
	  	$UserDataw = Gdn::SQL()
	 	->Select('UserID')
	  	->From('UserAuthentication')
	  	->Where('ForeignUserKey',  $lrdata['id'])
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
	
    /*
    Add Interface on Profile Page and Map the user Accounts.
	*/
	  
	public function ProfileController_AfterAddSideMenu_Handler($Sender) {
		$MappingTitle=trim(C('Plugins.SocialLogin.Mappingtitle'));
		$MappingTitle=!empty($MappingTitle)? $MappingTitle :'';
		$Get_Lr_Data=$_SESSION['lrdata_store'];
		$Is_User_Login=Gdn::Session()->UserID;
		if(Gdn::Session()->UserID >0) {
			$Map_Provider=Gdn::SQL()
			->Select('ProviderKey')
			->From('UserAuthentication')
			->Where('UserID',$Is_User_Login)
			->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($Map_Provider as $UpdateUser) {
			$Check_Map_Provider[] =
			GetValue('ProviderKey', $UpdateUser);
			}

		$Map_current_Provider=Gdn::SQL()
		->Select('ProviderKey')
		->From('UserAuthentication')
		->Where('ProviderKey',$Get_Lr_Data['Provider'])
		->Where('UserID',$Is_User_Login)
		->Get()->Result(DATASET_TYPE_ARRAY);
		foreach ($Map_current_Provider as $UpdateUser) {
			$Check_Map_current_Provider[] =
			GetValue('ProviderKey', $UpdateUser);
		}
		$map='<ul>';
		if(!empty($Check_Map_current_Provider))
		{
			$map.='<li style="color:#1e79a7;font-family:Helvetica,arial,sans-serif; font-size:12px;">'.Img('plugins/SocialLogin/Images/'.($Get_Lr_Data['Provider']).'.png', array('style' => 'max-width: 763px;')).'<span style="color:GREEN"> Currently connected </span> With '. ucfirst($Get_Lr_Data['Provider']) .Anchor(Img('/plugins/SocialLogin/Images/del2.png', array('style' => 'max-width: 763px;')), '?kmode=remove&provider_id='.$Get_Lr_Data['Provider']. '&title=delete' ). '</li>';
	}
		foreach($Check_Map_Provider as $identity)
		{
			if($identity!=$Get_Lr_Data['Provider'])
			{
				$map.='<li style="color:#1e79a7;font-family:Helvetica,arial,sans-serif; font-size:12px; ">'.Img('plugins/SocialLogin/Images/'.$identity.'.png', array('style' => 'max-width: 763px;')).' Connected With '.ucfirst($identity).' '.Anchor(Img('/plugins/SocialLogin/Images/del2.png', array('style' => 'max-width: 763px;')), '?kmode=remove&provider_id='.$identity. '&title=delete' ).'</li>';
			}
		}

	'</ul>';

		if($_GET['kmode'] == 'remove'){
			Gdn::SQL()->Delete('UserAuthentication', array('ProviderKey' => $_GET['provider_id'],'UserID'=> $Is_User_Login));
			$Sender->InformMessage(T("Your Account have sucessfully Deleted"));
			$Sender->RedirectUrl= Url('profile.php');
		}
		$SideMenu = $Sender->EventArguments['SideMenu'];
		$SideMenu->AddLink($MappingTitle,'<center style="margin:10px 0 0 0"><div id="interfacecontainerdiv" 					class="interfacecontainerdiv"></div></center><br/>'.$map);
		$Sender->EventArguments['SideMenu'] = $SideMenu;
		$Secret = trim(C('Plugins.SocialLogin.Secretkey'));
		$obj1 = new LoginRadius();
		$userprofile1 = $obj1->loginradius_get_data($Secret);
		$lrdata=array();
		if ($obj1->IsAuthenticated == TRUE && $Is_User_Login>0) {
			$lrdata['id'] = (!empty($userprofile1->ID) ? $userprofile1->ID :"");
			$lrdata['Provider'] = (!empty($userprofile1->Provider) ? $userprofile1->Provider :"");
			$Map_Lr_Id=Gdn::SQL()
			->Select('ForeignUserKey')
			->From('UserAuthentication')
			->Where('ForeignUserKey',$lrdata['id'])
			->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($Map_Lr_Id as $UpdateUser) {
				$Check_Map_Lr_Id = GetValue('ForeignUserKey', $UpdateUser);
		}
		if(empty($Check_Map_Lr_Id))
		{
			$Check_Provider=Gdn::SQL()
			->Select('ProviderKey')
			->From('UserAuthentication')
			->Where('ProviderKey',$lrdata['Provider'])
			->Where('UserId',$Is_User_Login)
			->Get()->Result(DATASET_TYPE_ARRAY);
			foreach ($Check_Provider as $UpdateUser1) {
				$Check_Provider= GetValue('ProviderKey', $UpdateUser1);
			}
		if(empty($Check_Provider))
		{
			$Map_Data = array(
			'ForeignUserKey'=>$lrdata['id'],
			'ProviderKey'=>$lrdata['Provider'],
			'UserID'=> $Is_User_Login
			);
			Gdn::SQL()->Options('Ignore', TRUE)->Insert('UserAuthentication', $Map_Data);
			$Sender->InformMessage(T("Your Account have sucessfully mapped"));
			$Sender->RedirectUrl= Url('profile.php');
		}
		else
		{
			$Sender->InformMessage(T("Account cannot be mapped as it already exists in our database."));
			$Sender->RedirectUrl= Url('profile.php');
		}
	}
	else
		$Sender->InformMessage(T("Your Account have Already mapped with this Account"));
	}
	}
}


    /* 
	function for adding the widget before comment body on discussion page.
	*/
	
	public function DiscussionController_BeforeCommentBody_Handler($Sender) {
		$Sender->AddCssFile('plugins/SocialLogin/socialloginandsocialshare.css');
		$hsharing_position=C('Plugins.SocialShare.Horizontalsharingposition');
		$hcounter_position=C('Plugins.SocialCounter.Horizontalcounterposition');
		$enable_sharing=trim(C('Plugins.SocialShare.Enablesocialsharing'));
		$share_title=C('Plugins.SocialShare.Socialsharetitle');
		$share_title=!empty($share_title)? trim($share_title):"";
		$hsharing_theme= trim(C('Plugins.SocialShare.Horizontalsharingtheme'));
		$enable_counter=C('Plugins.SocialCounter.Enablesocialcounter');
		$counter_title=C('Plugins.SocialCounter.Socialcountertitle');
		$counter_title=!empty($counter_title)? trim($counter_title):"";
		$hconter_theme=trim(C('Plugins.SocialCounter.Horizontalcountertheme'));
		if($enable_sharing=='Yes' ) { 
    		if( $hsharing_theme!= '16VerticlewithBox' && $hsharing_theme!= '32VerticlewithBox' && $hsharing_position=='Top') {
				if($Sender->EventArguments['Type'] =='Discussion') {
					echo '<div style="font-size:0 0 0 10px; font-weight: bold;">'.$share_title.'</div>';
	    			echo '<div><div class="lrsharecontainer"></div></div>';
				}
			}
	
			elseif($hsharing_theme == '16VerticlewithBox' || $hsharing_theme == '32VerticlewithBox') {
				if($Sender->EventArguments['Type'] =='Discussion')
					echo '<div class="lrsharecontainer"></div>';
			}
	
		}
		if($enable_counter=='Yes' ) {
			if($hconter_theme!='hybrid-verticle-horizontal' && $hconter_theme!='hybrid-verticle-vertical' && $hcounter_position=='Top' ) {
				if($Sender->EventArguments['Type'] =='Discussion') {
					echo '<div style="font-size:0 0 0 10px; font-weight: bold;">'.$counter_title.'</div>';
					echo '<div><div class="lrcounter_simplebox"></div></div>';
				}
			}
    	

		}
	}


    /* 
	function for adding the widget before comment body on discussion page.
	*/
	
	public function DiscussionController_AfterCommentBody_Handler($Sender) {
		$Sender->AddCssFile('plugins/SocialLogin/socialloginandsocialshare.css');
		$hsharing_position=C('Plugins.SocialShare.Horizontalsharingposition');
		$hcounter_position=C('Plugins.SocialCounter.Horizontalcounterposition');
		$enable_sharing=trim(C('Plugins.SocialShare.Enablesocialsharing'));
		$share_title=C('Plugins.SocialShare.Socialsharetitle');
		$share_title=!empty($share_title)? trim($share_title):"";
		$hsharing_theme= trim(C('Plugins.SocialShare.Horizontalsharingtheme'));
		$enable_counter=C('Plugins.SocialCounter.Enablesocialcounter');
		$counter_title=C('Plugins.SocialCounter.Socialcountertitle');
		$counter_title=!empty($counter_title)? trim($counter_title):"";
		$hconter_theme=trim(C('Plugins.SocialCounter.Horizontalcountertheme'));
		if($enable_sharing=='Yes' ) { 
    		if( $hsharing_theme!= '16VerticlewithBox' && $hsharing_theme!= '32VerticlewithBox' && $hsharing_position=='Bottom') {
				if($Sender->EventArguments['Type'] =='Discussion') {
					echo '<div style="font-size:0 0 0 10px; font-weight: bold;">'.$share_title.'</div>';
	    			echo '<div class="lrsharecontainer"></div>';
				}
			}
	
			elseif($hsharing_theme== '16VerticlewithBox' || $hsharing_theme== '32VerticlewithBox') {
				if($Sender->EventArguments['Type'] =='Discussion')
					echo '<div class="lrsharecontainer"></div>';
			}
	
		}
		if($enable_counter=='Yes' ) {
			if($hconter_theme!='hybrid-verticle-horizontal' && $hconter_theme!='hybrid-verticle-vertical' && $hcounter_position=='Bottom') {
				if($Sender->EventArguments['Type'] =='Discussion') {
					echo '<div style="font-size:0 0 0 10px; font-weight: bold;">'.$counter_title.'</div>';
					echo '<div class="lrcounter_simplebox"></div>';
				}
			}
    		elseif($hconter_theme=='hybrid-verticle-horizontal' || $hconter_theme=='hybrid-verticle-vertical') {
	 			if($Sender->EventArguments['Type'] =='Discussion')
					echo '<div class="lrcounter_simplebox"></div>';
			}
		}
	
	}
    /*
    function for Returning Sharing code.
    */
	public function share() {
		$hsharing_theme= trim(C('Plugins.SocialShare.Horizontalsharingtheme'));
		$vsharing_position= trim(C('Plugins.SocialShare.Verticalsharingposition'));
		$sharing_offset=(C('Plugins.SocialShare.Sharingoffset'));
		$sharing_offset=is_numeric($sharing_offset)? ($sharing_offset):0;
		$rearrange_provider=C('Plugins.SocialShare.loginRadiusLIrearrange');
		$rearrnage_provider = (!empty($rearrange_provider) ? unserialize($rearrange_provider) : "");
		$AppID=trim(C('Plugins.SocialLogin.Apikey'));
		if($hsharing_theme=='horizonSharing32') {
			$interface='horizontal';
			$size=32;
		}
		elseif($hsharing_theme=='horizonSharing16') {
			$interface='horizontal';
			$size=16;
		}
		elseif($hsharing_theme=='single-image-theme-large') {
 			$interface='simpleimage';
			$size=32;
		}
		elseif($hsharing_theme=='single-image-theme-small') {
 			$interface='simpleimage';
    		$size=16;
		}
		elseif($hsharing_theme=='16VerticlewithBox') {
			$interface='Simplefloat';
    		$size=16;
		}
		else {
			$interface='Simplefloat';
    		$size=32;
		}
 		$code = '<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true;</script> <script 	type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js" id="lrsharescript"></script> <script type="text/javascript">LoginRadius.util.ready(function () { $i = $SS.Interface.'.$interface.';  $SS.Providers.Top = [';

 		if(empty($rearrange_provider)) {		
			 $rearrange_provider = array('Facebook' => 'rearrange1',
								'Pinterest' => 'rearrange2',
								'GooglePlus' => 'rearrange3',
								'Twitter' => 'rearrange4',
								'LinkedIn' => 'rearrange5'
								);			
		}
		foreach ($rearrange_provider as $key=>$value) { 
			if($value=='rearrange1')
				$sm='Facebook';
			elseif($value=='rearrange4')
				$sm='Twitter';
			elseif($value=='rearrange3')
				$sm='GooglePlus';
			elseif($value=='rearrange2')
				$sm='Pinterest';
			elseif($value=='rearrange5')
				$sm='LinkedIn';
			elseif($value=='rearrange6')
				$sm='Google';
			elseif($value=='rearrange7')
				$sm='Yahoo';
			elseif($value=='rearrange8')
				$sm='Reddit';
			elseif($value=='rearrange9')
				$sm='Email';
			elseif($value=='rearrange11')
				$sm='Tumblr';
			elseif($value=='rearrange12')
				$sm='Live';
			elseif($value=='rearrange13')
				$sm='Vkontakte';
			elseif($value=='rearrange14')
				$sm='Digg';
			elseif($value=='rearrange15')
				$sm='MySpace';
			elseif($value=='rearrange16')
				$sm='Delicious';
			elseif($value=='rearrange17')
				$sm='Hyves';
			elseif($value=='rearrange18')
				$sm='DotNetKicks';
		  $code.= '"' .$sm .'",';
	   }
		$code.='];$u = LoginRadius.user_settings; $u.apikey= "'.$AppID.'"; $i.size ='.$size.';';
		if($hsharing_theme=='16VerticlewithBox') {
			if($vsharing_position=='topleft') {
				$position1='top';
				$position2='left';
				$sharing_fromtop=$sharing_offset;
			}
			elseif($vsharing_position=='topright') {
				$position1='top';
				$position2='right';
				$sharing_fromtop=$sharing_offset;
			}
			elseif($vsharing_position=='bottomleft') {
				$position1='bottom';
				$position2='left';
				$sharing_fromtop=25;
			}
			else {
				$position1='bottom';
				$position2='right';
				$sharing_fromtop=25;
			}
		$code .= '$i.'.$position1.'="'.$sharing_fromtop.'px"; $i.'.$position2.' = \'5px\';';
		}
		elseif($hsharing_theme=='32VerticlewithBox') {
			if($vsharing_position=='topleft') {
				$position1='top';
				$position2='left';
				$sharing_fromtop=$sharing_offset;
			}
			elseif($vsharing_position=='topright') {
				$position1='top';
				$position2='right';
				$sharing_fromtop=$sharing_offset;
			}
			elseif($vsharing_position=='bottomleft') {
				$position1='bottom';
				$position2='left';
				$sharing_fromtop=25;
			}
			else {
				$position1='bottom';
				$position2='right';
				$sharing_fromtop=25;
			}
		$code .= '$i.'.$position1.'="'.$sharing_fromtop.'px"; $i.'.$position2.' = \'5px\';';
		}
		
		$code .= '$i.show("lrsharecontainer"); }); </script>';
		return($code);
}
    /*
    function for Returning Counter code.
    */
	public function counter() {
		$hconter_theme=trim(C('Plugins.SocialCounter.Horizontalcountertheme'));
		$vcounter_position=C('Plugins.SocialCounter.Verticalcounterposition');
		$counter_offset=(C('Plugins.SocialCounter.Counteroffset'));
		$counter_offset=is_numeric($counter_offset)?($counter_offset) : 0;
		$counter_provider=C('Plugins.SocialCounter.counterprovidercheckbox');
		if($hconter_theme=='hybrid-horizontal-horizontal') {
			$interface='horizontal';
			$is_horizontal='true';
		}
		elseif($hconter_theme=='hybrid-horizontal-vertical') {
			$interface='vertical';
			$is_horizontal='true';
		}
		elseif($hconter_theme=='hybrid-verticle-horizontal') {
			$interface='horizontal';
    		$is_horizontal='false';
		}
		elseif($hconter_theme=='hybrid-verticle-vertical') {
			$interface='vertical';
    		$is_horizontal='false';
		}
	
		$counterscript = '<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true;</script> <script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js"></script> <script type="text/javascript"> LoginRadius.util.ready(function () { $SC.Providers.Selected = [';
		/*if(empty($counter_provider)) {
		$counter_provider = array('Facebook Like' => 'Facebook Like',
								'Google+ +1' => 'Google+ +1',
								'Pinterest Pin it' => 'Pinterest Pin it',
								'Twitter Tweet' => 'Twitter Tweet',
								'Hybridshare' => 'Hybridshare'
								);		
	   }*/
		foreach ($counter_provider as $key=>$value) { 
			$counterscript.= '"' .$value .'",';
		}
		$counterscript.= ']; $S = $SC.Interface.simple; $S.isHorizontal = '.$is_horizontal.'; $S.countertype = "'.$interface.'";';
		if($hconter_theme=='hybrid-verticle-horizontal') {
			if($vcounter_position=='topleft') {
				$position1='top';
				$position2='left';
				$counter_fromtop=$counter_offset;
			}
			elseif($vcounter_position=='topright') {
				$position1='top';
				$position2='right';
				$counter_fromtop=$counter_offset;
			}
			elseif($vcounter_position=='bottomleft') {
				$position1='bottom';
				$position2='left';
				$counter_fromtop=20;
			}
			else {
				$position1='bottom';
				$position2='right';
				$counter_fromtop=20;
			}
		$counterscript .= '$S.'.$position1.'="'.$counter_fromtop.'px"; $S.'.$position2.' = \'5px\';';
		}
		elseif($hconter_theme=='hybrid-verticle-vertical') {  
			if($vcounter_position=='topleft') {
				$position1='top';
				$position2='left';
				$counter_fromtop=$counter_offset;
			}
			elseif($vcounter_position=='topright')
			{
				$position1='top';
				$position2='right';
				$counter_fromtop=$counter_offset;
			}
			elseif($vcounter_position=='bottomleft')
			{
				$position1='bottom';
				$position2='left';
				$counter_fromtop=20;
			}
			else
			{
				$position1='bottom';
				$position2='right';
				$counter_fromtop=20;
			}
		$counterscript .= '$S.'.$position1.'="'.$counter_fromtop.'px"; $S.'.$position2.' = \'5px\';';
		}
		$counterscript .= '$S.show("lrcounter_simplebox"); }); </script>'; 
		return($counterscript);
	}

  
  
  
  
	public function ValidateEmail($Value, $Field = '') {
    	$Result = PHPMailer::ValidateAddress($Value);
    	$Result = (bool)$Result;
    	return  $Result;
   	}


  	public function SettingsController_SocialLogin_Create($Sender, $Args) {
    	$Sender->Permission('Garden.Settings.Manage');
    	if ($Sender->Form->IsPostBack()) {
			$Apikey=trim($Sender->Form->GetFormValue('Apikey'));
			$Secretkey=trim($Sender->Form->GetFormValue('Secretkey'));
				if(!$this->isValidApiSettings($Apikey)) {
					$Sender->InformMessage(T('Please enter a valid API Key'));
				}
				elseif(!$this->isValidApiSettings($Secretkey)) {
					$Sender->InformMessage(T('Please enter a valid API Secret'));
				}
				elseif($Apikey == $Secretkey) {
					$Sender->InformMessage(T('Both API key and Secret are same. Please enter the correct API key and Secret.'));
				}
				else {
      				$rearangesettings = $_REQUEST['rearrange_settings'];
      				$Settings = array(
     				'Plugins.SocialLogin.Apikey' =>$Apikey,
     				'Plugins.SocialLogin.Secretkey' =>$Secretkey, 
	 				'Plugins.SocialLogin.Use_Api' =>$Sender->Form->GetFormValue('Use_Api'),
	 				'Plugins.SocialLogin.Sociallogintitle' =>$Sender->Form->GetFormValue('Sociallogintitle'),	 
					'Plugins.SocialLogin.Email_required' => $Sender->Form->GetFormValue('EmailRequired'),
	 				'Plugins.SocialLogin.Loginredirect' => $Sender->Form->GetFormValue('Loginredirect'),
	 				'Plugins.SocialLogin.Loginredirecturl' =>$Sender->Form->GetFormValue('Loginredirecturl'),
	 				'Plugins.SocialLogin.Account_linking' => $Sender->Form->GetFormValue('Accountlinking'),
	 				'Plugins.SocialLogin.Emailtitle' => $Sender->Form->GetFormValue('Emailtitle'),
	 				'Plugins.SocialLogin.Emailerrortitle' => $Sender->Form->GetFormValue('Emailerrortitle'),
	 				'Plugins.SocialLogin.Mappingtitle' => $Sender->Form->GetFormValue('Mappingtitle'),
	 				'Plugins.SocialLogin.SkipEmail' => $Sender->Form->GetFormValue('SkipEmail'),
					'Plugins.SocialShare.Enablesocialsharing' =>$Sender->Form->GetFormValue('Enablesocialsharing'),
	 				'Plugins.SocialShare.Socialsharetitle' =>$Sender->Form->GetFormValue('Socialsharetitle'),
	 				'Plugins.SocialShare.Horizontalsharingtheme' =>$Sender->Form->GetFormValue('Horizontalsharingtheme'),
	 				'Plugins.SocialShare.Horizontalsharingposition' =>$Sender->Form->GetFormValue('Horizontalsharingposition'),
	 				'Plugins.SocialShare.Verticalsharingtheme' =>$Sender->Form->GetFormValue('Verticalsharingtheme'),
	 				'Plugins.SocialShare.Verticalsharingposition' =>$Sender->Form->GetFormValue('Verticalsharingposition'),
	 				'Plugins.SocialShare.Sharingoffset' =>$Sender->Form->GetFormValue('Sharingoffset'),
	 				'Plugins.SocialShare.loginRadiusLIrearrange' => $rearangesettings, 
	 				'Plugins.SocialCounter.Enablesocialcounter' =>$Sender->Form->GetFormValue('Enablesocialcounter'),
	 				'Plugins.SocialCounter.Socialcountertitle' =>$Sender->Form->GetFormValue('Socialcountertitle'),
	 				'Plugins.SocialCounter.Horizontalcountertheme' =>$Sender->Form->GetFormValue('Horizontalcountertheme'),
	 				'Plugins.SocialCounter.Horizontalcounterposition' =>$Sender->Form->GetFormValue('Horizontalcounterposition'),
	 				'Plugins.SocialCounter.Verticalcountertheme' =>$Sender->Form->GetFormValue('Verticalcountertheme'),
	 				'Plugins.SocialCounter.Verticalcounterposition' =>$Sender->Form->GetFormValue('Verticalcounterposition'),
	 				'Plugins.SocialCounter.Counteroffset' =>$Sender->Form->GetFormValue('counteroffset'),
	 				'Plugins.SocialCounter.counterprovidercheckbox' =>$Sender->Form->GetFormValue('Counterprovidercheckbox'));
     				SaveToConfig($Settings);
					$Sender->InformMessage(T("Your settings have been saved."));
				}
  		}
    	else {
	 		$LoginRedirect=((C('Plugins.SocialLogin.Loginredirect')=='')?'Loginredirect1':C('Plugins.SocialLogin.Loginredirect'));
			$UseApi=((C('Plugins.SocialLogin.Use_Api')=='')?'CURL':C('Plugins.SocialLogin.Use_Api'));
			$EmailRequired=((C('Plugins.SocialLogin.Email_required')=='')?'Yes':C('Plugins.SocialLogin.Email_required'));
			$SkipEmail=((C('Plugins.SocialLogin.SkipEmail')=='')?'No':C('Plugins.SocialLogin.SkipEmail'));
			$AccountLinking=((C('Plugins.SocialLogin.Account_linking')=='')?'Yes':C('Plugins.SocialLogin.Account_linking'));
			$EnableSocialSharing=((C('Plugins.SocialShare.Enablesocialsharing')=='')?'No':C('Plugins.SocialShare.Enablesocialsharing'));
			$HorizontalSharing=((C('Plugins.SocialShare.Horizontalsharingtheme')=='')?'horizonSharing32':C('Plugins.SocialShare.Horizontalsharingtheme'));
			$HorizontalSharingPosition=((C('Plugins.SocialShare.Horizontalsharingposition')=='')?'Top':C('Plugins.SocialShare.Horizontalsharingposition'));
			$VerticalSharingPosition=((C('Plugins.SocialShare.Verticalsharingposition')=='')?'topleft':C('Plugins.SocialShare.Verticalsharingposition'));
			$EnableSocialCounter=((C('Plugins.SocialCounter.Enablesocialcounter')=='')?'No':C('Plugins.SocialCounter.Enablesocialcounter'));
			$HorizontalCounter=((C('Plugins.SocialCounter.Horizontalcountertheme')=='')?'hybrid-horizontal-horizontal':C('Plugins.SocialCounter.Horizontalcountertheme'));
			$HorizontalCounterPosition=((C('Plugins.SocialCounter.Horizontalcounterposition')=='')?'Bottom':C('Plugins.SocialCounter.Horizontalcounterposition'));
			$VerticalCounterPosition=((C('Plugins.SocialCounter.Verticalcounterposition')=='')?'topright':C('Plugins.SocialCounter.Verticalcounterposition'));
			
			
		    /*
		    sociallogin setting call
		    */
		
			$Sender->Form->SetFormValue('Apikey', C('Plugins.SocialLogin.Apikey'));
			$Sender->Form->SetFormValue('Secretkey', C('Plugins.SocialLogin.Secretkey'));	  
			$Sender->Form->SetFormValue('Use_Api', $UseApi);
			$Sender->Form->SetFormValue('Sociallogintitle', C('Plugins.SocialLogin.Sociallogintitle'));	 
			$Sender->Form->SetFormValue('EmailRequired', $EmailRequired);
			$Sender->Form->SetFormValue('SkipEmail', $SkipEmail);
			$Sender->Form->SetFormValue('Loginredirect', $LoginRedirect);
			$Sender->Form->SetFormValue('Accountlinking', $AccountLinking);
			$Sender->Form->SetFormValue('Emailtitle', C('Plugins.SocialLogin.Emailtitle'));
			$Sender->Form->SetFormValue('Emailerrortitle', C('Plugins.SocialLogin.Emailerrortitle'));
		
		    /*
			socialsharing setting call
			*/
			$Sender->Form->SetFormValue('Enablesocialsharing',$EnableSocialSharing);
			$Sender->Form->SetFormValue('Socialsharetitle', C('Plugins.SocialShare.Socialsharetitle'));
			$Sender->Form->SetFormValue('Horizontalsharingtheme', $HorizontalSharing);
			$Sender->Form->SetFormValue('Horizontalsharingposition', $HorizontalSharingPosition);
			$Sender->Form->SetFormValue('Verticalsharingtheme', C('Plugins.SocialShare.Verticalsharingtheme'));
			$Sender->Form->SetFormValue('Verticalsharingposition', $VerticalSharingPosition);
			$Sender->Form->SetFormValue('Sharingoffset', C('Plugins.SocialShare.Sharingoffset'));
			$Sender->Form->SetFormValue('loginRadiusLIrearrange', C('Plugins.SocialShare.loginRadiusLIrearrange'));
			
			/*
		    socialcounter setting call
			*/
			
			$Sender->Form->SetFormValue('Enablesocialcounter', $EnableSocialCounter);
			$Sender->Form->SetFormValue('Socialcountertitle', C('Plugins.SocialCounter.Socialcountertitle'));
			$Sender->Form->SetFormValue('Horizontalcountertheme',$HorizontalCounter);
			$Sender->Form->SetFormValue('Horizontalcounterposition', $HorizontalCounterPosition);
			$Sender->Form->SetFormValue('Verticalcountertheme', C('Plugins.SocialCounter.Verticalcountertheme'));
			$Sender->Form->SetFormValue('Verticalcounterposition', $VerticalCounterPosition);
			$Sender->Form->SetFormValue('counteroffset', C('Plugins.SocialCounter.Counteroffset'));
			$Sender->Form->SetFormValue('Counterprovidercheckbox', C('Plugins.SocialCounter.counterprovidercheckbox'));
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
  		$Loginredirect = C('Plugins.SocialLogin.Loginredirect'); 
  		$Loginredirecturl= C('Plugins.SocialLogin.Loginredirecturl'); 
  		if ($Loginredirect=="Loginredirect1") {
    		$loc ='';
		}
  		else if ($Loginredirect=="Loginredirect2") {
    		$loc = 'profile.php';
		}
  		else {
    		$loc = $Loginredirecturl;
  		}
	  return $loc;
	}
	
	
	public function isValidApiSettings($apikey) {
	      return !empty($apikey) && preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $apikey);
    }
	
   	public function popup($msg, $showinvitation)  { ?> 
    	<link rel="stylesheet" href="plugins/SocialLogin/login.css" type="text/css" />
    	<div id="fade" class="LoginRadius_overlay">
    	<div id="popupouter">
    	<div id="popupinner">
		<div id="textmatter"><?php
    	if ($msg) {
      		echo "<b>" . $msg . "</b>";
    	}?></div>
    	<form id="wp_login_form"  method="post"  action="">
		<?php if($showinvitation == 'no' || $showinvitation == '') {?>
    	Email <input type="text" name="email" id="email" class="inputtxt" />
		<?php } else {?>
	 	Invitation code  <input type="text" name="invitecode" id="invitecode" class="inputtxt" />
		<?php } ?>
    	<input type="submit" id="LoginRadiusRedSliderClick" name="LoginRadiusRedSliderClick" value="Submit" class="inputbutton">
    	<input type="submit" value="Cancel" class="inputbutton" onClick="history.back()" />
    	</form></div></div></div>
<?php }

 
 public function OnDisable() {}
}