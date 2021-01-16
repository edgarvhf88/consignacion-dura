<?php  
$nombre_usuario = 'Unregistered User';
if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] == '')){ header('Location: login.php'); }
else {
$nombre_usuario = '<a href="login.php">'.Nombre($_SESSION["logged_user"]).'</a>';
$tipo_usuario = validar_usuario($_SESSION["logged_user"]); 
$folio_pedido_index = "1";
$btns_menu = '';
switch($tipo_usuario)
{
	case 1:
	// si es admin
	
	break;
	case 2:
	//si es requisitor
	$btns_menu = ' <li>
                    <a href="#"  style="z-index:3;" onclick="mostrar_pedido();">
                       <span class="fa fa-shopping-cart" aria-hidden="true"></span> Cart
                    </a>
                </li> 
                <li>
                    <a href="#"  style="z-index:4;" onclick="mis_carritos_pendientes();">
                       My Pending Cart
                    </a>
                </li>  
                <li>
                    <a href="#"  style="z-index:5;" onclick="mis_pedidos();">
                       My Orders
                    </a>
                </li> ';
	break;
	case 3:
	//si es vendedor
	$nombre_usuario = '<a href="#"  >'.Nombre($_SESSION["logged_user"]).'</a>';
	break;
	case 4:
	//si es Cosultor
	$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';
	break;
	case 5:
	//si es Supervisor
	$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';
	break;
	
}
}

$menu_bar = '<ul class="nav navbar-nav navbar-right"   >
                
               '.$btns_menu.'
                <li  style="z-index:6;" >
                    
                        '.$nombre_usuario.'
                   
                </li>
                <li  style="z-index:7;" >
                     <a href="data/logout.php" class="btn btn-success nav-btn">Log Out</a>
                </li>
            </ul>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////AREA DE VARIABLES PARA LA PAGINA INDEX.PHP ///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* Lista de categorias a mostrar en el filtrado de la busqueda*/
$id_empresa = id_empresa($_SESSION["logged_user"]);

$lista_categorias = categorias_lista($id_empresa);
$categorias_filtro = "";
foreach ($lista_categorias as $id => $categoria){
			$categorias_filtro .= '<li><a href="#'.$id.'" >'.$categoria.'</a></li>';
			
		};
/*$header_index ES EL HEADER DE LA PAGINA INDEX.PHP*/
$ruta = "assets/images/header_dura.jpg";
$header_index = '<header class="hero overlay" style="background-image: url('.$ruta.');">  
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
             
                <span class="fa fa-bars"></span>
            </button>
            <a href="index.php" class="brand">
                <img src="assets/images/logo-allpart.png" alt="Knowledge">
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse">
            '.$menu_bar.'
        </div>
    </div>
</nav>
<div class="masthead single-masthead">
<div class="container">
    <div class="row">    
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8" style="z-index:2;">
            <div class="input-group">
                <div class="input-group-btn search-panel">
                    <button type="button" class="btn btn-success " data-toggle="dropdown">
                        <span id="search_concept">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      '.$categorias_filtro.'
                      <li class="divider"></li>
                      <li><a href="#0">All</a></li>
                    </ul>
                </div>
                <input type="hidden" name="txt_id_categoria" value="0" id="txt_id_categoria">
                <input type="text" id="txt_buscar" style="" class="form-control" placeholder=" Search ">
				
                <span class="input-group-btn">
                    <button class="btn btn-success" type="button" onclick="buscar();"><span class="fa fa-search"></span></button>
                </span>
            </div>
       </div>
        <div class="col-md-5 col-lg-4"  style="padding-bottom:8px; margin-top:-60px;">
            <a href="#" class="btn btn-hero" >
                <img src="assets/images/logos_empresas/DURA-logo.png" width="150" height="50"></span>
            </a>
        </div>
    </div>
</div>
</div>
   
</header>';
/*  
 <div class="masthead single-masthead">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <form method="post" onsubmit="return buscar()">
                        <input type="text" id="txt_buscar" class="search-field" placeholder=" Search "/>
                        <button type="submit" ><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <div class="col-md-4" style="padding-bottom:8px;">
                    <a href="#" class="btn btn-hero" >
                        <img src="assets/images/logos_empresas/DURA-logo.png" width="150" height="50"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
*/

