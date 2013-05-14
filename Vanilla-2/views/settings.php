<?php if (!defined('APPLICATION')) exit();
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script type="text/javascript" src="plugins/SocialLogin/views/setting_js.js"></script>
<link rel="stylesheet" type="text/css" href="plugins/SocialLogin/views/setting_style.css">
<style type="text/css">

.lrshare_iconsprite32{
	cursor: pointer !important;
	float:left;
	height: 32px;
	margin: 4px !important;
	width: 32px;
	padding: 0px !important;
	border: none !important;
	background: url("plugins/SocialLogin/views/images/socialshare/lrshare_iconsprite32.png") no-repeat scroll left top transparent;
	list-style-type: none !important;
}
 
#Error {
  background-image: url("plugins/SocialLogin/views/images/error.png");
  background-repeat:no-repeat;
  background-position:left; 
  color:#FF0000; 
  padding: 4px 2px 2px 30px;
  background-color: #FEF5F1;
  border: 1px solid #ED541D;
  width:50%;
  margin-left:150px;
  height:auto;
}
#loading {
  background-image: url('plugins/SocialLogin/views/images/loading.gif');
  background-repeat:no-repeat;
  padding: 0px 2px 0px 30px;
  width:50%;
  margin-left:150px;
   height:auto;
}
#Success {
  background-image: url("plugins/SocialLogin/views/images/ok.png");
  background-repeat:no-repeat;
  background-position:left; 
  color:#669933; 
  padding: 4px 2px 2px 30px;
  background-color: #EAFFDC;
  border: 1px solid #60CF4E;
  width:50%;
  margin-left:150px;
  height:auto;
}
</style>
<h1><?php echo $this->Data('Title'); ?></h1>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<div class="Info">
   <?php echo T('SocialLogin Connect allows users to sign in using their SocialLogin account. <b>You must register your application with SocialLogin for this plugin to work.</b>'); ?>
</div>

<div id='tabs'> 
<ul> 
<li style="float:left"><a href="#tab-1">API Settings</a></li> 
<li style="float:left"><a href="#tab-2">Social Login</a></li> 
<li style="float:left"><a href="#tab-3">Social Sharing</a></li> 
<li style="float:left"><a href="#tab-4">Social Counter</a></li>
</ul> 
</div> 
<div>
    <div class="container" id="tab-1">
    <div style="float:left;">
    <div style="float:left;width:70%;">
    <div style="background:none repeat scroll 0 0 #DBF3FC;border:1px solid #82BDDD;margin: 10px 0 0 20px;">
        <?php echo '<h4>Thank you for installing the LoginRadius Vanilla Module!</h4>'; ?>
     <br />
     <div style="margin:10px 0 0 20px;">
        <?php echo 'To activate the extension, please configure it and manage the social networks from you LoginRadius account. If you do not have an account, click <a href="http://www.loginradius.com" target="_blank">here</a> and create one for FREE! '; ?> 
      <br /><br />
        <?php echo 'We also have Social Plugin for <a href="https://www.loginradius.com/loginradius-cms-social-plugins/wordpress-plugin" target="_blank">WordPress</a>, <a href="https://www.loginradius.com/loginradius-cms-social-plugins/drupal-module" target="_blank">Drupal</a>, <a href="https://www.loginradius.com/loginradius-cms-social-plugins/vbulletin-forum-add-on" target="_blank">vBulletin</a>, <a href="https://www.loginradius.com/loginradius-cms-social-plugins/joomla-extension" target="_blank">Joomla</a>, <a href="https://www.loginradius.com/loginradius-cms-social-plugins/magento-extension" target="_blank">Magento</a>, <a href="https://www.loginradius.com/loginradius-cms-social-plugins/oscommerce-addon" target="_blank">osCommerce</a>, <a href="https://www.loginradius.com/loginradius-cms-social-plugins/prestashop-module" target="_blank">PrestaShop</a>, <a href="https://www.loginradius.com/loginradius-cms-social-plugins/x-cart-module" target="_blank">X-Cart</a>, <a href="https://www.loginradius.com/loginradius-cms-social-plugins/zencart-plugin" target="_blank">Zen-Cart</a>, <a href="https://www.loginradius.com/loginradius-cms-social-plugins/dotnetnuke-module" target="_blank">DotNetNuke</a> and <a href="https://www.loginradius.com/loginradius-cms-social-plugins/blogengine-extension" target="_blank">BlogEngine</a>!'; ?> 
        <br />
      	<a href="http://www.loginradius.com/" target="_blank"><input name="<?php echo 'Set up my FREE account!'; ?>" type="button" class="Button SliceSubmit" value="<?php echo 'Set up my FREE account!'; ?>" style="margin:10px;"></a>
