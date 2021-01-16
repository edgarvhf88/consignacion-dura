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
	  
			busca_pedido($id_pedido,$segundos,$folio,$total_pedido);
	  }
	  else
	  {
		  echo 0;
	  }
     function busca_pedido($id_pedido,$segundos,$folio,$total_pedido){ 
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
	$texto_folio = '<h3> Num#: '.$folio.'</h3>';
}

$minimo =1;

$consulta_lista = "SELECT * FROM pedido_traspaso_det WHERE id_pedido = $id_pedido ";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$total_rows2 = mysql_num_rows($resultado_lista);
if ($total_rows2 > 0){
	
echo ' <table>
	<tr>
		<td class="col-md-2" id="td_folio_pedido">'.$texto_folio.'
		</td>
		
		
	</tr>
 </table>
<table id="pedido_det" class="table table-striped table-bordered table-hover table-responsive">
                    	<thead>
                            <tr class="info">
                                
                                <th>Clave Microsip</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                                
                            </tr>
                        </thead><tbody>';
                            while($row2 = mysql_fetch_array($resultado_lista,MYSQL_BOTH)) // html de articulos a mostrar
                            {
                                
                            
                            
                             echo ' <tr>
                                                        
                                                        <td>'.$row2['clave_microsip'].'</td>
                                                        <td>'.$row2['articulo'].'</td>
                                                        <td>'.$row2['cantidad'].'</td>
                                                        
                                                        <td align="right">$'.number_format($row2['precio_unitario'],2).' </td>
                                                        <td align="right">$'.number_format($row2['precio_total'],2).'</td>
                                                    </tr>
                                                ';
                                                
                            }                
                             echo ' 
                             </table>
                             <table>
                                <tr>
                                    <td class="" style="width:85%;">
                                    </td>
                                    <td class="" id="td_total_pedido" style="align:right;"><h4 style="text-align:right; width:180px; float:right;">Total = $'.number_format($total_pedido,2).'</h4>
                                    </td>
                                </tr>
                             </tbody></table>';

                                 echo '

                                <script>

                                $(document).ready( function () {
                                    $("#pedido_det").DataTable();
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





                    	
                    	
                