<?php include("conexion.php"); 

		$id_empresa = '';
				  
	  if (isset($_POST['id_empresa'])){
      $id_empresa = $_POST['id_empresa'];
	 
      
		mostrar_cc($id_empresa);
		}
     function mostrar_cc($id_empresa){ 
global $database_conexion, $conex;

if ($id_empresa == '0'){
$consulta_cc = "SELECT * FROM centro_costos order by nombre_cc asc";
}
else
{
$consulta_cc = "SELECT * FROM centro_costos WHERE id_empresa='$id_empresa' order by nombre_cc asc";	
}
$resultado = mysql_query($consulta_cc, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


echo '<div class="col-md-12">
		   <table id="mostrar_cc" class="table table-striped table-responsive table-bordered table-hover">
				        <thead>
				        	<tr class="info">
				        		<th>Centro de Costos</th>
				        		<th>Empresa</th>
				        
				        	</tr>
				        </thead><tbody>';
				        							
				        while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
				        	{
				        	
				        	
				        	 echo '<tr id="trcc_'.$row2['id_cc'].'" class="lista_centro_costos">

				        		<td>'.$row2['nombre_cc'].'</td>
				        	
				        		<td>'.EMPRESA_NOMBRE($row2['id_empresa']).'</td>
				        		
				        	</tr>';
				        							
				        	}				
				        	 echo ' </tbody></table></div>';
 
 echo '<script> 
	$(document).ready(function(){
                $(".lista_centro_costos").dblclick(function(){
                               var tr_id = $(this).attr("id")
                         							   
							   var arr_id = tr_id.split("_");
							   var id_cc = arr_id[1];
							   
							  $("#txt_cc_id").val(id_cc);
							   datos_cc(id_cc);
							   
							   
                });
                $("#mostrar_cc").DataTable();
				
		});
</script>';  

 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No existen Centros de Costos Registrados</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>