</div>
</div>
</div>
<div style="float:right;width:30%;">
<div style="background:none repeat scroll 0 0 #DBF3FC;border:1px solid #82BDDD;margin: 10px 0 0 20px;">
	<h4><?php echo T('Help and Documentations'); ?></h4>
	<ul>
  <li><a href="http://support.loginradius.com/customer/portal/articles/638461-how-do-i-implement-social-login-on-vanilla-2#curlissue" target="_blank"><?php echo 'Plugin Installation, Configuration and Troubleshooting'; ?></a></li>
  <li><a href="http://support.loginradius.com/customer/portal/articles/677100-how-to-get-loginradius-api-key-and-secret" target="_blank"><?php echo 'How to get LoginRadius API Key and Secret'; ?></a></li>
	<li><a href="http://community.loginradius.com/" target="_blank"><?php echo 'Discussion Forum'; ?></a></li>
	<li><a href="https://www.loginradius.com/Loginradius/About" target="_blank"><?php echo 'About LoginRadius'; ?></a></li>
	<li><a href="https://www.loginradius.com/product/sociallogin" target="_blank"><?php echo 'LoginRadius Products'; ?></a></li>
	<li><a href="https://www.loginradius.com/addons" target="_blank"><?php echo 'Social Plugins'; ?></a></li>
	<li><a href="https://www.loginradius.com/sdks/loginradiussdk" target="_blank"><?php echo 'Social SDKs'; ?></a></li>
	<li><a href="https://www.loginradius.com/loginradius/Testimonials" target="_blank"><?php echo 'Testimonials'; ?></a></li>
</ul>
</div>
<div style="background:none repeat scroll 0 0 #DBF3FC;border:1px solid #82BDDD;margin: 10px 0 0 20px;">
	<h4><?php echo T('Stay Updated!');?></h4>
<p style="line-height: 19px;font-size:12px !important; margin:10px !important;">
<?php echo T('To receive updates on new features, releases, etc, please connect to one of our social media pages.'); ?> <br>
<ul class="stay_ul">
<li>
<iframe rel="tooltip" scrolling="no" frameborder="0" allowtransparency="true" style="border: none; overflow: hidden; width: 46px; height: 70px;" src="//www.facebook.com/plugins/like.php?app_id=194112853990900&href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FLoginRadius%2F119745918110130&send=false&layout=box_count&width=90&show_faces=false&action=like&colorscheme=light&font=arial&height=90" data-original-title="Like us on Facebook"></iframe>
</li>
</ul>
<div>
<div class="twitter_box"><span id="followers"></span></div>
<a href="https://twitter.com/LoginRadius" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false"></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>
</div>

<div style="background:none repeat scroll 0 0 #DBF3FC;border:1px solid #82BDDD;margin: 10px 0 0 20px;">
<h4><?php echo 'Support Us'; ?></h4>
<p align="left" style="line-height: 20px; font-size:12px !important;margin:10px !important;">
<?php echo T('If you liked our FREE open-source plugin, please send your feedback/testimonial to <a href="mailto:feedback@loginradius.com">feedback@loginradius.com</a>! '); ?></p></div>
</div>
<div>
	<h4>To activate the plugin, insert the LoginRadius API Key & Secret</h4>
	                               
	<div><label style="float:left; width:100px; margin:18px 0 0 15px; font-weight:bold">API Key</label>
		 <?php echo $this->Form->TextBox('Apikey',array( 
		        'maxlength' => 255,
		        'style' => 'width: 280px; margin: 15px 0 0 10px' 
		     ));?></div>	
	<div><label style="float:left; width:100px; margin:18px 0 0 15px; font-weight:bold">API Secret</label>
		 <?php echo $this->Form->TextBox('Secretkey',array( 
		        'maxlength' => 255,
		        'style' => 'width: 280px; margin: 15px 0 0 10px' 
		     ));
            ?></div>
            <h4><label>What API Connection Method do you prefer to use to enable API communication?</label></h4>
	     <?php $this->connection_method= array(
        'CURL' => T('Use CURL'),
        'Fsockopen' => T('Use FSOCKOPEN'.'<br>'),
		);
		echo $this->Form->RadioList('Use_Api', $this->connection_method, array('default' => 'CURL','style'=> 'margin:15px 0 0 20px;'));?>
      
      <div> <input class="SmallButton" type="button" style="font-weight:bold; float: left; margin-left:20px; " id="login_radius_detect_api" value="Verify API Settings" onclick="MakeRequest();" /> <div id="login_radius_response" style="margin:13px 0 0 25px;"></div></div>
