<?php include("conexion.php"); 

		$pais = '';
				  
	  if (isset($_POST['pais'])){
      $pais = $_POST['pais'];
      lista_paises();
	  }		  
	  if (isset($_POST['id_pais'])){
      $id_pais = $_POST['id_pais'];
      lista_estados($id_pais);
	  }
	  if ((isset($_POST['id_estado'])) && (isset($_POST['id_ciudad']))){
      $id_estado = $_POST['id_estado'];
      $id_ciudad = $_POST['id_ciudad'];
      lista_ciudad_select($id_estado,$id_ciudad);
	  }
	  if ((isset($_POST['id_estado'])) && (!isset($_POST['id_ciudad']))){
      $id_estado = $_POST['id_estado'];
      lista_ciudades($id_estado);
	  }
	  if (isset($_POST['id_empresa_usuario'])){
      $id_empresa_usuario = $_POST['id_empresa_usuario'];
      lista_sucursales_usuario($id_empresa_usuario);
	  }
	  if ((isset($_POST['id_empresa_usuario_multi'])) && (!isset($_POST['id_usuario']))){
      $id_empresa_usuario_multi = $_POST['id_empresa_usuario_multi'];
      lista_sucursales_usuario_multisucursal($id_empresa_usuario_multi);
	  }
	  if ((isset($_POST['id_empresa_usuario_multi'])) && (isset($_POST['id_usuario']))){
      $id_empresa_usuario_multi = $_POST['id_empresa_usuario_multi'];
      $id_usuario = $_POST['id_usuario'];
      lista_sucursales_usuario_multisucursal_user($id_empresa_usuario_multi,$id_usuario);
	  }
	  if (isset($_POST['id_empresa'])){
      $id_empresa = $_POST['id_empresa'];
      lista_sucursales($id_empresa);
	  }
	  if (isset($_POST['id_sucursal'])){
      $id_sucursal = $_POST['id_sucursal'];
      detalle_sucursal($id_sucursal);
	  }
	  
	  
	 
     function lista_paises(){ 
global $database_conexion, $conex;

$minimo =1;

$consulta_paises = "SELECT * FROM paises";
$resultado = mysql_query($consulta_paises, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


echo '<select class="form-control" name="" id="select_sucursal_pais">';	
				
while($row_pais = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id = $row_pais['id_pais'];
$pais = utf8_encode($row_pais['pais']);
if ($id == '147'){
 echo '<option value="'.$id.'" selected>'.$pais.'</option>';	
}
else{
 echo '<option value="'.$id.'" >'.$pais.'</option>';
}					
					
}				
 echo '</select>';
 
 echo '<script> 

	$(document).ready(function(){
				
                $("#select_sucursal_pais").change(function(){
                               var id_pais = document.getElementById("select_sucursal_pais").value;
                         		lista_estados(id_pais);
								 $("#div_sucursal_ciudad").html("<select class=\"form-control\" id=\"select_sucursal_ciudad\" disabled></select>");
								
                });
				
		});
</script>';  

 
} 
else /// sin resultados
{
	echo ' <option value="" > No existen Paises </option> ';		
		


}

}
     function lista_estados($id_pais){ 
global $database_conexion, $conex;

$minimo =1;

$consulta_estados = "SELECT * FROM estados WHERE id_pais = $id_pais";
$resultado = mysql_query($consulta_estados, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


echo '<select class="form-control" name="" id="select_sucursal_estado">';	
				
while($row_pais = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id = $row_pais['id_estado'];
$estado = $row_pais['estado'];

 echo '<option value="'.$id.'" >'.$estado.'</option>';
					
					
}				
 echo '</select>';
 
 echo '<script> 
	$(document).ready(function(){
                $("#select_sucursal_estado").change(function(){
                            var id_estado = document.getElementById("select_sucursal_estado").value;
                         	lista_ciudades(id_estado);
							
                });
				
		});
</script>';  

 
} 
else /// sin resultados
{
	echo ' <option value="" > No existen Estados </option> <script> $("#div_sucursal_ciudad").html("<select class=\"form-control\" id=\"select_sucursal_ciudad\" disabled></select>");</script>';		
		


}

}
     function lista_ciudades($id_estado){ 
global $database_conexion, $conex;

$minimo =1;

$consulta_estados = "SELECT * FROM region WHERE id_estado = $id_estado";
$resultado = mysql_query($consulta_estados, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


echo '<select name="" id="select_sucursal_ciudad" class="selectpicker form-control" data-show-subtext="true" data-live-search="true">';	
			
while($row_region = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{

$id_estado = $row_region['id_estado'];
$id = $row_region['id_region'];
$region = $row_region['region'];

if ($row_region['codigo_region'] != "")
{
$region = "(".$row_region['codigo_region'].") ".$row_region['region'];	
}

 echo '<option value="'.$id.'" >'.$region.'</option>';
					
					
}				
 echo '</select>';
 
 echo '<script> 
	$(document).ready(function(){
                $("#select_sucursal_ciudad").change(function(){
                            //var id_ciudad = document.getElementById("select_sucursal_ciudad").value;
                         	//lista_ciudad(id_ciudad);
                });
 			
		});
</script>';  

 
} 
else /// sin resultados
{
	echo ' <option value="" > No existen Ciudades </option>';		
		


}

}
function lista_ciudad_select($id_estado,$id_ciudad){ 
global $database_conexion, $conex;

$minimo =1;

$consulta_estados = "SELECT * FROM region WHERE id_estado = $id_estado";
$resultado = mysql_query($consulta_estados, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


echo '<select class="form-control" name="" id="select_sucursal_ciudad">';	
				
while($row_region = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id_estado = $row_region['id_estado'];
$id = $row_region['id_region'];
$region = $row_region['region'];

if ($row_region['codigo_region'] != "")
{
$region = "(".$row_region['codigo_region'].") ".$row_region['region'];	
}

if ($id == $id_ciudad){	$select = "selected"; } else { $select =""; }
					
	echo '<option value="'.$id.'" '.$select.'>'.$region.'</option>';				
}				
 echo '</select>';
 
 
} 
else /// sin resultados
{
	echo ' <option value="" > No existen Ciudades </option>';		
		


}

}

function lista_sucursales($id_empresa){ 
global $database_conexion, $conex;

$minimo =1;

$consulta_sucursales = "SELECT * FROM sucursales WHERE id_empresa = $id_empresa";
$resultado = mysql_query($consulta_sucursales, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


echo '<div class="table-responsive " >
				                    <table class="table table-striped table-bordered table-hover">
				                    	<tr class="info">
				                    		<th>Sucursales</th>
				                    		</tr>';
				
while($row_sucursal = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id = $row_sucursal['id_sucursal'];
$sucursal = $row_sucursal['sucursal'];


 echo '<tr id="trsucursal_'.$id.'" class="lista_sucursales">
				<td>'.$sucursal.'</td>
		</tr>';					
					
}				
  echo ' </table></div>';
  echo '<script> 
	$(document).ready(function(){
                $(".lista_sucursales").click(function(){
                               var tr_id = $(this).attr("id")
                         							   
							   var arr_id = tr_id.split("_");
							   var id_sucursal = arr_id[1];
							   //alert("sucursal "+id_sucursal);
							   $("#txt_id_sucursal").val(id_sucursal);
							   datos_sucursal(id_sucursal);
                });
				
		});
</script>'; 
 


 
} 
else /// sin resultados
{
	echo '<b> Esta empresa no tiene sucursales registradas </b>';		
		


}

}

function lista_sucursales_usuario($id_empresa){ 
global $database_conexion, $conex;

$consulta_sucursales = "SELECT * FROM sucursales WHERE id_empresa = '$id_empresa'";
$resultado = mysql_query($consulta_sucursales, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


				
while($row_sucursal = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id = $row_sucursal['id_sucursal'];
$sucursal = $row_sucursal['sucursal'];
				
echo '<option value="'.$id.'" >'.$sucursal.'</option>';					
}				
 


 
} 
else /// sin resultados
{
	echo '<option value="0" >Sin Sucursales</option>';		
		


}

}
function lista_sucursales_usuario_multisucursal($id_empresa){ 
global $database_conexion, $conex;

$consulta_sucursales = "SELECT * FROM sucursales WHERE id_empresa = '$id_empresa'";
$resultado = mysql_query($consulta_sucursales, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados

echo '	<ul >';
				
while($row_sucursal = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
$id = $row_sucursal['id_sucursal'];
$sucursal = $row_sucursal['sucursal'];
				
//echo '<option value="'.$id.'" >'.$sucursal.'</option>';	
echo '	
		<li class="list-group-item" >
		<div class="checkbox" ><label>
		<input type="checkbox" id="chksuc_'.$id.'"  class="list_sucursales"/> '.$sucursal.'
		</label></div>
		</li>
		';				
}				
echo '	</ul>
<script>
$(".list_sucursales").click(function(){
						var id = $(this).attr("id");
						var sucursal = document.getElementsByClassName("list_sucursales");
						//console.log(sucursal.length);
						 var valor = "";
						 for (var i = 0; i < sucursal.length; i++) {
							 
							 valor = sucursal[i];
							var valor_id = valor.id.split("_");
							//var valor_id_sucursal = valor_id.split("_");
							console.log(valor_id[1]);
							console.log(valor.checked);
						}
   						//alert(sucursal);
				
                });
				</script>';
 
} 
else /// sin resultados
{
	echo '<option value="0" >Sin Sucursales</option>';		
}
}
function lista_sucursales_usuario_multisucursal_user($id_empresa,$id_usuario){ 
global $database_conexion, $conex;

$consulta_sucursales = "SELECT * FROM sucursales WHERE id_empresa = '$id_empresa'";
$resultado = mysql_query($consulta_sucursales, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados

echo '	<ul >';
				
while($row_sucursal = mysql_fetch_array($resultado,MYSQL_BOTH)) 
{
$id = $row_sucursal['id_sucursal'];
$sucursal = $row_sucursal['sucursal'];
$checked = '';
	// busca si esta registrada una sucursal con el usuario seleccionado
	$consulta_reg_suc = "SELECT * FROM registros_sucursales WHERE id_sucursal = '$id' and id_usuario = '$id_usuario'";
	$result_reg_suc = mysql_query($consulta_reg_suc, $conex) or die(mysql_error());
	//$row = mysql_fetch_assoc($result_reg_suc);
	$total_rows_reg_suc = mysql_num_rows($result_reg_suc);
	if ($total_rows_reg_suc > 0){ ///si encuentra el registro significa que tiene permitido el uso de la sucursal y se marcara el checked
	$checked = 'checked';	
	}	
	
//echo '<option value="'.$id.'" >'.$sucursal.'</option>';	
echo '	
		<li class="list-group-item" >
		<div class="checkbox" ><label>
		<input type="checkbox" id="chksuc_'.$id.'" '.$checked.' class="list_sucursales"/> '.$sucursal.'
		</label></div>
		</li>
		';				
}				
echo '	</ul>
<script>
$(".list_sucursales").click(function(){
						var id = $(this).attr("id");
						var sucursal = document.getElementsByClassName("list_sucursales");
						//console.log(sucursal.length);
						 var valor = "";
						 for (var i = 0; i < sucursal.length; i++) {
							 
							 valor = sucursal[i];
							var valor_id = valor.id.split("_");
							//var valor_id_sucursal = valor_id.split("_");
							console.log(valor_id[1]);
							console.log(valor.checked);
						}
   						//alert(sucursal);
				
                });
				</script>';
 
} 
else /// sin resultados
{
	echo '<option value="0" >Sin Sucursales</option>';		
}
}

function detalle_sucursal($id_sucursal){ 
global $database_conexion, $conex;

$minimo =1;

$consulta_sucursales = "SELECT * FROM sucursales WHERE id_sucursal = $id_sucursal";
$resultado = mysql_query($consulta_sucursales, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados
$id = $row['id_sucursal'];
$sucursal = $row['sucursal'];
$direccion = str_replace("\n", "@", $row['direccion']);
$pais = $row['id_pais'];
$estado = $row['id_estado'];
$ciudad = $row['id_ciudad'];
$cp = $row['codigo_postal'];
$uso_suc = $row['uso_suc'];
$uso_fac = $row['uso_dir_fac'];
if($uso_fac == "0"){
	$check_fac = '$("#chk_dir_fac").prop("checked",false);';
}
else if($uso_fac == "1"){
	$check_fac = '$("#chk_dir_fac").prop("checked",true);';
}
if($uso_suc == "0"){
	$check_suc = '$("#chk_dir_suc").prop("checked",false);';
}else if($uso_suc == "1"){
	$check_suc = '$("#chk_dir_suc").prop("checked",true);';
}



  echo '<script> 
  $(document).ready(function(){
	  
	var dir =  "'.$direccion.'"; 
	var direccion = dir.replace(/@/g, "\n");
	
  $("#txt_sucursal").val("'.$sucursal.'");
  $("#txtarea_direccion").html(direccion);
  $("#select_sucursal_pais").val("'.$pais.'");
  $("#select_sucursal_estado").val("'.$estado.'");
  $("#txt_id_sucursal").val("'.$id.'");
  lista_ciudad_select("'.$estado.'","'.$ciudad.'");
	'.$check_fac.'
	'.$check_suc.'
  $("#txt_codigo_postal").val("'.$cp.'");
  $("#modal_nueva_sucursal").modal("show");  
  
	
                
				
		});
</script>'; 
 


 
}

}




?>