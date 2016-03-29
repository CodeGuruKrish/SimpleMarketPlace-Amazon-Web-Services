 <?php 

//connect to the database
error_reporting(0);
session_start();
ob_start();
//$host="173.194.235.162"; // Host name 
//$username="root"; // Mysql username; change here in case you use a local server 
//$password="hith1242"; // Mysql password; change here in case you use a local server
$db_name="csvdb"; // Database name 
$tbl_name="csvdata"; // Table name 

// Connect to server and select databse.

$conn = mysql_connect(':/cloudsql/hitheshcloud:userdb',
  'root', // username
  ''      // password
  )or die("cannot connect");
  mysql_select_db("csvdb")or die("cannot select DB");


 $result=mysql_query("show columns from $tbl_name");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Import a CSV File with PHP & MySQL</title>
</head>

<body>
<style type="text/css">
	  body { font: 14px/1.4 Georgia, Serif; background: background-size: cover; color: #cccccc; }
.loginForm {
	color: #996699;
	text-decoration: none;
}
.page-wrapper {
	background: transparent url() repeat-y top left;
	margin: 0px auto;
	padding: 0px;
	width: 752px;
	text-align: left;
}

.loginForm{
	display:table;
	margin: 20px auto;
	padding: 6px 24px 26px;
	position:relative;
	font-weight: 400;
	background: #fff;
	box-shadow: 0 1px 3px rgba(0,0,0,.13);
}
.loginForm .overlay{
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	padding: 50px 30px;
	display: none;
	background: rgba(204, 204, 204, 0.7);
	text-align: center;
}
.loginForm .overlay .message{
	display: none;
}
.loginForm label{
	display: block;
	margin-top: 20px;
}
.loginForm label span{
	color: #777;
	display: block;
	font-size: 14px;
}
.loginForm input[type=text], .loginForm input[type=password]{
	margin-top: 5px;
	background: #fbfbfb;
	padding: 3px;
	border: 1px solid #ddd;
	box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
	font-size: 24px;
}
.loginForm .submit{
	display: inline-block;
	padding: 7px 15px;
	margin-top: 15px;
	background: #23A3F8;
	color: white;
	cursor: pointer;
	box-shadow: 0 1px 3px rgba(0,0,0,.13);
}
.loginForm .submit:hover{
	box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
}
.loginForm .submit:active{
	box-shadow: rgba(0, 0, 0, 0.498039) 0px 2px 10px -3px inset;
}
</style>

<?php //if (isset($_GET[success])) {if (!empty($_GET[success])) { echo "<b>Your file has been imported.</b><br><br>"; }} //generic success notice ?>
<div id="content" style='margin:10px'>
                              <div class="loginForm">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  Choose column to query: <br />
  <select>
<?php
 while($row = mysql_fetch_array($result))
  {
$thing = $row['Field'];
echo "<option name='tblfield' VALUE=1>$thing</option>";
  //echo $row['Field']."  ";

  }

?>
</select>
<input name ="querytx" type="text" name="query">
</form>
<form action="query.php">
    <input type="submit" value="Execute query">
</form>
</div>
</div>
<div class="loginForm">
<h1>Query Results</h1>
<?php

//if (isset($_POST['querytx']) && isset($_POST['tblfield']))
//{
$column = $_GET['tblfield'];
$querytxt = $_GET['querytx'];

print "<h4>$column</h4>";
print "<h2>$querytxt</h2>";

$first = microtime(true);
 $result=mysql_query("select * from $tbl_name where fname = 'hithesh'");
  // $result=mysql_query("select * from $tbl_name"); 
while($row = mysql_fetch_array($result))
  {
$fname = $row['fname'];
$lname = $row['lname'];
$mail = $row['mail'];
echo "<h4>$fname $lname</h4>";
echo "<h4>$mail</h4>";
  }
$timetaken = microtime(true) - $first;
$timetaken = $timetaken/60;
$_SESSION['time'] = $timetaken;
    //
    //redirect
    //header('Location: csv.php?success=1'); die;
echo "<h4>File queried in : ".$_SESSION['time']." Minutes</h4>"; 
//}
?>
</div>
</body>
</html>