/*$container_index ES EL CONTENEDOR PRINCIPAL DE LOS RESULTADOS DE LAS CONSULTAS EN LA PAGINA INDEX.PHP*/
$container_index = '<section class="topics">
    <div class="container">
        <div class="row">
            <div class="col-lg-12"  id="area_resultados">

                
				<div class="row"> 
                   
                </div>
              
			  </div>
			  <div class=""  id="resultados_js"></div>
			  <div class=""  id="resultados_mail"></div>
			  <div id="div_lista_pedido" class=" col-lg-12"></div> 
			  <div id="div_mis_pedidos" class=" col-lg-12"></div>
			  <div id="div_tabla_usuarios" class=" col-lg-12"></div>

        </div>
    </div>
</section>';

/*$modal_orden_index ES EL MODAL DONDE SE AGREGA EL NUMERO DE ORDEN DEL CLIENTE EN LA PAGINA INDEX.PHP*/
$modal_orden_index = '<div class="modal fade" id="modal_orden" tabindex="-1" role="dialog" aria-labelledby="Modal orden" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                            Add Purchase Order
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body">
                                        <input type="text" value="" id="txt_orden_compra" class="" placeholder="Numero de Orden"/>
                                        <input type="hidden" value="" id="txt_id_pedido" />
                                    
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-success " data-dismiss="modal" id="btn_guardar_orden">Save</button>
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">Close</button>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                    </div>';
				
					
/*$modal_select_dir_index ES EL MODAL PARA ESCOGER LAS DIRECCIONES DE FACTURACION Y ENVIO EN LA PAGINA INDEX.PHP*/
$modal_select_dir_index = '<div class="modal fade" id="modal_select_dir" tabindex="-1" role="dialog" aria-labelledby="Modal Dir" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                            Select your address
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body">
									<div class="row">
									<div class="col-sm-1"></div>
									
									<div id="div_dir_fac" class="row col-md-5" style=" max-height:450px; height:250px;">
									<h4> Bill to</h4><br/>
										<select class="form-control" name="" id="select_dir_fac">
									</select>
									<div id="datos_dir_fac"></div>
									
									</div>
									<div class="col-sm-1"></div>
									
									<div id="div_dir_suc" class="row col-md-5" style="max-height:450px; height:250px;" >
									<h4> Ship to</h4><br/>
									<select class="form-control" name="" id="select_dir_suc">
									</select>
									<div id="datos_dir_suc" ></div>
									
									</div>
									<div id="div_orden_cliente" class="row col-md-12" style=" max-height:50px; height:50px; padding-top:15px;" align="center">
									<input type="text" value="" id="txt_add_orden" placeholder="Add Customer P.O." /><span style="font-size:12px;">(Optional)</span>
									</div>
                                        <input type="hidden" value="" id="txt_id_pedido_dir" />
                                    </div>
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-success " data-dismiss="modal" id="btn_guardar_pedido">Order Now</button>
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">Close</button>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                    </div>';
					
					
$msj_sin_dir_fact_index = "Solicite al administrador que agregue su direccion de facturacion";
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
                   <!-- <ul class="footer-links">
                        <li><a href="#">Buzon de sugerencias</a></li>
                        <li><a href="#">Contactos</a></li>
                        <li><a href="#">otro campo mas</a></li>	
                    </ul> -->
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="copyright">
                        <p>© 2019 All Part Products & Services</p>
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
					
/*$resultados_busqueda_index variable con texto que devuelve el texto de resultados de busqueda en la pagina de buscar_archivo.php*/					
$resultados_busqueda_index = 'Results';
										
/*$btn_agregar_articulo_index texto en el boton de agregar articulos al pedido en buscar_archivo.php*/					
$btn_agregar_articulo_index = 'Add to Order';
										
/*$modal_msj_agregar_articulo_index texto en mesaje de confirmacion de agregar articulo a pedido en buscar_archivo.php*/					
$modal_msj_agregar_articulo_index = 'Succefully added';
										
