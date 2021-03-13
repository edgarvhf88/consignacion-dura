<?php include("data/conexion.php"); ?>

<!DOCTYPE html>
<html lang="es-mx">
<head>
<?php  

if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] == '')){ 
/*header('Location: login.php');*/ 
echo '<script> window.location.replace("login.php"); </script>';
}
else { $tipo_usuario = validar_usuario($_SESSION["logged_user"]);}
/// $tipo_usuario = 1 == requisitor
switch($tipo_usuario)
{
	case 1:
	//header('Location: admin.php');
	echo '<script> window.location.replace("admin.php"); </script>';
	break;
	case 2:
	//header('Location: index.php');
	break;
	case 3:
	//header('Location: vendor.php');
	echo '<script> window.location.replace("vendor.php"); </script>';
	break;
	case 4:
	//header('Location: index.php');
	break;
	case 5:
	//header('Location: supervisor.php');
	break;
	case 11:
	//header('Location: cxc.php');
	echo '<script> window.location.replace("cxc.php"); </script>';
	break;
	case 17:
	//header('Location: transfer.php');
	echo '<script> window.location.replace("transfer.php"); </script>';
	break;
	
}
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////
$Display = '';			
$display_empresas = display_empresas();
$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	//echo $id." ** ".id_empresa($_SESSION["logged_user"])."<br />"; 
};

include("displays/".$Display.".php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Control Inventario Consignas </title>

    <!-- CSS -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" type="image/png" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
	<link href="assets/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">

</head>
<body class="archive">
<script language="javascript">
var timestamp = null;
function cargar_push() 
{ 
	$.ajax({
	async:	true, 
    type: "POST",
    url: "data/act_push.php",
    data: "&timestamp="+timestamp,
	dataType:"html",
    success: function(data)
	{	
		var json    = eval("(" + data + ")");
		timestamp 	= json.timestamp;
		id        	= json.id;
		status      = json.status;
		tipo      	= json.tipo;
		id_reg      = json.id_reg;
		
		if(timestamp == null)
		{
		
		}
		else
		{
			$.ajax({
			async:	true, 
			type: "POST",
			url: "data/actualizar.php",
			data: {tipo:tipo,id_reg:id_reg},
			dataType:"html",
			success: function(data)
			{	
				$('#resultados_js').html(data);
				
			}
			});	
		}
		setTimeout('cargar_push()',1000);
		    	
    }
	});		
}



</script>
<script> 
function mostrar_user(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
   //alert("ok");
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_usuarios.php",
				data: {id_user:id_user},
				success: function(resultados)
				
				{ 
								
				$("#div_tabla_usuarios").html(resultados);		
			
				}
			});
		return false;	
   
   
   };

//*******************************//////SPOT BY//////*******************************************
function spotby_lista(){
			var tipo =1;
			limpiardivs();
			$("#lista_spotby").show();
			jQuery.ajax({ //
				type: "POST",
				url: "data/spotby.php",
				data: {tipo:tipo},
				success: function(response)
				{
					$('#lista_spotby_tabla').html(response);
				}
			});
		};
		function spotby(){
			var tipo =0;
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/spotby.php",
				data: {tipo:tipo},
				success: function(response)
				{
					$('#resultados_js').html(response);
				}
			});
		};
		
		
	function spotby_save(){
			var tipo =2;
			//tomo los valores
			var descripcion =  document.getElementById("descripcion_spotby").value;
			var cantidad =  document.getElementById("cantidad_spotby").value;
			var datos_adicionales =  document.getElementById("a_datos_spotby").value;
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/spotby.php",
				data: {tipo:tipo, descripcion:descripcion, cantidad:cantidad, datos_adicionales:datos_adicionales},
				success: function(response)
				{
					$('#resultados_js').html(response);
				}
			});
		};	
		
		
		
		function subir_iamgen_spotby(id){
                     var formData = new FormData($("#formulario")[0]);
                     var ruta = "data/subir_imagen_spotby.php";
                     var imagen = $("input[name='file']").val();
                     //alert(imagen);

                     if (imagen == "")//si no tiene nada le dice que sube algo
                     {
                                    alert("No se adjunto una imagen");

                     }
                     else//si tiene algo lo guarda
                     {
						
                                    $.ajax({
                                    url: ruta,
                                    type: "POST",
                                    data: formData,
                                    contentType: false,
                                    processData:false,
                                    success: function(datos)
                                    {
										//aqui no llegue
                                        $('#resultados_js').html(datos);
										var nombre =  document.getElementById("nombre_imagen_spotby").value;
										var tipo=3;
										jQuery.ajax({ //
											type: "POST",
											url: "data/spotby.php",
											data: {tipo:tipo, id:id, nombre:nombre},
											success: function(response)
											{
												$('#resultados_js').html(response);
												$("#spotby").modal("hide");
												$('#descripcion_spotby').val("");
												$('#cantidad_spotby').val("");
												$('#a_datos_spotby').val("");
											}
											});
                                    }
                     
                                    });
                     } 
		};    
		
		function ver_img_spotby(imagen)
		{	
			var mostrar ='<img src="spotby_img/imagenes/'+imagen+'" width="600" height="500">';
		
			$('#spotby_imagen_body').html(mostrar);
			
			$("#spotby_imagen").modal("show");
		}

