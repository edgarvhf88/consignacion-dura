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

$consulta_usuarios = "SELECT * FROM usuarios WHERE id_empresa = '$id_empresa' and tipo_usuario <> '4' and tipo_usuario <> '5'";
$resultado = mysql_query($consulta_usuarios, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados

echo '	<ul >';
				
while($row_subordinados = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
	
$id_subordinado = $row_subordinados['id'];
$subordinado = $row_subordinados['nombre'];
if ($id_usuario != $id_subordinado){	
	$checked = '';
	
	if ($id_usuario != ""){
	// busca si esta registrada una relacion con el usuario y el supervisor	
	$consulta_reg_sub = "SELECT * FROM supervisor_relacion WHERE id_supervisor = '$id_usuario' and id_subordinado = '$id_subordinado'";
	$result_reg_sub = mysql_query($consulta_reg_sub, $conex) or die(mysql_error());
	//$row = mysql_fetch_assoc($result_reg_sub);
	$total_rows_reg_sub = mysql_num_rows($result_reg_sub);
	if ($total_rows_reg_sub > 0){ ///si encuentra el registro significa que puede supervisar los movimientos del usuario
	$checked = 'checked';	
	}
	}

echo '	
		<li class="list-group-item" >
		<div class="checkbox" ><label>
		<input type="checkbox" id="chksub_'.$id_subordinado.'" '.$checked.' class="list_subordinados"/> '.$subordinado.'
		</label></div>
		</li>
		';				
}
}				
echo '	</ul>
<script>
$(".list_subordinados").click(function(){
						var id = $(this).attr("id");
						var subordinado = document.getElementsByClassName("list_subordinados");
						//console.log(subordinado.length);
						 var valor = "";
						 for (var i = 0; i < subordinado.length; i++) {
							 
							 valor = subordinado[i];
							var valor_id = valor.id.split("_");
							//var valor_id_subordinado = valor_id.split("_");
							console.log(valor_id[1]);
							console.log(valor.checked);
						}
   						//alert(subordinado);
				
                });
				</script>';
 
} 
else /// sin resultados
{
	echo '<option value="0" >Sin subordinado</option>';		
}
}
?>