</div></div></div>
<div class="container" id="tab-2">
<div>
<div><h4><label>What text should be displayed above the Social Login interface? Leave blank if you do not want any text to be displayed</label></h4></div>
<?php $Sociallogintitle=$this->Form->GetFormValue('Sociallogintitle');

			 echo $this->Form->TextBox('Sociallogintitle', array( 
		        'maxlength' => 255,
		        'value' =>$Sociallogintitle, 
		        'style' => 'width: 280px; margin: 15px 0 0 18px;' 
		     ) 
		  );
?>
<div><h4><label>What text should be displayed above the Email PopUp?</label></h4></div>
<?php $Emailtitle=$this->Form->GetFormValue('Emailtitle');
			 echo $this->Form->TextBox('Emailtitle', array( 
		        'maxlength' => 255,
		        'value' =>'Please enter your email address to proceed', 
		        'style' => 'width: 280px; margin: 15px 0 0 18px;' 
		     ) 
		  );
?>
<div><h4><label>What text should be displayed when user enters incorrect email?</label></h4></div>
<?php $Emailerrortitle=$this->Form->GetFormValue('Emailerrortitle');
			 echo $this->Form->TextBox('Emailerrortitle', array( 
		        'maxlength' => 255,
		        'value' =>'Please enter your correct email address to proceed', 
		        'style' => 'width: 280px; margin: 15px 0 0 18px;' 
		     ) 
		  );
?>
<div><h4><label>A few ID Providers do not supply users' e-mail IDs as part of the user profile data they provide. Do you want to prompt users to provide their email IDs before completing registration process if it is not provided by the ID Provider?</label></h4></div>
<div>
<?php $this->Email_required= array(
        'Yes' => T('YES, ask users to enter their email address in a pop-up<br/>'),
        'No' => T('NO, just auto-generate random email IDs for users')
		);
		echo $this->Form->RadioList('EmailRequired', $this->Email_required, array('default' => 'Yes','style'=> 'margin:10px 0 0 20px;'));
		?> 
 </div>
 <div><h4><label>Do you want users to skip email verification when logging in from social ID providers like Facebook, Google, Yahoo, etc. and email is already provided by the ID provider?:</label></h4></div>
 <div>
<?php $this->Email_skip= array(
        'Yes' => T('YES'),
        'No' => T('NO') 
		);
		echo $this->Form->RadioList('SkipEmail', $this->Email_skip, array('default' => 'No','style'=> 'margin:10px 0 0 20px;'));
		?> 
 </div>
<div><h4><label>Where do you want to redirect your users after successfully logging in?</label></h4></div>
<div>
 <?php
	    $this->Login_Redirect= array(
        'Loginredirect1' => T('Redirect to Same page where the user logged in <strong>(Default)</strong></br>'),
        'Loginredirect2' => T('Redirect to account of user </br>'),
		'Loginredirect3' => T('Redirect to Custom URL</br> ')
		);					  
		echo $this->Form->RadioList('Loginredirect', $this->Login_Redirect, array('default' => 'Loginredirect1','style'=> 'margin:10px 0 0 20px;'));
		$Loginredirecturl=$this->Form->GetFormValue('Loginredirecturl');
        echo $this->Form->TextBox('Loginredirecturl', array( 
		        'maxlength' => 255,
		        'value' => $Loginredirecturl, 
		        'style' => 'width: 280px; margin: 15px 0 0 18px;')
				);
		?>

