<?php include("../data/conexion.php"); 
/* if ($_SESSION["logged_user"] <> ''){ header('Location: ../index.php'); } */
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////
$tipo_usuario = validar_usuario($_SESSION["logged_user"]); 

$Display = '';			
$display_empresas = display_empresas();
$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	//echo $id." ** ".id_empresa($_SESSION["logged_user"])."<br />"; 
};

if ($tipo_usuario == 3){
	// vendedor 
	$Display = 'vendor_style';
}
include("../displays/".$Display.".php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$id_pedido = '';
		$segundos = '';
		$folio = '';
		$total_pedido = '';
		  
	  if (isset($_POST['folio'])){
      $folio = $_POST['folio'];
      }
	  if (isset($_POST['total_pedido'])){
      $total_pedido = $_POST['total_pedido'];
      }
	  if (isset($_POST['id'])){
      $id_pedido = $_POST['id'];
      }
	  if (isset($_POST['segundos'])){
      $segundos = $_POST['segundos'];
      }
	  if ($id_pedido != ''){
	  
			busca_pedido($id_pedido,$segundos,$folio,$total_pedido,$tipo_usuario);
	  }
	  else
	  {
		  echo 0;
	  }
     function busca_pedido($id_pedido,$segundos,$folio,$total_pedido,$tipo_usuario){ 
global $database_conexion, $conex, $folio_tabla_mis_pedidos, $clave_lista_pedido_index, $nombre_articulo_lista_pedido_index, $cantidad_lista_pedido_index, $precio_unitario_lista_pedido_index, $total_lista_pedido_index ;
if ($segundos != '')
{
	sleep($segundos);
}
$texto_folio = "";
if ($folio == 0){
	$folio = "-";
}
else
{
	$texto_folio = '<h3>'.$folio_tabla_mis_pedidos.': '.$folio.'</h3>';
}

$select_traspaso ='';
$sql_traspasos = "SELECT folio_traspaso, id_pedido FROM pedido_traspaso WHERE id_pedido_cliente = '$id_pedido' AND estatus = '2'";
$res_traspasos = mysql_query($sql_traspasos, $conex) or die(mysql_error());
$total_rows_traspasos = mysql_num_rows($res_traspasos);
//$lista_traspasos = array();
if ($total_rows_traspasos > 0){
	$select_traspaso = '<select class=" form-control elementos_recibir">';
	 while($row_traspaso = mysql_fetch_array($res_traspasos,MYSQL_BOTH)) // html de articulos a mostrar
    {
		//$lista_traspasos[$row_traspaso['id_pedido']] = $row_traspaso['folio'];
		$select_traspaso .= '<option value="'.$row_traspaso['id_pedido'].'"> '.$row_traspaso['folio_traspaso'].'</option>';
	}
	$select_traspaso .= '</select>';
}

$ocultar_precio_unitario = '';
$ocultar_precio_total = '';
$ocultar_precio_total_global = '';
$estatus ='';	
$ocultar_btn_recibir ='';	

$consulta_lista = "SELECT pd.clave_empresa as clave_empresa, pd.id_articulo as id_articulo, pd.articulo as articulo, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.cantidad as cantidad, p.estatus as estatus
					FROM pedidos_det pd
					INNER JOIN pedidos p ON p.id = pd.id_pedido
					WHERE id_pedido = $id_pedido ";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$total_rows2 = mysql_num_rows($resultado_lista);
if ($total_rows2 > 0){
if ($tipo_usuario == 4){
	//tipo usuario almacenista no puede ver el td de totales
	//ni precios	
	$ocultar_precio_unitario = 'hidden';
	$ocultar_precio_total = 'hidden';
	$ocultar_precio_total_global = 'hidden';
}

$tabla = '<table id="pedido_det" class="table table-striped table-bordered table-hover table-responsive">
                    	<thead>
                            <tr class="info">
                                
                                <th>'.$clave_lista_pedido_index.'</th>
                                <th>'.$nombre_articulo_lista_pedido_index.'</th>
                                <th>'.$cantidad_lista_pedido_index.'</th>
                                <th class="elementos_recibir">Por Recibir</th>
                                <th '.$ocultar_precio_unitario.' >'.$precio_unitario_lista_pedido_index.'</th>
                                <th '.$ocultar_precio_total.' >'.$total_lista_pedido_index.'</th>
                                
                            </tr>
                        </thead><tbody>';
                            while($row2 = mysql_fetch_array($resultado_lista,MYSQL_BOTH)) // html de articulos a mostrar
                            {
                                
                            $estatus = $row2['estatus'];
							
                            
                             $tabla .= ' <tr>
                            
                            <td>'.$row2['clave_empresa'].'</td>
                            <td>'.$row2['articulo'].'</td>
                            <td>'.$row2['cantidad'].'</td>
                            <td class="elementos_recibir" id="td_art_'.$row2['id_articulo'].'">'.CantRecibir($row2['id_articulo'],$id_pedido).'</td>
                            
                            <td align="right" '.$ocultar_precio_unitario.' >$'.number_format($row2['precio_unitario'],2).' </td>
                            <td align="right" '.$ocultar_precio_total.' >$'.number_format($row2['precio_total'],2).'</td>
							</tr>
                                                ';
                                       
                                                
                            } 
							if ($estatus != 2){
								// ocultar boton para recibir el material si el estatus no es 2
								//$ocultar_btn_recibir = '$("#btn_recibir_tool").hide();';
								$ocultar_btn_recibir = '$(".elementos_recibir").hide();';
							}
                             $tabla .= ' 
                             </table>
                             <table>
                                <tr>
                                    <td class="" style="width:85%;">
                                    </td>
                                    <td '.$ocultar_precio_total_global.' class="" id="td_total_pedido" style="align:right;"><h4 style="text-align:right; width:180px; float:right;">Total = $'.number_format($total_pedido,2).'</h4>
                                    </td>
                                </tr>
                             </tbody></table>';

							$cabezera = ' <table class="col-lg-12 col-md-12">
							<tr>
								<td class="col-md-2" id="td_folio_pedido">'.$texto_folio.'
								</td>
								<td class="col-md-5 col-lg-7" id="">
								
								</td>
								<td class="col-md-2 " align="right" >
								<input type="button" class="btn btn-info elementos_recibir" value="Recive" id="btn_recibir_tool" onclick="recibir();"/>
								
								</td>
								<td  class="col-md-2 " >
									'.$select_traspaso.'
								</td>
							</tr>
							</table>';
                                 echo $cabezera.$tabla.'

                                <script>

                                $(document).ready( function () {
                                    $("#pedido_det").DataTable();
									'.$ocultar_btn_recibir.' 
                                } );

                                </script>

                                    ';

}  
 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No has agregado productos a tu pedido</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>





                    	
                    	
                