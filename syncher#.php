<?php

include('dbc_connector.php');
$Arranged = array('WHERE', 'AND', 'AND', 'AND', 'AND', 'AND');
$counted = 0;

$column = array('username', 'firstName', 'lastName', 'gender', 'email', 'department');

$query = "
SELECT * FROM Account
";
if(isset($_POST['filter_department']) && $_POST['filter_department'] != ''){
	 $query .= ''.$Arranged[$counted].' department = "'.$_POST['filter_department'].'"';
	 $counted = $counted + 1;
	
}
if(isset($_POST['filter_gender']) && $_POST['filter_gender'] != ''){
	 $query .= ''.$Arranged[$counted].' gender = "'.$_POST['filter_gender'].'"';
	 $counted = $counted + 1;
}
if(isset($_POST['filter_fName']) && $_POST['filter_fName'] != ''){
	 $query .= ''.$Arranged[$counted].' firstName = "'.$_POST['filter_fName'].'"';
	 $counted = $counted + 1;
}
if(isset($_POST['filter_lName']) && $_POST['filter_lName'] != ''){
	 $query .= ''.$Arranged[$counted].' lastName = "'.$_POST['filter_lName'].'"';
	 $counted = $counted + 1;
}
if(isset($_POST['filter_uName']) && $_POST['filter_uName'] != ''){
	 $query .= ''.$Arranged[$counted].' username = "'.$_POST['filter_uName'].'"';
	 $counted = $counted + 1;
}
if(isset($_POST['filter_email']) && $_POST['filter_email'] != ''){
	 $query .= ''.$Arranged[$counted].' email = "'.$_POST['filter_email'].'"';
	 $counted = $counted + 1;
}
if(isset($_POST['order']))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY accountNumber DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();



$data = array();

foreach($result as $row)
{
 $sub_array = array();
 $sub_array[] = $row['username'];
 $sub_array[] = $row['firstName'];
 $sub_array[] = $row['lastName'];
 $sub_array[] = $row['gender'];
 $sub_array[] = $row['email'];
 $sub_array[] = $row['department'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM Account";
 $statement = $connect->prepare($query);
 $statement->execute();
 return $statement->rowCount();
}

$output = array(
 "draw"       =>  intval($_POST["draw"]),
 "recordsTotal"   =>  count_all_data($connect),
 "recordsFiltered"  =>  $number_filter_row,
 "data"       =>  $data
);

echo json_encode($output);

?>