</div>
<div><h4><label>Do you want to automatically link your existing users' accounts to their social accounts</label></h4></div>
<div><div> <?php $this->Account_linking= array(
        'Yes' => T('Yes'),
        'No' => T('No'),
		);
		echo $this->Form->RadioList('Accountlinking', $this->Account_linking, array('default' => 'Yes','style'=> 'margin:10px 0 0 20px;'));        ?> </div></div>
<div><h4><label>What text should be displayed above the mapping interface? Leave blank if you do not want any text to be displayed</label></h4></div>
<?php $Mappingtitle=$this->Form->GetFormValue('Mappingtitle');
			 echo $this->Form->TextBox('Mappingtitle', array( 
		        'maxlength' => 255,
		        'value' =>$Mappingtitle, 
		        'style' => 'width: 280px; margin: 15px 0 0 18px;' 
		     ) 
		  );
?>

</div>
</div>
<div class="container" id="tab-3">

<h4>Do you want to enable Social Sharing for your website?</h4>
 <?php $this->Enable_socialsharing= array(
        'Yes' => T('Yes'),
        'No' => T('No')
		);
echo $this->Form->RadioList('Enablesocialsharing', $this->Enable_socialsharing, array('style'=> 'margin:10px 0 0 20px;'));?> 

<h4>What text should be displayed above the Social Share interface? Leave blank if you do not want any text to be displayed</h4>
<?php $Socialsharetitle=$this->Form->GetFormValue('Socialsharetitle');
			 echo $this->Form->TextBox('Socialsharetitle', array( 
		        'maxlength' => 255,
		        'value' =>$Socialsharetitle, 
		        'style' => 'width: 280px; margin: 15px 0 0 18px;' 
		     ) 
		  );
?>
<br />
<h4>What Social Sharing widget theme do you want to use across your website?</h4>
<?php $choosesharing=trim(C('Plugins.SocialShare.Horizontalsharingtheme'));?>
	<a id="mymodal1" href="javascript:void(0);" onClick="Makehorivisible();" style="color: <?php if($choosesharing == '' || $choosesharing == 'horizonSharing32' || $choosesharing == 'horizonSharing16' || $choosesharing == 'single-image-theme-large' || $choosesharing == 'single-image-theme-small'){echo '#00CCFF';} else{ echo '#000000';}?>; font-weight: bold;margin: 10px 0 0 20px;"><?php echo 'Horizontal'; ?></a> &nbsp;|&nbsp; 
	     <a id="mymodal2" href="javascript:void(0);" onClick="Makevertivisible();" style="color: <?php if($choosesharing == '16VerticlewithBox' || $choosesharing == '32VerticlewithBox'){echo '#00CCFF';} else{ echo '#000000';}?>; font-weight: bold;"><?php echo 'Vertical'; ?></a>
	     <div style="border:#dddddd 1px solid; padding:10px; background:#FFFFFF; margin:10px 0 0 20px;">
	     <span id = "arrow" style="background-image:                                                                             url('/plugins/SocialLogin/views/images/socialshare/arrow_vic.png'); height: 11px;
margin: -21px 0 0 40px;
position: absolute;
width: 21px;

 <?php if($choosesharing == '' || $choosesharing == 'horizonSharing32' || $choosesharing == 'horizonSharing16' || $choosesharing == 'single-image-theme-large' || $choosesharing == 'single-image-theme-small'){echo '2px';} else{ echo '90px';}?>;"></span>
	     <div id="sharehorizontal" style="display:<?php if($choosesharing == '' || $choosesharing == 'horizonSharing32' || $choosesharing == 'horizonSharing16' || $choosesharing == 'single-image-theme-large' || $choosesharing == 'single-image-theme-small'){echo 'Block';} else{ echo 'none';}?>">
	      <?php $this->Horizontal_sharingtheme= array(
        'horizonSharing32' => T(Img('/plugins/SocialLogin/views/images/socialshare/horizonSharing32.png').'<br>'),
        'horizonSharing16' => T(Img('/plugins/SocialLogin/views/images/socialshare/horizonSharing16.png').'<br>'),
		'single-image-theme-large' => T(Img('/plugins/SocialLogin/views/images/socialshare/single-image-theme-large.png').'<br>'),
		'single-image-theme-small' => T(Img('/plugins/SocialLogin/views/images/socialshare/single-image-theme-small.png').'<br>')
		);
