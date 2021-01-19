<?php  $nombre_usuario = 'Usuario no registrado';
if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] == '')){ header('Location: login.php'); }
else {
$nombre_usuario = '<a href="login.php">'.Nombre($_SESSION["logged_user"]).'</a>';
$tipo_usuario = validar_usuario($_SESSION["logged_user"]); 
$btns_menu_vendor = '';
switch($tipo_usuario)
{
	case 1:
	// si es admin
	
	break;
	case 2:
	//si es requisitor
	
	break;
	case 17:
	//si es vendedor  mostrar_articulos(9);
	$btns_menu_vendor = '
				<li class="dropdown" style="background-color:rgba(192,192,192,0.8); z-index:4;">
					<a href="#" class="dropdown-toggle" id="dropinv" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                       Pedido Traspaso
                    </a>
					<ul class="dropdown-menu">
					
						<li  style="background-color:rgba(192,192,192,0.8);">
							<a href="#" onclick="lista_solicitudes_traspaso();">
									Lista de solicitudes de traspaso
							</a>
						</li>
					</ul> 
                </li>
				<li class="dropdown" style="background-color:rgba(192,192,192,0.8); z-index:5;">
					<a href="#" class="dropdown-toggle" id="dropinv" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                       Pedido NEF
                    </a>
					<ul class="dropdown-menu">
					
						<li  style="background-color:rgba(192,192,192,0.8);">
							<a href="#" onclick="lista_pedidos_nef();">
									Lista de Pedidos NEF
							</a>
						</li>
					</ul> 
                </li> ';
	$nombre_usuario = '<a href="#" class="dropdown-toggle" id="dropinv" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">'.Nombre($_SESSION["logged_user"]).'</a>';
	break;
	
}
}

$menu_bar = '<ul class="nav navbar-nav navbar-right">
                
               '.$btns_menu_vendor.'
                <li style="background-color:rgba(192,192,192,0.8);" class="dropdown">
                    '.$nombre_usuario.'
					<ul class="dropdown-menu">		
					<li >
                     <a href="data/logout.php" class="btn btn-success nav-btn">cerrar</a>
					</li>
					</ul>
                </li>
               
            </ul>';
			

			
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////AREA DE VARIABLES PARA LA PAGINA VENDOR.PHP ///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
/*$header_vendor ES EL HEADER EN LA PAGINA VENDOR.PHP*/					
$ruta = "assets/images/poly-blue.png";
$header_vendor = '<header class="hero overlay" style="background-image: url('.$ruta.');">  
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>
            <a href="vendor.php" class="brand">
                <img src="assets/images/logo-allpart.png" alt="Knowledge">
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse">
            '.$menu_bar.'	
        </div>
    </div>
</nav>
    
</header>';		
			
/*$content_vendor ES EL CONTENIDO DONDE SE MUESTRAN LOS RESULTADOS DE LAS CONSULTAS EN LA PAGINA VENDOR.PHP*/					
$content_vendor = '';	
$folio_tabla_mis_pedidos = 'Folio';
$clave_lista_pedido_index = 'Clave';
$nombre_articulo_lista_pedido_index = 'Nombre';
$cantidad_lista_pedido_index = 'Cant.';
$precio_unitario_lista_pedido_index = 'Precio';
$total_lista_pedido_index = 'Total';
/*$modal_tracking_vendor ES EL MODAL PARA AGREGAR NUMERO DE TRACKING EN LA PAGINA VENDOR.PHP*/					
$modal_tracking_vendor = '<div class="modal fade" id="modal_tracking" tabindex="-1" role="dialog" aria-labelledby="Modal Traking" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                            Agregar #Tracking
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body">
                                        <input type="text" value="" id="txt_tracking" class="" placeholder="#Tracking"/>
                                        <input type="hidden" value="" id="txt_id_pedido" />
                                    
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-success " data-dismiss="modal" id="btn_guardar_tracking">Guardar</button>
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">Cerrar</button>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                    </div>';	

/*$modal_tracking_vendor ES EL MODAL PARA AGREGAR NUMERO DE TRACKING EN LA PAGINA VENDOR.PHP*/					
$footer_vendor = '<footer>
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
                        <li><a href="#"></a></li>
                        <li><a href="#">Contactos</a></li>
                        <li><a href="#"></a></li>	
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

	
?>