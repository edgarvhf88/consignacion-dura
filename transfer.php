<!DOCTYPE html>
<html lang="en">
<head>
<?php include("data/constructor.php"); 
if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] == '')){ header('Location: login.php'); }
else { $tipo_usuario = validar_usuario($_SESSION["logged_user"]);}
/// $tipo_usuario = 0 == admin
switch($tipo_usuario)
{
	case 1:
	header('Location: admin.php');
	break;
	case 2:
	header('Location: index.php');
	break;
	case 3:
	//header('Location: vendor.php');
	break;
	case 4:
	header('Location: index.php');
	break;
	case 5:
	header('Location: supervisor.php');
	break;
}

/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////


include("displays/transfer_style.php");

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>InvCtrl</title>

    <!-- CSS -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" type="image/png" href="assets/images/favicon.ico">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
	

</head>
<body class="archive">
<style>
input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(2); /* IE */
  -moz-transform: scale(2); /* FF */
  -webkit-transform: scale(2); /* Safari and Chrome */
  -o-transform: scale(2); /* Opera */
  transform: scale(2);
  padding: 10px;
}
/* 
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    max-height: 450px;
    overflow-y: auto;
} */
</style>

<script>
 function lista_pedidos(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
	ocultar_divs_principales();
	$("#div_lista_pedidos").show();	
	$("#modal_cargando").modal("show");
   jQuery.ajax({ //
				type: "POST",
				url: "data/lista_pedidos.php",
				data: {id_user:id_user},
				success: function(resultados)
				{
					$("#div_lista_pedidos").html(resultados);		
					
					$("#modal_cargando").modal("hide");					
				}
			});
		return false;	
   }; 
   function lista_pedidos_nef(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
	ocultar_divs_principales();
	$("#div_content_pedidos_nef").show();	
	$("#modal_cargando").modal("show");
   jQuery.ajax({ //
				type: "POST",
				url: "data/lista_pedidos_nef.php",
				data: {id_user:id_user},
				success: function(resultados)
				{
					$("#div_lista_pedidos_nef").html(resultados);		
					
					$("#modal_cargando").modal("hide");					
				}
			});
		return false;	
   };
   function detalle_pedido(id,folio,total_pedido){
		//alert("Articulo ID= "+id);
		
		 
		  
			jQuery('#pedido_detalle').modal('show', {backdrop: 'static'});
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/pedido_det.php",
				data: {id:id,folio:folio,total_pedido:total_pedido},
				success: function(resultados)
				{
					jQuery('#pedido_detalle .modal-body').html(resultados);
				}
			});
		};
   function detalle_pedido_nef (id,folio,total_pedido){
		jQuery('#pedido_detalle_nef').modal('show', {backdrop: 'static'});
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/pedido_nef_det.php",
				data: {id:id,folio:folio,total_pedido:total_pedido},
				success: function(resultados)
				{
					jQuery('#pedido_detalle_nef .modal-body').html(resultados);
				}
			});
		};
		
   function detalle_pedido_tras (id,folio,total_pedido){
		jQuery('#traspaso_detalle').modal('show', {backdrop: 'static'});
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/pedido_det_tras.php",
				data: {id:id,folio:folio,total_pedido:total_pedido},
				success: function(resultados)
				{
					jQuery('#traspaso_detalle .modal-body').html(resultados);
				}
			});
		};
		//vista previa de la remision
		function detalle_remision (folio){
			
			jQuery('#remision_detalle').modal('show', {backdrop: 'static'});
			var tipo='1';
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/recepcion_de_remision.php",
				data: {folio:folio, tipo:tipo},
				success: function(resultados)
				{
					jQuery('#remision_detalle .modal-body').html(resultados);
				}
			});
		};
		
		
		
function solicitar_traspaso(id_pedido){
	
	var id_user = <?php echo $_SESSION["logged_user"]; ?> 
		  
				jQuery.ajax({ //
				type: "POST",
				url: "data/solicitar_traspaso.php",
				data: {id_pedido:id_pedido,id_user:id_user},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);	
					
				}
			});
		};	
function requerir_pedido_nef(id_pedido){
	//$("#modal_cargando").modal("show");
	var id_user = <?php echo $_SESSION["logged_user"]; ?> 
		  
				jQuery.ajax({ //
				type: "POST",
				url: "data/requerir_pedido_nef.php",
				data: {id_pedido:id_pedido,id_user:id_user},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);	
					
				}
			});
		};		
		
	function buscar_remisiones(folio, base_datos, tipo)
	{
		
		jQuery.ajax({ //
				type: "POST",
				url: "data/search_next_docto.php",
				data: {folio:folio, base_datos:base_datos, tipo:tipo},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);	
					
				}
			});
		
	};
	
	function generar_orden_compra(folio)
	{
		
		jQuery.ajax({ //
				type: "POST",
				url: "data/insertar_orden_compra_allpart.php",
				data: {folio:folio},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);	
					
				}
			});
		
	};
	
	
	
