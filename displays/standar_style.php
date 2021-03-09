<?php  
$nombre_usuario = 'Usuario no registrado';
if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] == '')){ header('Location: login.php'); }
else {
$nombre_usuario = '<a href="login.php" class="dropdown-toggle" id="drop4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><span class="fa fa-user" aria-hidden="true"></span> '.Nombre($_SESSION["logged_user"]).'</a>';
$tipo_usuario = validar_usuario($_SESSION["logged_user"]); 
$folio_pedido_index = "1";
$btns_menu = '';
$spor_by = '';
$reportes = '	
				<li style="">
                    <a href="#" style="z-index:8;" onclick="mostrar_reportes();">
                      <span class="fa fa-table" aria-hidden="true"></span>  Reports
                    </a>
                </li>
				
			';
switch($tipo_usuario)
{
	case 1:
	// si es admin
	
	break;
	case 2:
	//si es comprador
	$btns_menu = ' <li style="">
                    <a href="#" style="z-index:3;" onclick="spotby_lista();">
                      <span class="fa fa-bars" aria-hidden="true"></span> Spot Buy
                    </a>
                </li> 
				<li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#" style="z-index:3;" onclick="mostrar_pedido();">
                      <span class="fa fa-bars" aria-hidden="true"></span>  Current Request
                    </a>
                </li> 
				 <li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#"  style="z-index:4;" onclick="mis_carritos_pendientes();">
                      <span class="fa fa-pause" aria-hidden="true"></span> Paused requests
                    </a>
                </li> 
			<!--	 <li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#"  style="z-index:4;" onclick="mis_pedidos_pend_aut();">
                      <span class="fa fa-hand-paper-o" aria-hidden="true"></span> Pedidos Pend. Autorizar          </a>
                </li> 
			-->	
                <li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#"  style="z-index:5;" onclick="mis_pedidos();">
                     <span class="fa fa-list-alt" aria-hidden="true"></span>  My requests
                    </a>
                </li> ';
	break;
	case 3:
	//si es vendedor
	
	$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';
	break;
	case 4:
	//si es Almacenista
	//$reportes = '';  //para no mostrar ningun reportes
	echo '<script> </script>';
	$btns_menu = '<!-- --><li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#" style="z-index:3;" onclick="mostrar_pedido();">
                      <span class="fa fa-bars" aria-hidden="true"></span>  Current request
                    </a>
                </li> 
				 <li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#"  style="z-index:4;" onclick="mis_carritos_pendientes();">
                      <span class="fa fa-pause" aria-hidden="true"></span> Paused requests
                    </a>
                </li> 
                <li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#"  style="z-index:5;" onclick="lista_requis_almacen();">
                     <span class="fa fa-list-alt" aria-hidden="true"></span>   Requests
                    </a>
                </li> ';
	break;
	case 5:
	//si es Supervisor
	//$nombre_usuario = '<a href="#" >'.Nombre($_SESSION["logged_user"]).'</a>';

	$btns_menu = ' <li style="">
                    <a href="#" style="z-index:3;" onclick="spotby_lista();">
                      <span class="fa fa-bars" aria-hidden="true"></span> Spot Buy
                    </a>
                </li> 
				<li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#" style="z-index:3;" onclick="mostrar_pedido();">
                      <span class="fa fa-bars" aria-hidden="true"></span>  Current request
                    </a>
                </li> 
				 <li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#"  style="z-index:4;" onclick="mis_carritos_pendientes();">
                      <span class="fa fa-pause" aria-hidden="true"></span> Paused requests
                    </a>
                </li> 
			<!--	 <li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#"  style="z-index:4;" onclick="mis_pedidos_para_aut();">
                      <span class="fa fa-bell-o" aria-hidden="true"></span> Solicitudes de Autorizacion  </a>
                </li>
			-->	
                <li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#"  style="z-index:5;" onclick="mis_pedidos();">
                     <span class="fa fa-list-alt" aria-hidden="true"></span>   My requests
                    </a>
                </li> 
                <li style="background-color:rgba(192,192,192,0.8);">
                    <a href="#"  style="z-index:5;" onclick="lista_pedidos();">
                     <span class="fa fa-tasks" aria-hidden="true"></span> Requests of my team 
                    </a>
                </li> ';
	break;
	
}
}



