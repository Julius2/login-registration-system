<?php
$Hostname="localhost";
$username="root";
$password="";
$dbname="comment_sys";
$conn=mysql_connect($server="localhost",$username="root",$password="",$dbname="comment_sys");
if (!($conn)){
	echo "could not connect".mysql_error();
}
else{
	echo "connected successfully";
}

?>
	