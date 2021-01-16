<?php include("constructor.php"); 
 /* if ($_SESSION["logged_user"] <> ''){ header('Location: ../index.php'); } */ 
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////
$Display = '';			
$display_empresas = display_empresas();
$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
$tipo_usuario = validar_usuario($_SESSION["logged_user"]); 
foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	//echo $id." ** ".id_empresa($_SESSION["logged_user"])."<br />"; 
};

include("../displays/".$Display.".php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$valor = '';
		$almacen_id = '';
		  
	  if (isset($_POST['valor'])){
      $valor = $_POST['valor'];
      $id_categoria = $_POST['id_categoria'];
      $almacen_id = $_POST['almacen_id'];
      
	  
			busca_articulo($valor,$id_empresa_user_activo, $id_categoria, $almacen_id,$tipo_usuario);
	  }
function cantidad_pedidas($id_articulo){
		global $conex;
				
		$consulta = "SELECT SUM(pd.cantidad) as cantidad
		FROM pedidos_det pd
		INNER JOIN pedidos p on p.id = pd.id_pedido
		WHERE pd.id_articulo = '$id_articulo' 
		AND p.estatus <> '0' 
		AND p.estatus <> '3' ";  // estatus diferente a abieto y surtido
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$cantidad = 0; // la cantidad que esta en pedidos ordenados y en proceso
		
		if ($total_rows > 0)
		{ // con resultados --> actualiza el total
			$cantidad = $row['cantidad'];
		}  
		return $cantidad;
	}	
	
     function busca_articulo($valor,$id_empresa_user_activo, $id_categoria, $almacen_id, $tipo_usuario){ 
global $database_conexion, $conex, $resultados_busqueda_index, $btn_agregar_articulo_index, $modal_msj_agregar_articulo_index, $btn_ir_orden_index, $modal_titulo_agregar_articulo_index, $sin_resultados_busqueda, $modal_btn_continuar_index;

if (($id_categoria == "0") && ($valor !="")){

$consulta = "SELECT a.nombre as nombre, a.descripcion as descripcion, a.precio as precio, a.clave_empresa as clave_empresa, a.id as id, a.src_img as src_img, exis.min as minimo, exis.max as maximo , exis.reorden as reorden , exis.existencia_actual as existencia 
FROM articulos a
LEFT JOIN registros_categorias r on r.id_articulo = a.id
LEFT JOIN existencias exis on exis.id_articulo = a.id
WHERE a.nombre LIKE  '%$valor%' and a.id_empresa='$id_empresa_user_activo' and exis.almacen_id='$almacen_id' 
OR a.clave_empresa LIKE  '%$valor%' and a.id_empresa='$id_empresa_user_activo' and exis.almacen_id='$almacen_id'";
}
else if (($id_categoria != "0") && ($valor !="")){
$consulta = "SELECT a.nombre as nombre, a.descripcion as descripcion, a.precio as precio, a.clave_empresa as clave_empresa, a.id as id, a.src_img as src_img, exis.min as minimo, exis.max as maximo , exis.reorden as reorden , exis.existencia_actual as existencia 
FROM articulos a
LEFT JOIN registros_categorias r on r.id_articulo = a.id
LEFT JOIN existencias exis on exis.id_articulo = a.id
WHERE a.nombre LIKE  '%$valor%' and a.id_empresa='$id_empresa_user_activo' and r.id_categoria='$id_categoria' and exis.almacen_id='$almacen_id' 
OR a.clave_empresa LIKE  '%$valor%' and a.id_empresa='$id_empresa_user_activo' and r.id_categoria='$id_categoria' and exis.almacen_id='$almacen_id'";
	
}
else if (($id_categoria != "0") && ($valor =="")){
$consulta = "SELECT a.nombre as nombre, a.descripcion as descripcion, a.precio as precio, a.clave_empresa as clave_empresa, a.id as id, a.src_img as src_img, exis.min as minimo, exis.max as maximo , exis.reorden as reorden , exis.existencia_actual as existencia 
FROM articulos a
LEFT JOIN registros_categorias r on r.id_articulo = a.id
LEFT JOIN existencias exis on exis.id_articulo = a.id
WHERE a.id_empresa='$id_empresa_user_activo' and r.id_categoria='$id_categoria' and exis.almacen_id='$almacen_id'";	
}
else if (($id_categoria == "0") && ($valor =="")){
$consulta = "SELECT a.nombre as nombre, a.descripcion as descripcion, a.precio as precio, a.clave_empresa as clave_empresa, a.id as id, a.src_img as src_img, exis.min as minimo, exis.max as maximo , exis.reorden as reorden , exis.existencia_actual as existencia 
FROM articulos a
LEFT JOIN registros_categorias r on r.id_articulo = a.id
LEFT JOIN existencias exis on exis.id_articulo = a.id
WHERE a.id_empresa='$id_empresa_user_activo' and exis.almacen_id='$almacen_id'";	
}


/* $consulta = "SELECT * FROM articulos WHERE nombre LIKE  '%$valor%' and id_empresa='$id_empresa_user_activo' OR clave_empresa LIKE  '%$valor%' and id_empresa='$id_empresa_user_activo'"; */


$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados

$minimo = 1;

 
$count = 0;
$descripcion ="";
/* echo '<header>
                    <h2><span class="icon-pages"></span>'.$resultados_busqueda_index.'</h2>                    
                </header>'; */





while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
	$count++;
if ($count == 4){
	echo '<div class="row"> ';
}
if ($row['descripcion'] != ''){
	$descripcion = '<p>'.$row['descripcion'].'</p>';
} 
else 
{
$descripcion = "";	
}
$nombre_abre = "";
if (strlen($row['nombre']) > 50){
$nombre_abre = substr($row['nombre'], 0, 50).'...';	
} else {
$nombre_abre = $row['nombre'];
}
if ($row['precio'] != ""){
$precio_sin_coma = str_replace(",","",$row['precio']);	
$precio_format = number_format($precio_sin_coma, 2);		
}else{ $precio_format = "";	}

if ($row['src_img'] != ""){
$src_img = $row['src_img'];	
$ruta = "assets/images/productos/emp-".$id_empresa_user_activo."/".$src_img;	
}else{
$src_img = "sin_imagen.jpg";
$ruta = "assets/images/".$src_img;	}	

if ($row['existencia'] != ""){
	
$existencia = $row['existencia']; // - cantidad_pedidas($row['id']);	
$existencia = number_format($existencia, 2);		
}else{ $existencia = "0";	}
if ($tipo_usuario == 4){
	$precio_addbtn = '';
}else{
	$precio_addbtn = '<p>$'.$precio_format.'</p>
								<input id="art_precio_'.$row['id'].'" type="hidden" value="'.$row['precio'].'"/> 
								<!----><p>
									<div class="input-spinner">
										<button type="button" class="btn btn-sm menos" id="menos_'.$row['id'].'" onclick="restar('.$row['id'].')">-</button>
										<input type="number" id="txt_cantidad_'.$row['id'].'" size="10" class="input-sm cant_resul_busq" align="center" value="'.$minimo.'" data-min="'.$minimo.'"/>
										<button type="button" class="btn btn-sm mas" id="mas_'.$row['id'].'" onclick="sumar('.$row['id'].')">+</button>
										
										
								</div></p>
						<p><button type="button" onclick="agregar_articulo_oc('.$row['id'].');" id="btn_add_oc" class="btn btn-primary btn-sm col-md-10"  data-toggle="modal">'.$btn_agregar_articulo_index.'</button>
							
							</p> ';

}


 echo '  <div class="col-sm-6 col-md-3">
                        <div class="topics-list">
                           
                            
                                <p><img src="'.$ruta.'" width="238" height="188" id="imagen_'.$row['id'].'" class="imagenes"></p>
                                <p><b>'.$row['clave_empresa'].'</b></p>
                                
                                <div style="height: 2em">
                                    <h5 data-toggle="tooltip" data-placement="bottom" title="'.$row['nombre'].'">'.$nombre_abre.'</h5>
                                </div>
								<p>Stock = '.$existencia.'</p>
                                '.$precio_addbtn.'
                            <input id="art_imagen_'.$row['id'].'" type="hidden" value="'.$src_img.'"/>
                            <input id="art_empresa_'.$row['id'].'" type="hidden" value="'.$id_empresa_user_activo.'"/>
                            <input id="art_precio_format_'.$row['id'].'" type="hidden" value="'.$precio_format.'"/>
                            <input id="art_nombre_'.$row['id'].'" type="hidden" value="'.$row['nombre'].'"/>
                            <input id="art_descripcion_'.$row['id'].'" type="hidden" value="'.$row['descripcion'].'"/>
                            <input id="art_existencia_'.$row['id'].'" type="hidden" value="'.str_replace(",","",$row['existencia']).'"/>
                            <input id="art_min_'.$row['id'].'" type="hidden" value="'. number_format($row['minimo'],2).'"/>
                            <input id="art_max_'.$row['id'].'" type="hidden" value="'. number_format($row['maximo'],2).'"/>
                            <input id="art_reorden_'.$row['id'].'" type="hidden" value="'. number_format($row['reorden'],2).'"/>

                        </div>
                    </div>
					
					';


					
					
if ($count == 4){
	echo '</div>';
$count = 0;	
}
					
}	// while end	

if ($tipo_usuario == 4){
	$precio_addbtn_mdl = '';
}else{
	$precio_addbtn_mdl = '<p class="h4" > Precio: <span id="max_precio"></span>    </p><p>
										<div class="input-spinner">
											<button type="button" class="btn btn-sm menos" >-</button>
										<input type="number" id="txt_cantidad3" size="10" class="input-sm cant_preview_busq" align="center" value="1" data-min="" />
											<button type="button" class="btn btn-sm mas">+</button>
											
											
										</div>
								</p>
								<p>
										<button type="button" onclick="agregar_articulo_oc2();" id="btn_add_oc2" class="btn btn-primary btn-sm col-md-10"  data-toggle="modal" data-dismiss="modal">'.$btn_agregar_articulo_index.'</button>
										<input type="hidden" id="id_articulo_modal" value="" />
                            
                                </p>';
}		
echo '';

    echo '<!-- Modal zoom -->
    <div class="modal fade" id="zoom" tabindex="-1" role="dialog" aria-labelledby="Modal zoom" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> 
            <div class="modal-content">
                <!-- contenido -->
                <div class="modal-body">

                        <div class="row">
                            <div class="col-md-7 col-sm-12">
                               <img width="458" height="458" src="assets/images/productos/" id="imagen_max">
                            </div>
                            <div class="col-md-5 col-sm-12">
								<h3 id="h_nombre"></h3>
								<div height="50%">
									<p id="descripcion"> </p>
								</div>
								
								<p class="h4" > Stock: <span id="span_existencia"></span>  </p>
								<p class="h4" >	Min: <span id="span_minimo"></span> </p>
								<p class="h4" >Max: <span id="span_maximo"></span>  </p>
								<p class="h4" > Reorder: <span id="span_reorden"></span> </p>
							<!-- -->'.$precio_addbtn_mdl.' 
                            </div>
                        </div>
                </div>

            </div>
        </div>                        
    </div>';                				
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">'.$sin_resultados_busqueda.'</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}
echo '<script>
        $(document).ready(function(){
            $(".imagenes").click(function(){
                         var tr_id = $(this).attr("id")
                            var arr_id = tr_id.split("_");
                            var id_articulo = arr_id[1];

                        var imagen = document.getElementById("art_imagen_"+id_articulo).value;
                        var empresa = document.getElementById("art_empresa_"+id_articulo).value;
                        var precio = document.getElementById("art_precio_format_"+id_articulo).value;
                        var existencia = document.getElementById("art_existencia_"+id_articulo).value;
                        var minimo = document.getElementById("art_min_"+id_articulo).value;
                        var maximo = document.getElementById("art_max_"+id_articulo).value;
                        var reorden = document.getElementById("art_reorden_"+id_articulo).value;
                        var nombre = document.getElementById("art_nombre_"+id_articulo).value;
                        var descripcion = document.getElementById("art_descripcion_"+id_articulo).value;

                      $("#imagen_max").attr("src","assets/images/productos/emp-"+empresa+"/max/"+imagen) ;

                      $("#max_precio").html("$"+precio);
                      $("#span_existencia").html(existencia);
                      $("#span_minimo").html(minimo);
                      $("#span_maximo").html(maximo);
                      $("#span_reorden").html(reorden);

                      $("#h_nombre").html(nombre);

                      $("#descripcion").html(descripcion);
                        $("#id_articulo_modal").val(id_articulo);

                    $("#zoom").modal("show");

                 });

                 $(".menos").click(function(){
                          restar3();
                });
                $(".mas").click(function(){
                       sumar3();

                });
				$(".cant_resul_busq").change(function(){
					var id = $(this).attr("id");
					//var arr = id.split("_");
					//id = arr[2];
					var valor = document.getElementById(id).value;
					if (valor <= 0){
						//console.log("Funcionando"+valor);
						$("#"+id).val("1");
					}
					
                });
				$(".cant_preview_busq").change(function(){
					var id = $(this).attr("id");
					//var arr = id.split("_");
					//id = arr[2];
					var valor = document.getElementById(id).value;
					if (valor <= 0){
						//console.log("Funcionando"+valor);
						$("#"+id).val("1");
					}
					
                });
    
            });


        </script>';




?>