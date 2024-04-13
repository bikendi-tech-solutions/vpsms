<?php
/**
*Plugin Name: VP SMS
*Plugin URI: http://vtupress.com
*Description: Add BulkSms feature to your vtu business . An extension for vtupress plugin
*Version: 1.3.0
*Author: Akor Victor
*Author URI: https://facebook.com/akor.victor.39
*/

if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
};
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH.'wp-admin/includes/plugin.php');
$path = WP_PLUGIN_DIR.'/vtupress/functions.php';
if(file_exists($path) && in_array('vtupress/vtupress.php', apply_filters('active_plugins', get_option('active_plugins')))){
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
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



require __DIR__.'/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/bikendi-tech-solutions/vpsms',
	__FILE__,
	'vpsms'
);
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

$myUpdateChecker->setAuthentication('your-token-here');

$myUpdateChecker->getVcsApi()->enableReleaseAssets();


add_action("user_feature","sms_user_feature");
add_action("template_user_feature","sms_template_user_feature");
vp_addoption("smscontrol","unchecked");

add_action("set_control","sms_set_control");
add_action("set_control_post","sms_set_control_post");

vp_addoption("smsbaseurl","");
vp_addoption("sms_info","Import A Vendor");
vp_addoption("smsendpoint","");
vp_addoption("smsrequesttext","");
vp_addoption("smsrequest","");
vp_addoption("smsaddpost","");
vp_addoption("sms_response_format","");
vp_addoption("sms_response_format_text","");
vp_addoption("sms_head","not_concatenated");

for($smspost=1; $smspost<=5; $smspost++){
vp_addoption("smspostdata".$smspost,"");
vp_addoption("smspostvalue".$smspost,"");
}
for($smsheaders=1; $smsheaders<=1; $smsheaders++){
vp_addoption("smshead".$smsheaders,"");
vp_addoption("smsvalue".$smsheaders,"");
}

vp_addoption("smsrequest_id","");
vp_addoption("messageattr","");
vp_addoption("flashattr","");
vp_addoption("senderattr","");
vp_addoption("receiverattr","");
vp_addoption("smsamountattribute","");
vp_addoption("smssuccesscode","");
vp_addoption("smssuccessvalue","");
vp_addoption("smssuccessvalue2","");
vp_addoption("smscharacter","");

for($smsaddheaders=1; $smsaddheaders<=4; $smsaddheaders++){
vp_addoption("smsaddheaders".$smsaddheaders,"");
vp_addoption("smsaddvalue".$smsaddheaders,"");
}




function create_sms_transaction(){

global $wpdb;
$table_name = $wpdb->prefix.'ssms';
$charset_collate=$wpdb->get_charset_collate();
$sql= "CREATE TABLE IF NOT EXISTS $table_name(
id int NOT NULL AUTO_INCREMENT,
name text,
email varchar(255) DEFAULT '',
sender text ,
receiver text,
bal_bf text,
bal_nw text,
amount text,
user_id int,
the_time text,
resp_log text,
status text,
PRIMARY KEY (id))$charset_collate;";
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
dbDelta($sql);

}
//Default Datas to ssms (s-airtime db)
function addsmsdata(){
global $wpdb;
$name='Akor Victor';
$email='vtupress.com@gmail.com';
$sender='admin';
$receiver='2';
$bal_bf ='10';
$bal_nw ='0';
$amount= '0001';
$tid = '1';
$table_name = $wpdb->prefix.'ssms';
$wpdb->insert($table_name, array(
'name'=> $name,
'email'=> $email,
'sender' => $sender,
'receiver' => $receiver,
'bal_bf' => $bal_bf,
'bal_nw' => $bal_nw,
'amount' => $amount,
'user_id' => $tid,
'resp_log' => "Sample Of Successful Sms Response Log",
'status' => "failed",
'the_time' => current_time('mysql', 1)
));
}





add_action("vtupress_gateway_tab","smstab");
function smstab(){
$tab = false;
if($tab){

}elseif($_GET["subpage"] == "sms"){
    include_once(ABSPATH .'wp-content/plugins/vpsms/pages/sms.php');
}

}





vp_addoption("drop_sms2", 0);
if(vp_getoption('drop_sms2') == "0" ){

global $wpdb;
$table_name = $wpdb->prefix . 'ssms';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'fsms';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

create_sms_transaction();
addsmsdata();

vp_updateoption( 'drop_sms2', "1" );
}





