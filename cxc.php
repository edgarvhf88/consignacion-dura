<!DOCTYPE html>
<html lang="en">
<head>
<?php include("data/constructor.php"); 
if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] == '')){ header('Location: login.html'); }
else { $tipo_usuario = validar_usuario($_SESSION["logged_user"]);}
/// $tipo_usuario = 0 == admin
switch($tipo_usuario)
{
	case 1:
	header('Location: admin.html');
	break;
	case 2:
	header('Location: index.html');
	break;
	case 3:
	header('Location: vendor.html');
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

    <title>Cobranza</title>

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


<script>

	function estatus_almacenes(id_empresa){
		ocultar_divs();
		$("#modal_cargando").modal("show");	
		var almacen_id =  document.getElementById("select_almacen_estatus").value;	
		jQuery.ajax({ 
				type: "POST",
				url: "data/estatus_almacen.php",
				data: {id_empresa:id_empresa,almacen_id:almacen_id},
				success: function(resultados)
				
				{ 
				$("#div_estatus_principal").show();				
				$("#div_estatus_almacenes").html(resultados);
				$("#modal_cargando").modal("hide");	
								
			
				}
			});
	};
	function cargar_almacenes(id_empresa){
		
		jQuery.ajax({ 
				type: "POST",
				url: "data/list_select_almacen.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				
				{ 
								
				$("#select_almacen_estatus").html(resultados);
				$("#select_almacen_estatus").selectpicker('refresh');
			
				}
			});
	};
	function lista_ordenes_cxc(){
		ocultar_divs();
		$("#modal_cargando").modal("show");	
		var almacen_id =  document.getElementById("select_almacen_estatus").value;	
		var id_empresa = 0;
		jQuery.ajax({ 
				type: "POST",
				url: "data/lista_ordenes_cxc.php",
				data: {almacen_id:almacen_id},
				success: function(resultados)
				
				{ 			
				$("#div_ordenes_principal").show();	
				$("#div_ordenes_cxc").html(resultados);
				$("#modal_cargando").modal("hide");			
			
				}
			});
	};
	
	function lista_traspasos(){
		ocultar_divs();
		$("#modal_cargando").modal("show");	
		var almacen_id =  document.getElementById("select_almacen_estatus").value;	
	
		jQuery.ajax({ 
				type: "POST",
				url: "data/lista_pedidos_traspaso.php",
				data: {almacen_id:almacen_id},
				success: function(resultados)
				{ 			
				$("#div_pedidos_traspasos").show();	
				$("#div_pedidos_traspasos2").show();	
				$("#div_pedidos_traspasos3").hide();	
				$("#div_pedidos_traspasos2").html(resultados);
				$("#modal_cargando").modal("hide");			
			
				}
			});
	};
	 function detalle_pedido_tras (id,folio,total_pedido){
		jQuery('#pedido_detalle').modal('show', {backdrop: 'static'});
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/pedido_det_tras.php",
				data: {id:id,folio:folio,total_pedido:total_pedido},
				success: function(resultados)
				{
					jQuery('#pedido_detalle .modal-body').html(resultados);
				}
			});
		};
	 function prep_apli_traspaso(id_pedido,folio){
		//jQuery('#pedido_detalle').modal('show', {backdrop: 'static'});
		//mostrar div con lista de articulos para check list de traspaso
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/dat_ped_tras.php",
				data: {id_pedido:id_pedido,folio:folio},
				success: function(resultados)
				{
					$("#div_pedidos_traspasos3").html(resultados);
					$("#div_pedidos_traspasos3").show(400);	
					$("#div_pedidos_traspasos2").hide(400);	
				}
			});
		};
	function goback(){
		$("#div_pedidos_traspasos3").hide(400);	
		$("#div_pedidos_traspasos2").show(400);	
	}
	function gentrasmicro(id_pedido_trapaso)
	{
		$("#modal_cargando").modal("show");	
		
		jQuery.ajax({ 
				type: "POST",
				url: "data/generar_traspaso_micro.php",
				data: {id_pedido_trapaso:id_pedido_trapaso},
				success: function(resultados)
				{ 			
					// success
					$("#resultados_js").html(resultados);			
				}
			});
	}
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
	function act_fecha_periodo()
	{
		var fecha_ini =  document.getElementById("datepicker_ini").value;	
		var fecha_fin =  document.getElementById("datepicker_fin").value;	
		
		jQuery.ajax({ 
				type: "POST",
				url: "data/act_fech_per.php",
				data: {fecha_ini:fecha_ini,fecha_fin:fecha_fin},
				success: function(resultados)
				{ 			
					// success
					$("#resultados_js").html(resultados);			
				}
			});
	};
	function obtener_fecha(tipofecha)
	{
		var fecha = new Date();
			jQuery.ajax({ 
			type: "POST",
			url: "data/obtenerfecha.php",
			data: {tipofecha:tipofecha},
			success: function(resultados)
			{ 			
				// success
				$("#resultados_js").html(resultados);	
				
				/* if (resultados == 0)
				{
					console.log("No se encontro fecha");
				}
				else
				{
					fecha = resultados
					//console.log(resultados);
				} */
					
			}
			});
		return fecha;	
	};
	
		//var matches = document.querySelectorAll('div.opc_oc > li');
	/* const lis = document.querySelectorAll('ul.opcoc > li');
	function setActive(e) {
		if (e.target.tagName === "LI") {
			for (let i = 0; i < lis.length; i++) {
				lis[i].classList.remove('active');
			}
			e.target.classList.add('active');
		}
			
	}; */
	
	function ocultar_divs()
	{
		$("#div_estatus_principal").hide();	
		$("#div_ordenes_principal").hide();	
		$("#div_pedidos_traspasos").hide();	
	};
