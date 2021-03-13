<?php include("../data/conexion.php"); 
/* if ($_SESSION["logged_user"] <> ''){ header('Location: ../index.php'); } */
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////
$Display = '';			
$display_empresas = display_empresas();
$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	//echo $id." ** ".id_empresa($_SESSION["logged_user"])."<br />"; 
};

include("../displays/".$Display.".php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$valor = '';
		$segundos = '';
		$almacen_id = '';
		  
	  if (isset($_POST['id_user'])){
      $valor = $_POST['id_user'];
      }
	  if (isset($_POST['segundos'])){
      $segundos = $_POST['segundos'];
      }
	  if (isset($_POST['almacen_id'])){
      $almacen_id = $_POST['almacen_id'];
      }
	  if ($valor != ''){
	  
			busca_pedido($valor,$segundos,$almacen_id);
	  }
	  else
	  {
		  echo 0;
	  }
     function busca_pedido($valor,$segundos,$almacen_id){ 
global $database_conexion, $conex, $msj_sin_pedidos_index, $remover_lista_pedido_index,$clave_lista_pedido_index,$nombre_articulo_lista_pedido_index, $cantidad_lista_pedido_index ,$precio_unitario_lista_pedido_index,$total_lista_pedido_index,$txt_pedido_index, $btn_ordenar_pedido, $btn_guardar_pendiente, $btn_aceptar_ordenar_pedido, $btn_remover_lista_pedido_index;
if ($segundos != '')
{
	sleep($segundos);
}

$minimo =1;

$consulta = "SELECT * FROM pedidos WHERE id_usuario = $valor and estatus = '0' and id_sucursal = '$almacen_id' ";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
$id_pedido = $row['id'];
$folio =  $row['folio'];


if ($total_rows > 0){ // con resultados

$consulta_lista = "SELECT * FROM pedidos_det WHERE id_pedido = $id_pedido ";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$total_rows2 = mysql_num_rows($resultado_lista);
if ($total_rows2 > 0){
	
$total_total = 0;
$div_btn_solicit_auto = '';
 // pondremos una funcion que buscara en la tabla requi_autorizacion el id_pedido = $id_pedido para comprobar si existe de ser asi pondra ver en el btn ver estatus autorizacion 
 $consulta_autorizaciones = "SELECT * FROM requi_autorizacion  WHERE id_pedido = $id_pedido ";
$resultado_auto = mysql_query($consulta_autorizaciones, $conex) or die(mysql_error());
$total_auto = mysql_num_rows($resultado_auto);
if ($total_auto > 0){
$div_btn_solicit_auto = '
<div class="row">
	<div class="col-xs-12" align="center">
<a href="#" class="text-primary"  onclick="detalle_autorizacion('.$id_pedido.');"> Ver estatus de autorizacion </a>
	</div>
</div> ';	
}
 
 $tipo_usuario = validar_usuario($_SESSION["logged_user"]);
echo '
'.$div_btn_solicit_auto.'
<table id="lista_pedido" class="table table-striped table-bordered table-hover responsive display">
                    <thead>
                    	<tr class="info">
                    	<th>'.$remover_lista_pedido_index.'</th>
                    	<th>'.$clave_lista_pedido_index.'</th>
                    	<th>'.$nombre_articulo_lista_pedido_index.'</th>
                    	<th>'.$cantidad_lista_pedido_index.'</th>';
						//**********precios solo para compradores *********************
						if ($tipo_usuario!=4){
						echo '<th>'.$precio_unitario_lista_pedido_index.'</th>
						<th>'.$total_lista_pedido_index.'</th>';
						}
						else {
						echo '<th hidden >'.$precio_unitario_lista_pedido_index.'</th>
						<th hidden >'.$total_lista_pedido_index.'</th>';
						}
						//*************************************************************
                    			
                    	echo '</tr>
                    </thead><tbody>';
                    	while($row2 = mysql_fetch_array($resultado_lista,MYSQL_BOTH)) // html de articulos a mostrar
                    	{
                    		$total_total = $total_total + $row2['precio_total'];
                    	
                    	
                    	 echo ' <tr>
                    			<td>
                    				<label>
                    					<input class="btn btn-primary" type="button" id="remover" onclick="remover_articulo_oc('.$row2['id'].');" value="'.$btn_remover_lista_pedido_index.'">
                    					</label>
                    			</td>
                    			<td>'.$row2['clave_empresa'].'</td>
                    			<td>'.$row2['articulo'].'</td>
                    			<td>
                    				<div class="input-spinner">
                    											<button type="button" class="btn btn-sm menos" id="menos_'.$row2['id'].'" onclick="restar2('.$row2['id'].')">-</button>
                    											<input type="number" id="txt_cantidad2_'.$row2['id'].'" size="10" class="input-sm cantidad_pedido" align="center" value="'.$row2['cantidad'].'" data-min="'.$minimo.'" />
                    											<button type="button" class="btn btn-sm mas" id="mas_'.$row2['id'].'" onclick="sumar2('.$row2['id'].')">+</button>
                    			</td>';
								if ($tipo_usuario!=4){
                    			echo '<td  align="right">$'.number_format($row2['precio_unitario'],2).' 
                    							<input type="hidden" id="txt_precio_'.$row2['id'].'" value="'.$row2['precio_unitario'].'"></td>
                    			<td  align="right" id="td_total_'.$row2['id'].'">$'.number_format($row2['precio_total'],2,".","").'
                    							<input type="hidden" id="txt_precio_total_'.$row2['id'].'" value="'.number_format($row2['precio_total'],2,".","").'"></td>
												
								</tr>';}
								else{
                    			echo '<td hidden align="right">$'.number_format($row2['precio_unitario'],2).' 
                    							<input type="hidden" id="txt_precio_'.$row2['id'].'" value="'.$row2['precio_unitario'].'"></td>
                    			<td hidden align="right" id="td_total_'.$row2['id'].'">$'.number_format($row2['precio_total'],2,".","").'
                    							<input type="hidden" id="txt_precio_total_'.$row2['id'].'" value="'.number_format($row2['precio_total'],2,".","").'"></td>
												
								</tr>';}
                    						
                    						
                    				
                    	
                    						
                    	}				
                    	 echo ' </tbody></table>
	<div class="row">
		<div class="col-sm-8 col-xs-3"></div>';
		if ($tipo_usuario!=4){
		echo '<div class="col-sm-4 col-xs-8"><p><h3 id="div_total_pedido">Total = $'.number_format($total_total,2,".","").'  </h3></p></div>';}
		
	echo '</div>
 <div class="row">
	<div class="col-sm-12">
	<a href="#" class="btn btn-primary" data-toggle="modal" onclick="seleccionar_opciones();">'.$btn_ordenar_pedido.'</a>
 <a href="#" class="btn btn-info" data-toggle="modal" onclick="guardar_carrito('.$id_pedido .');">'.$btn_guardar_pendiente.'</a>
	</div>
	</div>
 
 
 ';

    echo '

<script>

$(document).ready( function () {
    $("#lista_pedido").DataTable();
} );

</script>

    ';

 
 echo '<script>
				$("#txt_id_pedido_dir").val('.$id_pedido.');
				
				
					$(".cantidad_pedido").change(function(){
					var id = $(this).attr("id");
					var arr = id.split("_");
					id = arr[2];
					actualizar_precio(id);
                    });
				</script>
				';
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">'.$msj_sin_pedidos_index.'</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">'.$msj_sin_pedidos_index.'</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>





                    	
                    	
                