/*$btn_ir_orden_index texto en boton de modal para ir a detalle de pedido buscar_archivo.php*/					
$btn_ir_orden_index = 'Go to Order';
										
/*$btn_close_modal_msj_index texto en boton de modal para ir a detalle de pedido buscar_archivo.php*/					
$btn_close_modal_msj_index = 'Close';
										
/*$modal_titulo_agregar_articulo_index texto en titulo de modal para ir a detalle de pedido buscar_archivo.php*/					
$modal_titulo_agregar_articulo_index = 'Order';
										
/*$sin_resultados_busqueda texto cuando no se encuentran sesultados buscar_archivo.php*/					
$sin_resultados_busqueda = 'No results found';
										
/*$modal_btn_continuar_index texto boton para continuar comprando buscar_archivo.php*/	
$modal_btn_continuar_index = 'Continue';

/* $mensaje_html es el contenido del correo que se envia al o a los vendedores - en este caso se uso una funcion para poder pasar por parametro los valores que se imprimen en el correo  pagina enviar.php*/

$msj_asunto_mail = 'Order ';
$nombre_from_mail =  'AllPart';
/* se declara el color para las tablas que se muestran en los correos */
$color_tablas_correo = '2AAAFF';
function get_msj_mail($nombre_empresa,$folio_pedido,$nombre_empresa_vend,$direccion_vendedor,$ciudad_sucursal_vendedor,$estado_sucursal_vendedor,$cp_sucursal_vendedor,$pais_sucursal_vendedor,$rfc_vend,$direccion_comprador,$ciudad_sucursal_comprador,$estado_sucursal_comprador,$cp_sucursal_comprador,$pais_sucursal_comprador,$rfc,$tabla_articulos,$tabla_totales,$po_cliente){
	
	global $color_tablas_correo;
if ($po_cliente == ""){ $po_cliente = "Pending"; }	

	
	
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
						<img src="https://catalogo.allpart.mx/assets/images/logos_empresas/DURA-logo.png"  width="150" height="50" alt="">
					</td> 
				</tr>
				<tr style="height: 50px;"> 
					
					<td style="width: 40%;" align="center" valign="middle">
						<h3 align="center">'.$nombre_empresa.'</h3>
					</td>
					<td style="width: 30%;" align="center" valign="middle">
						<p align="center">Orden #: '.$folio_pedido .'</p>
					</td>
				</tr>
				</table>			
		</header>
		<br>		


				<table style="width: auto; margin: 0 auto;  width: 900px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'" width="100%" cellspacing="0" cellpadding="0" border="2">
					<tr style="height: 50px; background:#'.$color_tablas_correo.';">
							  <td style="width: 30%;" align="center" valign="middle">
							  	<h3>VENDOR</h3>
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
	
if ($po_cliente == ""){ $po_cliente = "Pending"; }	
	
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
						<img src="https://catalogo.allpart.mx/assets/images/logos_empresas/DURA-logo.png"  width="150" height="50" alt="">
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
                  <h3>Your order was delivered </h3>
                </td>
          </tr>
          <tr>
            <td style="padding: 15px 15px;" align="center">
              <h4>Customer P.O.: '.$po_cliente.'</h4>
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
	
	if ($po_cliente == ""){ $po_cliente = "Pending"; }	
	
	
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
						<img src="https://catalogo.allpart.mx/assets/images/logos_empresas/DURA-logo.png"  width="150" height="50" alt="">
					</td> 
				</tr>
        <tr style="height: 50px;"> 
          
          <td style="width: 40%;" align="center" valign="middle">
            <h3 align="center">'.$nombre_empresa.'</h3>
          </td>
          <td style="width: 30%;" align="center" valign="middle">
            <p align="center">Order No: '.$folio_pedido.'</p>
          </td>
        </tr>
        </table>      
    </header>
    <br>    


        <table style="width: auto; margin: 0 auto;  width: 900px; font-family: sans-serif; border-collapse: collapse; border-radius: 1em 1em; overflow: hidden; border-color: #'.$color_tablas_correo.'" width="100%" cellspacing="0" cellpadding="0" border="2">
          <tr style="height: 50px; background:#'.$color_tablas_correo.';">
                <td style="width: 30%;" align="center" valign="middle">
                  <h3> CONGRATULATIONS! Your order was sent </h3>
                </td>
          </tr>
          <tr>
            <td style="padding: 15px 15px;">
              <h4>Customer P.O.: '.$po_cliente.'</h4>
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
$clave_cliente_tabla_mail = 'Code';
$nombre_art_tabla_mail = 'Name';
$cantidad_tabla_mail = 'Qty';
$precio_tabla_mail = 'Price';
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


/* mensaje que sale cundo precionan el boton order en en el menu de cliente en la pagina lista pedido.php*/
$msj_sin_pedidos_index = 'No items added';

/* ESTOS SON CAMPOS DE TEXTO DE LA LISTA DE PEDIDO Y TAMBIEN DEL LOS BOTONES ORDENAR PEDIDO Y ACPETAR DE LA CONFIRMACION DE ENVIO DE PEDIDO (MODAL) Y MENSAJE EN EL MODAL*/
$btn_remover_lista_pedido_index = 'remove';
$remover_lista_pedido_index = 'Remove';
$clave_lista_pedido_index = 'Code';
$nombre_articulo_lista_pedido_index = 'Name';
$cantidad_lista_pedido_index = 'Qty';
$precio_unitario_lista_pedido_index = 'Price';
$total_lista_pedido_index = 'Total';	
$txt_pedido_index = 'Order';
$btn_aceptar_ordenar_pedido = 'OK';
$btn_ordenar_pedido = 'Send Order';
$btn_guardar_pendiente = 'Save order for later';

//////// nota el span <span id="span_folio_pedido"></span>  funciona como depositorio para folio de pedido //////
$msj_ordenado_index = 'Order succefully processed <br/> confirmation  <b><span id="span_folio_pedido"></span></b>';

$modal_dir_fac_suc = '<div class="row">
<div class="modal fade" id="modal_pedido" tabindex="-1">
                		<div class="modal-dialog modal-lg">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						'.$txt_pedido_index.'
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body" align="center">
                					<p class="h4">'.$msj_ordenado_index.' </p>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					
                					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="">'.$btn_aceptar_ordenar_pedido.'</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>
                </div>';

/*VARIABLES DE LA PAGINA mis_pedidos.php lista de todos los pedidos del cliente*/
$comprador_tabla_mis_pedidos = 'Purchaser';
$sucursal_tabla_mis_pedidos = 'Facility';
$fecha_tabla_mis_pedidos = 'Date';
$folio_tabla_mis_pedidos = 'Order#';
$estatus_tabla_mis_pedidos = 'Status';
$traking_tabla_mis_pedidos = 'UPS Tracking #';
$total_tabla_mis_pedidos = 'Total';
$orden_cliente_tabla_mis_pedidos = 'Your P.O.';
/* leyenda de boton para retomar carrito */
$retomar_carrito = 'Resume Cart';

/* tipos de estatus para switch validacion*/
$estatus_tipo_pendiente = 'Pending Cart';
$estatus_tipo_ordenado = 'Ordered';
$estatus_tipo_proceso = 'In Process';
$estatus_tipo_ruta = 'In Transit';
$estatus_tipo_entregado = 'Delivered';

$titulo_modal_lista_articulos = 'Item List';
$btn_cerrar_modal_lista_articulos = 'Close';
$msj_sin_pedidos_mis_pedidos = 'No order found';
$msj_sin_carritos_pendientes = 'No carts saved';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////AREA DE VARIABLES PARA LA PAGINA CONSULTOR.PHP ///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*$header_consultor ES EL HEADER DE LA PAGINA CONSULTOR.PHP*/

$header_consultor = '<header class="hero overlay" style="background-image: url('.$ruta.');">  
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
             
                <span class="fa fa-bars"></span>
            </button>
            <a href="index.php" class="brand">
                <img src="assets/images/logo-allpart.png" alt="Knowledge">
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse">
            '.$menu_bar.'
        </div>
    </div>
</nav>
<div class="masthead single-masthead">
<div class="container">
    <div class="row">    
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-6" style="z-index:2;">
            <div class="input-group">
           
                <input type="text" id="txt_buscar" style="" class="form-control" placeholder="Order#, P.O., Item">
				
                <span class="input-group-btn">
                    <button class="btn btn-success" type="button" onclick="buscar();"><span class="fa fa-search"></span></button>
                </span>
            </div>
       </div>
        <div class="col-md-5 col-lg-4"  style="padding-bottom:8px; margin-top:-60px;">
            <a href="#" class="btn btn-hero" >
                <img src="assets/images/logos_empresas/DURA-logo.png" width="150" height="50"></span>
            </a>
        </div>
    </div>
</div>
</div>
   
</header>';

/*$container_consultor ES EL CONTENEDOR PRINCIPAL DE LOS RESULTADOS DE LAS CONSULTAS EN LA PAGINA consultor.PHP*/
$container_consultor = '<section class="topics">
    <div class="container">
        <div class="row">
				<div class="col-lg-12"  id="area_resultados"></div>
				<div class=""  id="resultados_js"></div>
				<div id="div_lista_pedidos" class=" col-lg-12"></div> 
		</div>
    </div>
</section>';
					
$resultados_busqueda_consultor = 'Results';
$sin_resultados_busqueda_consultor = 'No matchs found';

$folio_tabla_cosultor='Order#';
$fecha_tabla_cosultor='Date';
$orden_tabla_cosultor ='P.O.';
$estatus_tabla_cosultor='Status';
$tracking_tabla_cosultor='UPS Tracking #';
$clave_tabla_cosultor='Code';
$articulo_tabla_cosultor='Name';
$cantidad_tabla_cosultor='Qty';
$imagen_tabla_cosultor='Image';
	
	

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////AREA DE VARIABLES PARA LA PAGINA SUPERVISOR.PHP ///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*$header_SUPERVISOR ES EL HEADER DE LA PAGINA SUPERVISOR.PHP*/

$header_supervisor = '<header class="hero overlay" style="background-image: url('.$ruta.');">  
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
             
                <span class="fa fa-bars"></span>
            </button>
            <a href="index.php" class="brand">
                <img src="assets/images/logo-allpart.png" alt="Knowledge">
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse">
            '.$menu_bar.'
        </div>
    </div>
</nav>
<div class="masthead single-masthead">
<div class="container">
    <div class="row">    
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-6" style="z-index:2;">
            <div class="input-group">
           
                <input type="text" id="txt_buscar" style="" class="form-control" placeholder="Order#, P.O., Item">
				
                <span class="input-group-btn">
                    <button class="btn btn-success" type="button" onclick="buscar();"><span class="fa fa-search"></span></button>
                </span>
            </div>
       </div>
        <div class="col-md-5 col-lg-4"  style="padding-bottom:8px; margin-top:-60px;">
            <a href="#" class="btn btn-hero" >
                <img src="assets/images/logos_empresas/DURA-logo.png" width="150" height="50"></span>
            </a>
        </div>
    </div>
</div>
</div>
   
</header>';

/*$container_consultor ES EL CONTENEDOR PRINCIPAL DE LOS RESULTADOS DE LAS CONSULTAS EN LA PAGINA consultor.PHP*/
$container_supervisor = '<section class="topics">
    <div class="container">
        <div class="row">
				<div class="col-lg-12"  id="area_resultados"></div>
				<div class=""  id="resultados_js"></div>
				<div id="div_lista_pedidos" class=" col-lg-12"></div> 
		</div>
    </div>
</section>';
					
$resultados_busqueda_supervisor = 'Results';
$sin_resultados_busqueda_supervisor = 'No matchs found';

$folio_tabla_supervisor='Order#';
$fecha_tabla_supervisor='Date';
$orden_tabla_supervisor ='P.O.';
$estatus_tabla_supervisor='Status';
$tracking_tabla_supervisor='UPS Tracking #';
$clave_tabla_supervisor='Code';
$articulo_tabla_supervisor='Name';
$cantidad_tabla_supervisor='Qty';
$imagen_tabla_supervisor='Image';
	
?>