function sms_template_user_feature(){
	if(isset($_GET["vend"]) && $_GET["vend"]=="sms" && vp_getoption("smscontrol") == "checked" && vp_getoption("resell") == "yes"){
    $id = get_current_user_id();
    $option_array = json_decode(get_option("vp_options"),true);
    $user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);
    $data = get_userdata($id);
    
    $bal = vp_getuser($id, 'vp_bal', true);
	?>
  
	<link rel="stylesheet" href="<?php echo plugins_url('msorg_template');?>/msorg_template/form.css" media="all">
	
	<div id="container">


		<style>
		.user-vends{
			border: 1px solid grey;
			border-radius: 5px;
			padding: 1rem !important;
		}
		</style>
		
		<div id="side-sms-w" class="pt-4 px-3">


		<div class="user-vends">
<form class="for" id="cfor" method="post" <?php echo apply_filters('formaction','target="_self"');?>>

		 <div class="visually-hidden">
                <input type="hidden" name="vpname" class="form-control sms-name" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1" value="<?php echo $data->user_login; ?>">
            </div>
            <div class="visually-hidden">
                <input type="hidden" name="vpemail" class="form-control sms-email" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" value="<?php echo $data->user_email; ?>">
            </div>
			<div class="visually-hidden">		
				<input type="hidden" id="tcode" name="tcode" value="csms">
				<input type="hidden" id="url" name="url">
				<input type="hidden" id="uniqidvalue" name="uniqidvalue" value="<?php echo uniqid('VTU-',false);?>">
				<input type="hidden" id="url1" name="url1" value="<?php echo esc_url(plugins_url('vtupress/process.php'));?>">
				<input type="hidden" id="id" name="id" value="<?php echo uniqid('VTU-',false);?>">
			</div>

<div class="input-group mb-2">
<span class="input-group-text">Sender</span>
<input type="text" name="sender" placeholder="Max.11 Characters" class="form-control sender" max="11" required>
<?php
if(preg_match('/cliqsms/',vp_getoption("smsbaseurl"))){
?>
  <span class="input-group-text"><input type="button" class=" btn-primary btn-sm lockSenderId" value="Lock Sender ID"></span>
  <script>

function raptorFailedResponse(msg){
  swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
}
function raptorSuccessfulResponse(msg){
  swal({
  title: "Successful!",
  text: msg,
  icon: "success",
  button: "Okay",
});
}

jQuery(".lockSenderId").click(function(){

var endpoint = "<?php echo get_site_url()."/wp-content/plugins/vpsms/lockSenderId.php";?>";

var senderId = jQuery(".sender").val();

if(senderId == ""){
  raptorFailedResponse("Sender Id can't be empty");
  return;
}

jQuery.LoadingOverlay("show");

var obj = {};
obj["senderId"] = senderId;

jQuery.ajax({
    url: endpoint,
    data : obj,
    dataType: 'text',
    'cache': false,
    "async": true,
    error: function (jqXHR, exception) {
      jQuery.LoadingOverlay("hide");
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection. Verify Network.";
                raptorFailedResponse(msg);
    
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
            raptorFailedResponse(msg);
            
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
            raptorFailedResponse(msg);
            
    
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
            raptorFailedResponse(msg);
            
    
        } else if (exception === "timeout") {
            msg = "Time out error.";
            raptorFailedResponse(msg);
            
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
            raptorFailedResponse(msg);
            
        } else {
            msg = "Uncaught Error." + jqXHR.responseText;
            raptorFailedResponse(msg);
            
        }
    },
  
    success: function(response) {
      jQuery.LoadingOverlay("hide");

      if(response == "100"){
      raptorSuccessfulResponse("You will receive an sms/email once your senderID is updated by an agent !!!. Also, you can check the status of the senderID by relocking it to get the message with its status");
      }else{
        raptorFailedResponse(response);
      }
    },
    type : 'POST'
    });

});
    
    </script>
<?php
}
?>

 <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="sms-sender-error-message"></span>
                      </div>
</div>

<label for="phone">Phone Numbers</label><br>
<div class="form-floating mb-2">
<input type="text" name="receiver" id="floatingInput" placeholder="Separate By Comma" class="form-control phone-numbers receiver" required>
<label for="floatingInput">Separate Numbers With Comma [,]</label>
</div>
 <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="sms-phone-error-message"></span>
                      </div>
					  
