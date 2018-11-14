<?php

include('dbc_connector.php');

?>

<html>
 <head>
  <title>Search Accounts</title>
  <script>
	function showHint(str,letter,id) {
		if (str.length == 0) {
			document.getElementById(id).innerHTML = "";
			return;
		} else {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById(id).innerHTML = this.responseText;
				}
			};
			xmlhttp.open("GET", "hinterbox.php?" + letter + "=" + str, true);
			xmlhttp.send();
		}
	}
</script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  
 </head>
 <body>
  <div class="container box">
   <h3 align="center">Account Database</h3>
   <br />
   <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
	<div class="form-group">
      <select name="filter_department" id="filter_department" class="form-control" required>
       <option value="" >Select Department</option>
	   <option value="Science">Science</option>
       <option value="Mathematics">Mathematics</option>
       <option value="Engineering">Engineering</option>
	   <option value="Liberal Arts">Liberal Arts</option>
	   <option value="Education">Education</option>
      </select>
     </div>
     <div class="form-group">
      <select name="filter_gender" id="filter_gender" class="form-control" required>
       <option value="">Select Gender</option>
       <option value="Male">Male</option>
       <option value="Female">Female</option>
      </select>
     </div>
	 <div class="form-group">
       <p><b>Username:</b>  <input type="text" name="filter_uName" id="filter_uName" placeholder="Enter a Username" class="form-control" onkeyup="showHint(this.value,'s','txtUserHint')"  required autofocus></p>
	   <p class="form-control"><b>Suggestions: <span style="color:#00c2ff" id="txtUserHint"></span></b></p>
     </div>
	 <div class="form-group">
       <p><b>First Name:</b>  <input type="text" name="filter_fName" id="filter_fName" placeholder="Enter a First Name" class="form-control" onkeyup="showHint(this.value,'r','txtFirstHint')"  required autofocus></p>
	   <p class="form-control"><b>Suggestions: <span style="color:#00c2ff" id="txtFirstHint"></span></b></p>
     </div>
     <div class="form-group">
       <p><b>Last Name:</b>  <input type="text" name="filter_lName" id="filter_lName" placeholder="Enter a Last Name" class="form-control" onkeyup="showHint(this.value,'q','txtLastHint')"  required autofocus></p>
	   <p class="form-control"><b>Suggestions: <span style="color:#00c2ff" id="txtLastHint"></span></b></p>
     </div>
	 <div class="form-group">
       <p><b>Email:</b>  <input type="text" name="filter_email" id="filter_email" placeholder="Enter a Registered Email Address" class="form-control" onkeyup="showHint(this.value,'t','txtEmailHint')"  required autofocus></p>
	   <p class="form-control"><b>Suggestions: <span style="color:#00c2ff" id="txtEmailHint"></span></b></p>
     </div>
	 <br/>
     <div class="form-group" align="center">
      <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>
     </div>
    </div>
    <div class="col-md-4"></div>
   </div>
   <div class="table-responsive">
    <table id="account_data" class="table table-bordered table-striped">
     <thead>
      <tr>
	   <th width="20%">Username</th>
       <th width="20%">First Name</th>
	   <th width="20%">Last Name</th>
       <th width="10%">Gender</th>
       <th width="25%">Email</th>
       <th width="15%">Department</th>
      </tr>
     </thead>
    </table>
    <br />
    <br />
    <br />
   </div>
  </div>
 </body>
</html>

<script type="text/javascript" language="javascript" >
 $(document).ready(function(){
  
  fill_datatable();
  
  function fill_datatable(filter_gender = '', filter_lName = '', filter_department = '', filter_fName = '', filter_uName = '', filter_email = '')
  {
   var dataTable = $('#account_data').DataTable({
    "processing" : true,
    "serverSide" : true,
    "order" : [],
    "searching" : false,
    "ajax" : {
     url:"syncher.php",
     type:"POST",
     data:{
      filter_department:filter_department, filter_gender:filter_gender, filter_lName:filter_lName, filter_fName:filter_fName, filter_uName:filter_uName, filter_email:filter_email
     }
    }
   });
  }
  $("#txtUserHint").hover(function (){
        $(this).css("text-decoration", "underline");
		$(this).css('cursor', 'pointer');
		},function(){
			$(this).css("text-decoration", "none");
		}
	);
	$("#txtFirstHint").hover(function (){
        $(this).css("text-decoration", "underline");
		$(this).css('cursor', 'pointer');
		},function(){
			$(this).css("text-decoration", "none");
		}
	);
	$("#txtLastHint").hover(function (){
        $(this).css("text-decoration", "underline");
		$(this).css('cursor', 'pointer');
		},function(){
			$(this).css("text-decoration", "none");
		}
	);
	$("#txtEmailHint").hover(function (){
        $(this).css("text-decoration", "underline");
		$(this).css('cursor', 'pointer');
		},function(){
			$(this).css("text-decoration", "none");
		}
	);
  $('#txtUserHint').click(function(){
	$('#filter_uName').val($('#txtUserHint').html());
	$('#txtUserHint').html('');
  });
  $('#txtFirstHint').click(function(){
	$('#filter_fName').val($('#txtFirstHint').html());
	$('#txtFirstHint').html('');
  });
  $('#txtLastHint').click(function(){
	$('#filter_lName').val($('#txtLastHint').html());
	$('#txtLastHint').html('');
  });
  $('#txtEmailHint').click(function(){
	$('#filter_email').val($('#txtEmailHint').html());
	$('#txtEmailHint').html('');
  });
  $('#clear').click(function(){
	$('#filter_gender').val('');
	$('#filter_lName').val('');
	$('#filter_department').val('');
	$('#filter_fName').val('');
	$('#filter_uName').val('');
	$('#filter_email').val('');
	$('#txtUserHint').html('');
	$('#txtLastHint').html('');
	$('#txtFirstHint').html('');
	$('#txtEmailHint').html('');	  
  });
  $('#filter').click(function(){
   var filter_gender = $('#filter_gender').val();
   var filter_lName = $('#filter_lName').val();
   var filter_department = $('#filter_department').val();
   var filter_fName = $('#filter_fName').val();
   var filter_uName= $('#filter_uName').val();
   var filter_email= $('#filter_email').val();
   if(filter_gender != '' || filter_lName != '' || filter_department != '' || filter_fName != '' || filter_uName != '' || filter_email != '')
   {
    $('#account_data').DataTable().destroy();
    fill_datatable(filter_gender, filter_lName, filter_department, filter_fName, filter_uName, filter_email);
	$('#filter_gender').val('');
	$('#filter_lName').val('');
	$('#filter_department').val('');
	$('#filter_fName').val('');
	$('#filter_uName').val('');
	$('#filter_email').val('');
	$('#txtUserHint').html('');
	$('#txtLastHint').html('');
	$('#txtFirstHint').html('');
	$('#txtEmailHint').html('');
   }
   else
   {
    $('#account_data').DataTable().destroy();
    fill_datatable();
	$('#filter_gender').val('');
	$('#filter_lName').val('');
	$('#filter_department').val('');
	$('#filter_fName').val('');
	$('#filter_uName').val('');
	$('#filter_email').val('');
	$('#txtUserHint').html('');
	$('#txtLastHint').html('');
	$('#txtFirstHint').html('');
	$('#txtEmailHint').html('');
   }
  });
  
  
 });
 
</script>
