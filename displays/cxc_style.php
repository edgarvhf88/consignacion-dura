<?php  
$nombre_usuario = 'Usuario no registrado';
if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] == '')){ header('Location: login.php'); }
else {
$nombre_usuario = '<a href="login.php" class="dropdown-toggle" id="drop4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><span class="fa fa-user" aria-hidden="true"></span> '.Nombre($_SESSION["logged_user"]).'</a>';
$tipo_usuario = validar_usuario($_SESSION["logged_user"]); 
$folio_pedido_index = "1";
$btns_menu = '';

switch($tipo_usuario)
{
	case 1:
	// si es admin
		$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';
	break;
	case 2:
	//si es comprador
	$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';
	break;
	case 3:
	//si es vendedor
	
	$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';
	break;
	case 4:
	//si es Cosultor
	$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';
	break;
	case 5:
	//si es Supervisor
	//$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';
	
	break;
	case 11:
	//si es Supervisor
	//$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';
	
	break;
	
}
}

$menu_bar = '<ul class="nav navbar-nav navbar-right ">
                <li style="" >
                    <a href="#" style="z-index:8;" onclick="estatus_almacenes(11);">
                      <span class="fa fa-list-alt" aria-hidden="true"></span> Estatus Almacen
                    </a>
					
                </li>
				<li  style="">
                    <a href="#" style="z-index:9;" onclick="lista_ordenes_cxc();">
                      <span class="fa fa-file" aria-hidden="true"></span>  Ordenes Capturadas
                    </a>
					
                </li> 
					
				<!-- -->
               
                <li class="dropdown" style="z-index:11; ">
                    
                '.$nombre_usuario.'
		<!--	<ul class="dropdown-menu"> '.$btns_menu.'</ul>  -->
                </li>
                <li  style="z-index:12;" >
                     <a href="data/logout.php" class="btn btn-primary" style="width:100px;">Salir</a>
                </li>
            </ul>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////AREA DE VARIABLES PARA LA PAGINA cxc.PHP ///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* Lista de categorias a mostrar en el filtrado de la busqueda*/
$id_empresa = id_empresa($_SESSION["logged_user"]);

$lista_categorias = categorias_lista($id_empresa);
$categorias_filtro = "";
foreach ($lista_categorias as $id => $categoria){
			$categorias_filtro .= '<li><a href="#'.$id.'" >'.$categoria.'</a></li>';
			
		};
/*$header_index ES EL HEADER DE LA PAGINA INDEX.PHP*/
$ruta = "assets/images/header_standar.jpg";
$header_cxc = '<header class="hero overlay" style="background-image: url('.$ruta.');"> 
    <nav class="navbar" style="">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>
            <a href="#" >
                <img src="assets/images/logo-allpart.png" alt="Knowledge" height="50">
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse">
            '.$menu_bar.'
        </div>
    </div>
</nav>
    
<div class="" style="">
<div class="container">
    <div class="row">    
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-12" style="z-index:2; padding-bottom:5px;">
        
			<div class="col-lg-2" ></div>
			<div class="col-lg-3 clearfix" align="center">
				
					<select id="select_almacen_estatus" class="select form-control form-control-sm"  style="width: 250px;" >
					</select>
				
			</div>
			<div class="col-lg-3 clearfix" align="center">
				
				 <input type="text" class="form-control" id="datepicker_ini" style="width: 250px;" />
				
			</div>
			<div class="col-lg-3 clearfix" align="center">
				
				 <input type="text" class="form-control" id="datepicker_fin" style="width: 250px;" />
				
			</div>
		</div>
        
    </div>
</div>
</div>

</header>';



/*$modal_orden_index ES EL MODAL DONDE SE AGREGA EL NUMERO DE ORDEN DEL CLIENTE EN LA PAGINA INDEX.PHP*/
$modal_orden_index = '<div class="modal fade" id="modal_orden" tabindex="-1" role="dialog" aria-labelledby="Modal orden" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                            Agregar Orden de Compra
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body">
                                        <input type="text" value="" id="txt_orden_compra" class="" placeholder="Numero de Orden"/>
                                        <input type="hidden" value="" id="txt_id_pedido" />
                                    
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-success " data-dismiss="modal" id="btn_guardar_orden">Guardar</button>
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">Cerrar</button>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                    </div>';
					