<label for="message">Message</label><br>
 <div class="input-group mb-2">
<textarea name="message" class="form-control message" max="<?php echo vp_getoption("smscharacter");?>" required></textarea>
 <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="sms-message-error-message"></span>
                      </div>
					 </div>
<small>Max. <?php echo vp_getoption("smscharacter");?> Characters</small>
<br>
 <div class="input-group mb-2">
                    <span class="input-group-text" id="basic-addon1">NGN.</span>
                    <input id="amt" name="amount" type="number" class="form-control sms-amount" max="<?php echo $bal;?>" placeholder="Amount" aria-label="Username" aria-describedby="basic-addon1" readonly required>
                    <span class="input-group-text" id="basic-addon1">.00</span>
                    <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="sms-amount-error-message"></span>
                      </div>
 </div>
   <div class="vstack gap-2">
				<?php
if(current_user_can("administrator")){
	?>
                <button type="button" class="btn btn-secondary view-url text-white btn">View Url</button>
<?php 
}
?>
                    <button type="button" class=" w-full p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow purchase-sms btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">SEND SMS</button>
  </div>	
			
</form>
</div>
	  <!--The Modal-->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Sms Purchase Confirmation</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div>
                    Sender : <span class="sms-sender-confirm"></span><br>
                    Receivers : <span class="sms-receiver-confirm"></span><br>
                    Amount : ₦<span class="sms-amount-confirm"></span><br>
                    Staus : <span class="sms-status-confirm"></span><br>
                    </div>
					<div class="input-group form">
					<span class="input-group-text">PIN</span>
					<input class="form-control pin" type="number" name="pin">
					</div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="p-2 text-xs font-bold text-white uppercase bg-gray-600 rounded shadow data-proceed-cancled btn-danger" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" name="wallet" id="wallet" class="p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  sms-proceed btn-success" form="cfor">Proceed</button>
                    </div>
                  </div>
                </div>
            </div>
    	
		
		</div>
		
		<script>
		jQuery(".receiver, .message").on("change",function(){
			var str = jQuery(".phone-numbers").val();
			var times = str.split(',').length-1;

			var numbers = times+1;
var len = Math.ceil(parseInt(jQuery(".message").val().length)/<?php echo intval(vp_getoption("smscharacter"));?>);
var len2 = len*<?php echo floatval(vp_getoption("smsamountattribute"));?>;
  
			var total_amount = len2*numbers;
			jQuery(".sms-amount").val(total_amount);
			
		});
		
		jQuery(".purchase-sms").click(function(){
			
			var url = jQuery("#url");
			var request_id =  jQuery("#uniqidvalue").val();
			var sender =  jQuery(".sender").val();
			var receiver =  jQuery(".receiver").val();
			var message =  jQuery(".message").val();
			<?php
			echo'
url.val("smsbaseurl'.vp_getoption("smsendpoint").'smspostdata1=smspostvalue1&'.vp_getoption("smsrequest_id").'="+request_id+"&smspostdata2=smspostvalue2&'.vp_getoption("smspostdata3").'='.vp_getoption("smspostvalue3").'&'.vp_getoption("smspostdata4").'='.vp_getoption("smspostvalue4").'&'.vp_getoption("smspostdata5").'='.vp_getoption("smspostvalue5").'&'.vp_getoption("senderattr").'="+sender+"&'.vp_getoption("receiverattr").'="+receiver+"&'.vp_getoption("flashattr").'='.vp_getoption("flash_value").'&'.vp_getoption("messageattr").'="+message);
';?>
			
			
			
			var str = jQuery(".phone-numbers").val();
			var times = str.split(',').length-1;
			var numbers = times+1;
			
			var set_amount = <?php echo floatval(vp_getoption("smsamountattribute"));?>;
			var total_amount = set_amount*numbers;
			jQuery(".sms-amount").val(set_amount*numbers);
			jQuery(".sms-sender-confirm").text(jQuery(".sender").val());
			jQuery(".sms-receiver-confirm").text(jQuery(".phone-numbers").val());
			jQuery(".sms-amount-confirm").text(total_amount);
			
			if(total_amount <= <?php echo $bal;?>){
			jQuery(".sms-proceed").show();
				jQuery(".sms-amount").removeClass("is-invalid");
				jQuery(".sms-amount").addClass("is-valid");
			}
			else if(total_amount > <?php echo $bal;?>){
			jQuery(".sms-status-confirm").text("Amount Too Low To Proceed");
			jQuery(".sms-proceed").hide();
			jQuery(".sms-amount").addClass("is-invalid");
			jQuery(".sms-amount-error-message").text("Amount Too Low To Proceed");
			}
			
			if(jQuery(".phone-numbers").val() == ""){
				jQuery(".phone-numbers").addClass("is-invalid");
				jQuery(".sms-phone-error-message").text("Phone Number Can't Be Empty");
				jQuery(".sms-proceed").hide();
			}
else{
jQuery(".sms-proceed").show();
jQuery(".phone-numbers").addClass("is-valid");
jQuery(".phone-numbers").removeClass("is-invalid");
}	
		
	if(jQuery(".sender").val() == ""){
		jQuery(".sms-proceed").hide();
				jQuery(".sender").addClass("is-invalid");
				jQuery(".sms-sender-error-message").text("Sender Can't Be Empty");
				
			}
else{
jQuery(".sms-proceed").show();
jQuery(".sender").addClass("is-valid");
jQuery(".sender").removeClass("is-invalid");
}	
		
	if(jQuery(".message").val() == "" || jQuery(".message").val() == " " || jQuery(".message").val().length < 1){
				jQuery(".message").addClass("is-invalid");
				jQuery(".sms-message-error-message").text("Message Can't Be Empty");
				jQuery(".sms-proceed").hide();
			}
else{
jQuery(".message").addClass("is-valid");
jQuery(".message").removeClass("is-invalid");
jQuery(".sms-proceed").show();
}

	
			
				if(jQuery(".message").val() == "" || jQuery(".sender").val() == "" || jQuery(".phone-numbers").val() == "" || total_amount > <?php echo $bal;?>){
			jQuery(".sms-status-confirm").text("Correct The Errors");
		
			jQuery(".sms-proceed").hide();
		}
		else{
			jQuery(".sms-status-confirm").text("Correct");
			
			jQuery(".sms-proceed").show();
		}
		
			
			
			
		});
		
		
		
		jQuery(".view-url").click(function(){
			var url = jQuery("#url");
			var request_id =  jQuery("#uniqidvalue").val();
			var sender =  jQuery(".sender").val();
			var receiver =  jQuery(".receiver").val();
			var message =  jQuery(".message").val();
			<?php
			echo'
url.val("smsbaseurl'.vp_getoption("smsendpoint").'smspostdata1=smspostvalue1&'.vp_getoption("smsrequest_id").'="+request_id+"&smspostdata2=smspostvalue2&'.vp_getoption("smspostdata3").'='.vp_getoption("smspostvalue3").'&'.vp_getoption("smspostdata4").'='.vp_getoption("smspostvalue4").'&'.vp_getoption("smspostdata5").'='.vp_getoption("smspostvalue5").'&'.vp_getoption("senderattr").'="+sender+"&'.vp_getoption("receiverattr").'="+receiver+"&'.vp_getoption("flashattr").'='.vp_getoption("flash_value").'&'.vp_getoption("messageattr").'="+message);
';?>

var durl = jQuery("#url").val();
alert(durl);
console.log(durl);
		});
		
		
				