//*********************************************************************************

	function detalle_pedido(id,folio,total_pedido){
		//alert("Articulo ID= "+id);
		
		 
		  
			jQuery('#pedido_detalle').modal('show', {backdrop: 'static'});
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/pedido_det.php",
				data: {id:id,folio:folio,total_pedido:total_pedido},
				success: function(response)
				{
					jQuery('#pedido_detalle .modal-body').html(response);
				}
			});
		};
	function detalle_autorizacion(id_pedido){
		//alert("Articulo ID= "+id);
		
		 
		  
			jQuery('#autorizacion_detalle').modal('show', {backdrop: 'static'});
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/autorizacion_det.php",
				data: {id_pedido:id_pedido},
				success: function(response)
				{
					jQuery('#autorizacion_detalle .modal-body').html(response);
				}
			});
		};
	//// funcion para aprobar requerimientos de autorizaciones  ////	
	function aprobar(id_requi){
		// tipo - aprobar o denegar
		var tipo = 1; // aprobar
		
			jQuery.ajax({ //
				type: "POST",
				url: "data/autorizacion.php",
				data: {id_requi:id_requi, tipo:tipo},
				success: function(response)
				{
					$("#resultados_js_autorizacion").html(response);
				}
			});
		};
	//// funcion para denegar requerimientos de autorizaciones  ////	
	function denegar(id_requi){
		// tipo - aprobar o denegar
		var tipo = 0; // denegar
		
			jQuery.ajax({ //
				type: "POST",
				url: "data/autorizacion.php",
				data: {id_requi:id_requi, tipo:tipo},
				success: function(response)
				{
					$("#resultados_js_autorizacion").html(response);
				}
			});
		};
		
		

		/////////////////////   ABRIR MODAL PARA AGREGAR ORDEN DE COMPRA  /////////////////////////
	function agregar_orden(id_pedido){ 
		//alert("Articulo ID= "+id);
		
		var id_pedido =  document.getElementById("txt_id_pedido").value;
		var orden =  document.getElementById("txt_orden_compra").value;
		  
			//jQuery('#modal_orden').modal('show', {backdrop: 'static'});
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_orden.php",
				data: {id_pedido:id_pedido,orden:orden},
				success: function(response)
				{
					$("#resultados_js").html(response);
					//$("#modal_orden").modal("toggle"); 					
					//jQuery('#modal_orden .modal-body').html(response);
				}
			});
		};
