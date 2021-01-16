<?php include("conexion.php")

if ((isset($_POST['id_reg'])) && (isset($_POST['tipo']))){
if (($_POST['id_reg'] =! "") && ($_POST['tipo'] =! "")){ 
	 actualizar($_POST['id_reg'], $_POST['tipo']);
}}
function actualizar($id_reg, $tipo){
	
switch($tipo) {
case 1:
$id_prefijo = 'trmispedidos_'.$row['id'];
$consulta = "SELECT p.estatus as estatus, p.orden_compra as orden_compra, p.id as id, p.folio as folio, p.total_pedido as total_pedido, p.fecha_pedido as fecha_pedido, cc.nombre_cc as nombre_cc, user.nombre as nombre_r, user.apellido as apellido_r
FROM pedidos p 
LEFT JOIN centro_costos cc on cc.id_cc = p.id_cc
LEFT JOIN usuarios user on user.id = p.id_recolector
WHERE p.id = '$id_reg'";

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

	switch($row['estatus']){
        case 0:
        $estatus = "Abierta";
        break;
        case 1:
        $estatus = $estatus_tipo_ordenado;
        $clase_td = 'class="btn-warning"';	
        break;
        case 2:
        $estatus = $estatus_pedido_preparado;
        $clase_td = 'class="btn-info"';	
        break;
        case 3:
        
        $estatus = $estatus_tipo_entregado;
        $clase_td = 'class="btn-success"';	
        break;
        }
            if ($row['orden_compra'] != '')
            {
            	$orden_compra = $row['orden_compra'];
            }
            else	
            {
            $orden_compra = '<button class="btn btn-warning btn_addorden" id="ocbtnpedido_'.$row['id'].'" >
                    							<i class="fa fa-file"></i>
                    							O.C.
                    							</button>';
            }
			if ($row['nombre_cc'] == ""){
				$nombre_cc = '-';
			}else
			{
				$nombre_cc = $row['nombre_cc'];
			}
			if ($row['nombre_r'] == ""){
				$nombre_recolector = 'Personalmente';
			}else
			{
				$nombre_recolector = $row['nombre_r'].''.$row['apellido_r'];
			}
$tr_actualizar = '
<td onclick="detalle_pedido('.$row['id'].','.$row['folio'].','.$row['total_pedido'].');" >
    '.$row['fecha_pedido'].'
</td>
<td onclick="detalle_pedido('.$row['id'].','.$row['folio'].','.$row['total_pedido'].');">
    '.$row['folio'].'
</td>
<td onclick="detalle_pedido('.$row['id'].','.$row['folio'].','.$row['total_pedido'].');" '.$clase_td.' align="center">
    '.$estatus.'
</td>
<td onclick="detalle_pedido('.$row['id'].','.$row['folio'].','.$row['total_pedido'].');">
    '.$nombre_cc.'
</td>
<td onclick="detalle_pedido('.$row['id'].','.$row['folio'].','.$row['total_pedido'].');">
	'.$nombre_recolector.'
</td>
<td align="right" onclick="detalle_pedido('.$row['id'].','.$row['folio'].','.$row['total_pedido'].');">
    $'.number_format($row['total_pedido'],2).'
</td>
<td align="right" style="width:120px;">
<input type="hidden" id="txt_folio_pedido_'.$row['id'].'" value="'.$row['folio'].'"/>
    '.$orden_compra.'
</td>';

echo '<script>
		var td_remplazar = document.getElementsById("#'.$id_prefijo.'");
		
		if (td_remplazar.length > 0){
		$("#'.$id_prefijo.'").html("'.$tr_actualizar.'");
		
		}		
					</script>';				
break;			

	
}	



}
?>