jQuery(".sms-proceed").click(function(){
	
	
	jQuery('.btn-close').trigger('click');
	jQuery.LoadingOverlay("show");
	
var obj = {};
obj["vend"] = "vend";
obj["vpname"] = jQuery(".sms-name").val();
obj["vpemail"] = jQuery(".sms-email").val();
obj["tcode"] = jQuery("#tcode").val();
obj["url"] = jQuery("#url").val();
obj["uniqidvalue"] = jQuery("#uniqidvalue").val();
obj["url1"] = jQuery("#url1").val();
obj["id"] = jQuery("#id").val();
obj["amount"] = jQuery("#amt").val();
obj["sender"] = jQuery(".sender").val();
obj["receiver"] = jQuery(".receiver").val();
obj["message"] = jQuery(".message").val();
obj["pin"] = jQuery(".pin").val();


jQuery.ajax({
  url: '<?php echo esc_url(plugins_url("vtupress/vend.php"));?>',
  data: obj,
  dataType: 'text',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery.LoadingOverlay("hide");
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: "Error",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data) {
	jQuery.LoadingOverlay("hide");
        if(data == "100"){
		  swal({
  title: "Message Sent!",
  text: "Thanks For Your Patronage",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  msg = data;
	  swal({
  title: "Message Not Sent!",
  text: "There was an issue",
  icon: "error",
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  }
}).then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
	swal(msg,{
  icon: "info",
  button: "Okay"
}).then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
   	location.reload();
  }
});

  }
});
	  }
  },
  type: 'POST'
});

});
		
		
		
		</script>
		
		

