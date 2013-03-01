<?php
class socialloginManager extends Delegation  {
  function socialloginManager(&$Context) {
    $this->Name	= 'socialloginManager';
	$this->Delegation($Context);
	$this->CallDelegate('Constructor');
  }
   /*
  *Check that  Provider is verified and verifies =1.
  *
  */
  function check_that($Context,$lrdata,$UserID) {
    $yes='';
    $s1=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
    $s1->SetMainTable('sociallogin', 'u');
	$s1->AddSelect('random', 'u');
	$s1->AddSelect('verified', 'u');
	$s1->AddSelect('ProviderName', 'u');
	$s1->AddWhere('u', 'UserID', '', $UserID, '=');
	$s1->AddWhere('u', 'ProviderName', '', $lrdata['Provider'], '=');
	$result = $Context->Database->Select($s1, $this->Name, 'check_that', 'A fatal error occurred while validating your input.');
	$Row = $this->Context->Database->GetRow($result); 
	$rand = explode('/',   $Row['random']);
	
	if($Row['random']=''||$Row['ProviderName']!=$lrdata['Provider']) {
	 $yes=1;
	}
    elseif($Row['ProviderName']==$lrdata['Provider']){
	  if($Row['verified']==1)
	    $yes=0;
	  else
	    $yes=2;
	}
    return $yes;
  }
   /*
   * User-id is verified.
   *
   */
  function Check_verify($Context,$UserID,$Provider){
    $s1=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
    $s1->SetMainTable('User', 'u');
	$s1->AddSelect('UserID', 'u');
	$s1->AddWhere('u', 'UserID', '', $UserID, '=');
	$result = $Context->Database->Select($s1, $this->Name, 'Check_verify', 'A fatal error occurred while validating your input.');
	$Row = $this->Context->Database->GetRow($result); 
	$s1=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
    $s1->SetMainTable('sociallogin', 'u');
	$s1->AddSelect('UserID', 'u');
	$s1->AddWhere('u', 'UserID', '', $Row['UserID'], '=');
	$result = $Context->Database->Select($s1, $this->Name, 'Check_verify', 'A fatal error occurred while validating your input.');
	$Row = $this->Context->Database->GetRow($result); 
	if($Row['UserID']>0) {
	  if($Provider=='') {
	    $s1=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
        $s1->SetMainTable('sociallogin', 'u');
	    $s1->AddSelect('verified', 'u');
	    $s1->AddSelect('random', 'u');
	    $s1->AddWhere('u', 'UserID', '', $UserID, '=');
	    $result = $Context->Database->Select($s1, $this->Name, 'Check_verify', 'A fatal error occurred while validating your input.');
	    $Row = $this->Context->Database->GetRow($result); 
	    $verify=($Row['verified']!=1)? 0 : 1 ;
	}
	else {
      $s1=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
      $s1->SetMainTable('sociallogin', 'u');
	  $s1->AddSelect('verified', 'u');
      $s1->AddSelect('random', 'u');
	  $s1->AddWhere('u', 'UserID', '', $UserID, '=');
	  $s1->AddWhere('u', 'ProviderName', '',$Provider, '=');
	  $result = $Context->Database->Select($s1, $this->Name, 'Check_verify', 'A fatal error occurred while validating your input.');
	  $Row = $this->Context->Database->GetRow($result); 
	  $verify=($Row['verified']!=1)? 0 : 1 ;
	  }
	}
	else
	$verify=1;
	return $verify;
  }
  /*
  * check role 'applicant, member' for particular userid.
  *
  */
  function check_Role($Context,$UserID) {
    $sj=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
    $sj->SetMainTable('User', 'u');
    $sj->AddSelect('RoleID', 'u');
    $sj->AddWhere('u', 'UserID', '', $UserID, '=');
    $result = $Context->Database->Select($sj, $this->Name, 'check_Role', 'A fatal error occurred while validating your input.');
    $Rowr = $this->Context->Database->GetRow($result);
    return  $verify_Role= (($Rowr['RoleID']==0 ||$Rowr['RoleID']==2)? 0 : 1);
   }
     /*
   * After retrieve Link what updation takes place.
   *
   */
  function verify_email($Context){
    $rand = explode('/',  $_REQUEST['SL_VERIFY_EMAIL']);
    $s1 = $Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
  	$s1->SetMainTable('sociallogin', 'u');
	$s1->AddSelect('UserID', 'u');
	$s1->AddSelect('ProviderName', 'u');
	$s1->AddWhere('u', 'random', '', $rand[0]."/".$rand[1], '=');
	$result = $Context->Database->Select($s1, $this->Name, 'Check_verify', 'A fatal error occurred while validating your input.');
	$Row = $this->Context->Database->GetRow($result);
    $s6=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	$s6->SetMainTable('sociallogin', 'u');
	$s6->AddSelect('UserID', 'u');
	$s6->AddWhere('u', 'ProviderName', '',$Row['ProviderName'], '=');
	$result = $Context->Database->Select($s6, $this->Name, 'verify_email', 'A fatal error occurred while validating your input.');
	$MatchCount = $Context->Database->RowCount($result);
	if($MatchCount>0)  {
	  $s6->SetMainTable('sociallogin', 'u');
	  $s6->AddWhere('u', 'random', '', $rand[0]."/".$rand[1], '!=');
	  $Context->Database->Delete($s6,$this->Name,'verify_email','error occured delete');
	}
	if($Row['UserID']>0){
	  $rh=0 ."/".$rand[1];
      $sj = $Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	  $sj->SetMainTable('sociallogin', 'u');
	  $sj->AddFieldNameValue('random',$rh);
	  $sj->AddFieldNameValue('verified',1);
	  $sj->AddWhere('u', 'random', '',$rand[0]."/".$rand[1], '=');
	  $this->Context->Database->Update($sj, $this->Name, 'verify_email', 'An error occurred while updating the social login.');
	  $msg= "Email is verified and Now you can login.";
	  $this->popup_verify($msg,$Context);
	}
  }
  /*
  * Render when user Registration setting is Applicant.
  *
  */
  