/*    function cambiar_estatus(id_pedido,tipo){
	
	var id_user = <?php echo $_SESSION["logged_user"]; ?> 
		  
				jQuery.ajax({ //
				type: "POST",
				url: "data/cambiar_estatus.php",
				data: {id_pedido:id_pedido,tipo:tipo,id_user:id_user},
				success: function(resultados)
				{
				lista_pedidos();
				$("#resultados_js").html(resultados);	
					
				}
			});
		}; */
		
		
	 // function enviar(id_pedido){
  
  
    // $.ajax({
		// type: "post",
		// url: "data/enviar.php",
		// data: {id_pedido : id_pedido},
		// dataType: "html",
        // success:  function (resultados) {
        // $('#resultados_js').html(resultados);
      // }
    // });
	
	// };
	function enviar_correo(id_pedido,id_usuario,tipo){ 
    $.ajax({
		type: "post",
		url: "data/enviar_correo.php",
		data: {id_pedido:id_pedido,id_usuario:id_usuario,tipo:tipo},
		dataType: "html",
        success:  function (response) {
        $('#resultados_js').html(response);
      }
    });
	
	};
	
	 function mostrar_articulos(id_empresa){
   //alert("ok");
  var almacen_id = document.getElementById("select_almacen_articulos").value;
	$("#modal_cargando").modal("show");
		ocultar_divs_principales();
		$("#div_articulos").show();
	
	 
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_articulos_almacen.php",
				data: {id_empresa:id_empresa,almacen_id:almacen_id},
				success: function(resultados)
				
				{ 
								
				$("#div_articulos_lista").html(resultados);
				$("#modal_cargando").modal("hide");			
			
				}
			});
		return false;	
   
   
   };
	function varificar_captura(almacen_id){
		$("#modal_cargando").modal("show");
		//var almacen_id = 0;
		jQuery.ajax({ 
				type: "POST",
				url: "data/verif_levin.php",
				data: {almacen_id:almacen_id},
				success: function(resultados)
				
				{ 
								
				$("#resultados_js").html(resultados);
				$("#modal_cargando").modal("hide");			
			
				}
			}); 
		return false;
	};
	function ocultar_divs_principales(){
		$("#div_registro_inventarios").hide();
		$("#div_articulos").hide();
		$("#div_ordenes_cliente").hide();
		$("#div_inventarios").hide();
		$("#div_solicitud_traspaso").hide();
		$("#div_lista_pedidos").hide();
		$("#div_pedido_nuevo").hide();
		$("#div_content_pedidos_nef").hide();
		
	}
	function invetarios_panel(){
		ocultar_divs_principales();
		$("#div_inventarios").show();
				
		
	 varificar_captura(0);
		//verificacion de iventario activo
	 
	};
	function traspaso_nuevo(){
		ocultar_divs_principales();
		$("#div_solicitud_traspaso").show();
		var verif_carga =  document.getElementById("txt_verif_carga_art_traspaso").value;
		if (verif_carga == 0){
			cargar_art_pedido();
		}
				cargar_lista_pedido();
	 
	};
	function pedido_nuevo()
	{
		ocultar_divs_principales();
		$("#div_pedido_nuevo").show();
		// cargar los datos correspondientes de las tablas pedido_nef y pedido_nef_det
		var verif_carga = document.getElementById("txt_verif_carga_art_nef").value; 
		if (verif_carga == 0){
			cargar_art_pedido_nef();
		}
			
			cargar_lista_pedido_nef();
	};
	function lista_inventarios_reg(id_empresa){
		$("#modal_cargando").modal("show");	
		jQuery.ajax({ 
				type: "POST",
				url: "data/lista_inventarios.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				
				{ 
				$("#div_lista_indetart").html("");				
				$("#div_lista_inventarios").html(resultados);
				$("#modal_cargando").modal("hide");			
				
				}
			});
	}
	function lista_inventarios_art(id_empresa){
		$("#modal_cargando").modal("show");	
		
		jQuery.ajax({ 
				type: "POST",
				url: "data/lista_invdet_art.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				
				{ 
				$("#div_lista_inventarios").html("");				
				$("#div_lista_indetart").html(resultados);
				$("#modal_cargando").modal("hide");	
								
			
				}
			});
	}
	function lista_ordenes_capturadas(){
		$("#modal_cargando").modal("show");	
		var id_empresa = 0;
		jQuery.ajax({ 
				type: "POST",
				url: "data/lista_ordenes.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				
				{ 			
				$("#div_lista_ordenes").html(resultados);
				$("#modal_cargando").modal("hide");			
			
				}
			});
	}
	function inventario_detalles(id_inventario)
	{	$("#modal_cargando").modal("show");	
		$("#txt_idinv_correo").val(id_inventario);
			jQuery.ajax({ 
				type: "POST",
				url: "data/inventario_detalle.php",
				data: {id_inventario:id_inventario},
				success: function(resultados)
				
				{ 
								
				$("#div_lista_indet").html(resultados);
				$("#modal_cargando").modal("hide");			
			
				}
			});
	}
	function registro_inv(){
		ocultar_divs_principales();
		$("#div_registro_inventarios").show();
		//lista_inventarios_reg(11);
		verif_mostrar_inventarios();
	 
	};
	function verif_mostrar_inventarios(){
		//validar check de solo #part	
		var valor = $("#chk_solonpart").prop("checked");		
		if (valor == false){
			//$("#chk_solonpart").attr("checked", true);
			lista_inventarios_reg(11);
		}
		else
		{ 
			//$("#chk_solonpart").attr("checked", false);
			lista_inventarios_art(11);
		}
		
	};
	function lista_articulos_almacen(id_empresa,id_inventario,almacen_id){
	$("#modal_cargando").modal("show");
	jQuery.ajax({ 
				type: "POST",
				url: "data/lista_articulos_almacen.php",
				data: {id_empresa:id_empresa,id_inventario:id_inventario,almacen_id:almacen_id},
				success: function(resultados)
				
				{ 
								
				$("#div_localizar_articulo").html(resultados);
					$("#modal_cargando").modal("hide");		
			
				}
			}); 
		return false;	
	};
	function nuevo_lev_inv(almacen_id){
		
		var aplicado = "N";
		var cancelado = "N";
		jQuery.ajax({ 
				type: "POST",
				url: "data/nuevo_lev_inv.php",
				data: {aplicado:aplicado,almacen_id:almacen_id},
				success: function(resultados)
				
				{ 
								
				$("#resultados_js").html(resultados);
							
			
				}
			}); 
		return false;
	};
	function unselect() {
              //  document.querySelectorAll('[name=accion_cantidad]').forEach((x) => x.checked = false);
            }
	 function guardar_conteo(){
	 var id_articulo = document.getElementById("txt_conteo_id_articulo").value;  
	 var articulo_id = document.getElementById("txt_conteo_id_art_microsip").value;  
	 // el almacen esta registrado en la tabla de invetarios
	 var id_inventario = document.getElementById("txt_id_inventario_activo").value;  
	 var cantidad_contada = document.getElementById("txt_inv_cantidad_contada").value;   
	 // var accion_cantidad = $('input:radio[name=accion_cantidad]:checked').val();
	 
	 if (id_articulo == "")
	 {
		 alert("No se se cuenta con id de articulo");
		 return false;
	 }
	 if (id_inventario == "")
	 {
		 alert("No se se cuenta con id de inventario");
		 return false;
	 }
	 
	 if (cantidad_contada == "")
	 {
		 alert("No ha proporcionado una cantidad");
		 $("#txt_inv_cantidad_contada").focus();
		 return false;
	 }
	 else if(cantidad_contada < 0){
		  alert("La cantidad proporcionada no es valida");
		  $("#txt_inv_cantidad_contada").focus();
		 return false;
	 }
	 $("#modal_cargando").modal("show");
		$.ajax
		({
			type: "post",
			url: "data/guardar_conteo.php",
			data: {id_articulo:id_articulo,articulo_id:articulo_id,id_inventario:id_inventario,cantidad_contada:cantidad_contada},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			 $("#modal_cargando").modal("hide");
			}
		}); 
	 
	 /* if (accion_cantidad == 1){
		// alert("Se selecciono opcion de sumar "+accion_cantidad);
		// sumar -  se registra otro movimiento
		// return false;
	 }else if (accion_cantidad == 2){
		//alert("Selecciono opcion de remplazar");
		//return false;
	 }else if (accion_cantidad == 3){
		//alert("Se Selecciono opcion de restar");
		//return false;
	 }else {
		 alert("Debe selecciona alguna de la opciones debido a que ya existe un conteo anterior");
		 return false;
	 }
	  */
	 
		
	 
	 
	} 
	
	function cambio_almacen(){
		
		var almacen_id = document.getElementById("select_almacen").value; 
		 
		 varificar_captura(almacen_id);
		
		
	}
	
	function validar_articulo_inventario(id_articulo){
		
		var almacen_id = document.getElementById("select_almacen").value; 
		 var id_inventario = document.getElementById("txt_id_inventario_activo").value; 
		$.ajax({
			type: "post",
			url: "data/validar_art_inv.php",
			data: {id_articulo:id_articulo,id_inventario:id_inventario,almacen_id:almacen_id},
			dataType: "html",
			success:  function (response) {
				$('#resultados_js').html(response); 
			}
		});
			
		//alert("Esta por cambiar el almacen donde se aplicara el inventario levantado, Deseas continuar "+almacen_id);
		
	}
	function modal_conteo(id_articulo)
	{
		var clave_empresa = document.getElementById("arti_c_empresa_"+id_articulo).value;
		var clave_microsip = document.getElementById("arti_c_microsip_"+id_articulo).value;
		var articulo_id = document.getElementById("arti_id_microsip_"+id_articulo).value;
		var articulo = document.getElementById("arti_articulo_"+id_articulo).value;
		var descripcion = document.getElementById("arti_descip_"+id_articulo).value;
		var udm = document.getElementById("arti_udm_"+id_articulo).value;
		var empresa = document.getElementById("arti_empresa_"+id_articulo).value;
		var precio = document.getElementById("arti_precio_"+id_articulo).value;
		var imagen = document.getElementById("arti_imagen_"+id_articulo).value;
		var minimo = document.getElementById("arti_min_"+id_articulo).value;
		var maximo = document.getElementById("arti_max_"+id_articulo).value;
		var reorden = document.getElementById("arti_reorden_"+id_articulo).value;
		var existencia = document.getElementById("arti_existencia_"+id_articulo).value;
		var existencia_real = document.getElementById("arti_existenciafisica_"+id_articulo).value;
		
		$("#txt_conteo_id_articulo").val(id_articulo);
		$("#txt_conteo_id_art_microsip").val(articulo_id);
		$("#ic_clave_cliente").html(clave_empresa);
		$("#ic_clave").html(clave_microsip);	
		$("#ic_precio").html(precio);	
		$("#ic_nombre").html(articulo);
		$("#ic_unidad_medida").html(udm);
		$("#ic_min").html(minimo);
		$("#ic_maximo").html(maximo);
		$("#ic_reorden").html(reorden);
		//$("#ic_existencia").html(existencia);
		$("#ic_existencia").html(existencia_real);
		
		if (imagen != "")
		{
		 $("#imagen_inv").attr("src","assets/images/productos/emp-"+empresa+"/max/"+imagen) ;
		}
		else
		{
		 $("#imagen_inv").attr("src","assets/images/sin_imagen.jpg") ;
		}						
		$("#modal_conteo_inventario").modal("show");
		$("#txt_inv_cantidad_contada").val("");
		
		$("#modal_conteo_inventario").on("shown.bs.modal", function() {
			$("#txt_inv_cantidad_contada").focus();
			});
			unselect();
			validar_articulo_inventario(id_articulo);
	}
	
	function cancelar_inv(tipo){
		
		//var almacen_id = document.getElementById("select_almacen").value; 
	var confirma = confirm("Esta seguro de querer cancelar este inventario?");
		if (confirma == false)
		{	
			return false;
		}
		else
		{	// se cancela inventario
			var id_inventario = document.getElementById("txt_id_inventario_activo").value; 
			$.ajax({
				type: "post",
				url: "data/cancel_inv.php",
				data: {id_inventario:id_inventario,tipo:tipo},
				dataType: "html",
				success:  function (response) {
					$('#resultados_js').html(response); 
				}
			});
		}	
		//alert("Esta por cambiar el almacen donde se aplicara el inventario levantado, Deseas continuar "+almacen_id);
		
	}
	function cerrar_captura_inv(){
		
		
	var confirma = confirm("Una ves cerrado y autorizado con documento firmado no se podran hacer cambios, Desea continuar?");
		if (confirma == false)
		{	
			return false;
		}
		else
		{	// se cierra inventario
			var id_inventario = document.getElementById("txt_id_inventario_activo").value; 
			/*	alert(id_inventario); */
		 $.ajax({
				type: "post",
				url: "data/cerrar_inv.php",
				data: {id_inventario:id_inventario},
				dataType: "html",
				success:  function (response) {
					$('#resultados_js').html(response); 
				}
			}); 
		}	
		//alert("Esta por cambiar el almacen donde se aplicara el inventario levantado, Deseas continuar "+almacen_id);
		
	}
	function remov_conteo(id_conteo)
	{
		var confirma = confirm("Confirme la eliminacion del conteo capturado");
		if (confirma == false)
		{	
			return false;
		}
		else
		{	
			$.ajax({
				type: "post",
				url: "data/delcont.php",
				data: {id_conteo:id_conteo},
				dataType: "html",
				success:  function (response) {
					$('#resultados_js').html(response); 
				}
			});
		}
	}
	function preparar_correo()
	{
		$("modal_cargando").modal("show");
		var id_inventario = document.getElementById("txt_idinv_correo").value;
		
		$.ajax({
				type: "post",
				url: "data/export_inv.php",
				data: {id_inventario:id_inventario},
				dataType: "html",
				success:  function (response) {
					$('#resultados_js').html(response); 
					$("#modal_cargando").modal("hide");
					$("#modal_correo_dir").modal("show");
				}
			});
	}
	function enviar_correo_inv()
	{
		var id_inventario = document.getElementById("txt_idinv_correo").value;
		var correo_destino = document.getElementById("txt_correo_nuevo").value;
		$("#modal_cargando").modal("show");
		$.ajax({
				type: "post",
				url: "data/correo_inventario.php",
				data: {id_inventario:id_inventario,correo_destino:correo_destino},
				dataType: "html",
				success:  function (response) {
					$('#resultados_js').html(response); 
					$("#modal_correo_dir").modal("hide");
				}
			});
	};
	function guardar_registro_imagen(nombre_imagen)
	{
		var id_inventario = document.getElementById("txt_idinv_correo").value;
		
		$.ajax({
				type: "post",
				url: "data/registrar_imagen.php",
				data: {id_inventario:id_inventario,nombre_imagen:nombre_imagen},
				dataType: "html",
				success:  function (response) {
					$('#resultados_js').html(response); 
				
				}
			});
	};
	function ordenes_cliente(id_cliente)
	{
		//var id_inventario = document.getElementById("txt_idinv_correo").value;
		
		
		ocultar_divs_principales();
		$("#div_ordenes_cliente").show();
		/* $.ajax({
				type: "post",
				url: "data/registrar_imagen.php",
				data: {id_inventario:id_inventario,nombre_imagen:nombre_imagen},
				dataType: "html",
				success:  function (response) {
					$('#resultados_js').html(response); 
				
				}
			}); */
	};
	
	function orden_nueva()
	{
		//var id_inventario = document.getElementById("txt_idinv_correo").value;
		
		$("#div_datos_ordenes").show();
		$("#txt_orden").val("");
		//$("#txt_orden").focus();
		$("#txt_fecha_orden").val("");
		$("#txt_requisitor").val("");
		$("#txt_comprador").val("");
		$("#select_almacen_oc").val("");
		$("#txt_orden_id").val("");
		$("#btn_cancelar_oc").hide();
		$("#btn_guardar_oc").hide();
		$("#btn_guardar_oc_abierta").hide();
		$("#btn_add_partida").hide();
		$("#btn_adjuntar_file").hide();
		$('#div_detalle_orden').html("");
		
	};
	
	function cancelar_orden()
	{
		var orden_id = document.getElementById("txt_orden_id").value;
		 var folio_orden = document.getElementById("txt_orden").value;
		
		 
		 // funcion para cancelar
		 var confirmar_cancelar = confirm("Esta por cancelar la #orden: "+folio_orden+", Desea continuar la cancelacion?");
		 
		 if (confirmar_cancelar == false)
		 {
			 return false;
		 }
		 else 
		 { // ajax cancelar oc
			 $.ajax({
			type: "post",
			url: "data/cancelar_orden.php",
			data: {orden_id:orden_id},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			
			}
			});
		 }
		
	};
	function mostrar_ordenes()
	{
		//var id_inventario = document.getElementById("txt_idinv_correo").value;
		
		//$("#div_datos_ordenes").hide();
		$("#div_lista_ordenes").toggle();
		// ajax para cargar lista de ordenes
		if ($('#div_lista_ordenes').is(':visible')) 
				{
					lista_ordenes_capturadas();
				}
		
	};
	function verif_orden()
	{	
		var folio_orden = document.getElementById("txt_orden").value;
		var fecha_orden = document.getElementById("txt_fecha_orden").value;
		var requisitor = document.getElementById("txt_requisitor").value;
		var comprador = document.getElementById("txt_comprador").value;
		var almacen = document.getElementById("select_almacen_oc").value;
		var orden_id = document.getElementById("txt_orden_id").value;
		
		if (folio_orden == ""){
			//
			alert("Proporcione folio de orden de compra");
			//setTimeout(() => { $("#txt_orden").focus(); }, 500);
		
			return false;
		}
		else
		{
			$("#txt_fecha_orden").focus();
		}
		
		$.ajax({
		type: "post",
		url: "data/verif_orden.php",
		data: {folio_orden:folio_orden,fecha_orden:fecha_orden,requisitor:requisitor,comprador:comprador,almacen:almacen,orden_id:orden_id},
		dataType: "html",
        success:  function (response) {
			$('#resultados_js').html(response);
		}
		});
		
	};
	function nueva_partida_oc()
	{
		$("#modal_partida_oc").modal("show");
		cargar_art_oc();
	};
	function cargar_art_oc()
	{	
		var almacen_id = document.getElementById("select_almacen_oc").value;
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
	function cargar_art_pedido()
	{	/// carga la lista de los articulos en allpart en almacen general
		
		$("#modal_cargando").modal("show");
		var almacen_id = 19;
		var id_empresa = 11;
		$.ajax({
			type: "post",
			url: "data/select_arti_pedido.php",
			data: {id_empresa:id_empresa,almacen_id:almacen_id},
			dataType: "html",
			success:  function (response) {
			
			$('#resultados_js').html(response);
			}
		});
	};
	function cargar_art_pedido_nef()
	{	
		
		$("#modal_cargando").modal("show");
		var almacen_id = 19;
		var id_empresa = 11;
		$.ajax({
			type: "post",
			url: "data/select_arti_pedido_nef.php",
			data: {id_empresa:id_empresa,almacen_id:almacen_id},
			dataType: "html",
			success:  function (response) {
			
			$('#resultados_js').html(response);
			}
		});
	};
	function cargar_dat_relacion()
	{
		var id_articulo = document.getElementById("select_arti_oc").value;
		$.ajax({
			type: "post",
			url: "data/dat_art_oc.php",
			data: {id_articulo:id_articulo},
			dataType: "html",
			success:  function (response) { 
			$('#resultados_js').html(response);
			}
		});
		
	};
	function cargar_dat_artimicro()
	{
		$("#modal_cargando").modal("show");
		var id_articulo = document.getElementById("select_arti_pedido").value;
		$.ajax({
			type: "post",
			url: "data/dat_art_pedido.php",
			data: {id_articulo:id_articulo},
			dataType: "html",
			success:  function (response) { 
			$('#resultados_js').html(response);
			}
		});
		
	};
	function cargar_dat_artimicro_nef()
	{
		$("#modal_cargando").modal("show");
		var id_articulo = document.getElementById("select_arti_pedido_nef").value;
		$.ajax({
			type: "post",
			url: "data/dat_art_pedido_nef.php",
			data: {id_articulo:id_articulo},
			dataType: "html",
			success:  function (response) { 
			$('#resultados_js').html(response);
			}
		});
		
	};	
	function cargar_dat_artimicro_nef_asig()
	{
		$("#div_datos_artnef").html("");
		$("#modal_cargando").modal("show");
		var id_articulo = document.getElementById("select_arti_pedido_nef_asig").value;
		$.ajax({
			type: "post",
			url: "data/dat_art_pedido_nef_asig.php",
			data: {id_articulo:id_articulo},
			dataType: "html",
			success:  function (response) { 
			$('#resultados_js').html(response);
			}
		});
		
	};
	function asignar_articulonef(id_articulo)
	{
		$("#txt_idart_relacionar").val(id_articulo);
		//mostrar modal para relacionar articulo nef 
		$("#modal_asig_artnef").modal("show");
	}
	function asignar_artnef()
	{
		var id_art_nef = document.getElementById("txt_idart_nef").value;
		var clave_nef = document.getElementById("txt_clave_nef").value;
		var id_articulo = document.getElementById("txt_idart_relacionar").value;
		
		if (id_articulo === "")
		{
			alert("No esta seleccionado ningun articulo de la lista del pedido nef");
		}
		else 
		{
			if (id_art_nef === "")
			{
				alert("No esta seleccionado ningun articulo Nef");
			}
			else 
			{
				$.ajax({
				type: "post",
				url: "data/asignar_artnef.php",
				data: {id_articulo:id_articulo,id_art_nef:id_art_nef,clave_nef:clave_nef},
				dataType: "html",
				success:  function (response) { 
				$('#resultados_js').html(response);
				}
				});
			}
		}	
		
	} 
	function guardar_pedido_nef()
	{
		var id_pedido = document.getElementById("txt_id_pedido_nef").value;
		var fecha_entrega = document.getElementById("txt_fecha_entrega_pedido").value;
		
		
		if (id_pedido === ""){
			alert("No se a agragado ningun articulo a la lista de pedido NEF");
		}
		else
		{
			// actualizar estatus,folio,fecha_entrega,total_pedido
			$("#modal_cargando").modal("show");
			$.ajax({
			type: "post",
			url: "data/guardar_pedido_nef.php",
			data: {id_pedido:id_pedido,fecha_entrega:fecha_entrega},
			dataType: "html",
			success:  function (response) { 
			$('#resultados_js').html(response);
			}
			});
		}
		
		
	};
	
	function calc_add_precio_total()
	{
		
		var cantidad = document.getElementById("txtadd_unidades").value;
		var precio_unitario = document.getElementById("txtadd_precio").value;
		
		var precio_total = cantidad * precio_unitario;
		
		$("#txtadd_precio_total").val(precio_total);
	};
	
	function agregar_art_pedido()
	{
		var id_art = document.getElementById("select_arti_pedido").value;
		var nombre_art = document.getElementById("txtadd_nombre_art_micro").value;
		var unidades = document.getElementById("txtadd_unidades").value;
		var precio = document.getElementById("txtadd_precio").value;
		var precio_total = document.getElementById("txtadd_precio_total").value;
		var udm = document.getElementById("txtadd_udm").value;
	//	alert("id articulo > "+id_art); si es cero debe seleccionar un articulo
	if (id_art === 0)
	{
		alert("Debe seleccionar un articulo para poder agregarlo");
	}else if (id_art != 0){
		// funcion para agregar a lista de pedido a surtir para traspaso a alamacen correspondiente
		if (unidades != "" && unidades > 0)
		{
			
			$.ajax({
				type: "post",
				url: "data/add_art_pedido_traspaso.php",
				data: {id_art:id_art,nombre_art:nombre_art,unidades:unidades,precio:precio,precio_total:precio_total,udm:udm},
				dataType: "html",
				success:  function (response) { 
				$('#resultados_js').html(response);
				}
			});
			$("#txtadd_unidades").val("");
			$("#txtadd_precio").val("");
			$("#txtadd_precio_total").val("");
			$("#td_addartudm").attr("title","");
			$("#td_addartudm").html("");
			$("#txtadd_clave_art_micro").val("");
			$("#select_arti_pedido").focus();
		}
		else
		{
			alert("Proporcione la cantidad de unidades para traspaso");
			$("#txtadd_unidades").focus();
		}
	}
		
		
	};
	function cargar_lista_pedido(){
		var id_user = <?php echo $_SESSION["logged_user"]; ?> 
		$.ajax({
			type: "post",
			url: "data/lista_pedido_traspaso_det.php",
			data: {id_user:id_user},
			dataType: "html",
			success:  function (response) { 
			$('#div_lista_pedido_det').html(response);
			}
		});
	};
	function cargar_lista_pedido_nef(){
		var id_user = <?php echo $_SESSION["logged_user"]; ?> 
		$.ajax({
			type: "post",
			url: "data/lista_pedido_nef_det.php",
			data: {id_user:id_user},
			dataType: "html",
			success:  function (response) { 
			$('#div_lista_pedido_det_nef').html(response);
			}
		});
	};
	
	function calc_precio_total()
	{
		var cantidad = document.getElementById("txt_cantidad_oc").value;
		var precio_unitario = document.getElementById("txt_precio_unitario").value;
		
		var precio_total = cantidad * precio_unitario;
		
		
		$("#txt_precio_total").val(precio_total);
	};
	
	function agregar_partida_oc()
	{	
		var id_articulo = document.getElementById("select_arti_oc").value;
		var cantidad = document.getElementById("txt_cantidad_oc").value;
		var udm = document.getElementById("txt_udm_oc").value;
		var numero_parte = document.getElementById("txt_numero_parte").value;
		var descripcion = document.getElementById("txt_descripcion_oc").value;
		var precio_unitario = document.getElementById("txt_precio_unitario").value;
		var precio_total = document.getElementById("txt_precio_total").value;
		var orden_id = document.getElementById("txt_orden_id").value;
		
		if (id_articulo == 0){
			alert("Es necesario relacionar el articulo de la orden con el articulo correspondiente en nuestro sistema");
			$("#select_arti_oc").focus();
			return false;
		}
		
		$.ajax({
		type: "post",
		url: "data/add_part_oc.php",
		data: {id_articulo:id_articulo,cantidad:cantidad,udm:udm,numero_parte:numero_parte,descripcion:descripcion,precio_unitario:precio_unitario,precio_total:precio_total,orden_id:orden_id},
		dataType: "html",
        success:  function (response) {
			$('#resultados_js').html(response);
		}
		});
		
	};
	function lista_oc_det()
	{
		var orden_id = document.getElementById("txt_orden_id").value;
		$.ajax({
		type: "post",
		url: "data/lista_ordenes_det.php",
		data: {orden_id:orden_id},
		dataType: "html",
        success:  function (response) {
			$('#div_detalle_orden').html(response);
		}
		});
	};
	function del_part_oc(id_oc_det)
	{
		$.ajax({
			type: "post",
			url: "data/del_part_oc.php",
			data: {id_oc_det:id_oc_det},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			}
		});
	}
	function cancelar_pedido_traspaso()
	{
		var id_pedido =  document.getElementById("txt_id_pedido_traspaso").value;
		
		if (id_pedido == ""){
			alert("No ha generado ningun pedido de traspaso para poder cancelar");
		}else{
			$.ajax({
			type: "post",
			url: "data/cancelar_pedido_traspaso.php",
			data: {id_pedido:id_pedido},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			}
			});
		}
		
	}
	function cancelar_pedido_nef()
	{
		
		var id_pedido =  document.getElementById("txt_id_pedido_nef").value;
		if (id_pedido == ""){
			alert("No ha generado ningun pedido Nef para poder cancelar");
		}else{
			$("#modal_cargando").modal("show");
			$.ajax({
			type: "post",
			url: "data/cancelar_pedido_nef.php",
			data: {id_pedido:id_pedido},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			}
			});
		}
		
	}
	function borrar_partida(id_det)
	{
		$("#modal_cargando").modal("show");
		$.ajax({
			type: "post",
			url: "data/del_part_pedido.php",
			data: {id_det:id_det},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			}
		});
	}
	function borrar_partidaNef(id_det)
	{
		$("#modal_cargando").modal("show");
		$.ajax({
			type: "post",
			url: "data/del_part_pedidonef.php",
			data: {id_det:id_det},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			}
		});
	}
	function guardar_oc(tipo_oc){
		/// guardar oc cerrada
		var orden_id = document.getElementById("txt_orden_id").value;
		var fecha_orden = document.getElementById("txt_fecha_orden").value;
		var requisitor = document.getElementById("txt_requisitor").value;
		var comprador = document.getElementById("txt_comprador").value;
		
		$.ajax({
		type: "post",
		url: "data/guardar_oc.php",
		data: {orden_id:orden_id,tipo_oc:tipo_oc,fecha_orden:fecha_orden,requisitor:requisitor,comprador:comprador},
		dataType: "html",
        success:  function (response) {
			$('#div_detalle_orden').html(response);
		}
		});
		//verif_orden();
		
	};
	function guardar_traspaso(){ 
	/// guarda la solicitud de traspaso y en base a esta la persona encargada de realizar el traspaso cuando el material este listo para surtirse
		var id_pedido_traspaso =  document.getElementById("txt_id_pedido_traspaso").value;
		var fecha_entrega = document.getElementById("txt_fecha_entrega_traspaso").value;
		 var inps = document.getElementsByName('txt_cant_surtir[]');
		 
		 var arr_art_tras = "";
		 var id_art_tras = "";
		 var arr_cantidades = new Array();
for (var i = 0; i <inps.length; i++) {
var inp=inps[i];
   //console.log("txt_cant_surtir["+id_art_tras+"].value="+inp.value);
	arr_art_tras = inp.id.split("_");
	id_art_tras = arr_art_tras[1];
	//console.log("txt_cant_surtir["+id_art_tras+"].value="+inp.value);
	arr_cantidades.push(id_art_tras+"_"+inp.value);
	if (inp.value <= 0){
		alert("proporcione la cantidad a requerida para el traspaso");
		$("#"+inp.id).focus();
		return false;
	}	
}	
//console.log(arr_cantidades);
		if (id_pedido_traspaso === ""){
			alert("No se a agragado ningun articulo a la lista de Solicitud de Traspaso");
		}
		else if (fecha_entrega === ""){
			alert("Seleccione una fecha de entrega");
		}
		else
		{ 
			$.ajax({
				type: "post",
				url: "data/guardar_pedido_traspaso.php",
				data: {id_pedido_traspaso:id_pedido_traspaso,fecha_entrega:fecha_entrega,arr_cantidades:arr_cantidades},
				dataType: "html",
				success:  function (response) {
				$('#resultados_js').html(response);
				}
			});
		}
	};
	function lista_solicitudes_traspaso(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
 // var values = $("input[name='txt_cant_surtir[]']").map(function(){return $(this).val();}).get();
	  
	ocultar_divs_principales();
	$("#div_content_pedidos_nef").show();	
	$("#modal_cargando").modal("show");
			jQuery.ajax({ 
				type: "POST",
				url: "data/lista_pedidos_traspaso.php",
				data: {id_user:id_user},
				success: function(resultados)
				{
					$("#div_lista_pedidos_nef").html(resultados);		
					
					$("#modal_cargando").modal("hide");					
				}
			});
		return false;	
   };
	/////// inserta pedido nef a base de id de pedido en sistema
	function insertar_pedido_nef(id_pedido){
		$("#modal_cargando").modal("show");
		$.ajax({
			type: "post",
			url: "data/insertar_pedido_nef.php",
			data: {id_pedido:id_pedido},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			}
		});
		
	};
	
	/////// inserta la remision del pedido dado
	function Insert_remision(id_pedido){
		$.ajax({
			type: "post",
			url: "data/generar_remision.php",
			data: {id_pedido:id_pedido},
			dataType: "html",
			success:  function (response) {
			$('#resultados_js').html(response);
			}
		});
		
	};
	/////// Actualizar inventario despues de surtir pedido
	function SincronizarInventario(id_pedido){
		$.ajax({
		type: "post",
		url: "data/sincronizarinvped.php",
		data: {id_pedido:id_pedido},
		dataType: "html",
        success:  function (response) {
        $('#resultados_js').html(response);
      }
    });
		
	};
	/////// Actualizar inventario por articulo
	function SincronizarInventarioArticulo(id_articulo){
		$("#modal_cargando").modal("show");
		var almacen_id = document.getElementById("select_almacen_articulos").value;
		$.ajax({
		type: "post",
		url: "data/sincronizarinvart.php",
		data: {id_articulo:id_articulo,almacen_id:almacen_id},
		dataType: "html",
        success:  function (response) {
        $('#resultados_js').html(response);
		$("#modal_cargando").modal("hide");
      }
    });
		
	};
		/////////////////////   ABRIR MODAL PARA AGREGAR tracking  /////////////////////////
	function agregar_tracking(id_pedido){ 
		//alert("Articulo ID= "+id);
		
		var id_pedido =  document.getElementById("txt_id_pedido").value;
		var tracking =  document.getElementById("txt_tracking").value;
		  
			//jQuery('#modal_orden').modal('show', {backdrop: 'static'});
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_traking.php",
				data: {id_pedido:id_pedido,tracking:tracking},
				success: function(response)
				{
					$("#resultados_js").html(response);
					//$("#modal_orden").modal("toggle"); 					
					//jQuery('#modal_orden .modal-body').html(response);
				}
			});
		};
	
	function BuscarArticuloMicrosip(clave_nombre){
		$("#div_tabla_art_microsip").html("Cargando resultados....");
	var almacen_id =  document.getElementById("select_almacen_busqueda").value;
   jQuery.ajax({ //
				type: "POST",
				url: "data/busca_articulo_microsip_almacen.php",
				data: {clave_nombre:clave_nombre,almacen_id:almacen_id},
				success: function(resultados)
				
				{
								
				$("#div_tabla_art_microsip").html(resultados);		
						
					
				
				}
			});
		return false;	
   
   
   };
   function solicitar_compra(){
	   var art_seleccionados = document.getElementsByClassName("chk_art_select");
		
		var item_list = "";
		var list_art_seleccionados = new Array();
		if (art_seleccionados.length > 0){ // comprueba si existe el check
		//console.log(art_seleccionados.length+"_ length");
		for (var ii = 0; ii < art_seleccionados.length; ii++) {
		 	item_list = art_seleccionados[ii];
			var array_item_list = item_list.id.split("_");
			var id_articulo = array_item_list[1];
			var estatus_check = item_list.checked;
			list_art_seleccionados.push(id_articulo+"_"+estatus_check);
			/// validacion para saber que articulos se van a mandar pedir
			if (estatus_check == true) // si esta marcado el check entonces lo debe mostrar en lista para pedir
			{
				alert("id_articulo: "+id_articulo+" Estatus: "+estatus_check);
			}
			
		}
		} else { list_art_seleccionados.push("0"); }
		
   };
  
 /////////GUARDA ARTICULO ///////////////////////////////////////////  
   function guardar_articulo(){

	//var empresa_id = document.getElementById("select_art_empresa").value;
	var empresa_id = "11"; // empresa DURA
	var almacen_id = document.getElementById("txt_almacen_id").value;
	var udm = document.getElementById("txt_udm").value;
	var clave_empresa	= document.getElementById("txt_clave_empresa").value;
	var clave_microsip = document.getElementById("txt_clave_microsip").value;
	var articulo = document.getElementById("txt_nombre_articulo").value;
	//var descripcion = document.getElementById("txt_descripcion").value;
	var precio = document.getElementById("txt_precio").value;
   // var imagen = document.getElementById("txt_imagen").value;
    var id_articulo = document.getElementById("txt_id_articulo").value;
    var id_articulo_microsip = document.getElementById("txt_id_articulo_microsip").value;
    var min = document.getElementById("txt_min").value;
    var max = document.getElementById("txt_max").value;
    var reorden = document.getElementById("txt_reorden").value;
    var existencia = document.getElementById("txt_existencia").value;
	
	if (id_articulo == ''){ 
   jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_articulo.php",
				data: {empresa_id:empresa_id,clave_empresa:clave_empresa,clave_microsip:clave_microsip,precio:precio,articulo:articulo,min:min,max:max,reorden:reorden,existencia:existencia,id_articulo_microsip:id_articulo_microsip,almacen_id:almacen_id,udm:udm},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				}
			});
			
   }else {
	jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_articulo.php",
				data: {empresa_id:empresa_id,clave_empresa:clave_empresa,clave_microsip:clave_microsip,precio:precio,articulo:articulo,id_articulo:id_articulo,min:min,max:max,reorden:reorden,existencia:existencia,id_articulo_microsip:id_articulo_microsip,almacen_id:almacen_id,udm:udm},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				}
			});   
   }	
			
			
		return false;	
   
   
   };
   
   function sin_accion(){
	  return false;
  };
