<?php include("conexion.php");
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////
function id_comprador($id_pedido) { // obtiene NOMBRE de usuario**********************************
global $database_conexion, $conex;

$query = "SELECT * FROM pedidos WHERE id = $id_pedido";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

if ($totalRows > 0){
return $row['id_usuario'];
}
 else
{
return '0';
}	
mysql_free_result($resultado);  
}
if ((isset($_POST['id_pedido'])) && ($_POST['id_pedido'] !="")){
$Display = '';			
$display_empresas = display_empresas();
$tipo = validar_usuario($_SESSION["logged_user"]);

if ($tipo == 3){ //si el usuario que esta enviando el correo es de tipo (3) vendedor, se busca el comprador y la empresa a la que pertenece para cargar el display correspondiente
	
	$id_comprador = id_comprador($_POST['id_pedido']);
	//include("../displays/dura_style.php");
	$id_empresa_user_activo = id_empresa($id_comprador);
	foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	
};

include("../displays/".$Display.".php");
}else{
	$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	
}

include("../displays/".$Display.".php");

}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';



if ((isset($_POST['id_pedido'])) && ($_POST['id_pedido'] !="")){
$id_pedido  = $_POST['id_pedido']; /// generar una consulta y obtener los datos para el correo
$query = "SELECT * FROM pedidos WHERE id = '$id_pedido'";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$nombre_comprador  = Nombre($row['id_usuario']);
$direccion_correo = correo_usuario($row['id_usuario']);
$arr_empresa = explode("_",EMPRESA_NOMBRE_RFC($row['id_empresa']));
$nombre_empresa = $arr_empresa['0'];
$rfc = $arr_empresa['1'];

$arr_dir_cp_comprador = explode("_", direccion_sucursal($row['id_dir_suc']));
$direccion_comprador = str_replace("\n", "<br/>", $arr_dir_cp_comprador['0']);
$cp_sucursal_comprador = $arr_dir_cp_comprador['1'];
$pais_sucursal_comprador = utf8_decode($arr_dir_cp_comprador['2']);
$estado_sucursal_comprador = utf8_decode($arr_dir_cp_comprador['3']);
$ciudad_sucursal_comprador = utf8_decode($arr_dir_cp_comprador['4']);

$folio_pedido = $row['folio'];
$id_usuario = $row['id_usuario'];
$estatus = $row['estatus'];
$po_cliente = $row['orden_compra'];

mysql_free_result($resultado);

$query_articulos = "SELECT * FROM pedidos_det WHERE id_pedido = '$id_pedido'";
$resultado_articulos = mysql_query($query_articulos, $conex) or die(mysql_error());
//$row_articulos = mysql_fetch_assoc($resultado_articulos);
$totalRows_art = mysql_num_rows($resultado_articulos);
$total_sumado = '';
$iva = '';
$total_total = '';


if ($totalRows_art){
$tabla_articulos = '<table style=" width: auto; margin: 0 auto;  width: 900px; auto; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; border-color: #'.$color_tablas_correo.'" width="100%" cellspacing="0" cellpadding="4" border="2">
<tr style="height: 50px; background:#'.$color_tablas_correo.';">
                    		<th class="success">Clave cliente</th>
                    		<th class="success">Clave microsip</th>
                    		<th>Nombre</th>
                    		<th>Cantidad</th>
                    		<th>Precio Unitario</th>
                    		<th>Total</th>
                    	</tr>
						';
$tabla_articulos_comprador = '<table style=" width: auto; margin: 0 auto;  width: 900px; auto; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; border-color: #'.$color_tablas_correo.'" width="100%" cellspacing="0" cellpadding="4" border="2">
<tr style="height: 50px; background:#'.$color_tablas_correo.';">
                <th class="success">'.$clave_cliente_tabla_mail.'</th>
                <th>'.$nombre_art_tabla_mail.'</th>
                <th>'.$cantidad_tabla_mail.'</th>
                <th>'.$precio_tabla_mail.'</th>
                <th>'.$total_tabla_mail.'</th>
                    	</tr>
						';
/////////while articulos//////
while($row_articulos = mysql_fetch_array($resultado_articulos,MYSQL_BOTH)) // lista de vendedores a quienes se les enviara el correo
{
$tabla_articulos .= '<tr >
                    		<td class="success">'.$row_articulos['clave_empresa'].'</td>
                    		<td>'.$row_articulos['clave_microsip'].'</td>
                    		<td>'.$row_articulos['articulo'].'</td>
                    		<td align="center">'.$row_articulos['cantidad'].'</td>
                    		<td align="right">'.number_format($row_articulos['precio_unitario'],2).'</td>
                    		<td align="right">'.number_format($row_articulos['precio_total'],2).'</td>
                    	</tr>';
$tabla_articulos_comprador .= '<tr >
                    		<td class="success">'.$row_articulos['clave_empresa'].'</td>
                    		<td>'.$row_articulos['articulo'].'</td>
                    		<td align="center">'.$row_articulos['cantidad'].'</td>
                    		<td align="right">'.number_format($row_articulos['precio_unitario'],2).'</td>
                    		<td align="right">'.number_format($row_articulos['precio_total'],2).'</td>
                    	</tr>';
						
$total_sumado += $row_articulos['precio_total'];						
}
$iva = $total_sumado * 0.08;
$total_total = $iva + $total_sumado;						
$tabla_articulos .= '</table>';
$tabla_articulos_comprador .= '</table>';
}
$tabla_totales = '<table style="width: auto; margin: 0 auto; width: 900px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; border-color: #'.$color_tablas_correo.'" width="100%" cellspacing="0" cellpadding="4" border="0">
                         <tr>
                              <td style="width: 70%;" ></td>
                              <td></td>
                              <td></td>
                              <td style="text-align: right;">'.$subtotal_tabla_totales_mail.'</td>
                              <td class="table-active" style="text-align: right;">$'.number_format($total_sumado,2).'</td>
                         </tr>
                         '.iva_totales_mail($iva).'
                         <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td style="text-align: right;">Total=</td>
                              <td class="table-active" style="text-align: right;">$'.number_format($total_total,2).'</td>
                         </tr>
				</table>';
mysql_free_result($resultado_articulos);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
$query_relacion = "SELECT * FROM relaciones WHERE id_requisitor = '$id_usuario'";
$resultado_relacion = mysql_query($query_relacion, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado_relacion);
$totalRows2 = mysql_num_rows($resultado_relacion);
if ($totalRows2 > 0){	

while($row2 = mysql_fetch_array($resultado_relacion,MYSQL_BOTH)) // lista de vendedores a quienes se les enviara el correo
{
$correo_vendedor = correo_usuario($row2['id_vendedor']);	
$nombre_vendedor = Nombre($row2['id_vendedor']);
$empresa_id = id_empresa($row2['id_vendedor']);
$arr_empresa_vend = explode("_",EMPRESA_NOMBRE_RFC($empresa_id));
$nombre_empresa_vend = $arr_empresa_vend['0'];
$rfc_vend = $arr_empresa_vend['1'];

$arr_dir_cp_vendedor = explode("_", direccion_vendedor($row2['id_vendedor']));
$direccion_vendedor = str_replace("\n", "<br/>", $arr_dir_cp_vendedor['0']);
$cp_sucursal_vendedor = $arr_dir_cp_vendedor['1'];
$pais_sucursal_vendedor = utf8_decode($arr_dir_cp_vendedor['2']);
$estado_sucursal_vendedor = utf8_decode($arr_dir_cp_vendedor['3']);
$ciudad_sucursal_vendedor = utf8_decode($arr_dir_cp_vendedor['4']);


///////////validacion para tipo correo /////////////////////////////////////////////////////////////////////////////////////////////
if ($estatus == "5") {// mensaje de cuando ya esta entregado el pedido - este mensaje no se enviara al vendedor

} else if ($estatus == "1"){ //// mensaje de cuando avisa que el pedido a sido ordenado y esta en espera de ser atendido

$mensaje_html = get_msj_mail($nombre_empresa,$folio_pedido,$nombre_empresa_vend,$direccion_vendedor,$ciudad_sucursal_vendedor,$estado_sucursal_vendedor,$cp_sucursal_vendedor,$pais_sucursal_vendedor,$rfc_vend,$direccion_comprador,$ciudad_sucursal_comprador,$estado_sucursal_comprador,$cp_sucursal_comprador,$pais_sucursal_comprador,$rfc,$tabla_articulos,$tabla_totales,$po_cliente);

////////////////////// empiesa code mail/////////////////////////////////////////////////////////////////////////	
$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output 0 1 2
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'allpart.mx';  						// Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'system@allpartmysupplies.com';                 // SMTP username
    $mail->Password = 'Allpart_2021';                           // SMTP password
    //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->SMTPAutoTLS = false;
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('system@allpartmysupplies.com', $nombre_from_mail);
    $mail->addAddress($correo_vendedor, $nombre_vendedor);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $msj_asunto_mail.$folio_pedido;
    $mail->Body    = $mensaje_html; 
    $mail->AltBody = '';

    $mail->send();
   // echo 'Mensage enviado';
} catch (Exception $e) {
   // echo 'El mensaje no pudo ser enviado. Mailer Error: ', $mail->ErrorInfo;
}
}//esta llave cierra validacion de estatus 	
} ///fin de while
//echo $mensaje_html;	
}
mysql_free_result($resultado_relacion);
	

