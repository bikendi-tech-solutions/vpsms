<?php
include_once($_SERVER['DOCUMENT_ROOT']."/wp-load.php");
include_once(ABSPATH.'wp-admin/includes/plugin.php');
$path = WP_PLUGIN_DIR.'/vtupress/functions.php';
if(file_exists($path) && in_array('vtupress/vtupress.php', apply_filters('active_plugins', get_option('active_plugins')))){
include_once(ABSPATH .'/wp-content/plugins/vtupress/functions.php');
}
else{
	if(!function_exists("vp_updateuser")){
function vp_updateuser(){
	
}

function vp_getuser(){
	
}

function vp_adduser(){
	
}

function vp_updateoption(){
	
}

function vp_getoption(){
	
}

function vp_option_array(){
	
}

function vp_user_array(){
	
}

function vp_deleteuser(){
	
}

function vp_addoption(){
	
}

	}

}

if(isset($_REQUEST["smsbaseurl"])){
vp_updateoption("smsbaseurl",$_REQUEST["smsbaseurl"]);
vp_updateoption("smsendpoint",$_REQUEST["smsendpoint"]);
vp_updateoption("smsrequest",$_REQUEST["smsrequest"]);
vp_updateoption("smsrequesttext",$_REQUEST["smsrequesttext"]);
vp_updateoption("smssuccesscode",$_REQUEST["smssuccesscode"]);
vp_updateoption("smssuccessvalue",$_REQUEST["smssuccessvalue"]);
vp_updateoption("smssuccessvalue2",$_REQUEST["smssuccessvalue2"]);
vp_updateoption("sms_head",$_REQUEST["smshead"]);


vp_updateoption("smsrequest_id",$_REQUEST["smsrequest_id"]);


for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("smshead".$cheaders,$_REQUEST["smshead".$cheaders]);
vp_updateoption("smsvalue".$cheaders,$_REQUEST["smsvalue".$cheaders]);
}


vp_updateoption("smsaddpost",$_REQUEST["smsaddpost"]);

for($cpost=1; $cpost<=5; $cpost++){
vp_updateoption("smspostdata".$cpost,$_REQUEST["smspostdata".$cpost]);
vp_updateoption("smspostvalue".$cpost,$_REQUEST["smspostvalue".$cpost]);
}

vp_updateoption("smsamountattribute",$_REQUEST["smsamountattribute"]);
vp_updateoption("smscharacter",$_REQUEST["smscharacter"]);
vp_updateoption("flashattr",$_REQUEST["flashattr"]);
vp_updateoption("receiverattr",$_REQUEST["receiverattr"]);
vp_updateoption("senderattr",$_REQUEST["senderattr"]);
vp_updateoption("messageattr",$_REQUEST["messageattr"]);
vp_updateoption("sms_response_format",$_REQUEST["sms_response_format"]);
vp_updateoption("sms_response_format_text",$_REQUEST["sms_response_format_text"]);
vp_updateoption("flash_value",$_REQUEST["flash_value"]);


for($smsaddheaders=1; $smsaddheaders<=4; $smsaddheaders++){
vp_updateoption("smsaddheaders".$smsaddheaders,$_REQUEST["smsaddheaders".$smsaddheaders]);
vp_updateoption("smsaddvalue".$smsaddheaders,$_REQUEST["smsaddvalue".$smsaddheaders]);
}

die("100");

}


?>