</div>
		<?php
		

		}
		
		
}



function sms_user_feature(){
	if(isset($_GET["vend"]) && $_GET["vend"]=="sms" && vp_getoption("smscontrol") == "checked" && vp_getoption("resell") == "yes"){
$id = get_current_user_id();
$option_array = json_decode(get_option("vp_options"),true);
$user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);
$data = get_userdata($id);

$bal = vp_getuser($id, 'vp_bal', true);
		
		?>
		
	<div id="dashboard-main-content">
<section class="container mx-auto">
<div class="p-md-5 p-1">
<div class="bg-white shadow">
<div class="dark-white flex items-center justify-between p-5 bg-gray-100">
<h1 class="text-xl font-bold">
<span class="lg:inline">Sms</span>
</h1>
<div class="font-bold tracking-wider">
<span class="dark inline-block px-3 py-1 bg-gray-200 border rounded-lg cursor-pointer" x-text="`NGN ${$format(total_sum)}`">NGN<?php echo $bal;?></span>
</div>
</div>
<div class="p-2 bg-white lg:p-5">
<template x-for="transaction in transactions"></template>


		<style>
		.user-vends{
			border: 1px solid grey;
			border-radius: 5px;
			padding: 1rem !important;
		}
		</style>
		
		<div id="side-sms-w">


		<div class="user-vends">
<form class="for" id="cfor" method="post" <?php echo apply_filters('formaction','target="_self"');?>>

		 <div class="visually-hidden">
                <input type="hidden" name="vpname" class="form-control sms-name" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1" value="<?php echo $data->user_login; ?>">
            </div>
            <div class="visually-hidden">
                <input type="hidden" name="vpemail" class="form-control sms-email" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" value="<?php echo $data->user_email; ?>">
            </div>
			<div class="visually-hidden">		
				<input type="hidden" id="tcode" name="tcode" value="csms">
				<input type="hidden" id="url" name="url">
				<input type="hidden" id="uniqidvalue" name="uniqidvalue" value="<?php echo uniqid('VTU-',false);?>">
				<input type="hidden" id="url1" name="url1" value="<?php echo esc_url(plugins_url('vtupress/process.php'));?>">
				<input type="hidden" id="id" name="id" value="<?php echo uniqid('VTU-',false);?>">
			</div>

<div class="input-group mb-2">
<span class="input-group-text">Sender</span>
<input type="text" name="sender" placeholder="Max.11 Characters" class="form-control sender" max="11" required>
<?php
if(preg_match('/cliqsms/',vp_getoption("smsbaseurl"))){
?>
  <span class="input-group-text"><input type="button" class=" btn-primary btn-sm lockSenderId" value="Lock Sender ID"></span>
  <script>

function raptorFailedResponse(msg){
  swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
}
function raptorSuccessfulResponse(msg){
  swal({
  title: "Successful!",
  text: msg,
  icon: "success",
  button: "Okay",
});
}

jQuery(".lockSenderId").click(function(){

var endpoint = "<?php echo get_site_url()."/wp-content/plugins/vpsms/lockSenderId.php";?>";

var senderId = jQuery(".sender").val();

if(senderId == ""){
  raptorFailedResponse("Sender Id can't be empty");
  return;
}

jQuery("#cover-spin").show();

var obj = {};
obj["senderId"] = senderId;

jQuery.ajax({
    url: endpoint,
    data : obj,
    dataType: 'text',
    'cache': false,
    "async": true,
    error: function (jqXHR, exception) {
      jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection. Verify Network.";
                raptorFailedResponse(msg);
    
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
            raptorFailedResponse(msg);
            
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
            raptorFailedResponse(msg);
            
    
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
            raptorFailedResponse(msg);
            
    
        } else if (exception === "timeout") {
            msg = "Time out error.";
            raptorFailedResponse(msg);
            
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
            raptorFailedResponse(msg);
            
        } else {
            msg = "Uncaught Error." + jqXHR.responseText;
            raptorFailedResponse(msg);
            
        }
    },
    
    success: function(response) {
      jQuery("#cover-spin").hide();

      if(response == "100"){
        raptorSuccessfulResponse("You will receive an sms/email once your senderID is updated by an agent !!!. Also, you can check the status of the senderID by relocking it to get the message with its status");
       }
      else{
        raptorFailedResponse(response);
      }
    },
    type : 'POST'
    });

});
    
    </script>
<?php
}
?>

 <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="sms-sender-error-message"></span>
                      </div>
