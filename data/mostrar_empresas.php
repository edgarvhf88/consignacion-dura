<?php include("conexion.php"); 

		$id_usuario = '';
				  
	  if (isset($_POST['depapues'])){
			mostrar_depapues();
      }
	  if (isset($_POST['id_referencia'])){
			list_empresas();
      }	
	  if (isset($_POST['id_referencia_cc'])){
			list_empresas_cc();
      }		
	  if (isset($_POST['id_referencia_cc_new'])){
			id_referencia_cc_new();
      }	
	  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
      }
	  if ($id_usuario != ''){
	  	mostrar_empresas();
	  }
	  else
	  {
		 // echo 0;
	  }
     function mostrar_empresas()
	 { 
global $database_conexion, $conex;

$minimo =1;

$consulta_empresas = "SELECT * FROM empresas";
$resultado = mysql_query($consulta_empresas, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados <th>Fecha de Alta</th>


echo '<div class="table-responsive " >
				                    <table id="mostrar_empresas" class="display table table-bordered table-hover">
				                    	<thead>
				                    		<tr class="info">
				                    			<th>Nombre Empresa</th>
				                    			<th>RFC</th>
				                    			
				                    			
				                    		</tr>
				                    	</thead><tbody>';
				                    		$tipo_usuario = '';						
				                    		while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar <td>'.$row['fecha_alta'].'</td>
				                    		{
				                    		
				                    		
				                    		 echo '<tr id="trempresa_'.$row['id_empresa'].'" class="lista_empresas ">
				                    				                		
				                    				            <td>'.$row['nombre'].'<input type="hidden" id="txtnombreempresa_'.$row['id_empresa'].'" value="'.$row['nombre'].'"/></td>
				                    				            <td>'.$row['rfc'].'<input type="hidden" id="txtrfcempresa_'.$row['id_empresa'].'" value="'.$row['rfc'].'"/></td>
				                    				            
				                    				            </tr>';
				                    						
				                    							
				                    		}				
				                    		 echo ' </tbody></table></div>';
  echo '<script> 
	$(document).ready(function(){
                $(".lista_empresas").dblclick(function(){
                               var tr_id = $(this).attr("id")
                         							   
							   var arr_id = tr_id.split("_");
							   var id_empresa = arr_id[1];
							   var nombre_empresa = document.getElementById("txtnombreempresa_"+id_empresa).value;
							   var rfc_empresa = document.getElementById("txtrfcempresa_"+id_empresa).value;
							   $("#txt_empresa").val(nombre_empresa);
							   $("#txt_rfc").val(rfc_empresa);
							   $("#txt_id_empresa").val(id_empresa);
							   jQuery("#modal_modificar_empresa .modal-header").html("Empresa: "+nombre_empresa);
							   $("#modal_modificar_empresa").modal("show");
							  
                });
				$(".lista_empresas").click(function(){
                               var tr_id = $(this).attr("id")
                         		$(".lista_empresas").removeClass("active");
                               $("#"+tr_id).addClass("active");					   
							   var arr_id = tr_id.split("_");
							   var id_empresa = arr_id[1];
							   var nombre_empresa = document.getElementById("txtnombreempresa_"+id_empresa).value;
							   
							   $("#txt_id_empresa").val(id_empresa);
							   jQuery("#modal_nueva_sucursal .modal-header").html("Nueva sucursal para empresa: "+nombre_empresa) ; 
							   
							   mostrar_sucursales();
                });
                $("#mostrar_empresas").DataTable();
				
		});
</script>'; 

 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No existen Empresas Registradas</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}
     function list_empresas()
	 { 
global $database_conexion, $conex;

$consulta_empresas = "SELECT * FROM empresas";
$resultado = mysql_query($consulta_empresas, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados <th>Fecha de Alta</th>


echo '<option value="0">Todos los Articulos </option>';
					
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
{

	echo '<option value="'.$row['id_empresa'].'">'.$row['nombre'].'</option>';
					
}				
} 
else /// sin resultados
{
	echo '<option value="0">Sin Empresas Registradas</option>';	
}
}
     function list_empresas_cc()
	 { 
global $database_conexion, $conex;

$consulta_empresas = "SELECT * FROM empresas";
$resultado = mysql_query($consulta_empresas, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados <th>Fecha de Alta</th>


echo '<option value="0">Todos los Centros de costo </option>';
					
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
{

	echo '<option value="'.$row['id_empresa'].'">'.$row['nombre'].'</option>';
					
}				
} 
else /// sin resultados
{
	echo '<option value="0">Sin Empresas Registradas</option>';	
}
}
     function id_referencia_cc_new()
	 { 
global $database_conexion, $conex;

$consulta_empresas = "SELECT * FROM empresas";
$resultado = mysql_query($consulta_empresas, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados <th>Fecha de Alta</th>



					
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
{

	echo '<option value="'.$row['id_empresa'].'">'.$row['nombre'].'</option>';
					
}				
} 
else /// sin resultados
{
	echo '<option value="0">Sin Empresas Registradas</option>';	
}
}

function mostrar_depapues(){ 
global $database_conexion, $conex;

$consulta_departamento = "SELECT * FROM departamentos";   ////      EDITANDO
$resultado = mysql_query($consulta_departamento, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados

echo '<div class="table-responsive col-md-5">
				                    <table class="table table-striped table-bordered table-hover">
				                    	<tr class="info">
				                    		<th>Departamentos</th>
				                    		</tr>';
$tipo_usuario = '';						
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
 echo '<tr>
		<td>'.$row['departamento'].'</td>
		</tr>';
}				
 echo ' </table></div>';
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No existen Departamentos Registrados</a></h3>
                        </div>
                    </div>
				</div>';		
}
echo '<div class=" col-md-2"></div>';

$consulta_puesto = "SELECT * FROM puestos";   ////      EDITANDO
$resultado2 = mysql_query($consulta_puesto, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado2);

if ($total_rows > 0){ // con resultados

echo '<div class="table-responsive col-md-5">
				                    <table class="table table-striped table-bordered table-hover">
				                    	<tr class="info">
				                    		<th>Puestos</th>
				                    		</tr>';
$tipo_usuario = '';						
while($row2 = mysql_fetch_array($resultado2,MYSQL_BOTH)) // html de articulos a mostrar
{
 echo '<tr>
		<td>'.$row2['puesto'].'</td>
		</tr>';
}				
 echo ' </table> </div>';
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No existen Departamentos Registrados</a></h3>
                        </div>
                    </div>
				</div>';		
}
}



?>





                    	
                    	
                