echo $this->Form->RadioList('Horizontalsharingtheme', $this->Horizontal_sharingtheme, array('default' => 'horizonSharing32','style'=> 'vertical-align: super;'));?><br />

<div style="overflow:auto; background:#EBEBEB; padding-bottom:10px;">
<h4>Select the position of Social sharing interface</h4>
<?php $this->Horizontal_sharingposition= array(
        'Top' => T('Show at the Top of content'),
        'Bottom' => T('Show at the Bottom of content')
		);
echo $this->Form->RadioList('Horizontalsharingposition', $this->Horizontal_sharingposition, array('Default' => 'Bottom','style'=> 'margin:10px 0 0 20px;'));?></div>
 
		 </div>
         
  <div id="sharevertical" style="display:<?php if($choosesharing == '16VerticlewithBox' || $choosesharing == '32VerticlewithBox'){echo 'Block';} else{ echo 'none';}?>;">
         <?php $this->Horizontal_sharingtheme= array(
        '16VerticlewithBox' => T(Img('/plugins/SocialLogin/views/images/socialshare/16VerticlewithBox.png', array('style'=> 'vertical-align:top;'))),
        '32VerticlewithBox' => T(Img('/plugins/SocialLogin/views/images/socialshare/32VerticlewithBox.png', array('style'=> 'vertical-align:top;')))
		);
echo $this->Form->RadioList('Horizontalsharingtheme', $this->Horizontal_sharingtheme, array('style'=> 'vertical-align:top;margin:10px 0 0 20px;'));?><br /><br />
<div style="overflow:auto; background:#EBEBEB; padding-bottom:10px;">
<h4><?php echo 'Select the position of Social Sharing widget'; ?></h4>
<?php $this->Vertical_sharingposition= array(
        'topleft' => T('Top Left<br>'),
        'topright' => T('Top Right<br>'),
		'bottomleft' => T('Bottom Left<br>'),
		'bottomright' => T('Bottom Right<br>')
		);
echo $this->Form->RadioList('Verticalsharingposition', $this->Vertical_sharingposition, array('default' => 'topleft', 'style'=> 'margin:10px 0 0 20px;'));?>
       
<h4><?php echo 'Specify distance of vertical sharing interface from top. (Leave empty for default behaviour)';?><a title="<?php echo 'Enter a number (For example - 200). It will set the \'top\' CSS attribute of the interface to the value specified. Increase in the number pushes interface towards bottom.';?>" href="javascript:void(0)" style="text-decoration:none"> (?)</a></h4>
<?php $Sharingoffset=$this->Form->GetFormValue('Sharingoffset');
			 echo $this->Form->TextBox('Sharingoffset', array( 
		        'maxlength' => 255,
		        'value' =>$Sharingoffset, 
		        'style' => 'width: 280px; margin: 15px 0 0 18px;' 
		     ) 
		  );
?>         
         </div>
         </div>    
</div>
<?php $rearrange=C('Plugins.SocialShare.loginRadiusLIrearrange'); ?>
<div id ="rearrangedefaulttext"><h4><?php echo T('What sharing network order do you prefer for your sharing widget?');?></h4></div>
<div id ="rearrangedefault">
<ul onMouseOver="lr_sortable();" id="sortable" class="ui-sortable" style="margin: 0px 0px 0px 40px; padding: 0px;">
						<?php 
						
						
						if (empty($rearrange)) {
						  $rearrange = array('Facebook' => 'rearrange1',
											 'Pinterest' => 'rearrange2',
											 'GooglePlus' => 'rearrange3',
											 'Twitter' => 'rearrange4',
											 'LinkedIn' => 'rearrange5'
											 );
						}					
$this->providercheckbox = array('Facebook' => 'rearrange1',
								'Pinterest' => 'rearrange2',
								'GooglePlus' => 'rearrange3',
								'Twitter' => 'rearrange4',
								'LinkedIn' => 'rearrange5',
								'Google' => 'rearrange6',
								'Yahoo' => 'rearrange7',
								'Reddit' => 'rearrange8',
								'Email' => 'rearrange9',
								'Print' => 'rearrange10',
								'Tumblr' => 'rearrange11',
								'Live' => 'rearrange12',
								'Vkontakte' => 'rearrange13',
								'Digg' => 'rearrange14',
								'MySpace' => 'rearrange15',
								'Delicious' => 'rearrange16',
								'Hyves' => 'rearrange17',
								'DotNetKicks' => 'rearrange18'
								); 
								
						$providername=array_flip($this->providercheckbox);
						
							foreach($rearrange  as $provider){
								?>
								<li title="<?php echo $providername[$provider] ?>" id="loginRadiusLI<?php echo $provider ?>" class="lrshare_iconsprite32 lrshare_<?php echo $provider ?>">
								<input type="hidden" name="rearrange_settings[]" value="<?php echo $provider ?>" />
								</li>
								<?php
							}
						
						?>
			  </ul>

