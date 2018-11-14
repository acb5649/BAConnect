<?php

include('dbc_connector.php');

$query = "SELECT DISTINCT lastName FROM Account ORDER BY lastName ASC";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
foreach($result as $row)
{
 $a[]= $row['lastName'];
}
$query = "SELECT DISTINCT firstName FROM Account ORDER BY firstName ASC";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
foreach($result as $row)
{
 $b[]= $row['firstName'];
}
$query = "SELECT DISTINCT username FROM Account ORDER BY username ASC";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
foreach($result as $row)
{
 $c[]= $row['username'];
}
$query = "SELECT DISTINCT email FROM Account ORDER BY email ASC";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
foreach($result as $row)
{
 $d[]= $row['email'];
}
// get the q parameter from URL
if(isset($_REQUEST["q"])){
	$q = $_REQUEST["q"];
	$r = "";
	$s = "";
	$t ="";
}else if(isset($_REQUEST["r"])){
	$r = $_REQUEST["r"];
	$q="";
	$s ="";
	$t ="";
}else if(isset($_REQUEST["s"])){
	$s = $_REQUEST["s"];
	$q="";
	$r ="";
	$t ="";
}else if(isset($_REQUEST["t"])){
	$t = $_REQUEST["t"];
	$q="";
	$r ="";
	$s= "";
}
	
$hint = "";

// lookup all hints from array if $q is different from ""
if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);
    foreach($a as $name) {
        if (stristr($q, substr($name, 0, $len))) {
            if($hint == $q){
				$hint = "";
			}
            elseif ($hint === "") {
                $hint = $name;
            } else {
                $hint .= ", $name";
            }
        }
    }
}
if ($r !== "") {
    $r = strtolower($r);
    $len=strlen($r);
    foreach($b as $name) {
        if (stristr($r, substr($name, 0, $len))) {
            if($hint == $r){
				$hint = "";
			}
            elseif ($hint === "") {
                $hint = $name;
            } else {
                $hint .= ", $name";
            }
        }
    }
}

if ($s !== "") {
    $s = strtolower($s);
    $len=strlen($s);
    foreach($c as $name) {
        if (stristr($s, substr($name, 0, $len))) {
            if($hint == $s){
				$hint = "";
			}
            elseif ($hint === "") {
                $hint = $name;
            } else {
                $hint .= ", $name";
            }
        }
    }
}
if ($t !== "") {
    $t = strtolower($t);
    $len=strlen($t);
    foreach($d as $name) {
        if (stristr($t, substr($name, 0, $len))) {
			if($hint == $t){
				$hint = "";
			}
            elseif ($hint === "") {
                $hint = $name;
            } else {
                $hint .= ", $name";
            }
        }
    }
}
// Output "no suggestion" if no hint was found or output correct values
echo $hint === "" ? "Nothing Available" : $hint;
?>

