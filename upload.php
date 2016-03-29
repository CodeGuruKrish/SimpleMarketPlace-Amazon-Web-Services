<?php
  // error_reporting(0);
   session_start();
   
   $_SESSION['csvtime'] = null;
   $_SESSION['filetime'] = null;

   require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
   use google\appengine\api\cloud_storage\CloudStorageTools;

   $options = [ 'gs_bucket_name' => 'hitheshcloud' ];
   $upload_url = CloudStorageTools::createUploadUrl('/', $options);
   

ob_start();
$host="173.194.235.162"; // Host name 
$username="root"; // Mysql username; change here in case you use a local server 
$password="hith1242"; // Mysql password; change here in case you use a local server
$db_name="csvdb"; // Database name 
$tbl_name="csvdata"; // Table name 

// Connect to server and select databse.

$conn = mysql_connect(':/cloudsql/hitheshcloud:userdb',
  'root', // username
  ''      // password
  )or die("cannot connect");
  mysql_select_db("csvdb")or die("cannot select DB");


//mysql_connect("173.194.235.162","root","hith1242")or die("cannot connect"); 
//mysql_select_db("csvdb")or die("cannot select DB");

?>
<!DOCTYPE html>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="/inc/topcoat-0.8.0/css/topcoat-mobile-dark.css">
<link rel="stylesheet" type="text/css" href="/inc/css/main.css">
</head>
<body>
<div class="contentArea">
<?php

if(isset($_POST['do-upload']) AND $_POST['do-upload'] === "yes"){

   $yesupload = $_POST['do-upload'];
   preg_match("/yes/", "".$yesupload."");

   $filename = $_FILES['testupload']['name'];
   $ext = pathinfo($filename, PATHINFO_EXTENSION);
   
   if (strcmp($ext,'csv')==0)
{
	$first = microtime(true);
    //get the csv file
    $file = $_FILES['testupload'][tmp_name];
    $handle = fopen($file,"r");
    
    //loop through the csv file and insert into database
    do {
        if ($data[0]) {
            mysql_query("INSERT INTO $tbl_name (fname, lname, mail) VALUES
                (
                    '".addslashes($data[0])."',
                    '".addslashes($data[1])."',
                    '".addslashes($data[2])."'
                )
            ");
        }
    } while ($data = fgetcsv($handle,1000,",","'"));

$timetaken = microtime(true) - $first;
$timetaken = $timetaken/60;
$_SESSION['csvtime'] = $timetaken;
}
else
{
   $firstfiletime = microtime(true);
   $gs_name = $_FILES['testupload']['tmp_name'];
   move_uploaded_file($gs_name, 'gs://hitheshcloud/'.$filename.'');
   $filetimetaken = microtime(true) - $firstfiletime;
   $filetimetaken = $filetimetaken/60;
   $_SESSION['filetime'] = $filetimetaken;
   }
?>

<?php
   echo "<p>File Uploaded</p>";
   echo "<p>Name of the file you uploaded: ".$filename."</p>";
   }
   
?>
<form class="SomeSpaceDude" action="<?php echo $upload_url?>" enctype="multipart/form-data" method="post">
   <p>Files to upload: </p> <br>
   <input type="hidden" name="do-upload" value="yes">
   <input class="topcoat-button" type="file" name="testupload" >
   <input class="topcoat-button" type="submit" value="Upload">
   <button onclick="location.href='/query'">Query table</button>
</form>
     
<?php if (isset($_SESSION['csvtime'])){echo "<h4>".$filename. " file uploaded in : ".$_SESSION['csvtime']." Minutes</h4>";} 
        if (isset($_SESSION['filetime'])){ echo "<h4>".$filename. " file uploaded in : ".$_SESSION['filetime']." Minutes</h4>";} 
		session_destroy();
?>
</div>
</body>
</html>