$menu_bar = '<ul class="nav navbar-nav navbar-right ">
                '.$reportes.'
		<!--	<li  style="">
                    <a href="#" style="z-index:9;" onclick="solicitudes();">
                      <span class="fa fa-life-ring" aria-hidden="true"></span>  Solicitudes
                    </a>
					
                </li>   -->
               
                <li class="dropdown" style="z-index:6; ">
                    
                '.$nombre_usuario.'
			<ul class="dropdown-menu"> '.$btns_menu.'</ul>  <!---->
                </li>
                <li  style="z-index:7;" >
                     <a href="data/logout.php" class="btn btn-primary" style="width:100px;">Salir</a>
                </li>
            </ul>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////AREA DE VARIABLES PARA LA PAGINA INDEX.PHP ///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* Lista de categorias a mostrar en el filtrado de la busqueda*/
$id_empresa = id_empresa($_SESSION["logged_user"]);
$id_usuario = $_SESSION["logged_user"];
$lista_categorias = categorias_lista($id_empresa);
$categorias_filtro = "";
foreach ($lista_categorias as $id => $categoria){
			$categorias_filtro .= '<li><a href="#'.$id.'" >'.$categoria.'</a></li>';
			
		};
/*$header_index ES EL HEADER DE LA PAGINA INDEX.PHP*/
$ruta = "assets/images/header_standar.jpg";
$almacenes_dura = "";
$lista_almacenes = lista_almacenes_consigna();
	foreach($lista_almacenes as $id_almacen => $almacen)
	{
	
	// busca si esta registrada una sucursal con el usuario seleccionado
	$consulta_reg_suc = "SELECT * 
					FROM registros_sucursales rs
					INNER JOIN sucursales s ON s.id_sucursal = rs.id_sucursal
					WHERE s.id_almacen = '$id_almacen' and rs.id_usuario = '$id_usuario'";
	$result_reg_suc = mysql_query($consulta_reg_suc, $conex) or die(mysql_error());
	//$row = mysql_fetch_assoc($result_reg_suc);
	$total_rows_reg_suc = mysql_num_rows($result_reg_suc);
	if ($total_rows_reg_suc > 0){ ///si encuentra el registro significa que tiene permitido el uso de la sucursal(almacen)
	$almacenes_dura .= '<option value="'.$id_almacen.'">'.$almacen.'</option>';	
	}	
			
	}
								

$header_index = '<header class="hero overlay" style="background-image: url('.$ruta.');"> 
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
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
                    <button type="button" class="btn btn-primary " data-toggle="dropdown">
                        <span id="search_concept">All</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      '.$categorias_filtro.'
                      <li class="divider"></li>
                      <li><a href="#0">All</a></li>
                    </ul>
                </div>
                <input type="hidden" name="txt_id_categoria" value="0" id="txt_id_categoria">
                <input type="text" id="txt_buscar" style="" class="form-control" placeholder="Search your item ...">
				
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" onclick="buscar();"><span class="fa fa-search"></span></button>
                </span>
            </div>
       </div>
        <div class="col-md-5 col-lg-4"  style="padding-bottom:8px; ">
           <select id="select_almacen_oc" class="select form-control ">
			'.$almacenes_dura.'
			</select> 
        </div>
    </div>
</div>
</div>