/*$footer_index ES EL FOOTER EN LA PAGINA INDEX.PHP*/					
$footer_index = '<footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <a href="#" class="brand">
                        <img src="assets/images/logo-f.png" alt="Knowledge">
                        <span class="circle"></span>
                    </a>
                </div>
                <div class="col-lg-7 col-md-5 col-sm-9">
                    <ul class="footer-links">
                        
                        <li><a href="#">Contactos</a></li>
               
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="copyright">
                        <p>© 2019 All Part Productos y Servicios</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>';

/*$footer_index ES EL FOOTER EN LA PAGINA INDEX.PHP*/					
$modal_cargando_index = '<div class="modal fade" id="modal_cargando" tabindex="-1" role="dialog" aria-labelledby="Modal cargando" aria-hidden="true" style="top: 50%;" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content" style="background-color: rgba(0, 0, 0, 0.6);">
                                    <!-- Header de la ventana -->
                                    
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body">
                                        <div class="content">
      
      	    							  <div class="load-4">
      	    							      <div class="ring-1"></div>
      	    							  </div>
											</div>
											<div class="clear"></div>
                                    
                                    </div>
                                    <!-- Footer de la ventana -->
                                    
                                    
                                </div>
                            </div>
                    </div>';
	
/* $mensaje_html es el contenido del correo que se envia al o a los vendedores - en este caso se uso una funcion para poder pasar por parametro los valores que se imprimern en el correo pagina enviar.php*/
$msj_asunto_mail = 'Pedido ';
$nombre_from_mail =  'AllPart';
$color_tablas_correo = '52b25e';
function get_msj_mail($nombre_empresa,$folio_pedido,$nombre_empresa_vend,$direccion_vendedor,$ciudad_sucursal_vendedor,$estado_sucursal_vendedor,$cp_sucursal_vendedor,$pais_sucursal_vendedor,$rfc_vend,$direccion_comprador,$ciudad_sucursal_comprador,$estado_sucursal_comprador,$cp_sucursal_comprador,$pais_sucursal_comprador,$rfc,$tabla_articulos,$tabla_totales,$po_cliente){
	
	global $color_tablas_correo;
	if ($po_cliente == ""){ $po_cliente = "Pendiente"; }	
	
$mensaje_html = '<!DOCTYPE html>
<html lang="es-mx">
<head>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Correo</title>

</head>
<body> 

		<br>
		<header>
			
				<table style=" width: auto;  width: 900px; margin: 0 auto; font-family: sans-serif;" width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr style="height: 60px;"> 
				<td style="width: 100%;" colspan="2" align="center" valign="middle">
						<img src="https://i.postimg.cc/W3QT7Jj0/gen-logo.png" alt="">
					</td> 
				</tr>
				<tr style="height: 50px;"> 
					
					<td style="width: 40%;" align="center" valign="middle">
						<h3 align="center">'.$nombre_empresa.'</h3>
					</td>
					<td style="width: 30%;" align="center" valign="middle">
						<p align="center">Orden No: '.$folio_pedido .'</p>
					</td>
				</tr>
				</table>			
		</header>
		<br>		


				<table style="width: auto; margin: 0 auto;  width: 900px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'" width="100%" cellspacing="0" cellpadding="0" border="2">
					<tr style="height: 50px; background:#'.$color_tablas_correo.';">
							  <td style="width: 30%;" align="center" valign="middle">
							  	<h3>VENDEDOR</h3>
							  </td>
							  <td style="width: 30%;" align="center" valign="middle">
							  	<h3>FACTURAR A</h3>
							  </td>
					</tr>
					<tr>
						<td style="padding: 15px 15px;">
							<h4>'.$nombre_empresa_vend .'</h4>
							<p>'.$direccion_vendedor .'</p>
							<p>'.$ciudad_sucursal_vendedor.', '.$estado_sucursal_vendedor.' '.$cp_sucursal_vendedor.'</p>
							<p>'.$pais_sucursal_vendedor.'</p>
							<p>R.F.C. '.$rfc_vend.'</p>
						</td>
						<td style="padding: 15px 15px;">
							<h4>'.$nombre_empresa .'</h4>
							<h4>No. orden cliente: '.$po_cliente.'</h4>
							<p>'.$direccion_comprador.'</p>
							<p>'.$ciudad_sucursal_comprador.', '.$estado_sucursal_comprador.' '.$cp_sucursal_comprador.'</p>
							<p>'.$pais_sucursal_comprador.'</p>
							<p>R.F.C. '.$rfc.'</p>
						</td>
					</tr>
				</table>
<br>

				'.$tabla_articulos.'		
<br>
				'.$tabla_totales.'

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				
			</div>
		</div>
	</div>
</body>
</html>';

return $mensaje_html;	
}