  function Render_ValidPostBack($Context) {
    $this->CallDelegate('PreValidPostBackRender');
	include(ThemeFilePath($Context->Configuration, 'people_apply_form_validpostback.php'));
	$this->CallDelegate('PostValidPostBackRender');
   }
	/*
    * send Email to User.
    *
    */
  function SendMail($Context,$lrdata,$UserID){
    $Index_user = $Context->ObjectFactory->NewContextObject($Context, 'UserManager');
    $AffectedUser =  $Index_user->GetUserById($UserID);
    $e = $this->Context->ObjectFactory->NewContextObject($Context, 'Email');
	$e->HtmlOn = 0;
	$e->WarningCollector = &$this->Context->WarningCollector;
	$e->ErrorManager = &$this->Context->ErrorManager;
	$e->AddFrom($AffectedUser->Email, $this->Context->Configuration['APPLICATION_TITLE']);
	$e->AddRecipient($AffectedUser->Email, $AffectedUser->Name);
	$e->Subject = $this->Context->Configuration['APPLICATION_TITLE'].' '.'Email Verification';
    $File = '';
	$File = $this->Context->Configuration['EXTENSIONS_PATH'].'SocialLogin/email_verify.txt';
	$EmailBody = @file_get_contents($File);
    if (!$EmailBody) $this->Context->ErrorManager->AddError($this->Context, $this->Name, 'SendMail', 'Failed to read email template ('.$File.').');
	$EmailBody = str_replace(
					array(
						'{user_name}',
						'{forum_name}',
						'{forum_url}'
					),
					array(
						$AffectedUser->Name,
						$this->Context->Configuration['APPLICATION_TITLE'],
						GetUrl($this->Context->Configuration, '', '', 'SL_VERIFY_EMAIL', $lrdata['random']."/".$lrdata['id'])
					),
					$EmailBody
				);
     $this->DelegateParameters['AffectedUser'] = &$AffectedUser;
	 $this->DelegateParameters['EmailBody'] = &$EmailBody;
	 $e->Body = $EmailBody;
	 $e->Send();
  }
  
