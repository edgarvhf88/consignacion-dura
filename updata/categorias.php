<?php include("../data/conexion.php"); 

 if ((isset($_POST['id_empresa'])) && (!isset($_POST['id_articulo']))){
      $id_empresa = $_POST['id_empresa'];
      mostrar_categorias($id_empresa);
	  }
 if ((isset($_POST['id_empresa'])) && (isset($_POST['id_articulo']))){
      $id_empresa = $_POST['id_empresa'];
      $id_articulo = $_POST['id_articulo'];
      mostrar_reg_categorias($id_empresa,$id_articulo);
	  }
	 

function mostrar_categorias($id_empresa) { // Lista de Categorias**********************************
global $database_conexion, $conex;

$query = "SELECT a.categoria as categoria, a.tipo as tipo, a.id_categoria as id_categoria
FROM categorias a
WHERE a.id_empresa = '$id_empresa'
			";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

echo '<ul>';
if ($totalRows > 0){
	
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){


$id = $row['id_categoria'];	
$categoria = $row['categoria'];

//echo '<button type="button" id="listacategorias_'.$id.'" class="list-group-item btn_categorias">'.$categoria.'</button>';

echo '<li class="list-group-item" >
		<div class="checkbox" ><label>
		<input type="checkbox" id="chkcat_'.$id.'"  name="chkcat_'.$id.'"  class="list_categorias"/> '.$categoria.'
		</label></div>
		</li>';


}
echo '<script> 
	$(document).ready(function(){
		$(".list_categorias").click(function(){			
					var categorias = document.getElementsByClassName("list_categorias");
		var item_list = "";
		var list_categorias_seleccionadas = new Array();
		for (var ii = 0; ii < categorias.length; ii++) {
		 	item_list = categorias[ii];
			var array_item_list = item_list.id.split("_");
			var id_categoria = array_item_list[1];
			var estatus_check = item_list.checked;
			list_categorias_seleccionadas.push(id_categoria+"_"+estatus_check);
		}
		$("#txt_categorias").val(list_categorias_seleccionadas);
					
});
});
</script>';
echo '<ul>'; 	
} 
else 
{
echo '<ul><li class="list-group-item" >
		<div class="checkbox" ><label>
		La empresa seleccionada no tiene Categorias
		</label></div>
		</li></ul>';
}	
	
mysql_free_result($resultado);  
}




?>