// prepare rearrange provider list
function loginRadiusRearrangeProviderList(elem){
	var ul = document.getElementById('sortable');
//	alert(document.getElementById('loginRadiusLI'+elem.id));
	if(elem.checked){
		var listItem = document.createElement('li');
		listItem.setAttribute('id', 'loginRadiusLI'+elem.value);
		listItem.setAttribute('title', elem.value);
		listItem.setAttribute('class', 'lrshare_iconsprite32 lrshare_'+elem.value);
		// append hidden field
		var provider = document.createElement('input');
		provider.setAttribute('type', 'hidden');
		provider.setAttribute('name', 'rearrange_settings[]');
		provider.setAttribute('value', elem.value);
		listItem.appendChild(provider);
		ul.appendChild(listItem);
	}else{
		ul.removeChild(document.getElementById('loginRadiusLI'+elem.value));
	}
}
$(document).ready(function() {	
    $('#tabs li a:not(:first)').addClass('inactive');
    $('.container:not(:first)').hide();
	$('#tabs li a').click(function(){
    var t = $(this).attr('href');
    $('#tabs li a').addClass('inactive');		
    $(this).removeClass('inactive');
    $('.container').hide();
    $(t).fadeIn('slow');
    return false;
})
});

function Makevertivisible() {
  document.getElementById('sharevertical').style.display="block";
  document.getElementById('sharehorizontal').style.display="none";
  document.getElementById('arrow').style.cssText = "height: 11px;margin: -21px 0 0 90px;position: absolute;width: 21px;
background-image: url(\"/plugins/SocialLogin/views/images/socialshare/arrow_vic.png\")";
  document.getElementById('mymodal2').style.color = "#00CCFF";
  document.getElementById('mymodal1').style.color = "#000000";
}

function Makehorivisible() {
  document.getElementById('sharehorizontal').style.display="block";
  document.getElementById('sharevertical').style.display="none";
  document.getElementById('arrow').style.cssText = "position:absolute; border-bottom:8px solid #ffffff; border-right:8px solid transparent; border-left:8px solid transparent; margin:-18px 0 0 2px;";
   document.getElementById('mymodal1').style.color = "#00CCFF";
   document.getElementById('mymodal2').style.color = "#000000";
}
function Makecvertivisible() {
  document.getElementById('countervertical').style.display="block";
  document.getElementById('counterhorizontal').style.display="none";
  document.getElementById('carrow').style.cssText = "position:absolute; border-bottom:8px solid #ffffff; border-right:8px solid transparent; border-left:8px solid transparent; margin:-18px 0 0 90px;";
  document.getElementById('mymodal4').style.color = "#00CCFF";
  document.getElementById('mymodal3').style.color = "#000000";
}
function Makechorivisible() {
  document.getElementById('counterhorizontal').style.display="block";
  document.getElementById('countervertical').style.display="none";
  document.getElementById('carrow').style.cssText = "position:absolute; border-bottom:8px solid #ffffff; border-right:8px solid transparent; border-left:8px solid transparent; margin:-18px 0 0 2px;";
  document.getElementById('mymodal3').style.color = "#00CCFF";
  document.getElementById('mymodal4').style.color = "#000000";
 }
// check provider more then 9 select
function loginRadiusSharingLimit(elem){
	var provider = jQuery("#shareprovider").find(":checkbox");
	var checkCount = 0;
		for(var i = 0; i < provider.length; i++){
			if(provider[i].checked){
			// count checked providers
				checkCount++;
			if(checkCount >= 10){
				elem.checked = false;
			//document.getElementById('loginRadiusSharingLimit').style.display = 'block';
			jQuery("#loginRadiusSharingLimit").show('slow');
				setTimeout(function() {
					jQuery("#loginRadiusSharingLimit").hide('slow');
				}, 5000);
			return;
			}
		}
	}
}

//socialshare rearange 
function lr_sortable(){
	jQuery("#sortable").sortable({
    revert: true
  });
}

// check provider more then 9 select
function loginRadiusSharingLimit(elem){
	var provider = jQuery("#shareprovider").find(":checkbox");
	var checkCount = 0;
		for(var i = 0; i < provider.length; i++){
			if(provider[i].checked){
			// count checked providers
				checkCount++;
			if(checkCount >= 10){
				elem.checked = false;
			//document.getElementById('loginRadiusSharingLimit').style.display = 'block';
			jQuery("#loginRadiusSharingLimit").show('slow');
				setTimeout(function() {
					jQuery("#loginRadiusSharingLimit").hide('slow');
				}, 5000);
			return;
			}
		}
	}
}

function MakeRequest()
{

$('#login_radius_response').html('<div id ="loading">Contacting API - please wait ...</div>');	
var connection_url = $('#connection_url').val();
var apikey = $('#Form_Apikey').val();
var apisecret = $('#Form_Secretkey').val();
if (apikey == '') {
$('#login_radius_response').html('<div id="Error" style="color:RED;">please enter api key</div>');
return false;
}
if (apisecret == '') {
$('#login_radius_response').html('<div id="Error" style="color:Red;">please enter api secret</div>');
return false;
}
if ($('#Form_Use_Api').is(':checked')) {
var api_request = 'curl';
}
else if ($('#Form_Use_Api1').is(':checked')) {
var api_request = 'fsockopen'; 
}

$.ajax({
type: "GET",
url: 'plugins/SocialLogin/views/checkapi.php',
data: "apikey=" + apikey +"&apisecret="+apisecret+"&api_request="+api_request,
success: function(msg){
$("#login_radius_response").html(msg);
}
});
}
  
// JavaScript Document
function getXMLHttp()
{
var xmlHttp
try
{
//Firefox, Opera 8.0+, Safari+
xmlHttp = new XMLHttpRequest();
}
catch(e)
{
//Internet Explorer
try
{
xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
}
catch(e)
{
try
{
xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
}
catch(e)
{
alert("Your browser does not support AJAX!")
return false;
}
}
}
return xmlHttp;
}

jQuery(function(){
		function m(n, d){
			P = Math.pow;
			R = Math.round
			d = P(10, d);
			i = 7;
			while(i) {
				(s = P(10, i-- * 3)) <= n && (n = R(n * d / s) / d + "KMGTPE"[i])
			}
			return n;
			}
		jQuery.ajax({
			url: 'http://api.twitter.com/1/users/show.json',
			data: {
			screen_name: 'LoginRadius'
			},
			dataType: 'jsonp',
			success: function(data) {
			count = data.followers_count;
			jQuery('#followers').html(m(count, 1));
			}
		});
	});