<?php include("../data/conexion.php"); 

$tipo = $_POST['tipo'];

if($tipo==0)
{modal_spotby();}

else if($tipo==2)
{
	$descripcion = $_POST['descripcion'];
	$cantidad = $_POST['cantidad'];
	$datos_adicionales = $_POST['datos_adicionales'];
	insertar_spotby($descripcion, $cantidad, $datos_adicionales);
	
}

else if($tipo==3)
{
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	update_spotby($id, $nombre);
	
}

else if($tipo==1)
{
	lista_spotby();
	
}
else if($tipo==4)
{
	$id = $_POST['id'];
	verificar_spotby($id);
	
}
function modal_spotby()//imprime el modal de spotby
{	
		echo '<script>$("#spotby").modal("show");</script>';						
}

function insertar_spotby($descripcion, $cantidad, $d_adicional)
{
	
	
	$fecha_creacion= date ("Y-m-d");
	$usuario_creacion =$_SESSION["logged_user"];
	$estatus =1;
	global $conex;
	$insertar = "INSERT INTO spotby  
	(descripcion, cantidad, d_adicional, estatus, fecha_creacion, usuario_creacion)
	VALUES ('$descripcion', '$cantidad', '$d_adicional', '$estatus', '$fecha_creacion', '$usuario_creacion')";
	if (mysql_query($insertar, $conex) or die(mysql_error()))
		{
			$id_insertado =  mysql_insert_id();
			echo '<script> subir_iamgen_spotby('.$id_insertado.');</script>';
		}
}

function update_spotby($id, $nombre)
{
	
	global $conex;
	$update = "UPDATE spotby SET 
	imagen='$nombre'
	WHERE id = '$id'";
	
	if (mysql_query($update, $conex) or die(mysql_error()))
		{}
}

function verificar_spotby($id)
{
	
	global $conex;
	$update = "UPDATE spotby SET 
	estatus='2'
	WHERE id = '$id'";
	
	if (mysql_query($update, $conex) or die(mysql_error()))
		{echo '<script>spotby_lista();</script>';}
}
function lista_spotby()
{
	//lista 
	
	global $conex;
	$consulta = "SELECT * FROM spotby ";
	$res = mysql_query($consulta, $conex) or die(mysql_error());
	$total_rows = mysql_num_rows($res);
	
	$id="";
	$descripcion="";
	$cantidad="";
	$d_adicional="";
	$imagen="";
	$estatus="";
	
	$tabla = '<table id="tabla_spotby" class="table table-striped table-bordered table-hover table-responsive display" style="font-size:12px;">
			<thead>
				<tr class="bg-info">
					<th>Description</th>
					<th>Quantity</th>
					<th>Additional data</th>
					<th>Status</th>
					<th>Image</th>
				</tr>
			</thead><tbody>';
								
	while ($row = mysql_fetch_array($res,MYSQL_BOTH))
	{
	$id=$row['id'];
	$descripcion=$row['descripcion'];
	$cantidad=$row['cantidad'];
	$d_adicional = $row['d_adicional'];
	$imagen = $row['imagen'];
	$estatus = $row['estatus'];
	$tipo_user=permisos();
	if($tipo_user==3)
	{
		if($estatus==1)
		{
		$estatus='<div class="dropdown">
		<button class="btn btn-danger btn-block dropdown-toggle btn_estatus" type="button" id="btnestatus_1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Verificar
			<span class="caret"></span>
		</button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		<li class="btn btn-info btn-block btn_solicitud_traspaso" id="btn_solicitudtraspaso_1" onclick="verificar_spot('.$id.');" >Verificar</li></div>';
		}
		else if($estatus==2)
		{
			$estatus="Attended";
		}
	}
	else {
		if($estatus==1)
		{
			$estatus="Process";
		}
		else if($estatus==2)
		{
			$estatus="Attended";
		}
	}
		//aqui voy
	$tabla .= '<tr>
				<td>'.$descripcion.'</td>
				<td>'.$cantidad.'</td>
				<td>'.$d_adicional.'</td>
				<td>'.$estatus.'</td>
				<td><a onclick="ver_img_spotby(\''.$imagen.'\');" href="#"  >'.$imagen.' <i class="fa fa-check-square-o" aria-hidden="true"></i></a></td>
			   </tr>';
		
		
	}
		
		$tabla .= '</tbody></table>
		
							<script>
									$(document).ready(function()
									{
										$("#tabla_spotby").DataTable({
											"order": [[ 0, "asc" ]]
											});
										$("#modal_cargando").modal("hide");
									}); 
									</script>';
									
			echo $tabla;			
}

function permisos()
{
$id_user=$_SESSION["logged_user"];	
global $database_conexion, $conex;

$pedidos_nef = "
SELECT tipo_usuario
FROM usuarios
WHERE id= '$id_user' ";
$resultado_pednef= mysql_query($pedidos_nef, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_pednef);
$total_pednef = mysql_num_rows($resultado_pednef);
if ($total_pednef > 0)
	{
		$permiso=$row['tipo_usuario'];
		
	}
	return $permiso;
}


?>

