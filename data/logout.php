<?php include("../data/conexion.php"); 
if ($_SESSION["logged_user"] <> ''){ 
$_SESSION["logged_user"] = '';
header('Location: ../index.php'); }
else{
	header('Location: ../index.php');
}

?>