  /*
* display messages.
*
*/
function popup_verify($msg,$Context) {

?>
	<div id="fade" class="LoginRadius_overlay">
	<div id="popupouter">
	<div id="popupinner">
	<div id="textmatter"><?php
    if ($msg) {
      echo "<b>" . $msg . "</b>";
    }
	
    ?></div>
	
	<form method="post" action=""><div><input type="submit" value="OK" class="inputbutton"></div></form></div></div></div>
	
	
	
	<?php
}

/*
* Retrieve Userid.
*
*/
  function retrieveUserID($Context,$lrdata)  {
    $s3=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
    $s3->SetMainTable('sociallogin', 'c');
	$s3->AddSelect(array('UserID'), 'c');
	$s3->AddWhere('c', 'socialloginID', '', $lrdata['id'], '=');
	$ResultSet = $this->Context->Database->Select($s3, $this->Name, 'retrieveUserID', 'An error occurred while attempting to retrieve .');
	$Row = $this->Context->Database->GetRow($ResultSet); 
	$UserID = ForceInt(@$Row['UserID'], 0);
	$s4=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	$s4->SetMainTable('User', 'c');
	$s4->AddSelect(array('UserID'), 'c');
    $s4->AddWhere('c', 'UserID', '', $UserID, '=');
	$ResultSet = $this->Context->Database->Select($s4, $this->Name, 'retrieveUserID', 'An error occurred while attempting to retrieve .');
	$Row = $this->Context->Database->GetRow($ResultSet); 
	return	$UserID = ForceInt(@$Row['UserID'], 0);				
  }
   /*
  *Retrieve User-id from Email.
  */

  function retrieveemail($Context,$lrdata)  {
  $s4=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
  $s4->SetMainTable('User', 'c');
  $s4->AddSelect(array('UserID'), 'c');
  $s4->AddWhere('c', 'Email', '', $lrdata['email'], '=');
  $ResultSet = $this->Context->Database->Select($s4, $this->Name, 'retrieveemail', 'An error occurred while attempting to retrieve .');
  $Row = $this->Context->Database->GetRow($ResultSet); 
  return	$UserID = ForceInt(@$Row['UserID'], 0);
  }
  