</header>';


 function busca_cc($id_empresa_user_activo){ 
global $database_conexion, $conex;

$consulta = "SELECT cc.nombre_cc as nombre_cc,  cc.id_cc as id_cc 
FROM centro_costos cc
WHERE cc.id_empresa='$id_empresa_user_activo'";

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
$lista = array();
if ($total_rows > 0){ // con resultados
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
	$lista[$row['id_cc']] = $row['nombre_cc']; //'';					
}
} 
else /// sin resultados
{
	$lista['0'] = 'Sin resultados';
}
return $lista; 
}
 function busca_usuarios($id_empresa_user_activo){ 
global $database_conexion, $conex;

$consulta = "SELECT user.nombre as nombre,user.apellido as apellido,user.username as username,  user.id as id 
FROM usuarios user
WHERE user.id_empresa='$id_empresa_user_activo' AND user.tipo_usuario='2'";

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
$lista = array();
if ($total_rows > 0){ // con resultados
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
	$lista[$row['id']] = $row['nombre']." ".$row['apellido']." (".$row['username'].")"; //'';					
}
} 
else /// sin resultados
{
	$lista['0'] = 'Sin resultados';
}
return $lista; 
}
 function busca_usuarios_aut_spend($id_empresa_user_activo){ 
global $database_conexion, $conex;

$consulta = "SELECT user.nombre as nombre,user.apellido as apellido,user.username as username,  user.id as id 
FROM usuarios user
WHERE user.id_empresa='$id_empresa_user_activo' AND user.autorizar_limit_spend='1'";

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
$lista = array();
if ($total_rows > 0){ // con resultados
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
	$lista[$row['id']] = $row['nombre']." ".$row['apellido']." (".$row['username'].")"; //'';					
}
} 
else /// sin resultados
{
	$lista['0'] = 'Sin resultados';
}
return $lista; 
}
 function busca_dep($id_empresa_user_activo){ 
global $database_conexion, $conex;

$consulta = "SELECT dep.departamento as departamento,  dep.id_departamento as id_departamento 
FROM departamentos dep
WHERE dep.id_empresa='$id_empresa_user_activo'";

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
$lista = array();
if ($total_rows > 0){ // con resultados
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
	$lista[$row['id_departamento']] = $row['departamento']; //'';					
}
} 
else /// sin resultados
{
	$lista['0'] = 'Sin resultados';
}
return $lista; 
}
 function busca_comodity($id_empresa_user_activo){ 
global $database_conexion, $conex;

$consulta = "SELECT cat.categoria as categoria,  cat.id_categoria as id_categoria 
FROM categorias cat";

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
$total_rows = mysql_num_rows($resultado);
$lista = array();
if ($total_rows > 0){ // con resultados
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
{
	$lista[$row['id_categoria']] = $row['categoria']; //'';					
}
} 
else /// sin resultados
{
	$lista['0'] = 'Sin resultados';
}
return $lista; 
}
/* $lista_opciones_articulos = '';
$lista_articulos_empresa = busca_art($id_empresa);
//$lista_opciones_articulos .= '<option value="X" > TODOS </option>';
foreach($lista_articulos_empresa as $idart => $nombreart){
$lista_opciones_articulos .= '<option value="'.$idart.'" >'.$nombreart.'</option>';
} */
$lista_opciones_cc = '';
$lista_cc_empresa = busca_cc($id_empresa);
//$lista_opciones_cc .= '<option value="X" > TODOS </option>';
foreach($lista_cc_empresa as $idcc => $nombrecc){
$lista_opciones_cc .= '<option value="'.$idcc.'" >'.$nombrecc.'</option>';
}
$lista_opciones_usuarios = '';
$lista_usuarios_empresa = busca_usuarios($id_empresa);
//$lista_opciones_usuarios .= '<option value="X" > TODOS </option>';
foreach($lista_usuarios_empresa as $iduser => $nombreuser){
$lista_opciones_usuarios .= '<option value="'.$iduser.'" >'.$nombreuser.'</option>';
}
$lista_opciones_dep = '';
$lista_dep_empresa = busca_dep($id_empresa);
//$lista_opciones_dep .= '<option value="X" > TODOS </option>';
foreach($lista_dep_empresa as $iddep => $nombredep){
$lista_opciones_dep .= '<option value="'.$iddep.'" >'.$nombredep.'</option>';
}
$lista_opciones_comodity = '';
$lista_comodity_empresa = busca_comodity($id_empresa);
//$lista_opciones_comodity .= '<option value="X" > TODOS </option>';
foreach($lista_comodity_empresa as $idcomodity => $nombrecomodity){
$lista_opciones_comodity .= '<option value="'.$idcomodity.'" >'.$nombrecomodity.'</option>';
}
$lista_opciones_usu_aut = '';
$lista_usu_aut = busca_usuarios_aut_spend($id_empresa);
foreach($lista_usu_aut as $idaut => $nombre_usu){
$lista_opciones_usu_aut .= '<option value="'.$idaut.'" >'.$nombre_usu.'</option>';
}

