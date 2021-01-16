<?php include("../data/conexion.php"); 
/* if ($_SESSION["logged_user"] <> ''){ header('Location: ../index.php'); } */

		$user = $_POST['usuario'];
		$pass = $_POST['contrasena'];
		
	  
	  if (isset($_POST['usuario'])){
      $user = $_POST['usuario'];
      }
	  if ($user != ''){
	  
			verifica_datos($user,$pass);
	  }
     function verifica_datos($user,$pass){ 
global $database_conexion, $conex;


$consulta = "SELECT * FROM usuarios WHERE username = '$user' OR correo = '$user'";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
if ($total_rows == 0){
 echo 0; // usuario no registrado

} else if ($total_rows > 0){
			
			if ($row['contrasena'] == $pass){
				echo 1; // acceso consedido
				$_SESSION["logged_user"] = $row['id'];
			} 
			else
			{
				echo 2; // contrasena incorrecta
			}


}

}




?>