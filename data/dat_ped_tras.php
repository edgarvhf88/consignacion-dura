<?php include("../data/conexion.php"); 

	$id_pedido = '';
	$folio = '';

	if (isset($_POST['id_pedido'])){
		$id_pedido = $_POST['id_pedido'];
		$folio = $_POST['folio'];
    }

	if ($id_pedido != ''){
		mostrar_list($id_pedido,$folio);
	}
	  
     function mostrar_list($id_pedido,$folio){ 
global $database_conexion, $conex;
$total_pedido = 0;

$consulta_lista = "SELECT * FROM pedido_traspaso_det WHERE id_pedido = $id_pedido ";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$total_rows2 = mysql_num_rows($resultado_lista);
if ($total_rows2 > 0){
	
echo '<table class="col-lg-12">
	<tr>
		<td class="col-lg-10" id="td_folio_pedido"><h3>Solicitud de traspaso Folio: '.$folio.'</h3>
		</td>
		<td class="col-lg-2" align="right"> 
			<input type="button" class="btn btn-primary" value="Back" onclick="goback();"  /> 
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
                                    </tr>';
                                $total_pedido += $row2['precio_total'];                
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
                             </tbody></table>
							 <div class="col-lg-12" align="center">
							 <button id="gen_tras_micro" onclick="gentrasmicro('.$id_pedido.');" class="btn btn-primary" >Generar Traspaso Microsip </button>
							 </div>';

                                 echo '

                                <script>

                                $(document).ready( function () {
                                    $("#pedido_det").DataTable();
                                } );

                                </script>

                                    ';
 

}  


}




?>
