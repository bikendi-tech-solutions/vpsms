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
                  <h5 class="card-title">Pending Sms</h5>

                  <?php

if(!isset($_GET["trans_id"])){
    pagination_history_before("ssms","false");
}
elseif(empty($_GET["trans_id"])){
  pagination_history_before("ssms","false");
}
else{
    if(is_numeric($_GET["trans_id"])){
        $id = $_GET["trans_id"];
        pagination_history_before("ssms","false","AND id = '$id'");
    }
    else{
        pagination_history_before("ssms","false");
    }
}


?>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                      <tr>
                      <th class="">
                          <label class="customcheckbox mb-3">
                            <input type="checkbox" id="mainCheckbox"  />
                            <span class="checkmark"></span>
                          </label>
                        </th>
	<th scope='col'>ID</th>
	<th scope='col'>User Name</th>
	<th scope='col'>User Email</th>
	<th scope='col'>Sender</th>
	<th scope='col'>Receivers</th>
	<th scope='col'>Previous Balance</th>
	<th scope='col'>Current Balance</th>
	<th scope='col'>Amount</th>
	<th scope='col'>User ID</th>
    <th scope='col'>Action</th>
	<th scope='col'>Time</th>
	<th scope='col'>Response</th>
	</tr>
                      </thead>
                      <tbody>
                   
                      <?php


global $transactions;
if($transactions == "null"){
?>
    <tr  class="text-center">
    <td colspan="8">No Pending Sms Recorded</td>
    </tr>
<?php
}else{
            $option_array = json_decode(get_option("vp_options"),true);




foreach($transactions as $sbresult){


    echo"
    <tr>
    <th class=''>
    <label class=\"customcheckbox\">
      <input type=\"checkbox\" class=\"listCheckbox\" value=\"$sbresult->id\" amount='$sbresult->amount' user='$sbresult->user_id'/>
      <span class=\"checkmark\"></span>
    </label>
  </th>
    <th scope='row'>".$sbresult->id."</th>
    <td>".$sbresult->name."</td>
    <td>".$sbresult->email."</td>
    <td>".$sbresult->sender."</td>
    <td>".$sbresult->receiver."</td>
    <td>".$sbresult->bal_bf."</td>
    <td>".$sbresult->bal_nw."</td>
    <td>".$sbresult->amount."</td>
    <td>".$sbresult->user_id."</td>
    <td>
    <div class='input-group'>
    <button class='bg bg-danger text-white' onclick='reversetransaction(\"ssms\",\"".$sbresult->id."\", \"".$sbresult->user_id."\", \"".$sbresult->amount."\");'> <i class=' fas fa-retweet'></i></button>
    <button class='bg bg-success text-white' onclick='approvetransaction(\"ssms\",\"".$sbresult->id."\", \"".$sbresult->user_id."\", \"".$sbresult->amount."\");'> <i class=' fas fa-check'></i></button>
    </div>
    </td>
    <td>".$sbresult->the_time."</td>
    <td>".$sbresult->resp_log."</td>


  </tr>
    ";
    
}

}
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
	<th scope='col'>ID</th>
	<th scope='col'>User Name</th>
	<th scope='col'>User Email</th>
	<th scope='col'>Sender</th>
	<th scope='col'>Receivers</th>
	<th scope='col'>Previous Balance</th>
	<th scope='col'>Current Balance</th>
	<th scope='col'>Amount</th>
	<th scope='col'>User ID</th>
    <th scope='col'>Action</th>
	<th scope='col'>Time</th>
	<th scope='col'>Response</th>
	</tr>
                      </tfoot>
                    </table>

                    <div class="input-group">
  <span class="input-group-text">Bulk Action</span>

  <select onchange="openfunction('ssms','Bulk Sms')">
                      <option >--Select--</option>
                      <option value="reverse">Reverse Transaction</option>
                      <option value="success">Mark Successful</option>
                      <option value="delete">Delete Selected Record</option>
</select>
<?php include_once(ABSPATH."wp-content/plugins/vtupress/admin/pages/history/history.php");?>
    
</div>

<script>
function reversetransaction(db,trans_id,user_id,amount){

var obj = {};
obj['trans_id'] = trans_id;
obj['user_id'] = user_id;
obj['amount'] = amount;
obj['table'] = db;
obj['type'] = "BulkSMs";
obj['action'] = 'reverse';
if(confirm("Do You Want To Reverse The Transaction With ID "+trans_id+"?") == true){

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/history/saves/history.php'));?>",
  data: obj,
  dataType: "json",
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
	
		  swal({
  title: "Done!",
  text: "Transaction Marked Successful!",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Process Wasn't Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});



}
else{

    return;
}

}


function approvetransaction(db,trans_id,user_id,amount){

var obj = {};
obj['trans_id'] = trans_id;
obj['user_id'] = user_id;
obj['amount'] = amount;
obj['table'] = db;
obj['type'] = "BulkSMs";
obj['action'] = 'success';
if(confirm("Do You Want To Mark The Transaction With ID "+trans_id+" Successful?")){

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/history/saves/history.php'));?>",
  data: obj,
  dataType: "json",
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
	
		  swal({
  title: "Done!",
  text: "Transaction Refunded",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Process Wasn't Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});



}
else{

    return;
}

}

function loadinfo(id){

window.location = "?page=vtupanel&adminpage=users&subpage=info&id="+id;

}
</script>
                  </div>
                </div>
              </div>
</div>


</div>



<?php
?>