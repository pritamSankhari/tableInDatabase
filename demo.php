<?php

require('./tableInDatabase.php');

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// SHOULD BE USED CAREFULLY
// All the keys in associative array must be same as attribute name or coloumn name of specific table in database to access database
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

//------------------------
// Database basic settings
$serverName="localhost";
$username="root";
$password="";
$databaseName="";
//------------------------

//------------------------
//Create connection
$db=new mysqli($serverName,$username,$password,$databaseName);
//------------------------

//$db becomes a mysqli object 
// work with $db ... All The Best :)

//------------------------
//Check Connection
if($db->connect_error)
{
	echo "Database connection falied !";
	echo "<br>".$db->connect_error;
	exit();
}
//------------------------

//---------------------------------
function get_random_string($n){

	// creating random string
	$random_string=str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234");
	if($n>strlen($random_string)) $n=strlen($random_string);
	$random_string=substr($random_string,0,$n);
	return $random_string;
}
//---------------------------------

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// SHOULD BE USED CAREFULLY
// All the keys in associative array in this file must be same as attribute name or coloumn name of specific table in database to access database
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

$customer['id']=get_random_string(6);
$tableName='order_n';

// ###################################
// Creating new table object
// ###################################
//--------------------------------------
$order = new TableInDB($tableName,$db);
//--------------------------------------

// ###################################
// Avoiding id duplication :)
// ###################################
//--------------------------------------
while($order->has('id',"where id = '".$customer['id']."'")){
	$customer['id']=get_random_string(6);
}
//--------------------------------------

// ###################################
// Inserting data to the table
// ###################################
//--------------------------------------

if($order->insert($customer)) echo "Data is inserted !";
else echo "Failed to insert data";
//--------------------------------------


// ###################################
// Updating data in the table
// ###################################
//--------------------------------------
$updates['id']='test01';
$updates['customer_name']='tester01';

if($order->update($updates,'where id =\''.$customer['id'].'\'')) echo "Data is updated!";
else echo "Failed to update!";
//--------------------------------------


// #################################################################
// Fetching or Selecting data from table and showing by php scripts
// #################################################################
//--------------------------------------
$coloumns_to_be_fetched =array('id','order_verified');

$recent_order = $order->select($coloumns_to_be_fetched);

echo "<pre>";print_r($recent_order);echo "</pre>";
//--------------------------------------

// ###################################
// Deleting data in the table
// ###################################
//--------------------------------------
if($order->delete('where id =\'test01\'')) echo "Data of id test01 is deleted!";
else echo "Failed to delete!";
//--------------------------------------

// #################################################################
// Fetching or Selecting data from table and showing by php scripts
// #################################################################
//--------------------------------------
$coloumns_to_be_fetched =array('id','order_verified');

$recent_order = $order->select($coloumns_to_be_fetched);

echo "<pre>";print_r($recent_order);echo "</pre>";
//--------------------------------------

?>