</script>

<?php echo $header_vendor; ?>
<section class="topics">
    <div class="container">
        <div class="row">
			<div class="col-lg-12"  id="div_inventarios">
				
					
					<div class="col-lg-12 row"  id="div_captura_inventario">
						
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingThree">
									<h4 class="panel-title">
										
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree" onclick=""> <!-- VERIFICA INVENTARIOS  -->
										<div style="width:100%; height:20px;">
										<span class="caret pull-right"></span>
											Captura de Inventario
										</div>
										</a>
									</h4>
								</div>
								<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" >
									<div class="panel-body">
										<input type="hidden" id="txt_id_inventario_activo" value="" />
										<div class="col-lg-12" >
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
												
												<div class="dropdown" class="col-sm-12 col-xs-12" style="z-index:8;"  id="btn_nuevo_inv">
													<button class="btn btn-primary dropdown-toggle col-sm-12 col-xs-12" type="button" data-toggle="dropdown">Nueva Captura Inventario
													<span class="caret"></span></button>
													<ul class="dropdown-menu">
													<?php 
													$lista_almacenes = lista_almacenes_consigna();
													foreach($lista_almacenes as $id_almacen => $almacen){
													
													echo '<li id="btninvnew_'.$id_almacen.'"  class="btn almacenes_sin_inventario_activo"><a href="#" onclick="nuevo_lev_inv('.$id_almacen.');" class="btn btn-primary"><strong>'.$almacen.'</strong></a></li>';		
													}?>	
														
													</ul>
												</div>
											</div><div class="col-sm-12 hidden-md hidden-lg">&nbsp;</div>
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="z-index:9;">
												<input type="button" class="btn btn-success col-sm-12 col-xs-12" value="Cerrar Captura de Inventario" onclick="cerrar_captura_inv();" id="btn_cerrar_inv"/>
											</div><div class="col-sm-12 hidden-md hidden-lg">&nbsp;</div>
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="z-index:10;">
												<input type="button" class="btn btn-danger col-sm-12 col-xs-12" value="Cancelar Inventario" onclick="cancelar_inv(0);" id="btn_cancelar_inv"/>
											</div>
										</div>
										<div class="col-lg-12">&nbsp;</div>
										<div class="col-lg-12" id="div_datos_inventario_actual" style="font-size:12px;">
										
											
											
											
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>  
					<div class="col-lg-12 table-responsive"  id="div_localizar_articulo">
					</div>        
				
			</div>
			<div class="col-lg-12 "  id="div_articulos" >
				<div  class="col-lg-12 " style="z-index:3;">
					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" > 
						<div class="checkbox bg-warning btn col-lg-12 col-md-12 col-sm-12 col-xs-12" >
							<label >
								<input type="checkbox" id="chk_reorden" checked /> Mostar Articulos Reorden
							</label>
						</div>
					</div>
					<div class="col-lg-3  col-md-6 col-sm-6 col-xs-12" > 
						<div class="checkbox bg-danger  btn col-lg-12 col-md-12 col-sm-12 col-xs-12" >
							<label>
								<input type="checkbox" id="chk_urgentes" checked /> Mostar Articulos Urgentes
							</label>
						</div>
					</div>
					<div class="col-lg-3  col-md-6 col-sm-6 col-xs-12" > 
						<div class="checkbox bg-info btn col-lg-12 col-md-12 col-sm-12 col-xs-12" >
							<label>
								<input type="checkbox" id="chk_sobreinventario" checked /> Mostar Sobre-Inventariados
							</label>
						</div>
					</div>
					<div class="col-lg-3  col-md-6 col-sm-6 col-xs-12" > 
						<div class="checkbox bg-success btn col-lg-12 col-md-12 col-sm-12 col-xs-12" >
							<label>
								<input type="checkbox" id="chk_bien" checked /> Mostar Articulos Bien
							</label>
						</div>
					</div>
				</div>
				<div class="col-lg-12">&nbsp;</div>
				<div  class=" col-lg-12 ">
					<div class="col-lg-4 col-sm-12 col-xs-12"  style="z-index:5;"> 
						<a href="#modal_articulo_microsip" class="btn btn-primary agregar_nuevo_articulo col-sm-12 col-xs-12" data-toggle="modal">Agregar Articulo Microsip</a>
					</div>
					<div class="col-lg-12">&nbsp;</div>
					<div class="col-lg-4 col-sm-12 col-xs-12"  style="z-index:6;">
						<!--	<a href="#" class="btn btn-primary btn_solicitar_compra" data-toggle="modal">Solicitar Articulos Seleccionados</a>  -->
					</div><div class="col-lg-12">&nbsp;</div>
					<div class="col-lg-4 col-sm-12 col-xs-12" style="z-index:7;">
						<select id="select_almacen_articulos" class="select form-control ">
						<?php 
							foreach($lista_almacenes as $id_almacen => $almacen)
							{
								echo '<option value="'.$id_almacen.'"><strong>'.$almacen.'</strong></option>';
							}
						?>
						</select>
					</div>
				</div>
	
				<div class="col-lg-12">&nbsp;</div>
				<div  class="col-lg-12 table-responsive" id="div_articulos_lista"></div>
			</div>
			<div class="col-lg-12 "  id="div_registro_inventarios" >
				<div  class="col-lg-12 " style="z-index:3;">
					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" > 
						<div id="div_chk_solonpart" class="checkbox btn-primary btn col-lg-12 col-md-12 col-sm-12 col-xs-12" >
							<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="checkbox" id="chk_solonpart"  /> Solo #Part
							</label>
						</div>
					</div>
					<div class="col-lg-3  col-md-6 col-sm-6 col-xs-12" > 
						<div class="checkbox btn-warning  btn col-lg-12 col-md-12 col-sm-12 col-xs-12" >
							<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="checkbox" id="chk_invpend" checked />  Pendientes O.C. 
							</label>
						</div>
					</div>
					<div class="col-lg-3  col-md-6 col-sm-6 col-xs-12" > 
						<div class="checkbox btn-danger btn col-lg-12 col-md-12 col-sm-12 col-xs-12" >
							<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="checkbox" id="chk_invcancel" checked /> Cancelados
							</label>
						</div>
					</div>
					<div class="col-lg-3  col-md-6 col-sm-6 col-xs-12" > 
						<div class="checkbox btn-success btn col-lg-12 col-md-12 col-sm-12 col-xs-12" >
							<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="checkbox" id="chk_invliq" checked /> Liquidados
							</label>
						</div>
					</div>
				</div>
				<div class="col-lg-12">&nbsp;</div>
				<div  class="col-lg-12 table-responsive" id="div_lista_inventarios"></div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
				<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive" id="div_lista_indetart">
						</div>
							
			</div>
			<div class="col-lg-12 "  id="div_ordenes_cliente" >
				
				
				<div  class="col-lg-12 clearfix" style="z-index:6;">
					<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" > 
						<button class="btn btn-primary col-lg-12 col-md-12 col-sm-12 col-xs-12" onclick="orden_nueva();"> Capturar Orden
						</button>
						
					</div>
					<div class="col-lg-2  col-md-4 col-sm-6 col-xs-12" > 
						<button class="btn-danger btn col-lg-12 col-md-12 col-sm-12 col-xs-12" onclick="cancelar_orden();"  id="btn_cancelar_oc">
						  Cancelar
						</button>
					</div>
					<div class="col-lg-2  col-md-4 col-sm-6 col-xs-12" > 
						<button class="btn-success btn col-lg-12 col-md-12 col-sm-12 col-xs-12"  id="btn_guardar_oc" title="Se solicita facturacion de la OC a CXC">
						<i class="fa fa-files-o" aria-hidden="true" ></i> Cerrar OC y Fact.
						</button>
					</div>
					<div class="col-lg-2  col-md-4 col-sm-6 col-xs-12" > 
						<button class="btn-success btn col-lg-12 col-md-12 col-sm-12 col-xs-12"  id="btn_guardar_oc_abierta">
						<i class="fa fa-save"></i> Guardar OC Abierta
						</button>
					</div>
					
					<div class="col-lg-2  col-md-4 col-sm-6 col-xs-12" > 
						<button class="btn-warning  btn col-lg-12 col-md-12 col-sm-12 col-xs-12" 
						onclick="mostrar_ordenes();" id="btn_lista_ordenes_capturadas" >
						<i class="fa fa-list" aria-hidden="true"></i> OC Capturadas
						</button>
					</div>
				</div>
				<div class="col-lg-12">&nbsp;</div>
				<div  class="col-lg-12 table-responsive" id="div_lista_ordenes"></div>
				<div class="col-lg-12">&nbsp;</div>
				<div id="div_datos_ordenes" class="col-lg-12 table-responsive" style="z-index:7;">
					<table class="table table-responsive">
						<thead>	
							<tr>
								<th style="width:150px;">
									Folio Orden
								</th>
								<th style="width:130px;">
									Fecha Orden
								</th>
								<th>
									Requisitor
								</th>
								<th>
									Comprador
								</th>
								<th style="width:180px;">
									Planta
								</th>
								<th>
									Estado
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
								<input type="hidden" id="txt_orden_id" value="" />
								<input type="text" id="txt_orden" placeholder="Folio Orden" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
								</td>
								<td>
								<input type="date" id="txt_fecha_orden" placeholder="Fecha Orden" class="form-control"/>
								</td>
								<td>
								<input type="text" id="txt_requisitor" placeholder="Requisitor" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
								</td>
								<td>
								<input type="text" id="txt_comprador" placeholder="Comprador" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
								</td>
								<td>
									<select id="select_almacen_oc" class="select form-control ">
									<?php 
									foreach($lista_almacenes as $id_almacen => $almacen)
									{
									echo '<option value="'.$id_almacen.'">'.$almacen.'</option>';			
									}
									?>
									</select>
								</td>
								<td id="td_estatus_oc">
								Sin Guardar - Guardada - Remisionada - Facturada
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div  class="col-lg-12 table-responsive" id="div_detalle_orden"></div>
				
				
			</div>
			
			<div class="col-lg-12" id="div_lista_pedidos" >
			
			</div>
			
			<div id="div_solicitud_traspaso" class="col-lg-12"> 
						<!--  -->
				<div id="div_datos_traspaso" class="col-lg-12 table-responsive" style="z-index:8;">
					<table class="table table-responsive">
						<thead>	
							<tr>
								
								<th style="width:130px;">
									Fecha Entrega
								</th>
								<th>
									Requisitor
								</th>
								<th style="width:180px;">
									Planta
								</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								
								<td>
								<input type="date" id="txt_fecha_entrega_traspaso" placeholder="Fecha Entrega" class="form-control"/>
								</td>
								<td>
								<input type="text" id="txt_requisitor_traspaso" placeholder="Requisitor" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
								</td>
								<td>
									<select id="select_almacen_traspaso" class="select form-control ">
									<?php 
									foreach($lista_almacenes as $id_almacen => $almacen)
									{
									echo '<option value="'.$id_almacen.'">'.$almacen.'</option>';			
									}
									?>
									</select>
								</td>
								<td> 
									<input type="button" value="Guardar solicitud de traspaso" onclick="guardar_traspaso();" class="btn btn-success"/>
								</td>
								<td> 
									<input type="hidden" id="txt_verif_carga_art_traspaso" value="0" />
									<input type="hidden" id="txt_id_pedido_traspaso" value="" />
									<input type="button" value="Cancelar" onclick="cancelar_pedido_traspaso();" class="btn btn-danger"/>
								</td>
							</tr>
						</tbody>
					</table>
				</div>					
					
				<div  class="col-lg-12"  style="z-index:9;" id="div_add_art_traspaso">
					<table class="table ">
						<thead>	
							<tr>
								<th hidden >
									Clave
								</th>
								<th >
									Nombre Articulo
								</th>
								<th >
									UDM
								</th>
								<th style="width:150px;">
									Unidades
								</th>
								<th style="width:150px;">
									Precio
								</th>
								<th style="width:150px;">
									Importe Total
								</th>
								<th>
									Agregar
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td hidden >
							
								<input type="hidden" id="txtadd_nombre_art_micro" value="" />
								<input type="text" id="txtadd_clave_art_micro" placeholder="Clave" class="form-control" />
								</td>
								<td >
									<div class="col-sm-12" id="div_select_art_pedido">
										<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_arti_pedido" > 
										</select>
									</div>
								</td>
								<td id="td_addartudm">
								UDM
								</td>
								<td>
								<input type="number" id="txtadd_unidades" min="1"  class="form-control" />
								</td>
								<td>
									<input type="number" id="txtadd_precio"  class="form-control" />
								</td>
								<td id="tdadd_importe_total">
								<input type="text" id="txtadd_precio_total" value="" disabled  class="form-control"/>
								<input type="hidden" id="txtadd_udm" value="" />
								
								</td>
								<td id="tdadd_art_pedido">
								<input type="button" id="btn_add_artpedido" value="+"  class="form-control btn btn-primary" onclick="agregar_art_pedido();"/>
								</td>
							</tr>
							<tr>
								<td hidden>
								</td>
								<td>
								</td>
								<td>
								</td>
								<td id="td_existencia_art">
								</td>
								<td>
								</td>
								<td>
								</td>
								<td>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="msj_pedido_relacionado" class="col-lg-12">
				<h4>pedido cliente ligado no puede modificar el almacen ni agregar mas articulos
				si desea hacer cambio se debe desligar el pedido</h4></div>
				
				<div class="col-lg-12">&nbsp;</div>
				<div  class="col-lg-12 table-responsive" id="div_lista_pedido_det"></div>
			</div>
			<div id="div_pedido_nuevo" class="col-lg-12"> 
						<!--  -->
				<div id="div_datos_pedido" class="col-lg-12 table-responsive" style="z-index:9;">
					<table class="table table-responsive">
						<thead>	
							<tr>
								
								<th style="width:130px;">
									Fecha Entrega
								</th>
								<th>
									Requisitor
								</th>
								<th style="width:180px;" hidden >
									Planta
								</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								
								<td>
								<input type="date" id="txt_fecha_entrega_pedido" placeholder="Fecha Entrega" class="form-control"/>
								</td>
								<td>
								<input type="text" id="txt_requisitor_pedido" placeholder="Requisitor" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
								</td>
								<td hidden >
									<select id="select_almacen_pedido" class="select form-control ">
									<?php 
									foreach($lista_almacenes as $id_almacen => $almacen)
									{
									echo '<option value="'.$id_almacen.'">'.$almacen.'</option>';			
									}
									?>
									</select>
								</td>
								<td> 
									<input type="button" value="Guardar Pedido" onclick="guardar_pedido_nef();" class="btn btn-success"/>
								</td>
								<td> 
									
									<input type="hidden" id="txt_verif_carga_art_nef" value="0" />
									<input type="hidden" id="txt_id_pedido_nef" value="" />
									<input type="button" value="Cancelar" onclick="cancelar_pedido_nef();" class="btn btn-danger"/>
								</td>
							</tr>
						</tbody>
					</table>
				</div>					
					
				<div  class="col-lg-12"  style="z-index:9;" id="div_add_art_pedido" >
					<table class="table ">
						<thead>	
							<tr>
								<th hidden >
									Clave
								</th>
								<th >
									Nombre Articulo
								</th>
								<th >
									UDM
								</th>
								<th style="width:150px;">
									Unidades
								</th>
								<th style="width:150px;">
									Precio
								</th>
								<th style="width:150px;">
									Importe Total
								</th>
								<th>
									Agregar
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td hidden >
								
								<input type="hidden" id="txtadd_nombre_art_micro_nef" value="" />
								<input type="text" id="txtadd_clave_art_micro_nef" placeholder="Clave" class="form-control" />
								</td>
								<td >
									<div class="col-sm-12" id="div_select_art_pedido_nef">
										<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_arti_pedido_nef" > 
										</select>
									</div>
								</td>
								<td id="td_addartudm_nef">
								UDM
								</td>
								<td>
								<input type="number" id="txtadd_unidades_nef" min="1"  class="form-control" />
								</td>
								<td>
									<input type="number" id="txtadd_precio_nef"  class="form-control" />
								</td>
								<td id="tdadd_importe_total_nef">
								<input type="text" id="txtadd_precio_total_nef" value="" disabled  class="form-control"/>
								<input type="hidden" id="txtadd_udm_nef" value="" />
								
								</td>
								<td id="tdadd_art_pedido_nef">
								<input type="button" id="btn_add_artpedido_nef" value="+"  class="form-control btn btn-primary" onclick="agregar_art_pedido_nef();"/>
								</td>
							</tr>
							<tr>
								<td hidden>
								</td>
								<td>
								</td>
								<td>
								</td>
								<td id="td_existencia_art_nef">
								</td>
								<td>
								</td>
								<td>
								</td>
								<td>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-lg-12">&nbsp;</div>
				<div  class="col-lg-12 table-responsive" id="div_lista_pedido_det_nef"></div>
			</div>
			<div  id="div_content_pedidos_nef" class="col-lg-12"> 
				<div id="div_lista_pedidos_nef" class="col-lg-12"> 
				
				</div>
				
				
			</div>
			<div class=""  id="resultados_js"></div> <!-- RESULTADOS EN JS  -->
			<!-- <div id="pedidos" class=" col-lg-12"></div> -->
			  
        </div>
    </div>