</div>
<br /><br />
<div id="loginRadiusSharingLimit" style="color: red; display: none; margin: 10px 0 0 20px;"><?php echo 'You can select only 9 providers.'; ?></div>

<div id="shareprovider">
<h4>What Sharing Networks do you want to show in the sharing widget? (All other sharing networks will be shown as part of LoginRadius sharing icon)</h4>
 
 <?php
 echo $this->Form->CheckBoxList('loginRadiusLIcheckrearrange', $this->providercheckbox, 'loginRadiusLIrearrange', array('onchange' => 'loginRadiusSharingLimit(this);loginRadiusRearrangeProviderList(this);'));?>
 </div>

</div>
<div class="container" id="tab-4">

<h4>Do you want to enable Social Counter for your website?</h4>
 <?php $this->Enable_socialcounter= array(
        'Yes' => T('Yes'),
        'No' => T('No')
		);
echo $this->Form->RadioList('Enablesocialcounter', $this->Enable_socialcounter, array('default' => 'No','style'=> 'margin:10px 0 0 20px;'));?> 

<h4>What text should be displayed above the Social Counter interface? Leave blank if you do not want any text to be displayed</h4>
<?php $Socialcountertitle=$this->Form->GetFormValue('Socialcountertitle');
			 echo $this->Form->TextBox('Socialcountertitle', array( 
		        'maxlength' => 255,
		        'value' =>$Socialcountertitle, 
		        'style' => 'width: 280px; margin: 15px 0 0 18px;' 
		     ) 
		  );
?>
<br />
<?php $choosecounter=trim(C('Plugins.SocialCounter.Horizontalcountertheme'));?>
<h4>What Social Counter widget theme do you want to use across your website?</h4>
	<a id="mymodal3" href="javascript:void(0);" onClick="Makechorivisible();" style="color: <?php if($choosecounter == '' || $choosecounter == 'hybrid-horizontal-horizontal' || $choosecounter == 'hybrid-horizontal-vertical'){echo '#00CCFF';} else{ echo '#000000';}?>; font-weight: bold;margin: 10px 0 0 20px;"><?php echo 'Horizontal'; ?></a> &nbsp;|&nbsp; 
	     <a id="mymodal4" href="javascript:void(0);" onClick="Makecvertivisible();" style="color: <?php if($choosecounter == 'hybrid-verticle-horizontal' || $choosecounter == 'hybrid-verticle-vertical'){echo '#00CCFF';} else{ echo '#000000';}?>; font-weight: bold;"><?php echo 'Vertical'; ?></a>
	     <div style="border:#dddddd 1px solid; padding:10px; background:#FFFFFF; margin:10px 0 0 20px;">
		 
	      <span id = "crrow" style="background-image:                                                                             url('/plugins/SocialLogin/views/images/socialshare/arrow_vic.png'); height: 11px;
margin: -21px 0 0 40px;
position: absolute;
width: 21px;
<?php if($choosecounter == '' || $choosecounter == 'hybrid-horizontal-horizontal' || $choosecounter == 'hybrid-horizontal-vertical'){echo '2px';} else{ echo '90px';}?>;"></span>
	     <div id="counterhorizontal" style="display:<?php if($choosecounter == '' || $choosecounter == 'hybrid-horizontal-horizontal' || $choosecounter == 'hybrid-horizontal-vertical'){echo 'Block';} else{ echo 'none';}?>">
	      <?php $this->Horizontal_countertheme= array(
        'hybrid-horizontal-horizontal' => T(Img('/plugins/SocialLogin/views/images/socialcounter/hybrid-horizontal-horizontal.png').'<br>'),
        'hybrid-horizontal-vertical' => T(Img('/plugins/SocialLogin/views/images/socialcounter/hybrid-horizontal-vertical.png').'<br>')
		);
