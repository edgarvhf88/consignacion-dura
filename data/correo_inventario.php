<?php include("conexion.php");

function get_msj_mail_auto_sup($msj_titulo,$msj_header,$msj_body){
 
$mensaje_html = '<!DOCTYPE html>
<html lang="es-mx">
<head>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	 <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" type="image/png" href="https://www.allpartmysupplies.com/assets/images/favicon.png">
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


if ((isset($_POST['id_inventario'])) && ($_POST['id_inventario'] !="")){ // confirma parametro id_inventario

$id_inventario  = $_POST['id_inventario']; /// consulta para obtener datos de inventarios
$correo_destino  = $_POST['correo_destino']; /// consulta para obtener datos de inventarios
$consulta_inv = "SELECT inv.fecha as fecha,inv.fecha_hora_creacion as fecha_hora_creacion, inv.id_usuario_creador as id_usuario_creador,inv.folio as folio,inv.estatus as estatus ,inv.cancelado as cancelado ,inv.id_usuario_cierre as id_usuario_cierre,inv.fecha_hora_cierre as fecha_hora_cierre,inv.id_usuario_cancelacion as id_usuario_cancelacion,inv.fecha_hora_cancelacion as fecha_hora_cancelacion, alm.almacen as almacen 
					FROM inventarios inv 
					INNER JOIN almacenes alm ON alm.almacen_id = inv.almacen_id
					WHERE inv.id_inventario = '$id_inventario'";
$resultado_inv = mysql_query($consulta_inv, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_inv);
$totalRows = mysql_num_rows($resultado_inv);
// datos obtenidos
$usuario_creador = Nombre($row['id_usuario_creador']);
$fecha = $row['fecha'];
$folio = $row['folio'];
$fecha_hora_creacion = $row['fecha_hora_creacion'];
$almacen = $row['almacen'];
// declaracion de variables

$asunto_correo ='';
$msj_titulo = '';
$msj_header = '';
$msj_body = '';
$color_tablas_correo = '2AAAFF';

////////////////////////////////////////////////////////////////////////////////////////////////////////////
///// LISTA DE ARTICULOS EN EL INVENTARIO
$query_articulos = "SELECT 
		art.clave_microsip as clave_microsip,
		art.clave_empresa as clave_empresa,
		art.unidad_medida as unidad_medida,
		art.nombre as articulo,
		indet.id_inventario_det as id_inventario_det,
		indet.cantidad_contada as cantidad_contada,
		indet.existencia_actual as existencia_actual,
		indet.diferencia as diferencia
					FROM inventarios_det indet 
					INNER JOIN articulos art ON art.id_microsip = indet.articulo_id
					WHERE indet.id_inventario = '$id_inventario' ";
$resultado_articulos = mysql_query($query_articulos, $conex) or die(mysql_error());
//$row_articulos = mysql_fetch_assoc($resultado_articulos);
$totalRows_art = mysql_num_rows($resultado_articulos);

if ($totalRows_art){
$tabla_articulos = '<table style="width: auto; margin: 0 auto;  width: 600px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'"  cellspacing="0" cellpadding="0" border="2">
<tr style="height: 50px; background:#'.$color_tablas_correo.';">
                    	
				<th>#DURA</th>
				<th>#ALLPART</th>
				<th>Articulo</th>
				<th>Unid. Med.</th>
				<th>existencia</th>
				<th>Cant. Conteo</th>
				<th>Cant. facturar</th>
			</tr>
						';
$tabla_articulos_comprador = '<table style="width: auto; margin: 0 auto;  width: 600px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'"  cellspacing="0" cellpadding="0" border="2">
<tr style="height: 50px; background:#'.$color_tablas_correo.';">
                <th>#DURA</th>
				<th>#ALLPART</th>
				<th>Articulo</th>
				<th>Unid. Med.</th>
				<th>existencia</th>
				<th>Cant. Conteo</th>
				<th>Cant. facturar</th>
                    	</tr>
						';
/////////while articulos//////
while($row_articulos = mysql_fetch_array($resultado_articulos,MYSQL_BOTH)) 
	// lista de vendedores a quienes se les enviara el correo
{
$tabla_articulos .= '<tr >
                    		<td class="success">'.$row_articulos['clave_empresa'].'</td>
                    		<td>'.$row_articulos['clave_microsip'].'</td>
                    		<td>'.$row_articulos['articulo'].'</td>
                    		<td align="center">'.$row_articulos['unidad_medida'].'</td>
                    		<td align="right">'.number_format($row_articulos['existencia_actual'],2).'</td>
                    		<td align="right">'.number_format($row_articulos['cantidad_contada'],0).'</td>
                    		<td align="right">'.number_format($row_articulos['diferencia'],0).'</td>
                    	</tr>';
$tabla_articulos_comprador .= '<tr >
                    		<td class="success">'.$row_articulos['clave_empresa'].'</td>
                    		<td>'.$row_articulos['clave_microsip'].'</td>
                    		<td>'.$row_articulos['articulo'].'</td>
                    		<td align="center">'.$row_articulos['unidad_medida'].'</td>
                    		<td align="right">'.number_format($row_articulos['existencia_actual'],2).'</td>
                    		<td align="right">'.number_format($row_articulos['cantidad_contada'],0).'</td>
                    		<td align="right">'.number_format($row_articulos['diferencia'],0).'</td>
                    	</tr>';
						
						
}
					
$tabla_articulos .= '</table>';
$tabla_articulos_comprador .= '</table>';
}

mysql_free_result($resultado_articulos);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//$correo_destino = "ing.edgar.herebia@gmail.com"; // correo a quien se le envia
$nombre_destino = "Edgar Herebia"; // nombre a quien se le envia
$asunto_correo ='Lista de inventario #'.$folio; 
$msj_titulo = 'Inventario ';
$msj_header = '<h3>Saludos cordiales, le compartimos la captura de invetario de '.$almacen.' con el folio:'.$folio.' </h3>';
//$msj_body = '</br>'.$tabla_articulos_comprador.'</br>';	
$msj_body = '</br></br>';	
$ruta_adjunto = '../inv_docs/Inventario_'.$almacen.'_Folio_'.$folio.'.xlsx';	

$mensaje_html = get_msj_mail_auto_sup($msj_titulo,$msj_header,$msj_body);
//echo $mensaje_html;
////////////////////// empiesa code mail/////////////////////////////////////////////////////////////////////////	
$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output 0 1 2
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mail.allpartmysupplies.com';  						// Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'system@allpartmysupplies.com';                 // SMTP username
    $mail->Password = 'Allpart_2021';                           // SMTP password
    //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->SMTPAutoTLS = false;
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('system@allpartmysupplies.com', 'Sistema Consigna');
    $mail->addAddress($correo_destino, $nombre_destino);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment($ruta_adjunto);         // Add attachments
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
        $("#modal_cargando").modal("hide");
    
	  alert("Correo Enviado");
     });   
     </script>';
	 
} catch (Exception $e) {
   // echo 'El mensaje no pudo ser enviado. Mailer Error: ', $mail->ErrorInfo;
}

}/// validacion de si existe id_inventario 	como parametro
	?>