</section>
<?php echo $modal_tracking_vendor; ?>
<?php echo $footer_vendor; ?>

<!--modal articulo microsip-->
            		<div class="modal fade" id="modal_articulo_microsip" tabindex="-1" role="dialog">
                		<div class="modal-dialog modal-lg" role="document">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Articulo Microsip
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<form action="" class="form-horizontal" onsubmit="return sin_accion();">
										
									<div class="form-group">
						                   	<label for="txt_buscar_art_microsip" class="col-lg-4 control-label">Articulo a buscar en microsip</label>
						                   	<div class="col-lg-4">
						                   		<input type="text" id="txt_buscar_art_microsip" placeholder="Nombre o Clave" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="col-lg-12 form-control"/> 
												
						                   	</div>
											<div class="col-lg-4" >
												<select id="select_almacen_busqueda" class="select form-control ">
						<?php 
						
						foreach($lista_almacenes as $id_almacen => $almacen){
						echo '<option value="'.$id_almacen.'">'.$almacen.'</option>';			
						}?>
												</select>
											</div>
					                   	</div> 
										<div class="table-responsive" id="div_tabla_art_microsip">
						                   	
					                   	</div>
										
					                   
										
										<!---->
				                    </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                				 <!--	<button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_seleccionar_articulo">Agregar Articulo seleccionado</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
								  -->	
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal articulo microsip-->
<!--modal modal_listadet -->
        <div class="modal fade" id="modal_listadet" tabindex="-1" role="dialog" aria-labelledby="Modalreginv" aria-hidden="true" style="overflow-y: scroll;">
            <div class="modal-dialog modal-lg" role="document">
            	<div class="modal-content clearfix">
            		<!-- Header de la ventana -->
            		<div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            			<h3 class="modal-title">
            				Lista de productos inventariados
            			</h3>
            		</div>
            		<!-- Contenido de la ventana  //class="modal-body"  style="min-height:300px;"-->
            		<div class="modal-body" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix"> 
					
						<div class="col-lg-12 bg-info clearfix"  > 
							<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 " > 
								<h4> Lista de Invetario Folio: 
								<span id="span_folioinv"> </span>
								</h4>
							</div>
							<div class="col-lg-3 col-md-3 " >   
								<input type="button" value="Enviar Correo" id="btnenviarinv" class="btn btn-primary btn_enviar_inv" style="z-index:3;"/>
							</div>
							<div class="col-md-12 col-sm-12 hidden-md hidden-lg">&nbsp;</div>
							<div class="col-lg-3 col-md-3 " > 
								<input type="button" value="Agregar imagen de lista firmada" id="btnsubirimagen" class="btn btn-info subirimagen" style="z-index:5;"/>
							</div>
							<div class="col-md-12 col-sm-12 hidden-md hidden-lg">&nbsp;</div>
							<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="img_registradas"> </div>
							<div class="col-md-12 col-sm-12 hidden-md hidden-lg">&nbsp;</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
						
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive" id="div_lista_indet"></div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
						
            		</div>
            		<!-- Footer de la ventana -->
            		<div class="modal-footer clearfix">
            		 <!-- -->		
		
            			<button type="button" class="btn btn-danger" onclick="cancelar_inv(1);" id="btn_cancelar_inv2">Cancelar Inventario</button>
						<!-- <button type="button" class="btn btn-primary" id="btn_crear_remision_inv">Crear Remision</button> -->
						<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
			 
            		</div>
            			
            	</div>
            </div>
        </div>
          <!--final modal_listadet->