</script>


<!--Boton hacia arriba-->
<a class="ir-arriba"  href="#" title="Volver arriba">
        <span class="fa-stack">
            <i class="fa fa-circle fa-stack-2x"></i>
            <i class="fa fa-arrow-up fa-stack-1x fa-inverse"></i>
        </span>
    </a>


<?php echo $header_cxc; ?>
<section class="topics">
<div class="container" style=" min-height:350px;">
	<div class="row">
		
		<div class="col-lg-12"  id="div_estatus_principal">
			
			<div class="col-lg-12"  id="div_estatus_almacenes"></div>
			
		</div>
		<div class="col-lg-12"  id="div_ordenes_principal">
			<div class="col-lg-12 clearfix "  id="" >	
				<ul class="nav nav-pills nav-justified opcoc" >
					<li name="opciones_oc" id="li_oc_abiertas" role="presentation" border-radius ><a href="#" id="a_oc_abiertas" class="opc_oc">OC Abiertas</a></li>
					<li id="li_oc_todas"  name="opciones_oc" role="presentation" class="active"><a href="#"  id="a_oc_todas" class="opc_oc active">Todas</a></li>
					<li id="li_oc_cerradas"  name="opciones_oc" role="presentation"><a href="#"  id="a_oc_cerradas" class="opc_oc">OC Cerradas</a></li>
				</ul>
			</div>
			
			<div class="col-lg-12">&nbsp;</div>
			<!--<div class="col-lg-12 clearfix"  id="div_ord_nav">	
				<div class="col-lg-9"></div>
				<div class="col-lg-3"> 
					<div class="checkbox btn btn-default" >
						<label class="">
							<input type="checkbox" id="chk_ms" checked />  Mostrar en Captura
						</label>
					</div>
				</div>
			</div>
			<div class="col-lg-12">&nbsp;</div> -->
			<div class="col-lg-12"  id="div_ordenes_cxc"></div>
			
		</div>
		<div class="col-lg-12"  id="div_pedidos_traspasos">
			
			<div class="col-lg-12"  id="div_pedidos_traspasos2"></div>
			<div class="col-lg-12"  id="div_pedidos_traspasos3"></div>
			
		</div>
		<div class=""  id="resultados_js"></div>
		<div class=""  id="resultados_js_autorizacion"></div>
		<div class=""  id="resultados_mail"></div>
	</div>
			
</div>
</section>
<?php echo $modal_orden_index; ?>

<?php echo $footer_index; ?>


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
     <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
	 <script src="assets/js/moment.js"></script>
	 <script src="assets/js/locale/es.js"></script>
	 <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
	 <!-- Latest compiled and minified JavaScript -->
	<script src="assets/js/bootstrap-select.min.js"></script>
	
	<script>
		$(document).ready(function(){
			 var fecha_inicial = new Date();
			 var fecha_final = new Date();
			 $('#datepicker_ini').datetimepicker({
                
					format: 'DD/MM/YYYY',
					locale: 'es',
					useCurrent: true,
					defaultDate: fecha_inicial,					
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
				$('#datepicker_fin').datetimepicker({
                
					format: 'DD/MM/YYYY',
					locale: 'es',
					useCurrent: true,
					defaultDate: fecha_final,
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
					act_fecha_periodo();
					$('#datepicker_fin').data("DateTimePicker").minDate(e.date);
				});
				$("#datepicker_fin").on("dp.change", function (e) {	
				act_fecha_periodo();
					$('#datepicker_ini').data("DateTimePicker").maxDate(e.date);
				});
				
				obtener_fecha("ini");
				obtener_fecha("fin");
				ocultar_divs();
			
			cargar_almacenes(11);
			
			$("#select_almacen_estatus").change(function(){
				
				if ($('#div_estatus_principal').is(':visible')) 
				{
					estatus_almacenes(11);	
				}
				else if ($('#div_ordenes_principal').is(':visible')) 
				{
					lista_ordenes_cxc();
				}
				
				//var almacen_id = $(this).val();
							
            });	
			
			$(".opc_oc").on("click",function(){
				var id = $(this).attr("id");
				$("li[name=opciones_oc]").removeClass("active");
				if (id == "a_oc_abiertas")
				{
					$("#li_oc_abiertas").addClass("active");
					// mostrar solo claseocabierta
				}
				else if (id == "a_oc_todas")
				{
					$("#li_oc_todas").addClass("active");
					// mostrar todas las clasesoc
				}
				else if (id == "a_oc_cerradas")
				{
					$("#li_oc_cerradas").addClass("active");
					//mostrar solo claseoccerrada
				}
				
				
				//alert("ok"+id);
			});




		});
	</script>
</body>
</html>



