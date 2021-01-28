<?php include("conexion.php"); 

		$id_empresa = '';
		$id_inventario = '';
		$almacen_id = '';
				  
	  if (isset($_POST['id_empresa'])){
      $id_empresa = $_POST['id_empresa'];
      $id_inventario = $_POST['id_inventario'];
      $almacen_id = $_POST['almacen_id'];
     	list_art_almacen($id_empresa,$id_inventario,$almacen_id);
	  }	
	 
     function list_art_almacen($id_empresa,$id_inventario,$almacen_id){ 
global $database_conexion, $conex;

if($id_empresa == 0){
$consulta = "
					SELECT 
					a.id as id,
					a.id_empresa as id_empresa,
					a.clave_microsip as c_microsip,
					a.clave_empresa as c_empresa, 
					a.nombre as articulo, 
					a.descripcion as descip, 
					a.precio as precio, 
					a.unidad_medida as udm,  
					a.src_img as imagen,
					exis.min as min,
					exis.max as max,
					exis.reorden as reorden,
					exis.existencia_actual as existencia
					  FROM articulos a 
					  LEFT JOIN existencias exis on exis.id_articulo = a.id ";	
}else {
$consulta = "
					SELECT 
					a.id as id,
					a.id_empresa as id_empresa,
					a.clave_microsip as c_microsip,
					a.id_microsip as id_microsip,
					a.clave_empresa as c_empresa, 
					a.nombre as articulo, 
					a.descripcion as descip, 
					a.precio as precio, 
					a.unidad_medida as udm, 
					a.src_img as imagen, 
					exis.min as min,
					exis.max as max,
					exis.reorden as reorden,
					exis.existencia_actual as existencia
					  FROM articulos a 
					  LEFT JOIN existencias exis on exis.id_articulo = a.id 
					  WHERE a.id_empresa = '$id_empresa' and exis.almacen_id = '$almacen_id'";	
}
////  NOTA:: RECALCULARA EXISTENCIAS CON REGISTRO ESPEJO DE INVENTARIOS / -*/-* /-* /-*/ -/*

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados
echo '
<table id="lista_articulos_almacen" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-warning">
				
				<th>#DURA</th>
				<th>#ALLPART</th>
				<th>Articulo</th>
			<!--	<th>Unidad Medida</th>
				<th>Precio</th>
				<th>Imagen</th> 
				<th>Min</th>
				<th>Max</th>
				<th>Reorden</th> -->
				<th>Existencia Sistema</th>
				<th>Existencia Fisica</th>
				<th>Exist. Ult. Inv.</th>
				<th>Conteo</th>
				<th class="hidden"></th>
			</tr>
		</thead><tbody id="tbody_articulos_almacen">';
		$articulo = '';						
		$descripcion = '';
		$clase = "";
		$desc_clase = "";
		$clase_func = "";
				
		while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
		{
				$id_empresa = $row2['id_empresa']; 	
				$id_art = $row2['id'];
				$articulo_id = $row2['id_microsip'];
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
						//$min_val = '<a href="#" id="arti_min_'.$row2['id'].'" onclick=""> Asignar </a> ';
						//$min_val = '<input id="arti_min_'.$row2['id'].'" type="text" value="0" size="5"/>';
						$min_val = 0;
						}	
						if ($row2['max'] != ""){
						$max_val = $row2['max'];	
						}else{
						$max_val = 0;
						}	
						if ($row2['reorden'] != ""){
						$reorden_val = $row2['reorden'];	
						}else{
						$reorden_val = 0;
						}	
						if ($row2['existencia'] != ""){
						$existencia_val = $row2['existencia'];	
						}else{
						$existencia_val = 0;
						}	
						
						if (($reorden_val != 0) && ($max_val != 0) && ($min_val != 0))
						{	
							if ($existencia_val <= $reorden_val){
								if ($existencia_val <= $min_val){
									$clase = "danger"; /// URGENTE pedir	
									$desc_clase = "Urgente Surtir Material";
									$clase_func = "list_urgente";
									
								}else {
									$clase = "warning"; /// reordenar	
									$desc_clase = "Es Necesario Reordenar";
									$clase_func = "list_reorden";
								}
							}
							else if ($existencia_val > $max_val)
							{
								$clase = "info text-success";// sobreInventariado
								$desc_clase = "Este Articulo se encuentra Sobre-Inventariado";
								$clase_func = "list_sobreinventario";
								$check_box = '';
							}
							else
							{
								$clase = "success";// Se encuentra en buen estatus dentro de los parametros
								$desc_clase = "Articulo Dentro de los Parametros";
								$clase_func = "list_bien";
							}
						}
						else
						{ /// si el min,max,reorden = 0 , no se han especificador los puntos de reorden
							$clase = "light text-danger";// sobreInventariado
								$desc_clase = "Nesesita actualizar los datos de Minimo, Maximo y Reorden para ayudar a mantener en stock";
								$clase_func = "list_sinminmaxreo";
								$check_box = '';
						}
						//$unidades_pedidas = unidades_pedidas($row2['id'],$id_empresa);
						$unidades_contadas = unidades_contadas($row2['id'],$id_inventario);
						if ($unidades_contadas == "-"){
							
						}
				
			$total_cantidad_cobrada = cantidades_cobradas($id_art,$almacen_id);
			$total_consumido_nopagado = suma_diferencias($id_art,$almacen_id,1) - $total_cantidad_cobrada;
		if ($total_consumido_nopagado > 0){
			$existencia_actual = $existencia_val - $total_consumido_nopagado;	
		}
		else
		{
			$existencia_actual = $existencia_val;
		}	
		
		
			$arr = explode('_',ultimo_inv_art($id_art));
			if (count($arr) > 1){
			$ult_inv_act = $arr[0]; 
		$fecha_inventario = $arr[1]; 	
		}
		else
		{
			$ult_inv_act = ""; 
		$fecha_inventario = ""; 	
		}
			
														
		echo '<tr id="trarti_'.$id_art.'"  class="'.$clase.' '.$clase_func.' " title="'.$desc_clase.'" onclick="modal_conteo('.$id_art.')">
			
			<td  id="td_clave_empresa_'.$id_art.'">'.$row2['c_empresa'].'</td>
			<td id="td_clave_microsip_'.$id_art.'">'.$row2['c_microsip'].'</td>
			<td id="td_articulo_'.$id_art.'">'.$row2['articulo'].'</td>
			<!--<td id="td_descripcion_'.$id_art.'">'.$row2['udm'].'</td>
			<td align="right" id="td_precio_'.$id_art.'">'.$row2['precio'].'</td>
			<td><p href="#zoom">
			<img src="'.$ruta.'" width="50" heigth="50" id="imagen_'.$id_art.'" class="imagenes">
			
			</p></td>
			<td id="td_min_'.$id_art.'">'.$min_val.'</td>
			<td id="td_max_'.$id_art.'">'.$max_val.'</td>
			<td id="td_reorden_'.$id_art.'">'.$reorden_val.'</td>-->
			<td id="td_existencia_'.$id_art.'">'.$existencia_val.'</td>
			<td id="td_existenciafisica_'.$id_art.'">'.$existencia_actual.'</td>
			<td id="td_existenciafisica2_'.$id_art.'">'.$ult_inv_act.'</td>
			<td id="tdunidadescontadas_'.$id_art.'">'.$unidades_contadas.'</td>
			<td class="hidden">
			<input id="arti_c_empresa_'.$id_art.'" type="hidden" value="'.$row2['c_empresa'].'"/>
			<input id="arti_c_microsip_'.$id_art.'" type="hidden" value="'.$row2['c_microsip'].'"/>
			<input id="arti_id_microsip_'.$id_art.'" type="hidden" value="'.$row2['id_microsip'].'"/>
			<input id="arti_articulo_'.$id_art.'" type="hidden" value="'.$articulo.'"/>
			<input id="arti_udm_'.$id_art.'" type="hidden" value="'.$row2['udm'].'"/>
			<input id="arti_descip_'.$id_art.'" type="hidden" value="'.$descripcion.'"/>
			<input id="arti_empresa_'.$id_art.'" type="hidden" value="'.$id_empresa.'"/>
			<input id="arti_precio_'.$id_art.'" type="hidden" value="'.$row2['precio'].'"/>
			<input id="arti_imagen_'.$id_art.'" type="hidden" value="'.$row2['imagen'].'"/>
			<input id="arti_min_'.$id_art.'" type="hidden" value="'.$min_val.'"/>
			<input id="arti_max_'.$id_art.'" type="hidden" value="'.$max_val.'"/>
			<input id="arti_reorden_'.$id_art.'" type="hidden" value="'.$reorden_val.'"/>
			<input id="arti_existencia_'.$id_art.'" type="hidden" value="'.$existencia_val.'"/>
			<input id="arti_existenciafisica_'.$id_art.'" type="hidden" value="'.$existencia_actual.'"/>
			</td>
			
			</tr>
				';
				                    							
		}				
		echo ' </tbody></table>