/*$container_index ES EL CONTENEDOR PRINCIPAL DE LOS RESULTADOS DE LAS CONSULTAS EN LA PAGINA INDEX.PHP*/
$container_index = '
<section class="topics">
    <div class="container" style=" min-height:350px;">
		<div class="row">
			<input type="hidden" id="txt_mostrando" value="0" />
			<div class="col-lg-12"  id="area_resultados"></div>
			<div class=""  id="resultados_js"></div>
			<div class=""  id="resultados_js_autorizacion"></div>
			<div class=""  id="resultados_mail"></div>
			<div id="div_lista_pedido" class=" col-lg-12"></div> 
			<div id="div_mis_pedidos" class=" col-lg-12"></div>
			<div id="div_mis_pedidos_pend_aut" class=" col-lg-12"></div>
			
			<div id="lista_spotby">
			<div align="right"><button class="btn btn-primary" onclick="spotby();" > Add </button><br><br></div>
			<div id="lista_spotby_tabla"></div>
			</div>
			
			<div id="div_mis_solicitudes" class=" col-lg-12">
						
				<div id="div_limit_spend" class=" col-lg-12">
					<div id="div_nuevo_limit" class=" col-lg-5">
						<form action="" class="form-horizontal" onsubmit="return sin_accion();">
							<input type="hidden" value="" id="txt_id_articulo"/> 
							<input type="hidden" value="" id="txt_art_id_empresa"/>    
							<div class="form-group"> 
								<h3 align="center">Establecer limite de spend</h3><br />
									<label for="select_concepto" class="col-sm-4 control-label">Aplicar limite a: </label> 
										<div class="col-sm-4">
											<select class="form-control " name="" id="select_concepto"> 
												<option value="1" >Articulo</option>
												<option value="2" >Centro de costos</option>
												<option value="3" >Departamento</option>
												<option value="4" >Usuario</option>
											</select>
										</div> 
							</div>
							<div class="form-group">
								<label for="txt_monto_limite" class="col-sm-4 control-label">Cantidad limite:</label>
									<div class="col-sm-8">
										<input type="number" class="form-control" id="txt_monto_limite" placeholder="$$$$$$">
									</div>
							</div>
							<div class="form-group">
								<label for="datetimepicker1" class="col-sm-4 control-label">Fecha de inicio:</label>
									<div class="col-sm-8">
										<div class="input-group date" id="datetimepicker1">
											<input type="text" class="form-control" id="txt_fecha_inicio"/> 
											<span class="input-group-addon"> <span class="fa fa-calendar"></span> </span> 
										</div>
									</div>
							</div>	
							<div class="form-group"> 
								<label for="select_periodo" class="col-sm-4 control-label">Periodo:</label>
								<div class="col-sm-3">
									<select class="form-control " name="" id="select_periodo">
										<option value="1" >Meses</option>
										<option value="2" >Dias</option>
									</select> 
								</div>
								<div class="col-sm-2">
									<input type="number" class="form-control" id="txt_cantidad_periodo" value="1"> 
								</div> 
								<div class="col-sm-3">
									<div class="checkbox" title="Para que el limite sea constante en los periodos siguientes" >
										<label>
										<input type="checkbox" id="check_ciclo" /> Ciclo Automatico
										</label>
									</div>
								</div>
								
							</div> 
							<div class="form-group" id="div_select_articulos">	
								<label for="select_articulo" class="col-sm-4 control-label">Nombre Articulo:</label>	
									<div class="col-sm-8">														
										<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_articulo" > 
											<option value="" selected disabled hidden>Seleccionar Articulo</option> 
										</select>	
									</div>  
							</div>
							<div class="form-group" id="div_select_cc">	
								<label for="select_cc" class="col-sm-4 control-label">Centro de costos:</label>
									<div class="col-sm-8">	
										<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_cc" > 
											<option value="" selected disabled hidden>Seleccionar Centro de Costos</option> '.$lista_opciones_cc.'
										</select>
									</div> 
							</div>
							<div class="form-group" id="div_select_usuarios">
								<label for="select_usuario" class="col-sm-4 control-label">Usuario Comprador:</label>
									<div class="col-sm-8">
										<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_usuario" >
											<option value="" selected disabled hidden>Seleccionar Usuario</option> '.$lista_opciones_usuarios.'
										</select>
									</div> 
							</div>
							<div class="form-group" id="div_select_departamentos">
								<label for="select_dep" class="col-sm-4 control-label">Departamento:</label>
									<div class="col-sm-8">	
										<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_dep" > 
											<option value="" selected disabled hidden>Seleccionar Departamento</option> '.$lista_opciones_dep.'
										</select>
									</div>  
							</div>
							<div class="form-group">
								<div class="col-sm-4"> </div> 
								<div class="col-sm-8">				 														    	
									<input type="button" value="Establecer Limite" id="btn_establecer_limite" class="btn btn-primary btn-md" /> 
								</div> 
							</div>
						</form>
						
					</div>
					<div id="div_limites_establecidos" class=" col-lg-7">
						Tabla con registros de limites establecidos
					</div>
				</div>	
			</div>
			<div id="div_tabla_usuarios" class=" col-lg-12"></div>	
		</div>
		<div class="modal fade" id="validacion_autorizacion">
			<div class="modal-dialog">
				<div class="modal-content">
					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 class="modal-title">
							Limite Excedido
						</h3>
					</div>
					
					<div class="modal-body">
						<p class="h4">Mensaje de validacion de limite de spend, en dado caso de que alguno de los datos del pedido: articulo, centro de costos, departamento o usuario esten excediendo el limite, podran pedir autorizacion para continuar con el pedido </p>
						<span id="t_limites_excedidos"> </span>
					</div>
					
					<div class="modal-footer">
					<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_usu_aut" > 
											<option value="" selected disabled hidden>Pedir Autorizacion a:</option> '.$lista_opciones_usu_aut.'
										</select>
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="solicitar_auto">Solicitar Autorizacion</button>
						<button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
					</div>
					
				</div>
			</div>
		</div>
		<div class="modal fade" id="autorizacion_detalle" role="dialog" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <!-- Header de la ventana -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">
                             <span id="span_usuario"></span> solicita autorizacion para este pedido:
                        </h3>
                    </div>
                    <!-- Contenido de la ventana -->
                    <div class="modal-body" style="overflow:auto;">
                      
                    </div>
                    <!-- Footer de la ventana -->
                    <div class="modal-footer">
                        
                        <button type="button" class="btn btn-default " data-dismiss="modal" onclick="">Cerrar </button> 
                    </div>
                    
                </div>
            </div>
        </div>	
		
		<div class="row col-md-12" id="div_generador_reportes">
			<div class="panel panel-primary">
				<div class="panel-heading" role="tab" id="generador-esconder">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#generador" aria-expanded="false" aria-controls="generador" onclick="">
						<div style="width:100%; height:20px;">
				
						Reports Options<span class="caret pull-right"></span>
						</div>
						</a>
					</h4>
				</div>
			
				<div id="generador" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="generador-esconder">
				<div class="panel-body">	
					
					<!-- <div class="row col-md-12" align="center" >
						<h3> Reportes de consumos </h3>
					</div>
					-->
					<div class="col-md-5"  >
						<h4> Search range</h4>
						<div class="form-check">
							<input type="radio" class="form-check-input" id="opcion_horas" name="grupo_periodo" value="1">
							<label class="form-check-label" for="opcion_horas">Last 
							<input type="number" align="center" style="width:40px;" value="24" id="txt_p_horas"> Hours
							</label>
						</div>
						<div class="form-check">
							<input type="radio" class="form-check-input" id="opcion_dias" name="grupo_periodo" value="2">
							<label class="form-check-label" for="opcion_dias">Last 
							<input type="number" align="center" style="width:40px;" value="7"  id="txt_p_dias"> Days
							</label>
						</div>
						<div class="form-check">
							<input type="radio" class="form-check-input" id="opcion_meses" name="grupo_periodo" value="3" checked>
							<label class="form-check-label" for="opcion_meses">Last 
							<input type="number" align="center" style="width:40px;"  value="1"  id="txt_p_meses"> Months
							</label>
						</div>
						<div class="form-check">
							<input type="radio" class="form-check-input" id="opcion_rango" name="grupo_periodo" value="4">
							<label class="form-check-label" for="opcion_rango">De  </label>
								<div class="input-group date col-lg-10" id="datepicker_ini">
									<input type="text" class="form-control" size="10" id="txt_fecha_ini"/> 
									<span class="input-group-addon"> <span class="fa fa-calendar"></span> </span> 
								</div>
									<b>a</b> 
								<div class="input-group date col-lg-10" id="datepicker_fin">
									<input type="text" class="form-control" id="txt_fecha_fin"/> 
									<span class="input-group-addon"> <span class="fa fa-calendar"></span> </span> 
								</div>
							
						</div>
					</div>
					<div class="col-md-4"> 
					   
						<div class="col-lg-12">
							<h4 >Reports list:</h4>
							<div class="form-check">
								<input type="radio" class="form-check-input" id="rep_mat_req" name="grupo_tipo_reporte" value="1" checked>
								<label class="form-check-label" for="rep_mat_req">Requested Materials
								
								</label>
							</div>
						<div class="form-check" title="This report not use time range">
								<input type="radio" class="form-check-input" id="rep_inv" name="grupo_tipo_reporte" value="2">
								<label class="form-check-label" for="rep_inv">Inventory and Consume
								
								</label>
							</div>
							<div class="form-check">
								<input type="radio" class="form-check-input" id="rep_sug_req" name="grupo_tipo_reporte" value="5">
								<label class="form-check-label" for="rep_sug_req">Reorder Points 
								
								</label>
							</div>		
						<!--	<div class="form-check">
								<input type="radio" class="form-check-input" id="rep_consumo" name="grupo_tipo_reporte" value="3">
								<label class="form-check-label" for="rep_consumo"> Consumed Materials
								
								</label>
							</div>
								<div class="form-check">
								<input type="radio" class="form-check-input" id="rep_mat_pend" name="grupo_tipo_reporte" value="4">
								<label class="form-check-label" for="rep_mat_pend"> Pendding P.O. Materials 
								
								</label>
							</div>-->
							
						</div>
					</div>
					<div class="col-md-3"  >
						
						<h4>Generate as:</h4>
							<div class="form-check">
								<input type="radio" class="form-check-input" id="opcion_tabla" name="grupo_tipo_salida" value="1" checked>
								<label class="form-check-label" for="opcion_tabla">Table View
								
								</label>
							</div>
							<div class="form-check">
								<input type="radio" class="form-check-input" id="opcion_excel" name="grupo_tipo_salida" value="2">
								<label class="form-check-label" for="opcion_excel"> Excel File 
								
								</label>
							</div>
						<!-- <div class="form-check">
							<input type="radio" class="form-check-input" id="opcion_grafica" name="grupo_tipo_salida" value="3">
							<label class="form-check-label" for="opcion_grafica">Graphyc  
							
							</label>
						</div> -->
						<button class="btn btn-primary" id="btn_generar_reporte"> Generate Report </button>
					</div>
				</div>
				</div>
			</div>	
				
			<div class="row col-md-12" align="center" id="div_reportes_graficas">
			<!--	<h3> Vista Reporte y Vista Grafica </h3>   -->
				
			</div>	
		</div>
		
	</div>