</div>

<label for="phone">Phone Numbers</label><br>
<div class="form-floating mb-2">
<input type="text" name="receiver" id="floatingInput" placeholder="Separate By Comma" class="form-control phone-numbers receiver" required>
<label for="floatingInput">Separate Numbers With Comma [,]</label>
</div>
 <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="sms-phone-error-message"></span>
                      </div>
					  
<label for="message">Message</label><br>
 <div class="input-group mb-2">
<textarea name="message" class="form-control message" max="<?php echo vp_getoption("smscharacter");?>" required></textarea>
 <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="sms-message-error-message"></span>
                      </div>
					 </div>
<small>Max. <?php echo vp_getoption("smscharacter");?> Characters</small>
<br>
 <div class="input-group mb-2">
                    <span class="input-group-text" id="basic-addon1">NGN.</span>
                    <input id="amt" name="amount" type="number" class="form-control sms-amount" max="<?php echo $bal;?>" placeholder="Amount" aria-label="Username" aria-describedby="basic-addon1" readonly required>
                    <span class="input-group-text" id="basic-addon1">.00</span>
                    <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="sms-amount-error-message"></span>
                      </div>
 </div>
   <div class="vstack gap-2">
				<?php
if(current_user_can("administrator")){
	?>
                <button type="button" class="btn btn-outline-secondary view-url">View Url</button>
<?php 
}
?>
                    <button type="button" class="btn btn-secondary w-full p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow purchase-sms" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Send</button>
  </div>	
			