function get_msj_entregado_mail($nombre_empresa,$folio_pedido,$tabla_articulos_comprador,$tabla_totales,$po_cliente){

global $color_tablas_correo;	
if ($po_cliente == ""){ $po_cliente = "Pendiente"; }	

$mensaje_html = '<!DOCTYPE html>
<html lang="es-mx">
<head>
  <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Correo</title>

</head>
<body> 
 <br>
    <header>
      
        <table style=" width: auto;  width: 900px; margin: 0 auto; font-family: sans-serif;" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr style="height: 60px;"> 
				<td style="width: 100%;" colspan="2" align="center" valign="middle">
						<img src="https://i.postimg.cc/W3QT7Jj0/gen-logo.png" alt="">
					</td> 
				</tr>
        <tr style="height: 50px;"> 
          
          <td style="width: 40%;" align="center" valign="middle">
            <h3 align="center">'.$nombre_empresa.'</h3>
          </td>
          <td style="width: 30%;" align="center" valign="middle">
            <p align="center">Orden No: '.$folio_pedido.'</p>
          </td>
        </tr>
        </table>      
    </header>
    <br>    


        <table style="width: auto; margin: 0 auto;  width: 900px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'" width="100%" cellspacing="0" cellpadding="0" border="2">
          <tr style="height: 50px; background:#'.$color_tablas_correo.';">
                <td style="width: 30%;" align="center" valign="middle">
                  <h3>SU ORDEN DE COMPRA SE ENTREGO CON EXITO</h3>
                </td>
          </tr>
          <tr>
            <td style="padding: 15px 15px;" align="center">
              <h4>No. orden cliente: '.$po_cliente.'</h4>
            </td>
          </tr>
        </table>
<br>

          '.$tabla_articulos_comprador.'
<br>
      '.$tabla_totales.'

            

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        
      </div>
    </div>
  </div>
  </body>
</html>';

return $mensaje_html;	
}

function get_msj_ordenado_cliente_mail($nombre_empresa,$folio_pedido,$tabla_articulos_comprador,$tabla_totales,$po_cliente){
	
	global $color_tablas_correo;
if ($po_cliente == ""){ $po_cliente = "Pendiente"; }		
	
$mensaje_html = '<!DOCTYPE html>
<html lang="es-mx">
<head>
  <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Email</title>

</head>
<body> 

    <br>
    <header>
      
        <table style=" width: auto;  width: 900px; margin: 0 auto; font-family: sans-serif;" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr style="height: 60px;"> 
				<td style="width: 100%;" colspan="2" align="center" valign="middle">
						<img src="https://i.postimg.cc/W3QT7Jj0/gen-logo.png" alt="">
					</td> 
				</tr>
        <tr style="height: 50px;"> 
           
          <td style="width: 40%;" align="center" valign="middle">
            <h3 align="center">'.$nombre_empresa.'</h3>
          </td>
          <td style="width: 30%;" align="center" valign="middle">
            <p align="center">Orden No: '.$folio_pedido.'</p>
          </td>
        </tr>
        </table>      
    </header>
    <br>    


        <table style="width: auto; margin: 0 auto;  width: 900px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'" width="100%" cellspacing="0" cellpadding="0" border="2">
          <tr style="height: 50px; background:#'.$color_tablas_correo.';">
                <td style="width: 30%;" align="center" valign="middle">
                  <h3>Su orden de compra se envio con exito
</h3>
                </td>
          </tr>
          <tr>
            <td style="padding: 15px 15px;">
              <h4>No. orden cliente: '.$po_cliente.'</h4>
            </td>
          </tr>
        </table>
<br>

          '.$tabla_articulos_comprador.'
<br>
      '.$tabla_totales.'

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        
      </div>
    </div>
  </div>
</body>
</html>';

return $mensaje_html;	
}

/* variables de texto en correo pagina enviar.php*/
$clave_cliente_tabla_mail = 'Clave';
$nombre_art_tabla_mail = 'Nombre';
$cantidad_tabla_mail = 'Cantidad';
$precio_tabla_mail = 'Precio Unitario';
$total_tabla_mail = 'Total';
$subtotal_tabla_totales_mail = 'Sub-total=';
$total_tabla_totales_mail = 'Total=';
function iva_totales_mail($iva){
	
	$tr_iva = '';
	/* $tr_iva = '<tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td style="text-align: right;">IVA=</td>
                              <td class="table-active" style="text-align: right;">$'.number_format($iva,2).'</td>
                         </tr>'; */
	
return $tr_iva;	
}


?>