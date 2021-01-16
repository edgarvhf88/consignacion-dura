<?php include("conexion.php");

function estructurar_html_correo($msj_titulo,$msj_header,$msj_body){
	
$mensaje_html = '<!DOCTYPE html>
<html lang="es-mx">
<head>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	 <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" type="image/png" href="https://dura.allpart.mx/assets/images/favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<title>'.$msj_titulo.'</title>

</head>
<body> 

		<br>
		<header>
			'.$msj_header.'
		</header>
		<br>		

				'.$msj_body.'
			
</body>
</html>';

return $mensaje_html;	
}

	
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';



if ((isset($_POST['id_pedido'])) && ($_POST['id_pedido'] !="")){ // confirma parametro id_pedido
$tipo = 0;	
	if (isset($_POST['tipo'])){
		$tipo = $_POST['tipo'];
	}
$id_pedido  = $_POST['id_pedido']; /// consulta para obtener datos de pedido
$consulta_pedido = "SELECT * FROM pedidos WHERE id = '$id_pedido'";
$resultado_pedido = mysql_query($consulta_pedido, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_pedido);
$totalRows = mysql_num_rows($resultado_pedido);
// datos obtenidos
$folio_pedido = $row['folio'];
$id_usuario = $row['id_usuario'];
$estatus = $row['estatus'];
$po_cliente = $row['orden_compra'];
// declaracion de variables
$usuario_autoriza = 0;
$nombre_autorizo  = '';
$correo_autorizo = '';
$asunto_correo ='';
$msj_titulo = '';
$msj_header = '';
$msj_body = '';
$tabla_autorizaciones = '';
$color_tablas_correo = '2AAAFF';
$estatus_auto = 2;
/// cosulta para obtener datos de lo que se quiere autorizar y a quien de le pide el permiso 
$query_auto = "SELECT rq.id_requi as id_requi,rq.tipo as tipo, rq.id_aplicado as id_aplicado, rq.total_evaluado as total_evaluado, rq.justificacion as justificacion, rq.estatus as estatus, rq.id_usuario_autorizo as id_usuario_autorizo, rq.total_disponible as total_disponible, vl.cantidad_dinero as cantidad_dinero, us.nombre as nombre, us.apellido as apellido
					FROM requi_autorizacion rq
					INNER JOIN validacion_limit vl on vl.id_limit = rq.id_limite
					INNER JOIN usuarios us on us.id = rq.id_usuario_requiere
					WHERE rq.id_pedido = $id_pedido and rq.estatus <> $estatus_auto";
$resultado_auto = mysql_query($query_auto, $conex) or die(mysql_error());
$totalRows_auto = mysql_num_rows($resultado_auto);
if ($totalRows_auto > 0){ // si encuentra datos 
// tabla con lista de datos a autorizar
$tabla_autorizaciones = '	<table style="width: auto; margin: 0 auto;  width: 600px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'"  cellspacing="0" cellpadding="0" border="2">
                    	
                            <tr style="height: 50px; background:#'.$color_tablas_correo.';">
                                
                                <td align="center">Para</td>
                                <td align="center">Descripcion</td>
                                <td align="center">Limite establecido</td>
                                <td align="center">Disponible</td>
                                <td align="center">Monto en pedido</td>
                                <td align="center">Justificacion</td>
                                    
                            </tr>
                        ';
						$tipo_nombre = '';
						$nombre_concepto = '';
						$usuario_requiere = '';
						$td_estatus = '';
						$td_estatus_clase = '';
						
while($row_auto = mysql_fetch_array($resultado_auto,MYSQL_BOTH))  // lista de datos
{ $usuario_autoriza = $row_auto['id_usuario_autorizo'];	
		
	 $usuario_requiere = $row_auto['nombre'].' '.$row_auto['apellido'];
							
                           switch($row_auto['tipo'])
								{
									case 1:
									$tipo_nombre = "Articulo";
									$nombre_concepto= ARTICULO_NOMBRE($row_auto['id_aplicado']);
									//$clase_td = 'class="btn-warning"';	
									break;
									case 2:
									$tipo_nombre = "Centro de Costos";
									//$clase_td = 'class="btn-info"';	
									$nombre_concepto= CC_NOMBRE($row_auto['id_aplicado']);
									break;
									case 3:
									$tipo_nombre = "Departamento";
									//$clase_td = 'class="btn-success"';
									$nombre_concepto= DEPARTAMENTO_NOMBRE($row_auto['id_aplicado']);
									break;
									case 4:
									$tipo_nombre = "Usuario";
									$nombre_concepto= Nombre($row_auto['id_aplicado']);
									break;
								}
								 $tabla_autorizaciones .= ' <tr>               
                                    <td align="center">'.$tipo_nombre.'</td>
                                    <td align="center">'.$nombre_concepto.'</td>
									<td align="center">$'.number_format($row_auto['cantidad_dinero'],2).'</td>
									<td align="center">$'.number_format($row_auto['total_disponible'],2).'</td>
                                    <td align="center">$'.number_format($row_auto['total_evaluado'],2).' </td>
                                    <td align="center">'.$row_auto['justificacion'].'</td>
                                    
                                    </tr>
                                    ';
}
$tabla_autorizaciones .= '</table>';
} mysql_free_result($resultado_auto);
////////////////////////////////////////////////////////////////////////////////////////////////////////////
///// LISTA DE ARTICULOS EN EL PEDIDO
$query_articulos = "SELECT * FROM pedidos_det WHERE id_pedido = '$id_pedido'";
$resultado_articulos = mysql_query($query_articulos, $conex) or die(mysql_error());
//$row_articulos = mysql_fetch_assoc($resultado_articulos);
$totalRows_art = mysql_num_rows($resultado_articulos);
$total_sumado = '';
$iva = '';
$total_total = '';


if ($totalRows_art){
$tabla_articulos = '<table style="width: auto; margin: 0 auto;  width: 600px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'"  cellspacing="0" cellpadding="0" border="2">
<tr style="height: 50px; background:#'.$color_tablas_correo.';">
                    		<th class="success">Clave cliente</th>
                    		<th class="success">Clave microsip</th>
                    		<th>Nombre</th>
                    		<th>Cantidad</th>
                    		<th>Precio Unitario</th>
                    		<th>Total</th>
                    	</tr>
						';
$tabla_articulos_comprador = '<table style="width: auto; margin: 0 auto;  width: 600px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'"  cellspacing="0" cellpadding="0" border="2">
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

				$tabla_totales = '<table style="width: auto; margin: 0 auto; font-family: sans-serif; border-collapse: collapse; overflow: hidden; border-color: #'.$color_tablas_correo.'"  cellspacing="0" cellpadding="0" border="0" align="right">
                       <tr style="width:150px;">
                          <td style="text-align: right;">Total=</td>
                          <td class="table-active" style="text-align: right;">$'.number_format($total_sumado,2).'</td>
                         </tr>
				</table>';
mysql_free_result($resultado_articulos);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$nombre_requiere  = Nombre($id_usuario);
$correo_requiere = correo_usuario($id_usuario);
if ($usuario_autoriza != 0){
$nombre_autorizo  = Nombre($usuario_autoriza);
$correo_autorizo = correo_usuario($usuario_autoriza);
}
mysql_free_result($resultado_pedido);
	
if ($tipo == 0){ // si no hay tipo
	
}else if ($tipo == 1){ // tipo SOLICITUD DE APROBACION
$correo_destino = $correo_autorizo; // correo a quien se le solicita
$nombre_destino = $nombre_autorizo; // nombre a quien se le solicita
$asunto_correo ='Solicitud de Aprobacion'; 
$msj_titulo = 'Solicitud de Aprobacion';
$msj_header = '<h3>El usuario '.$nombre_requiere.' le solicita la aprobacion de lo siguiente</h3>';
$msj_body = '</br>'.$tabla_autorizaciones.'</br> <p> Para responder la solicitud entre <a href="#"> Aqui </a> </p>';
	
}else if ($tipo == 2){ // tipo aprobacion de autorizacion
$correo_destino = $correo_requiere; // correo a quien se le autorizo
$nombre_destino = $nombre_requiere; // nombre a quien se le autorizo
$asunto_correo ='Solicitud Aprobada'; 
$msj_titulo = 'Solicitud Aprobada';
$msj_header = '<h3>El usuario '.$nombre_autorizo.' le autorizo lo siguiente</h3>';
$msj_body = '</br>'.$tabla_autorizaciones.'</br> <p> El pedido ya se encuentra en su lista de pedidos en estatus "Ordenado" <a href="#"> Ver mis pedidos </a> </p>';
	
}else if ($tipo == 3){ // tipo denegar de autorizacion
$correo_destino = $correo_requiere; // correo a quien se le denego
$nombre_destino = $nombre_requiere; // nombre a quien se le denego
$asunto_correo ='Solicitud denegada'; 
$msj_titulo = 'Solicitud denegada';
$msj_header = '<h3>El usuario '.$nombre_autorizo.' ha denegado lo siguiente</h3>';
$msj_body = '</br>'.$tabla_autorizaciones.'</br> <p> El pedido se encuentra en su lista de pedidos pausados para que lo pueda retomar  <a href="#"> Ver mis pedidos pausados </a> </p>';
	
}else if ($tipo == 4){ // tipo pedido recolectado
$correo_destino = $correo_requiere; // correo a quien pidio el pedido
$nombre_destino = $nombre_requiere; // nombre a quien pidio el pedido
$asunto_correo ='Pedido Recolectado'; 
$msj_titulo = 'Pedido Recolectado';
$msj_header = '<h3>Su pedido folio:'.$folio_pedido.' ha sido recolectado </h3>';
$msj_body = '</br>'.$tabla_articulos_comprador.'</br>'.$tabla_totales.'</br> <p> El pedido ha sido recolectado <a href="#"> Ver mis pedidos </a> </p>';	
}	

$mensaje_html = estructurar_html_correo($msj_titulo,$msj_header,$msj_body);
//echo $mensaje_html;
////////////////////// empiesa code mail/////////////////////////////////////////////////////////////////////////	
$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output 0 1 2
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'allpart.mx';  						// Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'info@allpart.mx';                 // SMTP username
    $mail->Password = 'Contrasena_123';                           // SMTP password
    //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->SMTPAutoTLS = false;
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('info@allpart.mx', $nombre_from_mail);
    $mail->addAddress($correo_destino, $nombre_destino);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $asunto_correo;
    $mail->Body    = $mensaje_html; 
    $mail->AltBody = '';

    $mail->send();
   // echo 'Mensage enviado';
   echo '<script>
    $(document).ready(function(){
      //  $("#modal_cargando").modal("hide");
      //  $("#modal_pedido").modal("show");
	  //	mis_pedidos();
	  //alert("Correo Enviado");
     });   
     </script>';
	 
} catch (Exception $e) {
   // echo 'El mensaje no pudo ser enviado. Mailer Error: ', $mail->ErrorInfo;
}

}/// validacion de si existe id_pedido 	como parametro
	?>