</section>';



$modales_spotby='<div class="modal fade" id="spotby" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title" id="detalle_modal_titulo">
                                           SPOT BY
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body" style="overflow:auto;">
                                       
									   <div class=" col-lg-12" >
										<div class="form-group col-lg-8">
											<label >Description</label>
											<input class="form-control" id="descripcion_spotby">
										</div>
										<div class="form-group col-lg-3">
											<label >Quantity</label>
											<input type="number" class="form-control" id="cantidad_spotby">
										</div>
										</div>
										<div class=" col-lg-6" >
										<div class="form-group col-lg-12">
											<label >Additional data</label>
											<input class="form-control" type="text" id="a_datos_spotby">
										</div>
										
										</div>
										<div class=" col-lg-6" >
										
										<div class="form-group col-lg-12">
										<br>
										<form id="formulario" method="post" enctype="multipart/form-data" >
											<input id="file_input" type="file" name="file" accept="image/*, .docx, .pdf, .xlsx, .msg" />
										</form>
										</div>
										</div>
									</div>
									<input type="hidden" id="nombre_imagen_spotby">
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-primary " onclick="spotby_save();">Send</button>
										<button type="button" class="btn btn-primary " data-dismiss="modal">Close</button>
                                    </div>
									</div>	
									</div>
    </div>
	
	
	
	<div class="modal fade" id="spotby_imagen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title" id="detalle_modal_titulo">
                                           Image
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body" style="overflow:auto;" >
                                       <div id="spotby_imagen_body" align="center">
									   
									   </div>
									</div>
									
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
									<button type="button" class="btn btn-primary " data-dismiss="modal">Close</button>
                                    </div>
								</div>	
							</div>
    </div>';

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
					
