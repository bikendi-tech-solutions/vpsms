<?php
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



if(!isset($_POST["senderId"])){
die("All Parameters Are Required!");
}
elseif(empty($_POST["senderId"])){
die("Sender ID can't be empty!");
}


if(!is_user_logged_in()){
    die("Please Login!");
}

$senderId = trim(htmlspecialchars($_POST["senderId"]));
$userid = get_current_user_id();
$userData = get_userdata($userid);
$domain = get_site_url();
$userEmail = $userData->user_email;
$userPhone = vp_getuser($userid,"vp_phone",true);
$adminEmail = get_bloginfo("admin_email");
$adminPhone = vp_getoption("vp_phone_line");


$url = "https://utility.com.ng/wp-content/plugins/cliqsms/lockSenderId.php?senderID=$senderId&senderDomain=$domain&associatedUserEmail=$userEmail&associatedUserPhone=$userPhone&associatedAdminEmail=$adminEmail&adminPhone=$adminPhone";


$http_args = array(
    'headers' => array(
    'cache-control' => 'no-cache',
    'Content-Type' => 'application/json'
    ),
    'timeout' => '300',
    'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
    'blocking'=> true
    );       


$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);

if($response == "100"){
    die("100");
}
else{
    die($response);
}