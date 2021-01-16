<?php include("conexion.php"); 
$id_pedido = $_POST['id_pedido'];
$id_usuario = $_SESSION["logged_user"];
$consulta = "SELECT * FROM pedidos WHERE id_usuario = $id_usuario  and estatus = '0'";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
if ($total_rows == 0){echo 0;}else if($total_rows > 0){echo $row['id'];}
?>