/*$modal_cc_recolector_oc EN LA PAGINA INDEX.PHP*/
$modal_cc_recolector_oc_index = '<div class="modal fade" id="modal_cc_recolector_oc" tabindex="-1" role="dialog" aria-labelledby="Modal Dir" aria-hidden="true">
                         <div class="modal-dialog modal-md">
                             <div class="modal-content">
                                 <!-- Header de la ventana -->
                                 <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                     <h3 class="modal-title">
                                         Opciones del pedido  
                                     </h3>
                                 </div>
                                 <!-- Contenido de la ventana -->
                                 <div class="modal-body">
			<div class="row">
					<center>
						<div class="col-sm-2"></div>
						<div id="div_centro_costos" class="row col-xs-8" style=" max-height:450px; ">
						<h4>Centro de Costos</h4>
							<select class="form-control" name="" id="select_orden_cc">
						</select>
						<div id="datos_centro_costos"></div>
						
						</div>
					</center>
					<br/>					
			 </div>
			 <div class="row">
					<center>
						<div class="col-sm-2"></div>
						<div id="div_recolector" class="row col-xs-8" style="max-height:450px;" >
						<h4>Recolector</h4>
						<select class="form-control" name="" id="select_orden_recolector">
						</select>
						<div id="datos_recolector"></div>
						
						</div>
					</center>
					<br/>
			</div>
			 <div class="row">	
						<div class="col-sm-2"></div>			 
						<div id="div_orden_cliente" class="row col-xs-8" style=" max-height:50px;  padding-top:15px;" align="center">
						<input type="text" value="" id="txt_add_orden" placeholder="Agregar O.C. interna" /><span style="font-size:12px;" >(Opcional)</span>
						</div>
                                     <input type="hidden" value="" id="txt_id_pedido_dir" />
                              
            </div>
                                 </div>
                                 <!-- Footer de la ventana -->
                                 <div class="modal-footer">
                                     
                                     <button type="button" class="btn btn-success " data-dismiss="modal" id="btn_guardar_pedido">Ordenar Ahora</button>
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
					
					
/*$resultados_busqueda_index variable con texto que devuelve el texto de resultados de busqueda en la pagina de buscar_archivo.php*/				
$resultados_busqueda_index = 'Resultados de busqueda';

