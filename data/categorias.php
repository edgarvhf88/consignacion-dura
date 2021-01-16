<?php include("conexion.php"); 

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


if ($totalRows > 0){
	
	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){


$id = $row['id_categoria'];	
$categoria = $row['categoria'];

echo '<button type="button" id="listacategorias_'.$id.'" class="list-group-item btn_categorias">'.$categoria.'</button>';


}
echo '<script> 
	$(document).ready(function(){
              				
				$(".btn_categorias").click(function(){
                               var boton_id = $(this).attr("id");
                               $(".btn_categorias").removeClass("active");
                               $("#"+boton_id).addClass("active");
							    var arr_btn_id = boton_id.split("_");
							   var id_categoria = arr_btn_id[1];
							   
							   $("#txt_id_categoria").val(id_categoria);
                });			
				$(".btn_categorias").dblclick(function(){
                               var boton_id = $(this).attr("id");
                               var categoria_txt = $(this).text();
                               $(".btn_categorias").removeClass("active");
                               $("#"+boton_id).addClass("active");
							   var arr_btn_id = boton_id.split("_");
							   var id_categoria = arr_btn_id[1];
							   var id_empresa = document.getElementById("select_art_empresa").value;
							   $("#txt_id_edit_categoria").val(id_categoria);
							   
							   jQuery("#modal_categoria .modal-header").html("Modificando Categoria") ;
							


								$("#modal_categoria").on("shown.bs.modal", function() {
								$("#txt_categoria").val(categoria_txt);
								$("#txt_categoria").focus();
								$("#select_cat_empresa").val(id_empresa);
								
								});
								$("#modal_categoria").modal("show");
                });
				
});
</script>'; 	
} 
else 
{
echo '<button type="button"  class="list-group-item ">no existen categorias</button>';
}	
	
mysql_free_result($resultado);  
}

function mostrar_reg_categorias($id_empresa, $id_articulo) { // Lista de registros de Categorias  ****************
global $database_conexion, $conex;

$query = "SELECT a.categoria as categoria, a.id_categoria as id_categoria, b.id_reg_categoria as id_reg_categoria
FROM registros_categorias b
INNER JOIN categorias a ON a.id_categoria = b.id_categoria
WHERE b.id_articulo = '$id_articulo' AND a.id_empresa = '$id_empresa'
			";
$resultado = mysql_query($query, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

	
if ($totalRows > 0){

	while ($row = mysql_fetch_array($resultado,MYSQL_BOTH)){


$id = $row['id_reg_categoria'];	
$categoria = $row['categoria'];

echo '<button type="button" id="listaregcategorias_'.$id.'" class="list-group-item btn_reg_categorias">'.$categoria.'</button>';


}
echo '<script> 
	$(document).ready(function(){
              				
				$(".btn_reg_categorias").click(function(){
                               var boton_id = $(this).attr("id")
                               $(".btn_reg_categorias").removeClass("active");
                               $("#"+boton_id).addClass("active");
							    var arr_btn_id = boton_id.split("_");
							   var id_categoria = arr_btn_id[1];
							   
							   $("#txt_id_reg_categoria").val(id_categoria);
                });
				
});
</script>'; 	
} 
else 
{
echo '<button type="button"  class="list-group-item ">sin asignacion de categoria</button>';
}	
echo '';	
mysql_free_result($resultado);  
}


?>