</form>
</div>
	  <!--The Modal-->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Sms Purchase Confirmation</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div>
                    Sender : <span class="sms-sender-confirm"></span><br>
                    Receivers : <span class="sms-receiver-confirm"></span><br>
                    Amount : ₦<span class="sms-amount-confirm"></span><br>
                    Staus : <span class="sms-status-confirm"></span><br>
                    </div>
					<div class="input-group form">
					<span class="input-group-text">PIN</span>
					<input class="form-control pin" type="number" name="pin">
					</div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary p-2 text-xs font-bold text-dark uppercase bg-gray-600 rounded shadow data-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" name="wallet" id="wallet" class="btn btn-primary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  sms-proceed" form="cfor">Proceed</button>
                    </div>
                  </div>
                </div>
            </div>
    	
		
		</div>
		
		<script>
		jQuery(".receiver, .message").on("change",function(){
			var str = jQuery(".phone-numbers").val();
			var times = str.split(',').length-1;

			var numbers = times+1;
var len = Math.ceil(parseInt(jQuery(".message").val().length)/<?php echo intval(vp_getoption("smscharacter"));?>);
var len2 = len*<?php echo floatval(vp_getoption("smsamountattribute"));?>;
  
			var total_amount = len2*numbers;
			jQuery(".sms-amount").val(total_amount);
			
		});
		
		jQuery(".purchase-sms").click(function(){
			
			var url = jQuery("#url");
			var request_id =  jQuery("#uniqidvalue").val();
			var sender =  jQuery(".sender").val();
			var receiver =  jQuery(".receiver").val();
			var message =  jQuery(".message").val();
			<?php
			echo'
url.val("smsbaseurl'.vp_getoption("smsendpoint").'smspostdata1=smspostvalue1&'.vp_getoption("smsrequest_id").'="+request_id+"&smspostdata2=smspostvalue2&'.vp_getoption("smspostdata3").'='.vp_getoption("smspostvalue3").'&'.vp_getoption("smspostdata4").'='.vp_getoption("smspostvalue4").'&'.vp_getoption("smspostdata5").'='.vp_getoption("smspostvalue5").'&'.vp_getoption("senderattr").'="+sender+"&'.vp_getoption("receiverattr").'="+receiver+"&'.vp_getoption("flashattr").'='.vp_getoption("flash_value").'&'.vp_getoption("messageattr").'="+message);
';?>
			
			
			
			var str = jQuery(".phone-numbers").val();
			var times = str.split(',').length-1;
			var numbers = times+1;
			
			var set_amount = <?php echo floatval(vp_getoption("smsamountattribute"));?>;
			var total_amount = set_amount*numbers;
			jQuery(".sms-amount").val(set_amount*numbers);
			jQuery(".sms-sender-confirm").text(jQuery(".sender").val());
			jQuery(".sms-receiver-confirm").text(jQuery(".phone-numbers").val());
			jQuery(".sms-amount-confirm").text(total_amount);
			
			if(total_amount <= <?php echo $bal;?>){
			jQuery(".sms-proceed").show();
				jQuery(".sms-amount").removeClass("is-invalid");
				jQuery(".sms-amount").addClass("is-valid");
			}
			else if(total_amount > <?php echo $bal;?>){
			jQuery(".sms-status-confirm").text("Amount Too Low To Proceed");
			jQuery(".sms-proceed").hide();
			jQuery(".sms-amount").addClass("is-invalid");
			jQuery(".sms-amount-error-message").text("Amount Too Low To Proceed");
			}
			
			if(jQuery(".phone-numbers").val() == ""){
				jQuery(".phone-numbers").addClass("is-invalid");
				jQuery(".sms-phone-error-message").text("Phone Number Can't Be Empty");
				jQuery(".sms-proceed").hide();
			}
else{
jQuery(".sms-proceed").show();
jQuery(".phone-numbers").addClass("is-valid");
jQuery(".phone-numbers").removeClass("is-invalid");
}	
		
	if(jQuery(".sender").val() == ""){
		jQuery(".sms-proceed").hide();
				jQuery(".sender").addClass("is-invalid");
				jQuery(".sms-sender-error-message").text("Sender Can't Be Empty");
				
			}
else{
jQuery(".sms-proceed").show();
jQuery(".sender").addClass("is-valid");
jQuery(".sender").removeClass("is-invalid");
}	
		
	if(jQuery(".message").val() == "" || jQuery(".message").val() == " " || jQuery(".message").val().length < 1){
				jQuery(".message").addClass("is-invalid");
				jQuery(".sms-message-error-message").text("Message Can't Be Empty");
				jQuery(".sms-proceed").hide();
			}
else{
jQuery(".message").addClass("is-valid");
jQuery(".message").removeClass("is-invalid");
jQuery(".sms-proceed").show();
}

	
			
				if(jQuery(".message").val() == "" || jQuery(".sender").val() == "" || jQuery(".phone-numbers").val() == "" || total_amount > <?php echo $bal;?>){
			jQuery(".sms-status-confirm").text("Correct The Errors");
		
			jQuery(".sms-proceed").hide();
		}
		else{
			jQuery(".sms-status-confirm").text("Correct");
			
			jQuery(".sms-proceed").show();
		}
		
			
			
			
		});
		
		
		
		jQuery(".view-url").click(function(){
			var url = jQuery("#url");
			var request_id =  jQuery("#uniqidvalue").val();
			var sender =  jQuery(".sender").val();
			var receiver =  jQuery(".receiver").val();
			var message =  jQuery(".message").val();
			<?php
			echo'
url.val("smsbaseurl'.vp_getoption("smsendpoint").'smspostdata1=smspostvalue1&'.vp_getoption("smsrequest_id").'="+request_id+"&smspostdata2=smspostvalue2&'.vp_getoption("smspostdata3").'='.vp_getoption("smspostvalue3").'&'.vp_getoption("smspostdata4").'='.vp_getoption("smspostvalue4").'&'.vp_getoption("smspostdata5").'='.vp_getoption("smspostvalue5").'&'.vp_getoption("senderattr").'="+sender+"&'.vp_getoption("receiverattr").'="+receiver+"&'.vp_getoption("flashattr").'='.vp_getoption("flash_value").'&'.vp_getoption("messageattr").'="+message);
';?>

var durl = jQuery("#url").val();
alert(durl);
console.log(durl);
		});
		
		
				