/*$btn_agregar_articulo_index texto en el boton de agregar articulos al pedido en buscar_archivo.php*/					
$btn_agregar_articulo_index = 'Add to request';
										
/*$modal_msj_agregar_articulo_index texto en mesaje de confirmacion de agregar articulo a pedido en buscar_archivo.php*/					
$modal_msj_agregar_articulo_index = 'Item added to request list.';
										
/*$btn_ir_orden_index texto en boton de modal para ir a detalle de pedido buscar_archivo.php*/					
$btn_ir_orden_index = 'Request List';
										
/*$btn_close_modal_msj_index texto en boton de modal para ir a detalle de pedido buscar_archivo.php*/					
$btn_close_modal_msj_index = 'Close';
										
/*$modal_titulo_agregar_articulo_index texto en titulo de modal para ir a detalle de pedido buscar_archivo.php*/					
$modal_titulo_agregar_articulo_index = 'Request';
										
/*$sin_resultados_busqueda texto cuando no se encuentran sesultados buscar_archivo.php*/					
$sin_resultados_busqueda = 'No se encontraron resultados';
										
/*$modal_btn_continuar_index texto boton para continuar comprando buscar_archivo.php*/	
$modal_btn_continuar_index = 'Continue';
// mensaje que aparece despues de agregar un articulo al pedido
$modal_al_agregar_articulo = '<div class="modal fade" id="ventana1" tabindex="-1">
                		<div class="modal-dialog">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						'.$modal_titulo_agregar_articulo_index.'
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<p class="h4">'.$modal_msj_agregar_articulo_index.'</p>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="mostrar_pedido2();">'.$btn_ir_orden_index.'</button>
                					<button type="button" class="btn btn-info" data-dismiss="modal">'.$modal_btn_continuar_index.'</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>';
/* $mensaje_html es el contenido del correo que se envia al o a los vendedores - en este caso se uso una funcion para poder pasar por parametro los valores que se imprimern en el correo pagina enviar.php*/
$msj_asunto_mail = 'Request ';
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
/* mensaje que sale cundo precionan el boton order en en el menu de cliente en la pagina lista pedido.php*/
$msj_sin_pedidos_index = 'Not found Items';
	
/* ESTOS SON CAMPOS DE TEXTO DE LA LISTA DE PEDIDO Y TAMBIEN DEL LOS BOTONES ORDENAR PEDIDO Y ACPETAR DE LA CONFIRMACION DE ENVIO DE PEDIDO (MODAL) Y MENSAJE EN EL MODAL */
$btn_remover_lista_pedido_index = 'remover';
$remover_lista_pedido_index = 'Remove';
$clave_lista_pedido_index = '#Part';
$nombre_articulo_lista_pedido_index = 'Item';
$cantidad_lista_pedido_index = 'Qty.';
$precio_unitario_lista_pedido_index = 'Unit. Price';
$total_lista_pedido_index = 'Total';	
$txt_pedido_index = 'Request';
$btn_aceptar_ordenar_pedido = 'Ok';
$btn_ordenar_pedido = 'Order request';
$btn_guardar_pendiente = 'Save request for later';

