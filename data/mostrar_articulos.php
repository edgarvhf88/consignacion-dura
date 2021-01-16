<?php include("conexion.php"); 

		$id_empresa = '';
				  
	  if (isset($_POST['id_empresa'])){
      $id_empresa = $_POST['id_empresa'];
     	mostrar_usuarios($id_empresa);
	  }	
	 
     function mostrar_usuarios($id_empresa){ 
global $database_conexion, $conex;

if($id_empresa == 0){
$consulta_usuarios = "
					SELECT 
					a.id as id,
					a.id_empresa as id_empresa,
					a.clave_microsip as c_microsip,
					a.clave_empresa as c_empresa, 
					a.nombre as articulo, 
					a.descripcion as descip, 
					a.precio as precio, 
					a.src_img as imagen, 
					e.nombre as empresa,
					exis.min as min,
					exis.max as max,
					exis.reorden as reorden,
					exis.existencia_actual as existencia
					  FROM articulos a 
					  INNER JOIN empresas e on a.id_empresa = e.id_empresa	
					  LEFT JOIN existencias exis on exis.id_articulo = a.id ";	
}else {
$consulta_usuarios = "
					SELECT 
					a.id as id,
					a.id_empresa as id_empresa,
					a.clave_microsip as c_microsip,
					a.id_microsip as id_microsip,
					a.clave_empresa as c_empresa, 
					a.nombre as articulo, 
					a.descripcion as descip, 
					a.precio as precio, 
					a.src_img as imagen, 
					e.nombre as empresa,
					exis.min as min,
					exis.max as max,
					exis.reorden as reorden,
					exis.existencia_actual as existencia
					  FROM articulos a 
					  INNER JOIN empresas e on e.id_empresa = a.id_empresa
					  LEFT JOIN existencias exis on exis.id_articulo = a.id
					  WHERE a.id_empresa = '$id_empresa'";	
}

$resultado = mysql_query($consulta_usuarios, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados
echo '<div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
				                    <table id="mostrar_articulos" class="table table-fixed table-hover table-responsive" style="max-heigth:250px;">
				                    	<thead>
				                    		<tr class="info">
				                    			<th>Clave Empresa</th>
				                    			<th>Clave Microsip</th>
				                    			<th>Articulo</th>
				                    			<th>Descripcion</th>
				                    			<th>Empresa</th>
				                    			<th>Precio</th>
				                    			<th>Imagen</th>
				                    			<th>Min</th>
				                    			<th>Max</th>
				                    			<th>Reorden</th>
				                    			<th>Existencia</th>
				                    		</tr>
				                    	</thead><tbody id="tbody_articulos">';
				                    		$articulo = '';						
				                    		$descripcion = '';						
				                    		while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
				                    		{
											$id_empresa = $row2['id_empresa']; 	
									if ($row2['imagen'] != ""){
									$src_img = $row2['imagen'];	
									$ruta = "assets/images/productos/emp-".$id_empresa."/".$src_img;	
									}else{
									$src_img = "sin_imagen.jpg";
									$ruta = "assets/images/".$src_img;	}	
				                  	$articulo = str_replace(['"', "'"], '', $row2['articulo']);
				                  	$descripcion = str_replace(['"', "'"], '', $row2['descip']);
								
							if ($row2['min'] != ""){
							$min_val = $row2['min'];	
							}else{
							//$min_val = '<a href="#" id="art_min_'.$row2['id'].'" onclick=""> Asignar </a> ';
							//$min_val = '<input id="art_min_'.$row2['id'].'" type="text" value="0" size="5"/>';
							$min_val = '';
							}	
							if ($row2['max'] != ""){
							$max_val = $row2['max'];	
							}else{
							$max_val = '';
							}	
							if ($row2['reorden'] != ""){
							$reorden_val = $row2['reorden'];	
							}else{
							$reorden_val = '';
							}	
							if ($row2['existencia'] != ""){
							$existencia_val = $row2['existencia'];	
							}else{
							$existencia_val = '';
							}	
								
echo '<tr id="trarticulo_'.$row2['id'].'" class="lista_articulos">
	<td id="tdclaveempresa_'.$row2['id'].'">'.$row2['c_empresa'].'</td>
	<td id="tdclavemicrosip_'.$row2['id'].'">'.$row2['c_microsip'].'</td>
	<td id="tdarticulo_'.$row2['id'].'">'.$row2['articulo'].'</td>
	<td id="tdudm_'.$row2['id'].'">'.$row2['descip'].'</td>
	<td>'.$row2['empresa'].'</td>
	<td align="right" id="tdprecio_'.$row2['id'].'">'.$row2['precio'].'</td>
	<td><p href="#zoom">
	<img src="'.$ruta.'" width="50" heigth="50" id="imagen_'.$row2['id'].'" class="imagenes">
	<input id="art_c_empresa_'.$row2['id'].'" type="hidden" value="'.$row2['c_empresa'].'"/>
	<input id="art_c_microsip_'.$row2['id'].'" type="hidden" value="'.$row2['c_microsip'].'"/>
	<input id="art_id_microsip_'.$row2['id'].'" type="hidden" value="'.$row2['id_microsip'].'"/>
	<input id="art_articulo_'.$row2['id'].'" type="hidden" value="'.$articulo.'"/>
	<input id="art_descip_'.$row2['id'].'" type="hidden" value="'.$descripcion.'"/>
	<input id="art_empresa_'.$row2['id'].'" type="hidden" value="'.$id_empresa.'"/>
	<input id="art_precio_'.$row2['id'].'" type="hidden" value="'.$row2['precio'].'"/>
	<input id="art_imagen_'.$row2['id'].'" type="hidden" value="'.$row2['imagen'].'"/>
	<input id="art_min_'.$row2['id'].'" type="hidden" value="'.$min_val.'"/>
	<input id="art_max_'.$row2['id'].'" type="hidden" value="'.$max_val.'"/>
	<input id="art_reorden_'.$row2['id'].'" type="hidden" value="'.$reorden_val.'"/>
	<input id="art_existencia_'.$row2['id'].'" type="hidden" value="'.$existencia_val.'"/>
	</p></td>
	<td id="tdmin_'.$row2['id'].'">'.$min_val.'</td>
	<td id="tdmax_'.$row2['id'].'">'.$max_val.'</td>
	<td id="tdreorden_'.$row2['id'].'">'.$reorden_val.'</td>
	<td id="tdexistencia_'.$row2['id'].'">'.$existencia_val.'</td>
	
	</tr>';
				                    							
	}				
	echo ' </tbody></table></div></div></div>';
 
 echo '<script> 
	$(document).ready(function(){
                $(".lista_articulos").on("dblclick", function(){
                            var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_articulo = arr_id[1];
							
							var clave_empresa = document.getElementById("art_c_empresa_"+id_articulo).value;
							var clave_microsip = document.getElementById("art_c_microsip_"+id_articulo).value;
							var articulo = document.getElementById("art_articulo_"+id_articulo).value;
							var descripcion = document.getElementById("art_descip_"+id_articulo).value;
							var empresa = document.getElementById("art_empresa_"+id_articulo).value;
							var precio = document.getElementById("art_precio_"+id_articulo).value;
							var imagen = document.getElementById("art_imagen_"+id_articulo).value;
							var minimo = document.getElementById("art_min_"+id_articulo).value;
							var maximo = document.getElementById("art_max_"+id_articulo).value;
							var reorden = document.getElementById("art_reorden_"+id_articulo).value;
							var existencia = document.getElementById("art_existencia_"+id_articulo).value;
							
							$("#txt_id_articulo").val(id_articulo);
							$("#txt_clave_empresa").val(clave_empresa);
							$("#txt_clave_microsip").val(clave_microsip);	
							$("#txt_nombre_articulo").val(articulo);
							$("#txt_descripcion").val(descripcion);
							$("#select_art_empresa").val(empresa);
							$("#txt_precio").val(precio);
							$("#txt_min").val(minimo);
							$("#txt_max").val(maximo);
							$("#txt_reorden").val(reorden);
							$("#txt_existencia").val(existencia);
							
							$("#txt_imagen").val(imagen);
							jQuery("#modal_articulo .modal-header").html("Modificar datos de Articulo") ;
							
							$("#modal_articulo").modal("show");


							lista_categorias();
							lista_reg_categorias();
							   
                });


                 $(".imagenes").on("click", function(){
						 var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_articulo = arr_id[1];

						var imagen = document.getElementById("art_imagen_"+id_articulo).value;
						var empresa = document.getElementById("art_empresa_"+id_articulo).value;
						var precio = document.getElementById("art_precio_"+id_articulo).value;
						var clave_empresa = document.getElementById("art_c_empresa_"+id_articulo).value;
						var clave_microsip = document.getElementById("art_c_microsip_"+id_articulo).value;
						var articulo = document.getElementById("art_articulo_"+id_articulo).value;
						var descripcion = document.getElementById("art_descip_"+id_articulo).value;
						if (imagen != ""){
							 $("#imagen_max").attr("src","assets/images/productos/emp-"+empresa+"/max/"+imagen) ;
						}else{
							 $("#imagen_max").attr("src","assets/images/sin_imagen.jpg") ;
						}
					 

					  $("#max_precio").html(precio);
					  $("#nombre_articulo_detalle").html(articulo);
					  $("#clave_articulo_detalle").html(clave_empresa);
					  $("#descripcion_articulo_detalle").html(descripcion);

					$("#zoom").modal("show");

                 });
                 $("#mostrar_articulos").DataTable();
				
		});
</script>';  

 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No existe inventario, agrege articulos nuevos.</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>





                    	
                    	
                