<!--modal modal_correo_dir -->
            		<div class="modal fade" id="modal_correo_dir" tabindex="-1" role="dialog">
                		<div class="modal-dialog modal-lg" role="document">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Enviar correo con lista de inventario
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<form action="" class="form-horizontal" onsubmit="return sin_accion();">
										
									<div class="form-group">
						                   	<label for="txt_correo_nuevo" class="col-lg-4 control-label">Direcion de correo:</label>
						                   	<div class="col-lg-4">
												<input type="hidden" id="txt_idinv_correo" value="" />
						                   		<input type="text" id="txt_correo_nuevo" class="col-lg-12 form-control"/> 
												
						                   	</div>
											
					                  	</div> 
										<div class="table-responsive" id="div_tabla_correos">
						                   	
					                   	</div>
										
					                   
										
										<!---->
				                    </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                				 <!-- -->		
								 <button type="button" class="btn btn-primary" onclick="enviar_correo_inv();">Enviar Captura Inventario</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
								 
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal_correo_dir->
<!--modal modal asignar articulo nef para pedido en empresa nef -->
            		<div class="modal fade" id="modal_asig_artnef" tabindex="-1" role="dialog">
                		<div class="modal-dialog modal-lg" role="document">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h4 class="modal-title">
                						Seleccione el Articulo que desea relacionar para generar pedido en empresa NEF
                					</h4>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
									<div class="col-lg-12">	
									<label class="col-lg-12 control-label" id="articulo_asignado"></label> </div>
                					<form action="" class="form-horizontal" onsubmit="return sin_accion();">
										
									<div class="form-group">
						                   	<label for="txt_correo_nuevo" class="col-lg-4 control-label">Articulo Nef:</label>
						                   	<div class="col-lg-8">
												<input type="hidden" id="txt_idart_relacionar" value="" />
												<input type="hidden" id="txt_idart_nef" value="" />
												<input type="hidden" id="txt_clave_nef" value="" />
												
						                   		<div class="col-sm-12" id="div_select_art_pedido_nef_asig">
													<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_arti_pedido_nef_asig" > 
												</select>
												</div>
												
						                   	</div>
											
					                </div> 
									<div class="table-responsive" id="div_datos_artnef">
						                  	
					                </div>
										
					                 <!---->
				                    </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                				 <!-- -->		
								 <button type="button" class="btn btn-primary" onclick="
								 asignar_artnef();"> Guardar Relacion </button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
								 
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal_correo_dir->
<!--modal subir imagen -->
            		<div class="modal fade" id="modal_subir_imagen" tabindex="-1" role="dialog">
                		<div class="modal-dialog modal-lg" role="document">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Seleccione la imagen de lista de invetario firmada
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<form id="formulario" method="post" enctype="multipart/form-data" >
										
												
												<input type="file" name="file" accept="image/jpeg"/> 
												<input type="hidden" id="inpt_id_inventario" />	
									</form>	
										
										<div class="table-responsive" id="div_vista_imagen">
											<!--<span id="span_ruta" > </span>
						                   	<img id="imagen_muestra" width="300" alt="Imagen" />-->
					                   	</div>
										
										
				                    
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                				 <!-- -->		
								 <button type="button" class="btn btn-primary" id="btn_subir_imagen">Subir Imagen</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
								 
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final subir imagen->
<!--modal subir archivo OC -->
            		<div class="modal fade" id="modal_subir_file" tabindex="-1" role="dialog">
                		<div class="modal-dialog" role="document">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Seleccione la OC Cliente
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
									<form id="form_adjuntar" method="post" enctype="multipart/form-data" >
												
									<input type="file" name="file" /> 
									<input type="hidden" id="txt_orden_id" />	
									</form>	
										
									<div class="table-responsive" id="div_vista_pdf">
											<!--<span id="span_ruta" > </span>
						                   	<img id="imagen_muestra" width="300" alt="Imagen" />-->
					                </div>
										
										
				                    
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                				 <!-- -->		
								 <button type="button" class="btn btn-primary" id="btn_adjuntar_oc">Adjuntar OC Cliente</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
								 
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final subir archivo->