echo $this->Form->RadioList('Horizontalcountertheme', $this->Horizontal_countertheme, array('default' => 'hybrid-horizontal-vertical','style'=> 'vertical-align: super;'));?><br />

<div style="overflow:auto; background:#EBEBEB; padding-bottom:10px;">
<h4>Select the position of Social counter interface</h4>
<?php $this->Horizontal_counterposition= array(
        'Top' => T('Show at the Top of content'),
        'Bottom' => T('Show at the Bottom of content')
		);
echo $this->Form->RadioList('Horizontalcounterposition', $this->Horizontal_counterposition, array('default' => 'Bottom','style'=> 'margin:10px 0 0 20px;'));?></div>
 
		 </div>
         
  <div id="countervertical" style="display:<?php if($choosecounter == 'hybrid-verticle-horizontal' || $choosecounter == 'hybrid-verticle-vertical'){echo 'Block';} else{ echo 'none';}?>;">
         <?php $this->Verticalcountertheme= array(
        'hybrid-verticle-horizontal' => T(Img('/plugins/SocialLogin/views/images/socialcounter/hybrid-verticle-horizontal.png',array('style'=> 'vertical-align:top;'))),
        'hybrid-verticle-vertical' => T(Img('/plugins/SocialLogin/views/images/socialcounter/hybrid-verticle-vertical.png',array('style'=> 'vertical-align:top;')))
		);
echo $this->Form->RadioList('Horizontalcountertheme', $this->Verticalcountertheme, array('style'=> 'vertical-align:top;margin:10px 0 0 20px;'));?><br /><br />
<div style="overflow:auto; background:#EBEBEB; padding-bottom:10px;">
<h4><?php echo 'Select the position of Social Counter widget'; ?></h4>
<?php $this->Vertical_counterposition= array(
        'topleft' => T('Top Left'.'<br>'),
        'topright' => T('Top Right'.'<br>'),
		'bottomleft' => T('Bottom Left'.'<br>'),
		'bottomright' => T('Bottom Right'.'<br>')
		);
echo $this->Form->RadioList('Verticalcounterposition', $this->Vertical_counterposition, array('default' => 'topright', 'style'=> 'margin:10px 0 0 20px;'));?>
       
<h4><?php echo 'Specify distance of vertical counter interface from top. (Leave empty for default behaviour)';?><a title="<?php echo 'Enter a number (For example - 200). It will set the \'top\' CSS attribute of the interface to the value specified. Increase in the number pushes interface towards bottom.';?>" href="javascript:void(0)" style="text-decoration:none"> (?)</a></h4>
<?php $counteroffset=$this->Form->GetFormValue('counteroffset');
			 echo $this->Form->TextBox('counteroffset', array( 
		        'maxlength' => 255,
		        'value' =>$counteroffset, 
		        'style' => 'width: 280px; margin: 15px 0 0 18px;' 
		     ) 
		  );
?>         
         </div>
         </div>    
</div>

<div id="counterprovider"> 
<h4>What Counter Networks do you want to show in the counter widget? (All other sharing networks will be shown as part of LoginRadius sharing icon)</h4>
<?php

	
$this->counterprovidercheckbox = array('Facebook Like' => 'Facebook Like',
								'Facebook Recommend' => 'Facebook Recommend',
								'Facebook Send' => 'Facebook Send',
								'Google+ +1' => 'Google+ +1',
								'Google+ Share' => 'Google+ Share',
								'Pinterest Pin it' => 'Pinterest Pin it',
								'LinkedIn Share' => 'LinkedIn Share',
								'Twitter Tweet' => 'Twitter Tweet',
								'StumbleUpon Badge' => 'StumbleUpon Badge',
								'Reddit' => 'Reddit',
								'Hybridshare' => 'Hybridshare'
								); 
?>

 <?php
 echo $this->Form->CheckBoxList('Counterprovidercheckbox', $this->counterprovidercheckbox);?>
 </div>
 </div>
<div style="width:100%;float:right; margin:20px 0 0 0;"><div> <?php echo $this->Form->Button('Save Changes', array('class' => 'Button SliceSubmit')); ?></div></div>
</div>
