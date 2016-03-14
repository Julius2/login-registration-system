<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_Dbconnection = "localhost";
$database_Dbconnection = "comment_sys";
$username_Dbconnection = "root";
$password_Dbconnection = "password1";
$Dbconnection = mysql_pconnect($hostname_Dbconnection, $username_Dbconnection, $password_Dbconnection) or trigger_error(mysql_error(),E_USER_ERROR); 
?>