<!--modal nuevo Articulo-->
            		<div class="modal fade" id="modal_articulo" tabindex="-1" role="dialog">
                		<div class="modal-dialog" role="document">
                			<div class="modal-content modal-md">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Nuevo Articulo
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<form action="" class="form-horizontal" onsubmit="return sin_accion();">
										<input type="hidden" value="" id="txt_id_articulo" /><!-- TXT CON EL ID DE ARTICULO SELECCIONADO -->
										<input type="hidden" value="" id="txt_id_articulo_microsip" /><!-- TXT CON EL ID DE ARTICULO microsip -->
										<input type="hidden" value="" id="txt_art_id_empresa" /><!-- TXT CON EL ID DE empresa en articulos -->
					                   
						                <div class="form-group">
						                   	<label for="txt_clave_empresa" class="col-sm-4 control-label">Clave Articulo (Cliente):</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_clave_empresa" placeholder="Clave Articulo Cliente">
						                   	</div>
					                   	</div>
						                <div class="form-group">
						                   	<label for="txt_clave_microsip" class="col-sm-4 control-label">Clave Microsip:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_clave_microsip" placeholder="Nombre" disabled>
						                   	</div>
					                   	</div>
					                   	<div class="form-group">
						                   	<label for="txt_nombre_articulo" class="col-sm-4 control-label">Nombre Articulo:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_nombre_articulo" placeholder="Nombre Articulo" />
						                   		<input type="hidden"  id="txt_almacen_id" value="" />
						                   	</div>
						                </div>
					                   	<div class="form-group">
						                   	<label for="txt_udm" class="col-sm-4 control-label">Unidad Medida:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_udm" disabled />
						                   	
						                   	</div>
						                </div>
						              <!--  <div class="form-group">
						                   	<label for="txt_descripcion" class="col-sm-4 control-label">Descripcion:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_descripcion" placeholder="">
						                   	</div> 
					                   	</div> -->
						                <div class="form-group">
						                    <!---->	<label for="" class="col-sm-4 control-label">Puntos y Existencia:</label>
						                   	<div class="col-sm-2">
												<center>Min
						                   		<input type="text" class="form-control" id="txt_min" placeholder="Min">
												</center>
						                   	</div> 	
											<div class="col-sm-2">
												<center>Max
						                   		<input type="text" class="form-control" id="txt_max" placeholder="Max">
												</center>
						                   	</div>  	
											<div class="col-sm-2">
												<center>Reorden
						                   		<input type="text" class="form-control" id="txt_reorden" placeholder="Reorden">
												</center>
						                   	</div> 
											<div class="col-sm-2">
												<center>Existencia
						                   		<input type="text" class="form-control" id="txt_existencia" placeholder="Existencia">
												</center>	
						                   	</div> 
					                   	</div>
						          <!--      <div class="form-group">
						                   <div class="col-sm-1"></div>
						                   								
											<input type="hidden" id="txt_id_categoria" value=""/>
									<div class="col-sm-4"> 
									<span > El articulo pertenece a: </span>									
									<div class="list-group " style="overflow-y: scroll; height: 100px;" id="div_reg_categorias">
									 EL CONTENIDO ES LA LISTA DE CATEGORIAS REGISTRADAS A LOS ARTICULOS
									  
									</div>	
									</div>	
									<div class="col-sm-2">
									<div style="height:50px;"></div>
									<center> 
									<input type="button" class="btn" value="< >" id="add_del_categoria"/>
									</center>
									</div>
											<input type="hidden" id="txt_id_reg_categoria" value=""/>
									<div align="" class="col-sm-4">	
									<span>  Categorias: </span>									
									<div class="list-group " style="overflow-y: scroll; height: 100px;" id="div_categorias">
									 EL CONTENIDO ES LA LISTA DE CATEGORIAS SE ESCRIBE CON PHP 
									</div>
									<a href="#modal_categoria" class="agregar_nueva_categoria" data-toggle="modal">Agregar Categoria</a>
									</div>
					                   	</div>   -->
						             
					                   
										<div class="form-group">
						                   	<label for="precio" class="col-sm-4 control-label">Precio:</label>
						                   	<div class="col-sm-8">
						                   		<input type="number" class="form-control" id="txt_precio" placeholder="Precio$">
						                   	</div>
					                   	</div>
										<!--
					                   	<div class="form-group">
						                   	<label for="txt_imagen" class="col-sm-4 control-label">Imagen:</label>
						                   	<div class="col-sm-8">
						                   		<input type="file" class="form-control" id="txt_imagen" placeholder="imagen">
						                   	</div>
					                    </div> -->
					                    
					                   
				                    </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					<button type="button" class="btn btn-primary"  id="btn_guardar_articulo">Guardar</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal nuevo Articulo-->
		<!--modal conteo de productos inventario -->
            		<div class="modal fade" id="modal_conteo_inventario" tabindex="-1" role="dialog">
                		<div class="modal-dialog " role="document">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Conteo de Productos
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
									<div id="div_conteo_info_producto" class="col-lg-12">
										<div class="col-lg-8">
											<h4 id="ic_nombre"></h4>
											<h5> Clave: <span id="ic_clave"> </span></h5>
											<h5> Clave cliente: <span id="ic_clave_cliente"> </span></h5>
											<h5> Precio: <span id="ic_precio"> </span></h5>
										</div>
										<div class="col-lg-4"> 
										
											<img width="158" height="138" src="assets/images/productos/" id="imagen_inv">
											<input type="hidden" id="txt_conteo_id_articulo" value=""/>
											<input type="hidden" id="txt_conteo_id_art_microsip" value=""/>
										</div>
										<div class="col-lg-12"> 
											<table class="table">
												<tr>
													<td> Unid. Med.:  </td>
													<td> Min: </td>
													<td> reorden: </td>
													<td> Max:  </td>
													<td> Existencia: </td>
												</tr>
												<tr>
													<td> <span id="ic_unidad_medida"> </span></td>
													<td> <span id="ic_min"> </span> </td>
													<td> <span id="ic_reorden"> </span></td>
													<td> <span id="ic_maximo"> </span> </td>
													<td> <span id="ic_existencia"> </span></td>
												</tr>
											</table>
											
											  
											
											
										</div>
									</div>
									<div id="div_btn_conteo_opciones" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
										<div class="col-lg-12" id="div_msj_conteo" style="font-size:12px;">
										
										</div>
									<!--	<div class="col-lg-4">
											<h4>
											<div class="form-check">
												<input type="radio" class="form-check-input" id="opcion_sumar" 
												name="accion_cantidad" value="1">
												<label class="form-check-label" for="opcion_sumar">
													Sumar cantidad 
												</label>
											</div>
											</h4>
										</div>
										<div class="col-lg-4">
											<h4>
											<div class="form-check">
												<input type="radio" class="form-check-input" id="opcion_remplazar" 
												name="accion_cantidad" value="2">
												<label class="form-check-label" for="opcion_remplazar">
													Rempl. cantidad
															
												</label>
											</div>
											</h4>
										</div>
										<div class="col-lg-4">
											<h4>
											<div class="form-check">
												<input type="radio" class="form-check-input" id="opcion_restar" 
												name="accion_cantidad" value="3">
												<label class="form-check-label" for="opcion_restar">
													Restar cantidad 
												</label>
											</div>
											</h4>
										</div>   -->
										
									</div>
									<div>&nbsp;</div>
									<div style="margin-left:25px;">
										<label for="txt_inv_cantidad_contada" class="col-sm-4 control-label">Cantidad Contada</label>
												
										<input type="number" id="txt_inv_cantidad_contada" />
																				
									</div>	
									
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                				 	<button type="button" class="btn btn-success"  id="btn_guarda_cantidad_conteo">Guardar</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
								 <!-- -->	
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal articulo microsip-->
 <!-- Modal zoom -->
