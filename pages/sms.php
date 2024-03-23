<?php
if(current_user_can("vtupress_admin")){

?>

<div class="container-fluid license-container">
            <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
            <style>
                div.vtusettings-container *{
                    font-family:roboto;
                }
                .swal-button.swal-button--confirm {
                    width: fit-content;
                    padding: 10px !important;
                }
            </style>

<p style="visibility:hidden;">
Please take note to always have security system running and checked. DO not disclose your login details to anyone except for confidential reasons. 
Not even the developers of this plugin should be trusted enough to grant access anyhow.

                  </p>


<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
$option_array = json_decode(get_option("vp_options"),true);





?>

<div class="row">

    <div class="col-12">
    <link
      rel="stylesheet"
      type="text/css"
      href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/multicheck/multicheck.css"
    />
    <link
      href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
      rel="stylesheet"
    />
<div class="card">
                <div class="card-body">

                

                
                <div class="form-check form-switch card-title d-flex">
<div class="input-group">
<label class="form-check-label float-start input-group-text" for="flexSwitchCheckChecked">Bulk SMS Status</label>
<input onchange="changestatus('sms')" value="checked" class="form-check-input sms input-group-text h-100 float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"smscontrol");?>>
</div>
</div>
<script>
function changestatus(type){
var obj = {}
if(jQuery("input."+type).is(":checked")){
  obj["set_status"] = "checked";
}
else{
  obj["set_status"] = "unchecked";
}
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";
obj["set_control"] = type;



  jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/controls.php'));?>",
  data: obj,
  dataType: "text",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
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
  title: msg,
  text: jqXHR.responseText,
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
	jQuery(".preloader").hide();
        if(data == "100" ){
	location.reload();
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: data,
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}

</script>


                  <div class="table-responsive">
<div class="p-4">

    <div id="smseasyaccess" class="core-services" >
    <br>
    <div class="alert alert-primary mb-2" role="alert">
    <?php echo vp_getoption("sms_info");?>
    </div>
    WELCOME TO EASY ACCESS SMS
    </br>
    
    <div class="not-simple">
    
    <div class="input-group mb-2">
    <span class="input-group-text">SMS BaseUrl</span>
    <input type="text" id="smsbaseurl" placeholder="" value="<?php echo vp_getoption("smsbaseurl");?>" name="smsbaseurl">
    </div>
    <div class="input-group mb-2">
    <span class="input-group-text">SMS EndPoint</span>
    <input type="text" id="smsendpoint" placeholder="" value="<?php echo vp_getoption("smsendpoint");?>" name="smsendpoint">
    </div>
    <div class="input-group mb-2">
    <span class="input-group-text">Request Method</span>
    <input type="text" id="smsrequesttext" name="smsrequesttext" value="<?php echo vp_getoption("smsrequesttext");?>" readonly>
    <select name="smsrequest" id="smsrequest">
    <option value="<?php echo vp_getoption("smsrequest");?>">Select</option>
    <option value="get">GET 1</option>
    <option value="post">GET 2</option>
    <option value="post">POST</option>
    </select>
    <script>
    jQuery("#smsrequest").on("change",function(){
        jQuery("#smsrequesttext").val(jQuery("#smsrequest option:selected").text());
    });
    </script>
    </div>
    <div class="input-group mb-2">
    <span class="input-group-text">Add Post Datas To SMS Service?</span>
    <input type="text"  value="<?php echo vp_getoption("smsaddpost");?>" class="smsaddpost2" readonly>
    <select name="smsaddpost" class="smsaddpost">
    <option value="<?php echo vp_getoption("smsaddpost");?>">Select</option>
    <option value="yes">YES</option>
    <option value="no">No</option>
    </select>
    <script>
    jQuery(".smsaddpost").on("change",function(){
        jQuery(".smsaddpost2").val(jQuery(".smsaddpost option:selected").val());
    });
    </script>
    </div>
    <br>
    
    </div>
    
    <label class="simple">SMS POST datas</label>
    <br>
    <?php
    for($smspost=1; $smspost<=5; $smspost++){
 ?>
    <div class="input-group mb-3">
    <span class="input-group-text simple">Post Data</span>
    <input class="simple fillable" type="text" placeholder="Data" value="<?php echo vp_getoption("smspostdata".$smspost);?>" name="smspostdata<?php echo $smspost;?>">
    <span class="input-group-text simple">Post Value</span>
    <input type="text" placeholder="Value" value="<?php echo vp_getoption("smspostvalue".$smspost);?>" name="smspostvalue<?php echo $smspost;?>" class="simple fillable smspostvalue<?php echo $smspost;?>">
    </div>
   <?php
    }?>
    <label class="not-simple" >Header Authorization</label>
    <br>
  <?php
    for($smsheaders=1; $smsheaders<=1; $smsheaders++){
 ?>
    <div class="input-group mb-3 not-simple">
    <select class="sms-head" name="smshead">
    <option value="<?php echo vp_getoption("sms_head");?>"><?php echo vp_getoption("sms_head");?></option>
    <option value="not_concatenated">Not Concatenated</option>
    <option value="concatenated">Concatenated</option>
    </select>
    <span class="input-group-text">Key</span>
    <input type="text" name="smshead<?php echo $smsheaders;?>" value="<?php echo vp_getoption("smshead".$smsheaders);?>"  placeholder="Key">
    <span class="input-group-text">Value</span>
    <input placeholder="Value" type="text" name="smsvalue<?php echo $smsheaders;?>" value="<?php echo vp_getoption("smsvalue".$smsheaders);?>" class="smspostkey">
    </div>
<?php
    }
    ?>
  
    <br>
    <label class="form-label simple">Other Headers</label>
    <br>
  <?php
    for($smsaddheaders=1; $smsaddheaders<=4; $smsaddheaders++){
    ?>
    <div class="input-group md-3">
    <span class="input-group-text simple">Key</span> 	
    <input type="text" name="smsaddheaders<?php echo $smsaddheaders;?>" value="<?php echo vp_getoption("smsaddheaders".$smsaddheaders);?>"  placeholder="Key" class="simple fillable" >
    <span class="input-group-text simple">Value</span> 
    <input placeholder="Value" type="text" name="smsaddvalue<?php echo $smsaddheaders;?>" value="<?php echo vp_getoption("smsaddvalue".$smsaddheaders);?>" class="simple fillable">
    </div>
    <?php
    }
    ?>
   
    <br>
    <div class="not-simple">
    
    <div class="input-group mb-2">
    <span class="input-group-text">REQUEST ID</span>
    <input type="text" value="<?php echo vp_getoption("smsrequest_id");?>" name="smsrequest_id">
    </div>
    <div class="input-group mb-2">
    <span class="input-group-text">MESSAGE ATTRIBUTE/ID</span>
    <input type="text" value="<?php echo vp_getoption("messageattr");?>" name="messageattr">
    </div>
    <div class="input-group mb-2">
    <span class="input-group-text">FLASH ATTRIBUTE/ID</span>
    <input type="text" value="<?php echo vp_getoption("flashattr");?>" name="flashattr">
    <span class="input-group-text md-2">FLASH VALUE  [0/1]</span>
    <input type="text" value="<?php echo vp_getoption("flash_value");?>" name="flash_value">
    </div>
    <div class="input-group mb-2">
    <span class="input-group-text">SENDER NAME ATTRIBUTE/ID</span>
    <input type="text" value="<?php echo vp_getoption("senderattr");?>" name="senderattr">
    </div>
    <div class="input-group mb-2">
    <span class="input-group-text">PHONE/RECIPIENT ATTRIBUTE</span>
    <input type="text" value="<?php echo vp_getoption("receiverattr");?>" name="receiverattr" placeholder="enter your phone attribute">
    </div>
    
    </div>
    
    <label class="simple" >AMOUNT / * CHARACTERS</label>
    <br>
    <div class="input-group mb-3">
    <span class="input-group-text simple">AMOUNT -N-</span>
    <input class="simple fillable" type="number" value="<?php echo vp_getoption("smsamountattribute");?>" name="smsamountattribute" placeholder="enter your amount attribute">
    <span class="input-group-text simple">CHARACTERS</span>
    <input class="simple fillable" type="number" value="<?php echo vp_getoption("smscharacter");?>" name="smscharacter" placeholder="Amount will be charged per this characters" min="2">
    </div>
    
    <div class="not-simple">
    <label class="simple">Success/Status Attribute</label><br>
    <div class="input-group mb-3">
    <span class="input-group-text">Key</span>
    <input type="text" value="<?php echo vp_getoption("smssuccesscode");?>" name="smssuccesscode" placeholder="success value e.g success or status">
    <span class="input-group-text">Value</span>
    <input type="text" value="<?php echo vp_getoption("smssuccessvalue");?>" name="smssuccessvalue" placeholder="success value">
    <span class="input-group-text">Alternative Value</span>
    <input type="text" value="<?php echo vp_getoption("smssuccessvalue2");?>" name="smssuccessvalue2" placeholder="Alternative success value"> 
    </div>
    
    <label class="simple" >Success Status Format</label><br>
    <div class="input-group mb-3">
    <span class="input-group-text">Key</span>
    <input class="sms_response_format_text" type="text" value="<?php echo vp_getoption("sms_response_format_text");?>" name="sms_response_format_text">
    <select name="sms_response_format" class="sms_response_format">
    <option value="<?php echo vp_getoption("sms_response_format");?>"><?php echo vp_getoption("sms_response_format_text");?></option>
    <option value="json">JSON</option>
    <option value="plain">PLAIN</option>
    </select>
    <script>
    jQuery(".sms_response_format").on("change",function(){
        jQuery(".sms_response_format_text").val(jQuery(".sms_response_format option:selected").text());
    });
    </script>
    </div>
    
    </div>
    
    <br>
    <input type="button" class="btn btn-success save_sms" name="save_sms" value="SAVE SMS SETTINGS">
    
    <script>
    
    
    jQuery(".save_sms").click(function(){
    
    jQuery("#cover-spin").show();
        
    var obj = {};
    var toatl_input = jQuery("#smseasyaccess input,#smseasyaccess select").length;
    var run_obj;
    
    for(run_obj = 0; run_obj <= toatl_input; run_obj++){
    var current_input = jQuery("#smseasyaccess input,#smseasyaccess select").eq(run_obj);
    
    
    var obj_name = current_input.attr("name");
    var obj_value = current_input.val();
    
    if(typeof obj_name !== typeof undefined && obj_name !== false){
    obj[obj_name] = obj_value;
    }
    
    
        
    }
    
    jQuery.ajax({
      url: "<?php echo esc_url(plugins_url("vpsms/savesms.php"));?>",
      data: obj,
      dataType: "text",
      "cache": false,
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
      title: msg,
      text: jqXHR.responseText,
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
            if(data == "100" ){
        
              swal({
      title: "SAVED",
      text: "Update Completed",
      icon: "success",
      button: "Okay",
    }).then((value) => {
        location.reload();
    });
          }
          else{
              
        jQuery("#cover-spin").hide();
        swal({
      buttons: {
        cancel: "Why?",
        defeat: "Okay",
      },
      title: "Update Wasn\'t Successful",
      text: "Click \'Why\' To See reason",
      icon: "error",
    })
    .then((value) => {
      switch (value) {
     
        case "defeat":
          break;
        default:
          swal({
            title: "Details",
            text: data,
          icon: "info",
        });
      }
    }); 
    
      }
      },
      type: "POST"
    });
    
    
    
    
    
    
    });
    </script>
    </div>

</div>





                  </div>
                </div>
              </div>
</div>


</div>



</div>
<?php   
}
    
?>