';
 
 echo '<script> 
	$(document).ready(function(){
				$("#lista_articulos_almacen").DataTable({
						"order": [[ 1, "asc" ]]
					});
		
                $(".elemen_almacen_inv").on("click", function(){
                            var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_articulo = arr_id[1];
							//
							var clave_empresa = document.getElementById("arti_c_empresa_"+id_articulo).value;
							var clave_microsip = document.getElementById("arti_c_microsip_"+id_articulo).value;
							var articulo_id = document.getElementById("arti_id_microsip_"+id_articulo).value;
							var articulo = document.getElementById("arti_articulo_"+id_articulo).value;
							var descripcion = document.getElementById("arti_descip_"+id_articulo).value;
							var udm = document.getElementById("arti_udm_"+id_articulo).value;
							var empresa = document.getElementById("arti_empresa_"+id_articulo).value;
							var precio = document.getElementById("arti_precio_"+id_articulo).value;
							var imagen = document.getElementById("arti_imagen_"+id_articulo).value;
							var minimo = document.getElementById("arti_min_"+id_articulo).value;
							var maximo = document.getElementById("arti_max_"+id_articulo).value;
							var reorden = document.getElementById("arti_reorden_"+id_articulo).value;
							var existencia = document.getElementById("arti_existencia_"+id_articulo).value;
							var existencia_real = document.getElementById("arti_existenciafisica_"+id_articulo).value;
							//
							$("#txt_conteo_id_articulo").val(id_articulo);
							$("#txt_conteo_id_art_microsip").val(articulo_id);
							$("#ic_clave_cliente").html(clave_empresa);
							$("#ic_clave").html(clave_microsip);	
							$("#ic_nombre").html(articulo);
							$("#ic_unidad_medida").html(udm);
							//$("#select_art_empresa").val(empresa);
							//$("#txt_precio").val(precio);
							$("#ic_min").html(minimo);
							$("#ic_maximo").html(maximo);
							$("#ic_reorden").html(reorden);
							//$("#ic_existencia").html(existencia);
							$("#ic_existencia").html(existencia_real);
							
							//$("#txt_imagen").val(imagen);
							//	jQuery("#modal_conteo_inventario .modal-header").html("Modificar datos de Articulo") ;
							
							if (imagen != ""){
							 $("#imagen_inv").attr("src","assets/images/productos/emp-"+empresa+"/max/"+imagen) ;
							}else{
							 $("#imagen_inv").attr("src","assets/images/sin_imagen.jpg") ;
							}						
							$("#modal_conteo_inventario").modal("show");
							$("#txt_inv_cantidad_contada").val("");
							
							
							$("#modal_conteo_inventario").on("shown.bs.modal", function() {
								$("#txt_inv_cantidad_contada").focus();
								});
								unselect();
								validar_articulo_inventario(id_articulo);
							   
                });

			/* 
                 $(".imagenes").on("click", function(){
						 var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_articulo = arr_id[1];

						var imagen = document.getElementById("arti_imagen_"+id_articulo).value;
						var empresa = document.getElementById("arti_empresa_"+id_articulo).value;
						var precio = document.getElementById("arti_precio_"+id_articulo).value;
						var clave_empresa = document.getElementById("arti_c_empresa_"+id_articulo).value;
						var clave_microsip = document.getElementById("arti_c_microsip_"+id_articulo).value;
						var articulo = document.getElementById("arti_articulo_"+id_articulo).value;
						var descripcion = document.getElementById("arti_descip_"+id_articulo).value;
						if (imagen != ""){
							 $("#imagen_max").attr("src","assets/images/productos/emp-"+empresa+"/max/"+imagen) ;
						}else{
							 $("#imagen_max").attr("src","assets/images/sin_imagen.jpg") ;
						}
						
						var minimo = document.getElementById("arti_min_"+id_articulo).value;
						var maximo = document.getElementById("arti_max_"+id_articulo).value;
						var reorden = document.getElementById("arti_reorden_"+id_articulo).value;
						var existencia = document.getElementById("arti_existencia_"+id_articulo).value;

					  $("#max_precio").html("Precio: "+precio);
					  $("#nombre_articulo_detalle").html("Nombre: "+articulo);
					  $("#clave_articulo_detalle").html("Clave: "+clave_empresa);
					  $("#descripcion_articulo_detalle").html(descripcion);
					  $("#existencia_articulo_detalle").html("Existencia: "+existencia);
					  $("#min_articulo_detalle").html("Min: "+minimo);
					  $("#max_articulo_detalle").html("Max: "+maximo);
					  $("#reorden_articulo_detalle").html("Reorden: "+reorden);

					$("#zoom").modal("show");

                 }); */
			
				
				
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
