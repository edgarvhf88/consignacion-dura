<?php include("conexion.php");

     
	  
	  if (isset($_POST['requisitores'])){
		  
		 mostrar_requisitores();
	  }
	  if (isset($_POST['vendedores'])){
		  
		 mostrar_vendedores();
	  }
	  if (isset($_POST['relaciones'])){
		  
		 mostrar_relaciones();
	  }

function mostrar_requisitores() { // Lista de Requisitores**********************************
global $database_conexion, $conex;

$query = "SELECT a.nombre as nombre_usuario, a.apellido as apellido, a.id as id_usuario, b.nombre as nombre_empresa FROM usuarios a
INNER JOIN empresas b on a.id_empresa = b.id_empresa 
WHERE a.tipo_usuario = '2' or a.tipo_usuario = '5' or a.tipo_usuario = '4' ";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);


if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){
$id = $row['id_usuario'];	
$requisitor = $row['nombre_usuario'].' '.$row['apellido'].' ('.$row['nombre_empresa'].')';

echo '<button type="button" id="listarequisitor_'.$id.'" class="list-group-item btn_requisitores">'.$requisitor.'</button>';
echo '<script> 
	$(document).ready(function(){
                $(".btn_requisitores").click(function(){
                               var boton_id = $(this).attr("id")
                               $(".btn_requisitores").removeClass("active");
                               $("#"+boton_id).addClass("active");
							   
							   
							   var arr_btn_id = boton_id.split("_");
							   var id_requisitor = arr_btn_id[1];
							   
							   $("#txt_id_requisitor").val(id_requisitor);
                });
				
		});
</script>'; 
}
	
} 
else 
{
	echo '<button type="button"  class="list-group-item ">No existen requisitores registrados</button>';
}	



	
mysql_free_result($resultado);  
}
function mostrar_relaciones() { // Lista de Requisitores**********************************
global $database_conexion, $conex;

$query = "SELECT a.nombre as nombre_requisitor, a.apellido as apellido_requisitor, r.id_relacion as id_relacion, b.nombre as nombre_vendedor, b.apellido as apellido_vendedor, c.nombre as nombre_empresa, d.nombre as empresa_vendor
FROM relaciones r
INNER JOIN usuarios a on r.id_requisitor = a.id 
INNER JOIN usuarios b on r.id_vendedor = b.id 
INNER JOIN empresas c on a.id_empresa = c.id_empresa 
INNER JOIN empresas d on b.id_empresa = d.id_empresa 
			";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
 
if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){


$id = $row['id_relacion'];	
$relacion = $row['nombre_requisitor'].' '.$row['apellido_requisitor'].' ('.$row['nombre_empresa'].') Atendido por '.$row['nombre_vendedor'].' '.$row['apellido_vendedor'].' ('.$row['empresa_vendor'].')';

echo '<button type="button" id="listarelacion_'.$id.'" class="list-group-item btn_relaciones">'.$relacion.'</button>';
echo '<script> 
	$(document).ready(function(){
               
				$(".btn_relaciones").click(function(){
                               var boton_id = $(this).attr("id")
                               $(".btn_relaciones").removeClass("active");
                               $("#"+boton_id).addClass("active");
							    var arr_btn_id = boton_id.split("_");
							   var id_relacion = arr_btn_id[1];
							   
							   $("#txt_id_relacion").val(id_relacion);
                });
});
</script>'; 
}
	
} 
else 
{
	echo '<button type="button"  class="list-group-item ">No existen asignaciones registradas</button>';
}	


	
mysql_free_result($resultado);  
}
function mostrar_vendedores() { // Lista de Vendedores**********************************
global $database_conexion, $conex;

$query = "SELECT a.nombre as nombre_usuario, a.apellido as apellido, a.id as id_usuario, b.nombre as nombre_empresa FROM usuarios a
INNER JOIN empresas b on a.id_empresa = b.id_empresa 
WHERE a.tipo_usuario = '3'
			";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);


if ($totalRows > 0){
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){


$id = $row['id_usuario'];	
$vendedor = $row['nombre_usuario'].' '.$row['apellido'].' ('.$row['nombre_empresa'].')';

echo '<button type="button" id="listavendedor_'.$id.'" class="list-group-item btn_vendedores">'.$vendedor.'</button>';
echo '<script> 
	$(document).ready(function(){
              				
				$(".btn_vendedores").click(function(){
                               var boton_id = $(this).attr("id")
                               $(".btn_vendedores").removeClass("active");
                               $("#"+boton_id).addClass("active");
							    var arr_btn_id = boton_id.split("_");
							   var id_vendedor = arr_btn_id[1];
							   
							   $("#txt_id_vendedor").val(id_vendedor);
                });
				
});
</script>'; 

}
	
} 
else 
{
echo '<button type="button"  class="list-group-item ">No existen vendedores registrados</button>';
}	



	
mysql_free_result($resultado);  
}

?>