//////// nota el span <span id="span_folio_pedido"></span>  funciona como depositorio para folio de pedido //////
$msj_ordenado_index = 'The request <b><span id="span_folio_pedido"></span></b>  has been ordered';

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
                				<div class="modal-body">
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
$sucursal_tabla_mis_pedidos = 'Location';
$fecha_tabla_mis_pedidos = 'Date';
$folio_tabla_mis_pedidos = '#Req';
$estatus_tabla_mis_pedidos = 'Status';
$traking_tabla_mis_pedidos = '$Tracking';
$cc_tabla_mis_pedidos = 'Centro de Costos';
$recolector_tabla_mis_pedidos = 'Recolector';
$total_tabla_mis_pedidos = 'Total';
$orden_cliente_tabla_mis_pedidos = 'Ord. Compra';
/* leyenda de boton para retomar carrito */
$retomar_carrito = 'Pick up again';

/* tipos de estatus para switch validacion*/
$estatus_tipo_pendiente = 'Pendding';
$estatus_tipo_ordenado = 'Ordered';
$estatus_tipo_proceso = 'Process';
$estatus_pedido_preparado = 'Sending Supplies';
$estatus_tipo_ruta = 'En Ruta';
$estatus_tipo_entregado = 'Surtido';

$titulo_modal_lista_articulos = 'Items List';
$btn_cerrar_modal_lista_articulos = 'Close';
$msj_sin_pedidos_mis_pedidos = 'You not have request';
$msj_sin_carritos_pendientes = 'You not have paused request';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////AREA DE VARIABLES PARA LA PAGINA CONSULTOR.PHP ///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*$header_consultor ES EL HEADER DE LA PAGINA CONSULTOR.PHP*/

$header_consultor = '<header class="hero overlay" style="background-image: url('.$ruta.');"> 
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
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
           
                <input type="text" id="txt_buscar" style="" class="form-control" placeholder="#Pedido, Orden de compra, Articulo">
				
                <span class="input-group-btn">
                    <button class="btn btn-success" type="button" onclick="buscar();"><span class="fa fa-search"></span></button>
                </span>
            </div>
       </div>
        <div class="col-md-5 col-lg-4"  style="padding-bottom:8px; margin-top:-60px;">
            <a href="#" class="btn btn-hero" >
                <img src="assets/images/LOGOGEN.png" ></span>
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
					
$resultados_busqueda_consultor = 'Coincidencias';
$sin_resultados_busqueda_consultor = 'No se encontraron coincidencias';

$folio_tabla_cosultor='Folio';
$fecha_tabla_cosultor='Fecha';
$orden_tabla_cosultor ='Orden de Compra';
$estatus_tabla_cosultor='Estatus';
$tracking_tabla_cosultor='#Tracking';
$clave_tabla_cosultor='Clave';
$articulo_tabla_cosultor='Articulo';
$cantidad_tabla_cosultor='Cantidad';
$imagen_tabla_cosultor='Imagen';



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////AREA DE VARIABLES PARA LA PAGINA SUPERVISOR.PHP ///////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*$header_SUPERVISOR ES EL HEADER DE LA PAGINA SUPERVISOR.PHP*/

$header_supervisor = '<header class="hero overlay" style="background-image: url('.$ruta.');"> 
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
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
        
       
    </div>
</div>
</div>

</header>';

/*$container_consultor ES EL CONTENEDOR PRINCIPAL DE LOS RESULTADOS DE LAS CONSULTAS EN LA PAGINA supervisor.PHP*/
$container_supervisor = '<section class="topics">
    <div class="container">
        <div class="row">
				<div class="col-lg-12"  id="area_resultados"></div>
				<div class=""  id="resultados_js"></div>
				<div id="div_lista_pedidos" class=" col-lg-12"></div> 
		</div>
    </div>
</section>';
					

$folio_tabla_supervisor='Folio';
$fecha_tabla_supervisor='Fecha';
$orden_tabla_supervisor ='Orden de Compra';
$estatus_tabla_supervisor='Estatus';
$tracking_tabla_supervisor='#Tracking';
$clave_tabla_supervisor='Clave';
$articulo_tabla_supervisor='Articulo';
$cantidad_tabla_supervisor='Cantidad';
$imagen_tabla_supervisor='Imagen';
$precio_tabla_supervisor='Precio';
$total_tabla_supervisor='Total';	
?>