  /*
 * Insert User information in database.
 *
 */
  function insertNewUser(&$Context,$lrdata) {
	$index=0;
    $lrdata['username']= FormatStringForDatabaseInput($lrdata['username']);
    $Index_user = $Context->ObjectFactory->NewContextObject($Context, 'UserManager');
	$s1=$Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	$s1->SetMainTable('User', 'u');
	$s1->AddSelect('UserID', 'u');
	$s1->AddWhere('u', 'Name', '', $lrdata['username'].'%', 'like');
	$result = $Context->Database->Select($s1, $this->Name, 'insertNewUser', 'A fatal error occurred while validating your input.');
	$MatchCount = $Context->Database->RowCount($result);
	if($MatchCount>0)  {
	  for($i=0;$i<$MatchCount;$i++){
		$index++;
	  }
	  $lrdata['username']=$lrdata['username'].$index;
	}
	
	$s1->AddFieldNameValue('FirstName',FormatStringForDatabaseInput($lrdata['fname']));
	$s1->AddFieldNameValue('LastName',FormatStringForDatabaseInput($lrdata['lname']));
	$s1->AddFieldNameValue('Name',FormatStringForDatabaseInput($lrdata['username']));
	$s1->AddFieldNameValue('DateFirstVisit', MysqlDateTime());
	$s1->AddFieldNameValue('Password',$Index_user->HashPassword(''));
	$s1->AddFieldNameValue('RoleID', $lrdata['RoleId']);
	$s1->AddFieldNameValue('DateLastActive', MysqlDateTime());
	$s1->AddFieldNameValue('VerificationKey',DefineVerificationKey());
	$s1->AddFieldNameValue('Email',$lrdata['email']);	
	$s1->AddFieldNameValue('RemoteIp',GetRemoteIp(1));	
	return	$Context->UserID= $Context->Database->Insert($s1,$this->Name,'insertNewUser','error occured during insertion');
  }
	 /*
  * Insert into Social login table.
  *
  */
  function insertSocialloginID(&$Context,$lrdata,$UserID) {
	$st= $Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	$st->SetMainTable('sociallogin', 'll');
	$st->AddWhere('ll', 'socialloginID', '', $lrdata['id'], '=');
	$Context->Database->Delete($st,$this->Name,'insertSocialloginID','error occured delete');
	$s = $Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	$s->SetMainTable('sociallogin', 'l');
	$s->AddFieldNameValue('UserID', $UserID);
	$s->AddFieldNameValue('socialloginID',$lrdata['id']);
	$s->AddFieldNameValue('ProviderName',  $lrdata['Provider']);
	$s->AddFieldNameValue('random',$lrdata['rndm']);
	$s->AddFieldNameValue('verified',$lrdata['vrfied']);
	$Context->Database->Insert($s,$this->Name,'insertSocialloginID','error occured during insertion');
   }
    /*
    * Check user is also present in sociallogin or not for social linking.
    *
   */
   function checkprofile(&$Context,$lrdata,$UserID) {
	$s9 = $Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	$s9->SetMainTable('sociallogin', 's');
	$s9->AddSelect('UserID', 's');
	$s9->AddWhere('s', 'UserID', '', $UserID, '=');
	$row= $Context->Database->Select($s9, $this->Name, 'checkprofile', 'A fatal error occurred while retrieving UserID.');
	return  $Context->Database->RowCount($row);
	}
	/*
    *Add the User-id into Social-login table.
    *
    */
   function SocialLink(&$Context,$lrdata,$UserID) {
	$sg = $Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	$sg->SetMainTable('sociallogin', 'u');
	$sg->AddFieldNameValue('UserID',$UserID);
	$sg->AddFieldNameValue('socialloginID',$lrdata['id']);
	$sg->AddFieldNameValue('ProviderName',  $lrdata['Provider']);
	$sg->AddFieldNameValue('random', '');
	$sg->AddFieldNameValue('verified', 1);
	$Context->Database->Insert($sg, $this->Name, 'SocialLink', 'An error occurred while updating the Social Linking.');
	}
	/*
    * Upadte the Profile of User.
    *
    */
  function updateprofile(&$Context,$lrdata,$UserID)  {
	$s5 = $Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
	$s5->SetMainTable('User', 'u');
	$s5->AddFieldNameValue('DateLastActive', MysqlDateTime());
	$s5->AddFieldNameValue('CountVisit','CountVisit + 1', 0 );
	$s5->AddWhere('u', 'UserID', '', $UserID, '=');
	$this->Context->Database->Update($s5, $this->Name, 'updateprofile', 'An error occurred while updating the user profile.');
   }
    /*
   * Redirect at Particular url.
   *
   */
   function RedirectUrl(&$Context){
    $ReturnUrl = urldecode(ForceIncomingString('ReturnUrl', ''));
   if($ReturnUrl=='')
     $redirect=$Context->Configuration['FORWARD_VALIDATED_USER_URL'];
   else
     $redirect=$ReturnUrl;
   return $redirect;
   }
  /*
   * For Email Entering Pop-up box.
   *
   */
  function popup($msg)  {?>
	<div id="fade" class="LoginRadius_overlay">
	<div id="popupouter">
	<div id="popupinner">
	<div id="textmatter"><?php
    if ($msg) {
      echo "<b>" . $msg . "</b>";
    }
    ?></div>
	<form id="wp_login_form"  method="post"  action="">
 <input type="text" name="email" id="email" class="inputtxt" />
	<input type="submit" id="LoginRadiusRedSliderClick" name="LoginRadiusRedSliderClick" value="Submit" class="inputbutton">
	<input type="submit" value="Cancel" class="inputbutton"  />
	
	</form></div></div></div>
  
  <?php
   	}
 }
 ?>