<div class="modal fade" id="zoom" tabindex="-1" role="dialog" aria-labelledby="Modal zoom" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> 
        <div class="modal-content">
            <!-- contenido -->
            <div class="modal-body">

                    <div class="row">
                        <div class="col-md-7 col-sm-12">
                           <img width="458" height="458" src="assets/images/productos/" id="imagen_max">
                        </div>
                        <div class="col-md-5 col-sm-12">
                        <h4 id="clave_articulo_detalle">sss</h4>
                        <h4 id="nombre_articulo_detalle">sss</h4>
                        <h4 id="existencia_articulo_detalle">sss</h4>
                        <h4 id="min_articulo_detalle">sss</h4>
                        <h4 id="max_articulo_detalle">sss</h4>
                        <h4 id="reorden_articulo_detalle">sss</h4>
                        <div height="50%">
                            <p id="descripcion_articulo_detalle"> </p>
                        </div>
                        <p class="h3" id="max_precio">
                        </p>
                        </div>
                            <p>
                            </p>

                    </div>
            </div>

        </div>
    </div>                        
</div>
<div class="modal fade" id="modal_partida_oc" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
    <div class="modal-content modal-md">
       	<!-- Header de la ventana -->
       	<div class="modal-header">
       		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
       		<h3 class="modal-title">
       			Agregar Partida a OC
       		</h3>
       	</div>
       		<!-- Contenido de la ventana -->
       	<div class="modal-body">
       		<form action="" class="form-horizontal" onsubmit="return sin_accion();">
				
				<input type="hidden" value="" id="txt_articuloid_oc" />
				<!-- TXT CON EL ID DE ARTICULO SELECCIONADO -->
				 
				<div class="form-group">
	            	<label for="select_arti_oc" class="col-sm-4 control-label">
					Articulo a Relacionar: </label>
	            	<div class="col-sm-8" id="div_select_art_oc">
	            		<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_arti_oc" > 
						</select>
	            	</div>
				</div>
				<div class="form-group">
	            	<label for="txt_cantidad_oc" class="col-sm-4 control-label">
					Cantidad:</label>
	            	<div class="col-sm-8">
	            		<input type="number" class="form-control" id="txt_cantidad_oc">
	            	</div>
				</div>
				<div class="form-group">
	            	<label for="txt_udm_oc" class="col-sm-4 control-label">
					Unidad Medida:</label>
	            	<div class="col-sm-8">
	            		<input type="text" class="form-control" id="txt_udm_oc"  /> 
	            	</div>
				</div>
				<div class="form-group">
	            	<label for="txt_numero_parte" class="col-sm-4 control-label">
					#Part:</label>
	            	<div class="col-sm-8">
	            		<input type="text" class="form-control" id="txt_numero_parte" />
	            	</div>
				</div>
				<div class="form-group">
	            	<label for="txt_descripcion_oc" class="col-sm-4 control-label">
					Descripcion:</label>
	            	<div class="col-sm-8">
	            		<input type="text" class="form-control" id="txt_descripcion_oc" />
	            	</div>
				</div>
				
				<div class="form-group">
	            	<label for="txt_precio_unitario" class="col-sm-4 control-label">
					Precio Unitario:</label>
	            	<div class="col-sm-8">
	            		<input type="number" class="form-control" id="txt_precio_unitario" placeholder="Precio$">
	            	</div>
				</div>
				<div class="form-group">
	            	<label for="txt_precio_total" class="col-sm-4 control-label">
					Precio Total:</label>
	            	<div class="col-sm-8">
	            		<input type="number" class="form-control" id="txt_precio_total" disabled >
	            	</div>
				</div> 
	        </form>
       	</div>
       	<!-- Footer de la ventana -->
       	<div class="modal-footer">
       		<button type="button" class="btn btn-primary"  id="btn_guardar_partida" onclick="agregar_partida_oc();">
			Agregar</button>
       		<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
       	</div>
       		
    </div>
    </div>