/////////////////////   ABRIR MODAL PARA SELECCIONAR CENTRO DE COSTOS Y EL RECOLECTOR  /////////////////////////
	
	function seleccionar_opciones(){ 
	guardar_pedido();
	
		//	jQuery('#modal_cc_recolector_oc').modal('show', {backdrop: 'static'});
		//	id_pedido = document.getElementById("txt_id_pedido_dir").value;
		//
		//	 var id_user = <?php echo $_SESSION["logged_user"]; ?>;
		//
		//	 $("#txt_add_orden").val("");
		//	$("#modal_cc_recolector_oc").on("shown.bs.modal", function() {
		//						$("#txt_add_orden").focus();
		//						});
		//	
		//	jQuery.ajax({ //
		//		type: "POST",
		//		url: "data/lista_cc.php",
		//		data: {id_user:id_user},
		//		success: function(resultados)
		//		{
		//			$("#select_orden_cc").html(resultados);
		//			//valida_cc();
		//		}
		//	}); 
		//	jQuery.ajax({ //
		//		type: "POST",
		//		url: "data/lista_orden_recolector.php",
		//		data: {id_user:id_user},
		//		success: function(respuesta)
		//		{
		//			$("#select_orden_recolector").html(respuesta);
		//			//info_recolector();
		//		}
		//	});
						
			
		};
		
		// validara el centro de costos, para verificar el limite de spen
	// function valida_cc(){ 
	// var select_orden_cc =  document.getElementById("select_orden_cc").value;
			
			// jQuery.ajax({ //
				// type: "POST",
				// url: "data/valida_cc.php",
				// data: {select_orden_cc:select_orden_cc},
				// success: function(respuesta)
				// {
					// $("#datos_validacion_cc").html(respuesta);
				// }
			// });
	// }
	function mostrar_dir_suc(){ 
	var dir_select =  document.getElementById("select_dir_suc").value;
			jQuery.ajax({ //
				type: "POST",
				url: "data/listas_dir.php",
				data: {dir_select:dir_select},
				success: function(respuesta)
				{
					$("#datos_dir_suc").html(respuesta);
					
				}
			});
	}
	/////// LISTA DE TODOS LOS PEDIDOS DE LA EMPRESA
	function lista_pedidos(){
		$('#txt_mostrando').val('5');
		limpiardivs();	
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
   
   jQuery.ajax({ //
				type: "POST",
				url: "data/pedidos_supervisados.php",
				data: {id_user:id_user},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_mis_pedidos").html(resultados);		
		
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };	/////// REQUISICIONES DEL ALMACEN SELECCIONADO
	function lista_requis_almacen(){
		
		limpiardivs();	
 
    var almacen_id = document.getElementById("select_almacen_oc").value;
   jQuery.ajax({ //
				type: "POST",
				url: "data/lista_pedidos_almc.php",
				data: {almacen_id:almacen_id},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_mis_pedidos").html(resultados);		
		
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };
	//////// MUESTRA LA LISTA DE LOS PEDIDOS DE EL REQUISITOR LOGUEADO 	
   function mis_pedidos(){
   var id_user = <?php echo $_SESSION["logged_user"]; ?>;
	$('#txt_mostrando').val('4');
limpiardivs();				
   jQuery.ajax({ //
				type: "POST",
				url: "data/mis_pedidos.php",
				data: {id_user:id_user},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_mis_pedidos").html(resultados);		
				
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };
	//////// MUESTRA LOS PEDIDOS PENDIENTES DE AUTORIZAR DE EL REQUISITOR LOGUEADO 	
   function mis_pedidos_pend_aut(){
   var id_user = <?php echo $_SESSION["logged_user"]; ?>;
				
				limpiardivs();	
				
   jQuery.ajax({ //
				type: "POST",
				url: "data/mis_pedidos_pend_aut.php",
				data: {id_user:id_user},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_mis_pedidos_pend_aut").html(resultados);		
				
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };
	//////// PEDIDOS QUE REQUIERES DE AUTORIZACION DE USUARIO ACTUAL  	
   function mis_pedidos_para_aut(){
   var id_user = <?php echo $_SESSION["logged_user"]; ?>;
				
	limpiardivs();	
				
   jQuery.ajax({ //
				type: "POST",
				url: "data/mis_pedidos_pend_aut.php",
				data: {id_user:id_user},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_mis_pedidos_pend_aut").html(resultados);		
				
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };
   //////// MUESTRA LA LISTA DE LOS carritos pendientes	
   function mis_carritos_pendientes(){
   var id_user = <?php echo $_SESSION["logged_user"]; ?>;
  var almacen_id = document.getElementById("select_almacen_oc").value;
 limpiardivs();
 $('#txt_mostrando').val('3');
   jQuery.ajax({ //
				type: "POST",
				url: "data/mis_carritos_pendientes.php",
				data: {id_user:id_user,almacen_id:almacen_id},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_mis_pedidos").html(resultados);		
					
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };
   function mostrar_pedido(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
  var almacen_id = document.getElementById("select_almacen_oc").value;
limpiardivs();
   $('#txt_mostrando').val('2');
   jQuery.ajax({ //
				type: "POST",
				url: "data/lista_pedido.php",
				data: {id_user:id_user,almacen_id:almacen_id},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_lista_pedido").html(resultados);		
								
					$("#modal_cargando").modal("hide");
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };
   function solicitudes(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
   
limpiardivs();
	
$("#div_mis_solicitudes").show();	
	
lista_limite_spend();
  /*  jQuery.ajax({ //
				type: "POST",
				url: "data/lista_pedido.php",
				data: {id_user:id_user},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_lista_pedido").html(resultados);		
					
		
				}
			}); */
			
	return false;	
   
   
   };
   function mostrar_pedido2(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
   var almacen_id = document.getElementById("select_almacen_oc").value;
   $('#txt_mostrando').val('2');
  var segundos = 1;
   limpiardivs();
   jQuery.ajax({ //
				type: "POST",
				url: "data/lista_pedido.php",
				data: {id_user:id_user,segundos:segundos,almacen_id:almacen_id},
				success: function(resultados)
				
				{ ////// 
								
				$("#div_lista_pedido").html(resultados);		
			
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };
   function buscar(){
   var valor = document.getElementById("txt_buscar").value;
   var almacen_id = document.getElementById("select_almacen_oc").value;
   $('#txt_mostrando').val('6');
  limpiardivs();
   var id_categoria = document.getElementById("txt_id_categoria").value;
	$("#area_resultados").html("<center><img src='assets/images/cargando.gif' /></center>");
// if (valor != ""){  
		jQuery.ajax({ //
						type: "POST",
						url: "data/buscar_articulo.php",
						data: {valor:valor, id_categoria:id_categoria,almacen_id:almacen_id},
						success: function(resultados)
						
						{ ////// PENDIENTE ACCION DESPUES DE REGISTRO DE ARTICULOS EN PEDIDO 
						if (resultados == 0){
						$(location).attr('href','index.html');
						}
						else
						{				
						$("#area_resultados").html(resultados);	
						
						}		
							
						//alert("Se registro pedido con exito!");
							//jQuery('#modal1 .modal-body').html(response);
						}
					});
				return false;	
		
		
		//}
   };
   
   function agregar_articulo_oc(id_articulo){
   
   var cantidad = document.getElementById("txt_cantidad_"+id_articulo).value;
   var id_almacen = document.getElementById("select_almacen_oc").value;
   var id_user = <?php echo $_SESSION["logged_user"]; ?>;
  // alert("hola buscas "+ valor);
   
   jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_articulo.php",
				data: {id_articulo:id_articulo,cantidad:cantidad,id_user:id_user,id_almacen:id_almacen},
				success: function(resultados)
				
				{
				
				$("#resultados_js").html(resultados);		
						
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
	};
   function existencia(id_articulo){
   
   var cantidad = document.getElementById("txt_cantidad_"+id_articulo).value;
   var id_user = <?php echo $_SESSION["logged_user"]; ?>;
     var id_almacen = document.getElementById("select_almacen_oc").value;
	 
   jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_articulo.php",
				data: {id_articulo:id_articulo,cantidad:cantidad,id_user:id_user,id_almacen:id_almacen},
				success: function(resultados)
				
				{
				if (resultados == 1){
					//alert('Se agrego el articulo a su pedido');
				}
				else
				{				
				$("#area_resultados").html(resultados);		
				}		
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
	};

    function agregar_articulo_oc2(){
   
   var cantidad = document.getElementById("txt_cantidad3").value;
   var id_articulo = document.getElementById("id_articulo_modal").value;
    var id_almacen = document.getElementById("select_almacen_oc").value;
   var id_user = <?php echo $_SESSION["logged_user"]; ?>;
  // alert("hola buscas "+ valor);
   
   jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_articulo.php",
				data: {id_articulo:id_articulo,cantidad:cantidad,id_user:id_user,id_almacen:id_almacen},
				success: function(resultados)
				
				{
					
				if (resultados == 1){
					//alert('Se agrego el articulo a su pedido');
				}
				else
				{				
				$("#area_resultados").html(resultados);		
				}		
					
				//alert("Se registro pedido con exito!");
					//jQuery('#modal1 .modal-body').html(response);
				}
			});
		return false;	
   
   
   };
   function remover_articulo_oc(id_det){

   
   jQuery.ajax({ //
				type: "POST",
				url: "data/remover_articulo.php",
				data: {id_det:id_det},
				success: function(resultados)
				
				{
				if (resultados == 1){
				
					//alert('Se removio articulo del pedido');
					mostrar_pedido();
				}
				else
				{
				alert('No se pudo eliminar articulo del pedido');
						
				}		
					
				}
			});
		return false;	
   
   
   };
   function guardar_pedido(){

    $("#modal_cargando").modal("show");
	
	var orden_cc =  document.getElementById("select_orden_cc").value;
	var orden_recolector =  document.getElementById("select_orden_recolector").value;	
	var orden_cliente =  document.getElementById("txt_add_orden").value;	
	var id_pedido =  document.getElementById("txt_id_pedido_dir").value;	
	var almacen_id =  document.getElementById("select_almacen_oc").value;	
	
   jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_pedido.php",
				data: {id_pedido:id_pedido,orden_cc:orden_cc,orden_recolector:orden_recolector,orden_cliente:orden_cliente,almacen_id:almacen_id},
				success: function(resultados)
				
				{
					$("#resultados_js").html(resultados);
				if (resultados != 0){
									
				}
				else
				{
				alert('No se pudo ordenar el pedido');
				//$("#area_resultados").html(resultados);	
					$("#modal_cargando").modal("hide");				
				}							
				}
			});
			
		return false;	
   
   };
   function sumarDias(fecha, dias){
  fecha.setDate(fecha.getDate() + dias);
  return fecha;
}
   function sumarMeses(fecha, meses){
	 
  fecha.setMonth(fecha.getMonth() + meses);
  return fecha;
}
   function lista_limite_spend(){
	   var id_user = <?php echo $_SESSION["logged_user"]; ?>;
	  jQuery.ajax({ //
				type: "POST",
				url: "data/mostrar_limites_spend.php",
				data: {id_user:id_user},
				success: function(resultados)
				{
					$("#div_limites_establecidos").html(resultados);
				}
			});  
   }
   
   function guardar_limite_spend(){

   
	var id_user = <?php echo $_SESSION["logged_user"]; ?>;
	var tipo =  document.getElementById("select_concepto").value;
	var cantidad_limite =  document.getElementById("txt_monto_limite").value;	
	var fecha_inicial =  document.getElementById("txt_fecha_inicio").value;	
	var tipo_periodo =  document.getElementById("select_periodo").value;	
	var cantidad_periodo =  document.getElementById("txt_cantidad_periodo").value;	
	var ciclo =  document.getElementById("check_ciclo").checked;	
	var articulo =  document.getElementById("select_articulo").value;	
	var centro_costos =  document.getElementById("select_cc").value;	
	var departamento =  document.getElementById("select_dep").value;	
	var usuario =  document.getElementById("select_usuario").value;	
	var valor_concepto = "";
	
	if (tipo == 1){
		if (articulo == ""){
			alert("Debe seleccionar un Articulo");
			$("#select_articulo").focus();
			return false;
		} else {
		valor_concepto = articulo;	
	
		}
		
	}else if (tipo == 2){
		if (centro_costos == ""){
			alert("Debe seleccionar un Centro de costos");
			$("#select_cc").focus();
			return false;
		} else {
		valor_concepto = centro_costos;
		}
		
	}else if (tipo == 3){
		if (departamento == ""){
			alert("Debe seleccionar un Departamento");
			$("#select_dep").focus();
			return false;
		} else {
		valor_concepto = departamento;
		}
		
	}else if (tipo == 4){
		if (usuario == ""){
			alert("Debe seleccionar un Usuario");
			$("#select_usuario").focus();
			return false;
		} else {
		valor_concepto = usuario;
		}
		
	}
	if (cantidad_limite == ""){
			alert("Falta ingresar el Monto Limite");
			$("#txt_monto_limite").focus();
			return false;
		}
	if (fecha_inicial == ""){
			alert("Seleccione la Fecha de inicio");
			$("#txt_fecha_inicio").focus();
			return false;
		}	
	if (cantidad_periodo == ""){
			
			if (tipo_periodo == 1){ //meses
				alert("ingrese la cantidad de meses de rango");
			} else 	if (tipo_periodo == 2){ //dias
				alert("ingrese la cantidad de dias de rango");
			}
			$("#txt_cantidad_periodo").focus();
			return false;
		}
		
	var cant_sumar = parseInt(cantidad_periodo);
		var d = new Date(fecha_inicial);
		var fecha_cierre;
		
			if (tipo_periodo == 1){ //meses
				 fecha_cierre = sumarMeses(d, cant_sumar);
			} else 	if (tipo_periodo == 2){ //dias
				 fecha_cierre = sumarDias(d, cant_sumar);
			}
	//console.log(cant_sumar);
	//console.log(d);
		
	if (ciclo == true){
		//alert("Se ha guardado el limite de spend con ciclo automatico activado");
	} else if (ciclo == false){
		var opcion = confirm("Esta por establecer un Limite de spend pero no ha marcado \n la opcion de ciclo automatico por lo cual este limite durara solo el periodo registrado. Desea continuar guardandolo de todos modos?");
    if (opcion == true) {
		
		
		var options = {weekday: "long", year: "numeric", month: "long", day: "numeric",
       hour12 : true,
       hour:  "2-digit",
       minute: "2-digit",
      second: "2-digit"};
        alert("Se registrara el limite de spend y expirara el dia "+fecha_cierre.toLocaleDateString("es-ES", options));
		//console.log(fecha_cierre);
	} else {
	    return false;
	}
	}		
	//calcular_fecha_cierre();
		
	//	console.log(sumarDias(d, cant_sumar));

	
	 $("#modal_cargando").modal("show");
   jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_limite_spend.php",
				data: {id_user:id_user,tipo:tipo,cantidad_limite:cantidad_limite,fecha_inicial:fecha_inicial,tipo_periodo:tipo_periodo,cantidad_periodo:cantidad_periodo,ciclo:ciclo,valor_concepto:valor_concepto},
				success: function(resultados)
				
				{
				if (resultados != 0){
				
					$("#resultados_js").html(resultados);
				}
				else
				{
				alert('No se pudo ordenar el pedido');
				//$("#area_resultados").html(resultados);		
				}		
					
				}
			}); 
			
		$("#modal_cargando").modal("hide");
		return false;	
   
   
   };
   /* guardar carrito para  mas tarde // estatus de 0 a 0p*/
   function guardar_carrito(id_pedido){
    $("#modal_cargando").modal("show");
	
   jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_carrito.php",
				data: {id_pedido:id_pedido},
				success: function(resultados)
				{
					$("#resultados_js").html(resultados);
				}
			});
		return false;	
    };
	/*   Retomar carrito guardado estatus 0p*/
   function retomar_carrito(id_pedido){
    $("#modal_cargando").modal("show");
	
   jQuery.ajax({ //
				type: "POST",
				url: "data/retomar_carrito.php",
				data: {id_pedido:id_pedido},
				success: function(resultados)
				{
					$("#resultados_js").html(resultados);
				}
			});
			$("#modal_cargando").modal("hide");
		return false;	
    };
	/*   elimina carrito en curso - (abierto) estatus 0 */
   function eliminar_carrito(id_pedido){
    $("#modal_cargando").modal("show");
	
   jQuery.ajax({ //
				type: "POST",
				url: "data/eliminar_carrito.php",
				data: {id_pedido:id_pedido},
				success: function(resultados)
				{
					$("#resultados_js").html(resultados);
				}
			});
		return false;	
    };
	/*   solicitar Autorizacion de limite de spend */
   function solicitar_autorizacion(id_pedido,id_usuario_aut,id_cc,id_recolector,orden_cliente){
    $("#modal_cargando").modal("show");
	//var id_usuario_aut =  document.getElementById("select_usu_aut").value;	
   jQuery.ajax({ //
				type: "POST",
				url: "data/solic_aut.php",
				data: {id_pedido:id_pedido,id_usuario_aut:id_usuario_aut,id_cc:id_cc,id_recolector:id_recolector,orden_cliente:orden_cliente},
				success: function(resultados)
				{
					$("#resultados_js").html(resultados);
				}
			});
		return false;	
    };
	
   /* function enviar(id_pedido){
    $.ajax({
		type: "post",
		url: "data/enviar.php",
		data: {id_pedido : id_pedido},
		dataType: "html",
        success:  function (response) {
        $('#resultados_mail').html(response);
      }
    });
	
	}; */
	function enviar_correo(id_pedido,id_usuario,tipo){ 
    $.ajax({
		type: "post",
		url: "data/enviar_correo.php",
		data: {id_pedido:id_pedido,id_usuario:id_usuario,tipo:tipo},
		dataType: "html",
        success:  function (response) {
        $('#resultados_mail').html(response);
      }
    });
	
	};

	function sumar(id_articulo){
	var cant_actual = document.getElementById("txt_cantidad_"+id_articulo).value;
	var cant_actual2 = parseFloat(cant_actual);
	var cant_new = "";
	
		if (cant_actual2 == "")
		{
			$("#txt_cantidad_"+id_articulo).val("1");
		} 
		else if (cant_actual2 <= 0)
		{
			$("#txt_cantidad_"+id_articulo).val("1");
		}
		else
		{
			//cant_new = typeof(cant_actual2);
			cant_new = cant_actual2 + 1;
			$("#txt_cantidad_"+id_articulo).val(cant_new);
			
		}		
	};
	
	function restar(id_articulo){
	var cant_actual = document.getElementById("txt_cantidad_"+id_articulo).value;
	var cant_actual2 = parseFloat(cant_actual);
	var cant_new = "";
	
		if (cant_actual2 == "")
		{
			$("#txt_cantidad_"+id_articulo).val("1");
		} 
		else if (cant_actual2 <= 0)
		{
			$("#txt_cantidad_"+id_articulo).val("1");
		}
		else
		{
			//cant_new = typeof(cant_actual2);
			if (cant_actual2 == 1){
			cant_new = cant_actual2;
			}
			else 
			{
			cant_new = cant_actual2 - 1;
			}
			
			$("#txt_cantidad_"+id_articulo).val(cant_new);
		}		
	};
	function sumar2(id_articulo){
	var cant_actual = document.getElementById("txt_cantidad2_"+id_articulo).value;
	var precio_unitario = document.getElementById("txt_precio_"+id_articulo).value;
	//var precio_total = document.getElementById("txt_precio_total_"+id_articulo).value;
	var cant_actual2 = parseFloat(cant_actual);
	var cant_new = "";
	var precio_total = "";
		if (cant_actual2 == "")
		{
			$("#txt_cantidad2_"+id_articulo).val("1");
			$("#td_total_"+id_articulo).html("$"+precio_unitario);
			$("#txt_precio_total_"+id_articulo).val("$"+precio_unitario);
			
		} 
		else if (cant_actual2 <= 0)
		{
			$("#txt_cantidad2_"+id_articulo).val("1");
			$("#td_total_"+id_articulo).html("$"+precio_unitario);
			$("#txt_precio_total_"+id_articulo).val("$"+precio_unitario);
		}
		else
		{
			//cant_new = typeof(cant_actual2);
			cant_new = cant_actual2 + 1;
			$("#txt_cantidad2_"+id_articulo).val(cant_new);
			//precio_total = cant_new * precio_unitario;
			//$("#td_total_"+id_articulo).html("$"+parseFloat(precio_total).toFixed(2));
			//$("#txt_precio_total_"+id_articulo).val(parseFloat(precio_total).toFixed(2));
			actualizar_precio(id_articulo);
			
		}		
	};
	
	function restar2(id_articulo){
	var cant_actual = document.getElementById("txt_cantidad2_"+id_articulo).value;
	var precio_unitario = document.getElementById("txt_precio_"+id_articulo).value;
	//var precio_total = document.getElementById("txt_precio_total_"+id_articulo).value;
	var cant_actual2 = parseFloat(cant_actual);
	var cant_new = "";
	var precio_total = "";	
		if (cant_actual2 == "")
		{
			$("#txt_cantidad2_"+id_articulo).val("1");
			$("#td_total_"+id_articulo).html("$"+precio_unitario);
		} 
		else if (cant_actual2 <= 0)
		{
			$("#txt_cantidad2_"+id_articulo).val("1");
			$("#td_total_"+id_articulo).html("$"+precio_unitario);
		}
		else
		{
			//cant_new = typeof(cant_actual2);
			if (cant_actual2 == 1){
			cant_new = cant_actual2;
			}
			else 
			{
			cant_new = cant_actual2 - 1;
			}
			
			$("#txt_cantidad2_"+id_articulo).val(cant_new);
			//precio_total = cant_new * precio_unitario;
			//$("#td_total_"+id_articulo).html("$"+parseFloat(precio_total).toFixed(2));
			//$("#txt_precio_total_"+id_articulo).val(parseFloat(precio_total).toFixed(2));
			actualizar_precio(id_articulo);
		}		
	};
	
	function restar3(){
	var cant_actual = document.getElementById("txt_cantidad3").value;
	
	var cant_actual2 = parseFloat(cant_actual);
	var cant_new = "";
	
		if (cant_actual2 == "")
		{
			$("#txt_cantidad3").val("1");
			
		} 
		else if (cant_actual2 <= 0)
		{
			$("#txt_cantidad3").val("1");
			
		}
		else
		{
			//cant_new = typeof(cant_actual2);
			if (cant_actual2 == 1){
			cant_new = cant_actual2;
			}
			else 
			{
			cant_new = cant_actual2 - 1;
			}
			
			$("#txt_cantidad3").val(cant_new);
		
		}		
	};

function sumar3(){
	var cant_actual = document.getElementById("txt_cantidad3").value;
	var cant_actual2 = parseFloat(cant_actual);
	var cant_new = "";
	
		if (cant_actual2 == "")
		{
			$("#txt_cantidad3").val("1");
		} 
		else if (cant_actual2 <= 0)
		{
			$("#txt_cantidad3").val("1");
		}
		else
		{
			//cant_new = typeof(cant_actual2);
			cant_new = cant_actual2 + 1;
			$("#txt_cantidad3").val(cant_new);
			
		}		
	};

	function actualizar_precio(id)
	{
  var cant_actual = document.getElementById("txt_cantidad2_"+id).value;
  var precio_unitario = document.getElementById("txt_precio_"+id).value;
  //var precio_total = document.getElementById("txt_precio_total_"+id).value;
  var cantidad = parseFloat(cant_actual);
  var precio_format = cantidad * precio_unitario;
  var precio_total = parseFloat(precio_format).toFixed(2);
		
  
  //alert('alerta ');
  if (cant_actual > 0){
	jQuery.ajax({ 
				type: "POST",
				url: "data/guardar_pedido_det.php",
				data: {id:id,cantidad:cant_actual,precio_total:precio_total},
				success: function(resultados)
				{ 
				//alert("The text has been changed. ");				
				$("#resultados_js").html(resultados);
			
				}
			});
		$("#td_total_"+id).html("$"+precio_total);
		$("#txt_precio_total_"+id).val(precio_total);
	}else {
		$("#txt_cantidad2_"+id).val("1");
	}
	
			return false;
	
	};
	/* function setear_txtorden(){
	
	  $("#txt_orden_compra").focus();
	}; */
    function limpiarselects(){
		$("#div_select_articulos").hide();	
				$("#div_select_cc").hide();	
				$("#div_select_usuarios").hide();	
				$("#div_select_departamentos").hide();
	};
    function limpiarselectsrep(){
				$("#div_select_art").hide();	
				$("#div_select_cen").hide();	
				$("#div_select_usu").hide();	
				$("#div_select_dep").hide();
	};
    function limpiardivs(){
		$("#area_resultados").html("");		
				$("#div_lista_pedido").html("");		
				$("#div_mis_solicitudes").hide();
				$("#div_generador_reportes").hide();
				$("#div_mis_pedidos_pend_aut").html("");	
				$("#div_mis_pedidos").html("");	
				$("#lista_spotby").hide();
	};
    function mostrar_reportes(){
		limpiardivs();
	$("#div_generador_reportes").show(300);	
	$('#generador').collapse('show');
	$('#txt_mostrando').val('1');
	
	
	
	
	$("#select_comodity").attr("disabled", true);
				$("#select_usu").attr("disabled", true);
				$("#select_depa").attr("disabled", true);
				$("#select_ccos").attr("disabled", true);
				
	};
	function DescargarExcel(aplicado_a,cols,lista_resultados) {
	
	console.log("ejecutando");
    //window.open('entradas_excel.php?f1='+fecha_ini+'&f2='+fecha_fin, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=400, height=250");
}


function cargar_arti_reportes()
	{	
		//var almacen_id = document.getElementById("select_almacen_oc").value;
		var id_empresa = 11;
		$.ajax({
			type: "post",
			url: "data/selec_arti_almacen.php",
			data: {id_empresa:id_empresa,almacen_id:almacen_id},
			dataType: "html",
			success:  function (response) {
			//$('#select_arti_oc').html(response);
			$('#resultados_js').html(response);
			}
		});
	};
function ver_partidas_traspaso(id_pedido_traspaso)
	{	
		
		$.ajax({
			type: "post",
			url: "data/ver_partidas_traspaso.php",
			data: {id_pedido_traspaso:id_pedido_traspaso},
			dataType: "html",
			success:  function (response) {
			//$('#select_arti_oc').html(response);
			$('#resultados_js').html(response);
			}
		});
	};
	
	
function automatic_request(){
	var almacen_id = document.getElementById("select_almacen_oc").value; 
	$.ajax({
			type: "post",
			url: "data/generar_pedido_automatico.php",
			data: {almacen_id:almacen_id},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			}
		});
}	
</script>



<!--Boton hacia arriba-->
<a class="ir-arriba"  href="#" title="Volver arriba">
        <span class="fa-stack">
            <i class="fa fa-circle fa-stack-2x"></i>
            <i class="fa fa-arrow-up fa-stack-1x fa-inverse"></i>
        </span>
    </a>


<?php echo $header_index; ?>
<?php echo $container_index; ?>
<?php echo $modal_orden_index; ?>
<?php echo $modales_spotby; ?>
<?php echo $modal_al_agregar_articulo; ?>
<?php echo $modal_cc_recolector_oc_index; ?>
<?php echo $modal_dir_fac_suc; ?>
<?php echo $footer_index; ?>


    <script src="assets/js/jquery-1.12.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
     <script type="text/javascript" charset="utf8" src="assets/js/datatable.js"></script>
     <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
	 <script src="assets/js/moment.js"></script>
	 <script src="assets/js/locale/es.js"></script>
	 <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
	 <!-- Latest compiled and minified JavaScript -->
	<script src="assets/js/bootstrap-select.min.js"></script>


	<script>
	
		$(document).ready(function(){
			
			
			
			
			
				  
				$("#btn_guardar_orden").click(function(){
					agregar_orden();
				});
				$("#btn_guardar_pedido").click(function(){
					guardar_pedido();
				});
				$("#btn_establecer_limite").click(function(){
					guardar_limite_spend();
				});
				limpiardivs();	
				limpiarselects();
				limpiarselectsrep();
				$("#div_select_articulos").show();	
				$("#datetimepicker1").datetimepicker({
                
					format: 'MM/DD/YYYY HH:mm',
					locale: 'es',
					useCurrent: true,
					
					showTodayButton: false,
					icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
					next: 'fa fa-chevron-circle-right',
					previous: 'fa fa-chevron-circle-left'
					}
				});
				$("#datepicker_ini").datetimepicker({
                
					format: 'MM/DD/YYYY HH:mm',
					locale: 'es',
					useCurrent: true,
					
					showTodayButton: false,
					icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
					next: 'fa fa-chevron-circle-right',
					previous: 'fa fa-chevron-circle-left'
					}
				});
				$("#datepicker_fin").datetimepicker({
                
					format: 'MM/DD/YYYY HH:mm',
					locale: 'es',
					useCurrent: true,
					
					showTodayButton: false,
					icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
					next: 'fa fa-chevron-circle-right',
					previous: 'fa fa-chevron-circle-left'
					}
				});	
				$("#datepicker_ini").on("dp.change", function (e) {
					$('#datepicker_fin').data("DateTimePicker").minDate(e.date);
				});
				$("#datepicker_fin").on("dp.change", function (e) {	
					$('#datepicker_ini').data("DateTimePicker").maxDate(e.date);
				});
				//minDate: new Date(),format: 'DD-MM-YYYY HH:mm A', 	
				$('#txt_add_orden').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode == '13'){
						guardar_pedido();
						 $("#modal_cc_recolector_oc").modal("toggle");
						 $('#txt_add_orden').val("");
					}
				});
				$('#txt_orden_compra').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode == '13'){
						agregar_orden();
						 $("#modal_orden").modal("toggle");
						 $('#txt_orden_compra').val("");
					}
				});
				$('#txt_buscar').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode == '13'){
						buscar();
						 
					}
				});
				$("#select_orden_recolector").change(function(){
								
							//info_recolector();
                });	
				$("#select_orden_cc").change(function(){
								
							//valida_cc();
                });	
				$("#select_concepto").change(function(){
								var tipo = $(this).val();
								//alert("valor: "+tipo);
								
								if (tipo == 1){
									limpiarselects();	
								    $("#div_select_articulos").show();	
								    
								} else if (tipo == 2){
									limpiarselects();		
								    $("#div_select_cc").show();	
								}  else if (tipo == 3){
									limpiarselects();		
									$("#div_select_departamentos").show();
								}  else if (tipo == 4){
									limpiarselects();		
									$("#div_select_usuarios").show();	
									
								} 								
							//valida_cc();
                });		
				
				$("#solicitar_auto").click(function()
				{
					var id_pedido = document.getElementById("txt_auto_id_pedido").value;
					var id_usuario_aut =  document.getElementById("select_usu_aut").value;
					var id_cc =  document.getElementById("select_orden_cc").value;
					var id_recolector =  document.getElementById("select_orden_recolector").value;
					var orden_cliente =  document.getElementById("txt_add_orden").value;

					
					 solicitar_autorizacion(id_pedido,id_usuario_aut,id_cc,id_recolector,orden_cliente);
						//alert("pedido id = "+id_pedido); 
				});

				
				$("#checkboxcc").change(function(){
					
				    var valor = $(this).prop('checked');
					
					if (valor == false){
						$("#select_ccos").attr("disabled", true);
					}
					else
					{
						$("#select_ccos").attr("disabled", false);
					}	
					//alert(valor);
				});
				$("#checkboxdep").change(function(){
					
				    var valor = $(this).prop('checked');
					
					if (valor == false){
						$("#select_depa").attr("disabled", true);
					}
					else
					{
						$("#select_depa").attr("disabled", false);
						$("#checkboxusu").attr("checked", false);
						$("#select_usu").attr("disabled", true);
					}	
					//alert(valor);
				});
				$("#checkboxusu").change(function(){
					
				    var valor = $(this).prop('checked');
					
					if (valor == false){
						$("#select_usu").attr("disabled", true);
					}
					else
					{
						$("#select_usu").attr("disabled", false);
						$("#checkboxdep").attr("checked", false);
						$("#select_depa").attr("disabled", true);
					}	
					//alert(valor);
				});
				
				$("#txt_buscar").focus();
			
				
				$("#btn_generar_reporte").click(function()
				{
					var tipo_periodo = $('input:radio[name=grupo_periodo]:checked').val();
					var almacen_id = document.getElementById("select_almacen_oc").value; 	
					var tipo_reporte = $('input:radio[name=grupo_tipo_reporte]:checked').val();
					var tipo_salida = $('input:radio[name=grupo_tipo_salida]:checked').val();
				
					var valor_periodo;
					var valor_periodo2 = "";
					var valor_evaluado = ""; // esta variable es para pasar diferentes tipos de parametros para diferentes consultas
					
					if (tipo_periodo == 1)
						{ // tipo periodo = Horas
							valor_periodo = document.getElementById("txt_p_horas").value;
							 if (valor_periodo == ""){
								 alert("Indique las ultimas horas para el reporte");
								 $("#txt_p_horas").focus();
								 return false;
							 }
							 if (valor_periodo == "0"){
								  alert("Indique las ultimas horas para el reporte");
								 $("#txt_p_horas").focus();
								 return false;
							 }
							 
						}
						else if (tipo_periodo == 2)
						{ // tipo periodo = Dias
							valor_periodo = document.getElementById("txt_p_dias").value;
							 if (valor_periodo == ""){
								 alert("Indique los ultimos dias para el reporte");
								 $("#txt_p_dias").focus();
								 return false;
							 }
							 if (valor_periodo == "0"){
								  alert("Indique los ultimos dias para el reporte");
								 $("#txt_p_dias").focus();
								 return false;
							 }
							 
						}
						else if (tipo_periodo == 3)
						{ // tipo periodo = Meses
							valor_periodo = document.getElementById("txt_p_meses").value;
							if (valor_periodo == ""){
								 alert("Indique los ultimos meses para el reporte");
								 $("#txt_p_meses").focus();
								 return false;
							 }
							 if (valor_periodo == "0"){
								  alert("Indique los ultimos meses para el reporte");
								 $("#txt_p_meses").focus();
								 return false;
							 }
							 
						}
						else if (tipo_periodo == 4)
						{ // tipo periodo = Rango 
							valor_periodo = document.getElementById("txt_fecha_ini").value;
							valor_periodo2 = document.getElementById("txt_fecha_fin").value;
							if (valor_periodo == ""){
								 alert("Indique la fecha de inicio de rango para el reporte");
								 $("#txt_fecha_ini").focus();
								 return false;
							 }
							 if (valor_periodo2 == ""){
								  alert("Indique la fecha final de rango para el reporte");
								 $("#txt_fecha_fin").focus();
								 return false;
							 }
							 
						}
							
						
						 if (tipo_salida == 1) /// tipo vista tabla
						 {
							
							 jQuery.ajax({ 
							type: "POST",
							url: "data/generar_reporte.php",
							data: {
								tipo_periodo:tipo_periodo,
								valor_periodo:valor_periodo,
								valor_periodo2:valor_periodo2,
								valor_evaluado:valor_evaluado,
								tipo_reporte:tipo_reporte,
								almacen_id:almacen_id
								},
							success: function(resultados)
								{ 
								
								$('#generador').collapse('hide');
								$('#div_reportes_graficas').html(resultados);
								
								}
							});
						 }
						 else if(tipo_salida == 2) // Reporte en Excel
						 {
							  if (valor_periodo2 == ""){valor_periodo2 = 0;}
							 if (valor_evaluado == ""){valor_evaluado = 0;}
							 
						window.open('data/descargar_excel.php?tipo_periodo='+tipo_periodo+'&valor_periodo='+valor_periodo+'&valor_periodo2='+valor_periodo2+'&valor_evaluado='+valor_evaluado+'&tipo_reporte='+tipo_reporte+'&almacen_id='+almacen_id, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=400, height=250"); 
						 }
						 else if(tipo_salida == 3) // Reporte Grafica
						 {
							 
						 }
						 
 
							return false;
						 
						

				});
				$("#select_almacen_oc").on("change", function(){
					var mostrando = document.getElementById("txt_mostrando").value;
					if (mostrando == 0){ 	  // 0 ninguna opcion seleccionada
						//alert("nada");
					}
					else if (mostrando == 1){ // 1 reportes
						//alert("reportes");
					} 
					else if (mostrando == 2){ // 2 current request
						//alert("current request");
						mostrar_pedido();
					} 
					else if (mostrando == 3){ // 3 paused request
						//alert("paused request");
						mis_carritos_pendientes();
					}
					else if (mostrando == 4){ // 4 my request
						//alert("my request");
						mis_pedidos();
					} 
					else if (mostrando == 5){ // 5 request of my team
						//alert("request of my team");
						lista_pedidos();
					} 
					else if (mostrando == 6){ // 6 busqueda
						//alert("busqueda");
						buscar();
					} 
				});
				buscar();
				
				//cargar_push();
				
		});
	</script>
	
	
<!-- cargando 
<a href="#modal_cargando" class="btn btn-primary agregar_nuevo_usuario" data-toggle="modal">cargar</a> 
-->
<?php echo $modal_cargando_index; ?>



<!-- Final de cargando -->
<!-- WhatsChat.co widget -->
<!--
<script type="text/javascript">

(function () {var options = {

whatsapp: "5218681231729", // WhatsApp number 

position: "right", // Position may be 'right' or 'left'.

image: "", //Image to display. Leave blank to display whatsapp defualt icon

text:"Obtener Botón",

link_to:"https://whatschat.co"};

var proto = document.location.protocol, host = "https://whatschat.co", url = host;

var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/whatsapp/init4.js';

s.onload = function () { getbutton(host, proto, options); };

var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);

})();</script>

 WhatsChat.co widget -->	
</body>
</html>



