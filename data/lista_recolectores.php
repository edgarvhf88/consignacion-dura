<?php   include("conexion.php"); 
 if (isset($_POST['id_usuario'])){
      $id_usuario = $_POST['id_usuario'];
      $id_empresa_seleccionada = $_POST['id_empresa_seleccionada'];
      lista_subordinados($id_usuario,$id_empresa_seleccionada);
	  }	



function lista_subordinados($id_usuario,$id_empresa_seleccionada){ 
global $database_conexion, $conex;
$id_empresa_registrada = id_empresa($id_usuario);
if ($id_empresa_registrada != $id_empresa_seleccionada)
{
	$id_empresa = $id_empresa_seleccionada;
}else{
	$id_empresa = $id_empresa_registrada;
}

$consulta_usuarios = "SELECT * FROM usuarios WHERE id_empresa = '$id_empresa' and tipo_usuario = '4'";
$resultado = mysql_query($consulta_usuarios, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados

echo '	<ul >';
				
while($row_recolectores = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id_recolector = $row_recolectores['id'];
$recolector = $row_recolectores['nombre'];
if ($id_usuario != $id_recolector){	
	$checked = '';
	
	if ($id_usuario != ""){
	// busca si esta registrada una relacion con el usuario y el supervisor	
	$consulta_reg_sub = "SELECT * FROM recolector_relacion WHERE id_comprador = '$id_usuario' and id_recolector = '$id_recolector'";
	$result_reg_sub = mysql_query($consulta_reg_sub, $conex) or die(mysql_error());
	//$row = mysql_fetch_assoc($result_reg_sub);
	$total_rows_reg_sub = mysql_num_rows($result_reg_sub);
	if ($total_rows_reg_sub > 0){ /// si encuentra el registro significa que puede supervisar los movimientos del usuario
	$checked = 'checked';	
	}
	}

echo '	
		<li class="list-group-item" >
		<div class="checkbox" ><label>
		<input type="checkbox" id="chkrec_'.$id_recolector.'" '.$checked.' class="list_recolectores"/> '.$recolector.'
		</label></div>
		</li>
		';				
}
}
				
echo '	</ul>
<script>
$(".list_recolectores").click(function(){
						var id = $(this).attr("id");
						var recolector = document.getElementsByClassName("list_recolectores");
						//console.log(recolector.length);
						 var valor = "";
						 for (var i = 0; i < recolector.length; i++) {
							 
							 valor = recolector[i];
							var valor_id = valor.id.split("_");
							//var valor_id_recolector = valor_id.split("_");
							console.log(valor_id[1]);
							console.log(valor.checked);
						}
   						//alert(recolector);
				
                });
				</script>';
 
} 
else /// sin resultados
{
	echo 'Sin Recolectores';		
}
}
?>