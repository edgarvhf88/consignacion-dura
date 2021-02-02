<?php include("../data/conexion.php"); 
if ($_SESSION["logged_user"] <> ''){ 
$_SESSION["logged_user"] = '';
echo '<script> window.location.replace("../index.php"); </script>';}
else{
	echo '<script> window.location.replace("../index.php"); </script>';
}

?>