if ($estatus == "5") {// mensaje de cuando ya esta entregado el pedido
$mensaje_html2 = get_msj_entregado_mail($nombre_empresa,$folio_pedido,$tabla_articulos_comprador,$tabla_totales,$po_cliente);
} else if ($estatus == "1"){ //// mensaje de cuando avisa que el pedido a sido ordenado y esta en espera de ser atendido


$mensaje_html2 = get_msj_ordenado_cliente_mail($nombre_empresa,$folio_pedido,$tabla_articulos_comprador,$tabla_totales,$po_cliente);	
} 

//echo $mensaje_html2;

$mail2 = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail2->SMTPDebug = 0;                                 // Enable verbose debug output 0 1 2
    $mail2->isSMTP();                                      // Set mailer to use SMTP
    $mail2->Host = 'allpart.mx';  						// Specify main and backup SMTP servers
    $mail2->SMTPAuth = true;                               // Enable SMTP authentication
    $mail2->Username = 'system@allpartmysupplies.com';                 // SMTP username
    $mail2->Password = 'Allpart_2021';                           // SMTP password
    //$mail2->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail2->SMTPAutoTLS = false;
    $mail2->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail2->setFrom('system@allpartmysupplies.com', $nombre_from_mail);
    $mail2->addAddress($direccion_correo, $nombre_comprador);     // Add a recipient
    //$mail2->addAddress('ellen@example.com');               // Name is optional
    //$mail2->addReplyTo('info@example.com', 'Information');
    //$mail2->addCC('cc@example.com');
    //$mail2->addBCC('bcc@example.com');

    //Attachments
    //$mail2->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail2->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail2->isHTML(true);                                  // Set email format to HTML
    $mail2->Subject = $msj_asunto_mail.$folio_pedido;
    $mail2->Body    = $mensaje_html2; 
    $mail2->AltBody = '';

    $mail2->send();
   // echo 'Mensage enviado';
    echo '<script>
    $(document).ready(function(){
        $("#modal_cargando").modal("hide");
        $("#modal_pedido").modal("show");
		mis_pedidos();
     });   
     </script>'
     ;
} catch (Exception $e) {
  //  echo 'El mensaje no pudo ser enviado. Mailer Error: ', $mail2->ErrorInfo;
}

}/// validacion de si existe id_pedido 	como parametro
	?>