jQuery(".sms-proceed").click(function(){
	
	
	jQuery('.btn-close').trigger('click');
	jQuery("#cover-spin").show();
	
var obj = {};
obj["vend"] = "vend";
obj["vpname"] = jQuery(".sms-name").val();
obj["vpemail"] = jQuery(".sms-email").val();
obj["tcode"] = jQuery("#tcode").val();
obj["url"] = jQuery("#url").val();
obj["uniqidvalue"] = jQuery("#uniqidvalue").val();
obj["url1"] = jQuery("#url1").val();
obj["id"] = jQuery("#id").val();
obj["amount"] = jQuery("#amt").val();
obj["sender"] = jQuery(".sender").val();
obj["receiver"] = jQuery(".receiver").val();
obj["message"] = jQuery(".message").val();
obj["pin"] = jQuery(".pin").val();


jQuery.ajax({
  url: '<?php echo esc_url(plugins_url("vtupress/vend.php"));?>',
  data: obj,
  dataType: 'text',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: "Error",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data) {
	jQuery("#cover-spin").hide();
        if(data == "100"){
		  swal({
  title: "Message Sent!",
  text: "Thanks For Your Patronage",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  msg = data;
	  swal({
  title: "Message Not Sent!",
  text: "There was an issue",
  icon: "error",
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  }
}).then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
	swal(msg,{
  icon: "info",
  button: "Okay"
}).then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
   	location.reload();
  }
});

  }
});
	  }
  },
  type: 'POST'
});

});
		
		
		
		</script>
		
		
		

</div>
</div>

</div>
</section>
</div>
		<?php
		

		}
		
		
}






function sms_vptstyle($style){
return ".ssuccess{display:none;}
.sfailed{display:none;}
";
}

function sms_vptcsairtime($sairtime){
return "document.getElementById('ssuccess').style.display = 'none';
document.getElementById('sfailed').style.display = 'none';

";
}

function sms_vptcsdata($sdata){
return "document.getElementById('ssuccess').style.display = 'none';
document.getElementById('sfailed').style.display = 'none';
";
}

function sms_vptcfairtime($fairtime){
return "document.getElementById('ssuccess').style.display = 'none';
document.getElementById('sfailed').style.display = 'none';

";
}

function sms_vptcfdata($fdata){
return "document.getElementById('ssuccess').style.display = 'none';
document.getElementById('sfailed').style.display = 'none';

";
}

function sms_vptcsel($option){
return "<option value='sms'>SMS</option>
";
}



add_action("vtupress_gateway_submenu","sms_submenu");
function sms_submenu(){
?>
  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=sms" class="sidebar-link"
                      ><i class="mdi mdi-message-text"></i
                      ><span class="hide-menu">Sms</span></a
                    >
  </li>
<?php
}



add_action("vtupress_history_condition","smsservices");
function smsservices(){
  $bill = false;
  if($bill){

  }
  elseif($_GET["subpage"] == "sms" && $_GET["type"] == "approved"){
    include_once(ABSPATH .'wp-content/plugins/vpsms/pages/ssms.php');
  }
  elseif($_GET["subpage"] == "sms" && $_GET["type"] == "pending"){
    include_once(ABSPATH .'wp-content/plugins/vpsms/pages/fsms.php');
  }
  elseif($_GET["subpage"] == "sms" && $_GET["type"] == "failed"){
    include_once(ABSPATH .'wp-content/plugins/vpsms/pages/opsms.php');
  }
}

add_action("vtupress_history_submenu","smssubmenu");
function smssubmenu(){
?>
      <li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-receipt"></i
                  ><span class="hide-menu">Bulk Sms</span></a
                >
            <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=sms&type=pending" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu">Pending</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=sms&type=approved" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu">Approved</span></a
                    >
                  </li>
				  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=sms&type=failed" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu">Failed</span></a
                    >
                  </li>
            </ul>
      </li>
<?php
}




//transactions style
add_filter('vptstyle1','sms_vptstyle');

//to disappear when case=Airtime and success clicked
add_filter('vptcsairtime1', 'sms_vptcsairtime');
add_filter('vptcsdata1', 'sms_vptcsdata');
add_filter('transaction_successful_case','sms_success');
add_filter('transaction_failed_case','sms_failed');

add_filter('vptcfairtime1', 'sms_vptcfairtime');
add_filter('vptcfdata1','sms_vptcfdata');
add_filter('vptcfcase1', 'sms_vptcfcase');
add_filter('vptcsel1', 'sms_vptcsel');
add_action('vpttab1', 'sms_vpttab');


register_activation_hook(__FILE__,"create_sms_transaction");
register_activation_hook(__FILE__,"addsmsdata");