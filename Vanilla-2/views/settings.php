<?php 
?>
<style type="text/css">
.Configuration {
   margin: 0 20px 20px;
   background: #f5f5f5;
   float: left;
}
.ConfigurationForm {
   padding: 20px;
   float: left;
}
#Content form .ConfigurationForm ul {
   padding: 0;
}
#Content form .ConfigurationForm input.Button {
   margin: 0;
}
.ConfigurationHelp {
   border-left: 1px solid #aaa;
   margin-left: 340px;
   padding: 20px;
}
.ConfigurationHelp strong {
    display: block;
}
.ConfigurationHelp img {
   width: 99%;
}
.ConfigurationHelp a img {
    border: 1px solid #aaa;
}
.ConfigurationHelp a:hover img {
    border: 1px solid #777;
}
input.CopyInput {
   font-family: monospace;
   color: #000;
   width: 240px;
   font-size: 12px;
   padding: 4px 3px;
}
#Form_Secret {
   width: 280px;
}
#Form_ApplicationID {
   width: 120px;  
}
</style>
<h1><?php echo $this->Data('Title'); ?></h1>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<div class="Info">
   <?php echo T('SocialLogin Connect allows users to sign in using their SocialLogin account.', 'SocialLogin Connect allows users to sign in using their SocialLogin account. <b>You must register your application with SocialLogin for this plugin to work.</b>'); ?>
</div>
<div class="Configuration">
   <div class="ConfigurationForm">
      <ul>
	   
	  
	  
         <li>
            <?php
               echo $this->Form->Label('API Key', 'ApplicationID');
               echo $this->Form->TextBox('ApplicationID',array( 
		        'maxlength' => 255,
		        'style' => 'width: 280px' 
		     ));
            ?>
         </li>
         <li>
            <?php
               echo $this->Form->Label('API Secret', 'Secret');
               echo $this->Form->TextBox('Secret',array( 
		        'maxlength' => 255,
		        'style' => 'width: 280px' 
		     ));
            ?>
         </li>
		 
		    <li>
            <?php
			
			  $this->Options = array(
        'Yes' => T('Yes'),
        'No' => T('No')
        
      
      );
             echo $this->Form->Label('Email Required', 'Title');
 
      
      echo   $this->Form->DropDown('Title',$this->Options, array('default' => 'Yes'));
	  
            ?>
         </li>
		  <li>
            <?php
			$sub=$this->Form->GetFormValue('SUBTITLE');
			if($sub=='')
			$sub='Please Login with';
               echo $this->Form->Label('Title', 'SUBTITLE');
               echo $this->Form->TextBox('SUBTITLE', array( 
		        'maxlength' => 255,
		        'value' =>$sub, 
		        'style' => 'width: 280px' 
		     ) 
		  );
            ?>
         </li>
		 
		    <li>
            
			<?php
			  $this->APIOptions = array(
        'CURL' => T('CURL'),
        'FSOCKOPEN ' => T('FSOCKOPEN ')
        
      
      );
         echo $this->Form->Label('Select API Credential', 'USE_API');
         echo   $this->Form->DropDown('USE_API',$this->APIOptions, array('default' => 'CURL'));
      ?>
	   
	  
            
         </li>
		       <li>
            <?php
				

	    $this->Login_setting= array(
        'LOGIN_SETTING1' => T('Redirect to Same page</br>'),
        'LOGIN_SETTING2' => T('Redirect to account of user </br>'),
		'LOGIN_SETTING3' => T('Redirect to following Url</br> ')
		
        
      
      );
			$LOGIN_SETTING=$this->Form->GetFormValue('LOGIN_SETTING1');
			$LOGIN_SETTING =(($LOGIN_SETTING !="LOGIN_SETTING3")?"":$this->Form->GetFormValue('LOGIN_SETTING'));
	  		echo $this->Form->Label('Login Redirection Setting','LOGIN_SETTING1');
           echo $this->Form->RadioList('LOGIN_SETTING1', $this->Login_setting, array('Default' => 'LOGIN_SETTING1'));
           echo $this->Form->TextBox('LOGIN_SETTING',array('value'=>$LOGIN_SETTING));
		
			
            ?>
         </li>
		    
      </ul>
	 
      <?php echo $this->Form->Button('Save', array('class' => 'Button SliceSubmit')); ?>
   </div>
   
<?php 
   echo $this->Form->Close();