</div>
                	<!--final modal nuevo Articulo-->
<div class="modal fade" id="modal_cargando" tabindex="-1" role="dialog" aria-labelledby="Modal cargando" aria-hidden="true" style="top: 50%;" data-backdrop="static" data-keyboard="false">
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
                    </div>					
   
   <script src="assets/js/jquery-1.12.3.min.js"></script>
  
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script type="text/javascript" charset="utf8" src="assets/js/datatable.js"></script>
	<script src="assets/js/bootstrap-select.min.js"></script>
	
	<script>




		
		$(document).ready(function(){
				
				$("#btn_guardar_tracking").click(function(){
					agregar_tracking();
				});
				
			
				$('#txt_buscar_art_microsip').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
					var nombre_clave = document.getElementById("txt_buscar_art_microsip").value;
					if(keycode == '13'){
						BuscarArticuloMicrosip(nombre_clave);
						
						 
					}
				});
				$('#txt_orden').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
				//	var numero_orden = document.getElementById("txt_orden").value;
					if(keycode == '13'){
						verif_orden();
						
					}
				}).focusout(function(){
					verif_orden();
				});
				
				$('#txt_fecha_orden').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);

					if(keycode == '13'){
						
						 $("#txt_requisitor").focus();
					}
				});
				$('#txt_requisitor').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);

					if(keycode == '13'){
						
						 $("#txt_comprador").focus();
					}
				});
				$('#txt_comprador').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);

					if(keycode == '13'){
						
						 $("#select_almacen_oc").focus();
					}
				});
				$("#select_almacen_oc").change(function(){
					verif_orden();
					//cargar_art_oc();
				});
				$('#btn_guardar_articulo').click(function(event){
					
						guardar_articulo();
					
				});
				$('#select_almacen_articulos').change(function(event){
					
					mostrar_articulos(11);
					
				});
				$('#btn_guarda_cantidad_conteo').click(function(event){
					
						guardar_conteo();
					
				});
					
				$('#txtadd_unidades').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode == '13'){
						//calc_add_precio_total();
						$("#txtadd_precio").focus();
						
					}
				});
				$('#txtadd_precio').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode == '13'){
						//calc_add_precio_total();
						// agregar articulo
						$("#btn_add_artpedido").focus();
					}
				});
				
				$("#txtadd_unidades").focusout(function(){
					calc_add_precio_total();
				});
				$("#txtadd_precio").focusout(function(){
					calc_add_precio_total();
				});
				$('#txt_inv_cantidad_contada').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode == '13'){
						guardar_conteo();
					}
				});
				$(".btn_enviar_inv").on("click", function(){
                           var id_inventario = document.getElementById("txt_idinv_correo").value;
							preparar_correo(id_inventario);
							
							   
                });
				$(".subirimagen").on("click", function(){
                           var id_inventario = document.getElementById("txt_idinv_correo").value;
							$("#modal_subir_imagen").modal("show");
							$("#div_vista_imagen").html('');
							$("input[name='file']").val('');
							//preparar_correo(id_inventario);
							
							   
                });
				$("#btn_adjuntar_file").on("click",function(){
					$("#modal_subir_file").modal("show");
				});
				$("input[name='file']").on("change", function(){
					var file = this.files[0];
					var imagefile = file.type;
					var match= ["image/jpeg","image/png","image/jpg"];
					if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))){
						alert('Por favor seleccione una imagen valida (JPEG/JPG/PNG).');
						$("#file").val('');
						return false;
					}
					var TmpPath = URL.createObjectURL(file);
					//$('#ruta_temp').val(TmpPath);
					//$('#imagen_muestra').attr('src', TmpPath);
					
					$("#div_vista_imagen").html('<img src="'+TmpPath+'" style="max-width:500px;">');
				});
				$("#btn_subir_imagen").on("click", function(){
					var formData = new FormData($("#formulario")[0]);
					var ruta = "data/subir_imagen.php";
					
					var id_inv = document.getElementById("inpt_id_inventario").value;
					var imagen = $("input[name='file']").val();
					//alert(imagen);
					if (imagen == "")
					{
						alert("Seleccione una imagen para subir");
						$("#file").focus();
					}
					else
					{
						$.ajax({
						url: ruta,
						type: "POST",
						data: formData,
						contentType: false,
						processData:false,
						success: function(datos)
						{
							$("#div_vista_imagen").html(datos);
						}
					
						})
					}
					
				});
				$("#select_arti_oc").change(function(){
					// cargar unidad de medida y precio
					cargar_dat_relacion();
				});
				$("#select_arti_pedido").change(function(){
					// cargar unidad de medida y precio
					cargar_dat_artimicro();
				});
				$("#select_arti_pedido_nef").change(function(){
					// cargar unidad de medida y precio
					cargar_dat_artimicro_nef();
				});
				$("#select_arti_pedido_nef_asig").change(function(){
					// cargar unidad de medida y precio
					cargar_dat_artimicro_nef_asig();
				});
				$("#txt_precio_unitario").focusout(function(){
					calc_precio_total();
				});
				$("#txt_cantidad_oc").focusout(function(){
					calc_precio_total();
				});
				$("#btn_guardar_oc").click(function(){
					guardar_oc("C");
				});
				$("#btn_guardar_oc_abierta").click(function(){
					guardar_oc("A");
				});
				
				//$("#btn_nuevo_inv").hide();
				$("#btn_cerrar_inv").hide();
				$("#btn_cancelar_inv").hide();
				$("#btn_add_partida").hide();
				$("#btn_adjuntar_file").hide();
				$("#btn_cancelar_oc").hide();
				$("#btn_guardar_oc").hide();
				$("#btn_guardar_oc_abierta").hide();
				
				ocultar_divs_principales();
		
				$("#div_datos_ordenes").hide();
				$("#div_lista_ordenes").hide();
				varificar_captura(0);
					
				 $("#div_chk_solonpart").click(function(){
					/* var valor = $("#chk_solonpart").prop("checked");
					if (valor == false){
						//$("#chk_solonpart").attr("checked", true);
						
					}
					else
					{ 
						//$("#chk_solonpart").attr("checked", false);
					} */	
					verif_mostrar_inventarios();
				}); 
		});
	</script>
	
	
</body>
</html>



