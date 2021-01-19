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
	//header('Location: admin.php');
	break;
	case 2:
	header('Location: index.php');
	break;
	case 3:
	header('Location: vendor.php');
	break;
	case 4:
	header('Location: consultor.php');
	break;
	case 5:
	header('Location: supervisor.php');
	break;
}
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////

include("displays/nef_style.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ToolCrib Valeo Admin</title>

    <!-- CSS -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" type="image/png" href="https://catalogo.allpart.mx/assets/images/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">


	
	

</head>
<body class="archive">


<script>

///////////////    FUNCIONES PARA EMPRESA NUEVA    ///////////////////////////////////////////////////////////
function nueva_empresa(){
		 $("#div_new_empresa").show();
		 $("#select_empresa").hide();
		 $("#btn_new_empresa").hide();
				
		};
		function abrir(){
		 $("#modal_modificar_empresa").modal("show");
			//alert("okok");	
		};
function modifica_empresa(){
			
			var id_empresa = document.getElementById("txt_id_empresa").value;
			var empresa = document.getElementById("txt_empresa").value;
			var rfc = document.getElementById("txt_rfc").value;
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_dep_pue_emp.php",
				data: {id_empresa:id_empresa,empresa:empresa,rfc:rfc},
				success: function(response)
				{
				$("#resultados_js").html(response);
						
				}
			});
		};
function guardar_empresa_new(){
			var	iduser = "<?php echo $_SESSION["logged_user"]; ?>";
			var empresa = document.getElementById("txt_empresa_new").value;
			var rfc = document.getElementById("txt_empresa_rfc_new").value;
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_dep_pue_emp.php",
				data: {iduser:iduser,empresa:empresa,rfc:rfc},
				success: function(response)
				{
				$("#resultados_js").html(response);
						
				}
			});
		};
function cancel_empresa_new(){
		 $("#div_new_empresa").hide();
		 $("#select_empresa").show();
		 $("#btn_new_empresa").show();
		 $("#txt_empresa_new").val('');
		 $("#txt_empresa_rfc_new").val('');
			
		};
/////////////FUNCIONES PARA DEPARTAMENTO NUEVO//////////////////////////////////////////////////////////////////////////////		
function nuevo_departamento(){
		 $("#div_new_departamento").show();
		 $("#select_departamento").hide();
		 $("#btn_new_departamento").hide();
				
		};
function guardar_departamento_new(){
			var	iduser = "<?php echo $_SESSION["logged_user"]; ?>";
			var departamento = document.getElementById("txt_departamento_new").value;
			var id_empresa = document.getElementById("select_empresa").value;
			
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_dep_pue_emp.php",
				data: {iduser:iduser,departamento:departamento,id_empresa:id_empresa},
				success: function(response)
				{
				$("#resultados_js").html(response);
				$("#btn_new_departamento").show();
						
				}
			});
		};
function cancel_departamento_new(){
		 $("#div_new_departamento").hide();
		 $("#select_departamento").show();
		 $("#btn_new_departamento").show();
		 $("#txt_departamento_new").val('');
		
			
		};
////////////FUNCIONES PARA PUESTO NUEVO//////////////////////////////////////////////////////////////////////////////		
function nuevo_puesto(){
		 $("#div_new_puesto").show();
		 $("#select_puesto").hide();
		 $("#btn_new_puesto").hide();
				
		};
function guardar_puesto_new(){
			var	iduser = "<?php echo $_SESSION["logged_user"]; ?>";
			var puesto = document.getElementById("txt_puesto_new").value;
			
			
			jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_dep_pue_emp.php",
				data: {iduser:iduser,puesto:puesto},
				success: function(response)
				{
				$("#resultados_js").html(response);
				$("#btn_new_puesto").show();
						
				}
			});
		};
function cancel_puesto_new(){
		 $("#div_new_puesto").hide();
		 $("#select_puesto").show();
		 $("#btn_new_puesto").show();
		 $("#txt_puesto_new").val('');
		
			
		};	

//////////////MUESTRA LA LISTA DE LOS ARTICULOS//////////////////////////////////////////////////////////////		
   function mostrar_articulos(id_empre){
   //alert("ok");
   $("#div_tabla_articulos").html("<center><img src='assets/images/cargando.gif' /></center>");
   var id_empresa = document.getElementById("txt_art_id_empresa").value;
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_articulos.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				
				{ 
								
				$("#div_tabla_articulos").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
//////////////MUESTRA LA LISTA DE LOS CENTROS DE COSTOS //////////////////////////////////////////////////////		
   function mostrar_cc(){
   //alert("ok");
   $("#div_tabla_centro_costos").html("<center><img src='assets/images/cargando.gif' /></center>");
   var id_empresa = document.getElementById("txt_cc_id_empresa").value;
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_cc.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				
				{ 
								
				$("#div_tabla_centro_costos").html(resultados);		
			
				}
			});
		return false;	
   
   
   };	
  
//////////////MUESTRA LA LISTA DE LOS USUARIOS//////////////////////////////////////////////////////////////		
   function mostrar_user(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
  var tipo = document.getElementById("select_tipo_mostrar").value;
   //alert("ok");
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_usuarios.php",
				data: {id_user:id_user,tipo:tipo},
				success: function(resultados)
				
				{ 
								
				$("#div_tabla_usuarios").html(resultados);		
			
				}
			});
		return false;	
   
   
   };	
   
 /////////MUESTRA LA LISTA DE LAS EMPRESAS en Select en apartado de articulos /////////////////////////////////////// 
   function lista_art_empresas(){
  var id_referencia = <?php echo $_SESSION["logged_user"]; ?>;
   //alert("ok");
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_empresas.php",
				data: {id_referencia:id_referencia},
				success: function(resultados)
				
				{ 
								
				$("#select_list_empresas").html(resultados);		
			
				}
			});
			
   
   
   };  
 /////////MUESTRA LA LISTA DE LAS EMPRESAS SECCION DE CENTROS DE COSTOS /////////////////////////////////////// 
   function lista_art_empresas_cc(){
  var id_referencia_cc = <?php echo $_SESSION["logged_user"]; ?>;
   //alert("ok");
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_empresas.php",
				data: {id_referencia_cc:id_referencia_cc},
				success: function(resultados)
				
				{ 
								
				$("#select_list_empresas_cc").html(resultados);		
						
			
				}
			});
			
   
   
   };   
 /////////MUESTRA LA LISTA DE LAS EMPRESAS PARA NUEVO CENTRO DE COSTOS /////////////////////////////////////// 
   function lista_art_empresas_cc_new(){
  var id_referencia_cc_new = <?php echo $_SESSION["logged_user"]; ?>;
   //alert("ok");
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_empresas.php",
				data: {id_referencia_cc_new:id_referencia_cc_new},
				success: function(resultados)
				
				{ 
								
					
				$("#select_cc_empresa").html(resultados);		
			
				}
			});
			
   
   
   };   
 /////////MUESTRA LA LISTA DE LAS EMPRESAS /////////////////////////////////////////// 
   function mostrar_empresas(){
  var id_user = <?php echo $_SESSION["logged_user"]; ?>;
   //alert("ok");
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_empresas.php",
				data: {id_user:id_user},
				success: function(resultados)
				
				{ 
								
				$("#div_tabla_empresas").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
/////////MUESTRA LA LISTA DE LOS DEPARTAMENTOS Y PUESTOS /////////////////////////////////////////// 
   function mostrar_depues(){
 
  var depapues = 1;
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_empresas.php",
				data: {depapues:depapues},
				success: function(resultados)
				
				{ 
								
				$("#div_departamentos_puestos").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
/////////MUESTRA LA LISTA DE LOS PAISES /////////////////////////////////////////// 
   function lista_paises(){
 
  var pais = 1;
   jQuery.ajax({ 
				type: "POST",
				url: "data/pais_estado_region.php",
				data: {pais:pais},
				success: function(resultados)
				
				{ 
								
				$("#div_sucursal_pais").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
/////////MUESTRA LA LISTA DE LOS ESTADOS	 /////////////////////////////////////////// 
   function lista_estados(id_pais){
	if (!id_pais){
		//id_pais =  document.getElementById("select_sucursal_pais").value;
		id_pais =  "147";
	}
	
	//alert(id_pais);
   jQuery.ajax({ 
				type: "POST",
				url: "data/pais_estado_region.php",
				data: {id_pais:id_pais},
				success: function(resultados)
				
				{ 
								
				$("#div_sucursal_estado").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
/////////MUESTRA LA LISTA DE LOS Municipios	con municipio seleccionado /////////////////////////////////////////// 
   function lista_ciudad_select(id_estado,id_ciudad){
 
   jQuery.ajax({ 
				type: "POST",
				url: "data/pais_estado_region.php",
				data: {id_estado:id_estado,id_ciudad:id_ciudad},
				success: function(resultados)
				
				{ 
								
				$("#div_sucursal_ciudad").html(resultados);		
			
				}
			});
		return false;	
    };
/////////MUESTRA LA LISTA DE LOS Municipios	 /////////////////////////////////////////// 
   function lista_ciudades(id_estado){
 
   jQuery.ajax({ 
				type: "POST",
				url: "data/pais_estado_region.php",
				data: {id_estado:id_estado},
				success: function(resultados)
				
				{ 
								
				$("#div_sucursal_ciudad").html(resultados);		
			
				}
			});
		return false;	
    };
 /////////MUESTRA LA LISTA DE LAS LISTA DE REQUISITORES /////////////////////////////////////////// 
   function list_requisitores(){
  var requisitores = '1';
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_req_ven_rel.php",
				data: {requisitores:requisitores},
				success: function(resultados)
				
				{ 
								
				$("#div_requisitores").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
 /////////MUESTRA LA LISTA DE LAS LISTA DE VENDEDORES /////////////////////////////////////////// 
   function list_vendedores(){
  var vendedores = '1';
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_req_ven_rel.php",
				data: {vendedores:vendedores},
				success: function(resultados)
				
				{ 
								
				$("#div_vendedores").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
 /////////MUESTRA LA LISTA DE LAS LISTA DE RELACIONES /////////////////////////////////////////// 
   function list_relaciones(){
  var relaciones = '1';
   jQuery.ajax({ 
				type: "POST",
				url: "data/mostrar_req_ven_rel.php",
				data: {relaciones:relaciones},
				success: function(resultados)
				
				{ 
								
				$("#div_relaciones").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
   
   /////////MUESTRA LA LISTA DE LAS CATEGORIAS  /////////////////////////////////////////// 
   function lista_categorias(){
var id_empresa = document.getElementById("txt_art_id_empresa").value;
   jQuery.ajax({ 
				type: "POST",
				url: "data/categorias.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				
				{ 
								
				$("#div_categorias").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
   
   /////////MUESTRA LA LISTA DE REGISTROS DE LAS CATEGORIAS DEL ARTICULO  /////////////////////////////////////////// 
   function lista_reg_categorias(){
var id_empresa = document.getElementById("txt_art_id_empresa").value;
var id_articulo = document.getElementById("txt_id_articulo").value;
   jQuery.ajax({ 
				type: "POST",
				url: "data/categorias.php",
				data: {id_empresa:id_empresa,id_articulo:id_articulo},
				success: function(resultados)
				
				{ 
								
				$("#div_reg_categorias").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
   
 /////////MUESTRA LA LISTA DE LAS SUCURSALES ///////////////////////////////////////////  
   function mostrar_sucursales(){
  var id_empresa = document.getElementById("txt_id_empresa").value;
   //alert("ok");
   jQuery.ajax({ 
				type: "POST",
				url: "data/pais_estado_region.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				
				{ 
								
				$("#div_tabla_sucursales").html(resultados);		
			
				}
			});
		return false;	
   
   
   };  /////////MUESTRA LA LISTA DE LAS SUCURSALES en el modal de nuevo usuario ///////////////////////////////////////////  
/*    function lista_sucursales(id_empresa_usuario){

   jQuery.ajax({ 
				type: "POST",
				url: "data/pais_estado_region.php",
				data: {id_empresa_usuario:id_empresa_usuario},
				success: function(resultados)
				
				{ 
								
				$("#select_sucursal").html(resultados);		
			
				}
			});
		return false;	
   
   
   }; */ 

   
   /* esta es la lista de las sucursales multiple en los usuarios*/
   function lista_sucursales(id_empresa_usuario_multi){
	  var id_usuario = document.getElementById("txt_id_usuario").value;
	  if (id_usuario == ""){
   jQuery.ajax({ 
				type: "POST",
				url: "data/pais_estado_region.php",
				data: {id_empresa_usuario_multi:id_empresa_usuario_multi},
				success: function(resultados)
				
				{ 
								
				$("#div_list_sucursales").html(resultados);		
			
				}
			});
		
   } else {
	   jQuery.ajax({ 
				type: "POST",
				url: "data/pais_estado_region.php",
				data: {id_empresa_usuario_multi:id_empresa_usuario_multi,id_usuario:id_usuario},
				success: function(resultados)
				
				{ 
								
				$("#div_list_sucursales").html(resultados);		
			
				}
			});
   }
   return false;	
  
   }; 
   /* esta es la lista de los subordinados en los usuarios tipo supervisor */
   function lista_subor_recolect(){
	  var id_usuario = document.getElementById("txt_id_usuario").value;
	  var id_empresa_seleccionada = document.getElementById("select_empresa").value;
	  var tipo = document.getElementById("select_tipo").value;
	  
	  if (tipo == 5){
	  jQuery.ajax({ 
				type: "POST",
				url: "data/lista_subordinados.php",
				data: {id_usuario:id_usuario,id_empresa_seleccionada:id_empresa_seleccionada},
				success: function(resultados)
				
				{ 
								
				$("#div_lista_subordinados").html(resultados);		
			
				}
			});
			 jQuery.ajax({ 
				type: "POST",
				url: "data/lista_recolectores.php",
				data: {id_usuario:id_usuario,id_empresa_seleccionada:id_empresa_seleccionada},
				success: function(resultados)
				
				{ 
								
				$("#div_lista_recolectores").html(resultados);		
			
				}
			});
	  jQuery.ajax({ 
				type: "POST",
				url: "data/lista_user_cc.php",
				data: {id_usuario:id_usuario,id_empresa_seleccionada:id_empresa_seleccionada},
				success: function(resultados)
				
				{ 
								
				$("#div_lista_cc").html(resultados);		
			
				}
			});
	  }	
	  else if (tipo == 2){
	  jQuery.ajax({ 
				type: "POST",
				url: "data/lista_recolectores.php",
				data: {id_usuario:id_usuario,id_empresa_seleccionada:id_empresa_seleccionada},
				success: function(resultados)
				
				{ 
								
				$("#div_lista_recolectores").html(resultados);		
			
				}
			});
	  jQuery.ajax({ 
				type: "POST",
				url: "data/lista_user_cc.php",
				data: {id_usuario:id_usuario,id_empresa_seleccionada:id_empresa_seleccionada},
				success: function(resultados)
				
				{ 
								
				$("#div_lista_cc").html(resultados);		
			
				}
			});
	  }

   return false;	
  
   };
 /////////lista de departamentos por empresa seleccionada ///////////////////////////////////////////  
   function lista_departamentos(id_empresa){
 jQuery.ajax({ 
				type: "POST",
				url: "data/lista_dep.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				{ 
				// jQuery("#modal_nueva_sucursal .modal-header").html("Folio Pedido: "+folio) ;				
						
				$("#select_departamento").html(resultados);
				}
			});
		return false;	
   
   
   };
 /////////MUESTRA los datos de la sucursal seleccionada ///////////////////////////////////////////  
   function datos_sucursal(id_sucursal){
 jQuery.ajax({ 
				type: "POST",
				url: "data/pais_estado_region.php",
				data: {id_sucursal:id_sucursal},
				success: function(resultados)
				{ 
				// jQuery("#modal_nueva_sucursal .modal-header").html("Folio Pedido: "+folio) ;				
				$("#resultados_js").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
   
  /////////GUARDA USUARIO NUEVO ///////////////////////////////////////////  
   function guardar_new_user(){
      var id_user = <?php echo $_SESSION["logged_user"]; ?>;
   var id_usuario = document.getElementById("txt_id_usuario").value;
   var tipo = document.getElementById("select_tipo").value;
   var nombre = document.getElementById("nombre").value;
   var username	= document.getElementById("username").value;
   var apellido = document.getElementById("apellido").value;
   var contrasena = document.getElementById("contrasena").value;
   var empresa_id = document.getElementById("select_empresa").value;
  // var sucursal_id = document.getElementById("select_sucursal").value;
   var correo = document.getElementById("correo").value;
   var telefono = document.getElementById("telefono").value;
   var departamento_id = document.getElementById("select_departamento").value;
   var puesto_id = document.getElementById("select_puesto").value;
   var turno = document.getElementById("select_turno").value;
   var permiso_autorizar =  document.getElementById("chk_autspend").checked;
	var sucursal = document.getElementsByClassName("list_sucursales");
		var valor = "";
		var list_sucursales_permitidas = new Array();
		if (sucursal.length > 0){
		for (var i = 0; i < sucursal.length; i++) {
		 	valor = sucursal[i];
			var valor_id = valor.id.split("_");
			//var valor_id_sucursal = valor_id.split("_");
			//console.log(valor_id[1]);
			//console.log(valor.checked);
			var var_id = valor_id[1];
			var var_check = valor.checked;
			list_sucursales_permitidas.push(var_id+"_"+var_check);
		}
		} else { list_sucursales_permitidas.push("0_0"); }
		
	var subordinados = document.getElementsByClassName("list_subordinados");
		
		var item_list = "";
		var list_subordinados_seleccionados = new Array();
		if (subordinados.length > 0){
		//console.log(subordinados.length+"_ length");
		for (var ii = 0; ii < subordinados.length; ii++) {
		 	item_list = subordinados[ii];
			var array_item_list = item_list.id.split("_");
			var id_subordinado = array_item_list[1];
			var estatus_check = item_list.checked;
			list_subordinados_seleccionados.push(id_subordinado+"_"+estatus_check);
			
		}
		} else { list_subordinados_seleccionados.push("0"); }
		
	var recolectores = document.getElementsByClassName("list_recolectores");
		var item_list_r = "";
		var list_recolectores_seleccionados = new Array();
		if (recolectores.length > 0){
		for (var ii = 0; ii < recolectores.length; ii++) {
		 	item_list_r = recolectores[ii];
			var array_item_list_r = item_list_r.id.split("_");
			var id_recolector = array_item_list_r[1];
			var estatus_check_r = item_list_r.checked;
			list_recolectores_seleccionados.push(id_recolector+"_"+estatus_check_r);
		}
		} else { list_recolectores_seleccionados.push("0"); }
		
		var centros_costos = document.getElementsByClassName("list_user_cc");
		var item_list_cc = "";
		var list_cc_seleccionados = new Array();
		if (centros_costos.length > 0){
		for (var ii = 0; ii < centros_costos.length; ii++) {
		 	item_list_cc = centros_costos[ii];
			var array_item_list_cc = item_list_cc.id.split("_");
			var id_cc = array_item_list_cc[1];
			var estatus_check_cc = item_list_cc.checked;
			list_cc_seleccionados.push(id_cc+"_"+estatus_check_cc);
		}
		} else { list_cc_seleccionados.push("0"); }
		
		
   if (id_usuario == ""){
   jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_dep_pue_emp.php",
				data: {tipo:tipo,nombre:nombre,username:username,apellido:apellido,contrasena:contrasena,empresa_id:empresa_id,correo:correo,telefono:telefono,departamento_id:departamento_id,puesto_id:puesto_id,turno:turno,list_sucursales_permitidas:list_sucursales_permitidas,list_subordinados_seleccionados:list_subordinados_seleccionados,list_recolectores_seleccionados:list_recolectores_seleccionados,list_cc_seleccionados:list_cc_seleccionados,permiso_autorizar:permiso_autorizar},
				success: function(resultados)
				
				{
								
				$("#resultados_js").html(resultados);		
						
					
				
				}
			});
		return false;	
   }
   else {
    jQuery.ajax({ //
				type: "POST",
				url: "data/agregar_dep_pue_emp.php",
				data: {tipo:tipo,nombre:nombre,username:username,apellido:apellido,contrasena:contrasena,empresa_id:empresa_id,correo:correo,telefono:telefono,departamento_id:departamento_id,puesto_id:puesto_id,turno:turno,id_usuario:id_usuario,list_sucursales_permitidas:list_sucursales_permitidas,list_subordinados_seleccionados:list_subordinados_seleccionados,list_recolectores_seleccionados:list_recolectores_seleccionados,list_cc_seleccionados:list_cc_seleccionados,permiso_autorizar:permiso_autorizar},
				success: function(resultados)
				
				{
								
				$("#resultados_js").html(resultados);		
						
					
				
				}
			});
		return false;
   }
   
   };
   ///// MUESTRA LOS DATOS DEL USUARIO SELECCIONADO ///////////
   function datos_usuario(id_usuario){
   
   jQuery.ajax({ //
									type: "POST",
									url: "data/datos_usuario.php",
									data: {id_usuario:id_usuario},
									success: function(resultados)
									
									{
													
									$("#resultados_js").html(resultados);		
									//alert('ok success');
									}
								});
							   
							   $("#modal_nuevo_usuario").modal("show");
							   
							   
   }
   ///// MUESTRA LOS DATOS DEL CENTRO DE COSTOS SELECCIONADO ///////////
   function datos_cc(id_cc){
   
   jQuery.ajax({ //
									type: "POST",
									url: "data/datos_cc.php",
									data: {id_cc:id_cc},
									success: function(resultados)
									
									{
													
									$("#resultados_js").html(resultados);		
									//alert('ok success');
									}
								});
							   
							   $("#modal_nuevo_cc").modal("show");
							   
							   
   }
 /////////GUARDA ARTICULO ///////////////////////////////////////////  
   function guardar_articulo(){

	var empresa_id = document.getElementById("select_art_empresa").value;
	var clave_empresa	= document.getElementById("txt_clave_empresa").value;
	var clave_microsip = document.getElementById("txt_clave_microsip").value;
	var articulo = document.getElementById("txt_nombre_articulo").value;
	var descripcion = document.getElementById("txt_descripcion").value;
	var precio = document.getElementById("txt_precio").value;
    var imagen = document.getElementById("txt_imagen").value;
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
				data: {empresa_id:empresa_id,clave_empresa:clave_empresa,clave_microsip:clave_microsip,descripcion:descripcion,precio:precio,imagen:imagen,articulo:articulo,min:min,max:max,reorden:reorden,existencia:existencia,id_articulo_microsip:id_articulo_microsip},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				}
			});
			
   }else {
	jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_articulo.php",
				data: {empresa_id:empresa_id,clave_empresa:clave_empresa,clave_microsip:clave_microsip,descripcion:descripcion,precio:precio,imagen:imagen,articulo:articulo,id_articulo:id_articulo,min:min,max:max,reorden:reorden,existencia:existencia,id_articulo_microsip:id_articulo_microsip},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				}
			});   
   }	
			
			
		return false;	
   
   
   };
   /////////GUARDA CATEGORIA ///////////////////////////////////////////  
   function guardar_categoria(){

	var empresa_id = document.getElementById("select_cat_empresa").value;
	var categoria	= document.getElementById("txt_categoria").value;
	var id_categoria = document.getElementById("txt_id_edit_categoria").value;
	
	if (id_categoria == ''){
   jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_categoria.php",
				data: {empresa_id:empresa_id,categoria:categoria},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				}
			});
			
   }else {
	jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_categoria.php",
				data: {empresa_id:empresa_id,categoria:categoria,id_categoria:id_categoria},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				}
			});   
   }	
			
			
		return false;	
   
   
   };
   /////////GUARDA CENTRO DE COSTOS ///////////////////////////////////////////  
   function guardar_new_cc(){

	var empresa_id = document.getElementById("select_cc_empresa").value;
	var centro_costos = document.getElementById("txt_cc_nombre").value;
	var id_cc = document.getElementById("txt_cc_id").value;
	
	if (id_cc == ''){
   jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_cc.php",
				data: {empresa_id:empresa_id,centro_costos:centro_costos},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				}
			});
			
   }else {
	jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_cc.php",
				data: {empresa_id:empresa_id,centro_costos:centro_costos,id_cc:id_cc},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				}
			});   
   }	
			
			
		return false;	
   
   
   };
 /////////GUARDA SUCURSAL ///////////////////////////////////////////  
   function guardar_sucursal(){

	var sucursal = document.getElementById("txt_sucursal").value;
	var direccion	= document.getElementById("txtarea_direccion").value;
	var pais = document.getElementById("select_sucursal_pais").value;
	var estado = document.getElementById("select_sucursal_estado").value;
	var ciudad = document.getElementById("select_sucursal_ciudad").value;
	var cp = document.getElementById("txt_codigo_postal").value;
    var id_empresa = document.getElementById("txt_id_empresa").value;
    var id_sucursal = document.getElementById("txt_id_sucursal").value;
    var chk_dir_fac = document.getElementById("chk_dir_fac").checked;
    var chk_dir_suc = document.getElementById("chk_dir_suc").checked;
	
	
	if (id_sucursal == ''){
   jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_sucursal.php",
				data: {sucursal:sucursal,direccion:direccion,pais:pais,estado:estado,ciudad:ciudad,cp:cp,id_empresa:id_empresa,chk_dir_fac:chk_dir_fac,chk_dir_suc:chk_dir_suc},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				$("#txt_sucursal").val("");
				$("#txtarea_direccion").html("");
				$("#txt_codigo_postal").val("");
				$("#div_sucursal_ciudad").html('<select class="form-control" name="" id="select_sucursal_ciudad" disabled></select>');
				
				}
			});
			
   }else {
	jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_sucursal.php",
				data: {sucursal:sucursal,direccion:direccion,pais:pais,estado:estado,ciudad:ciudad,cp:cp,id_empresa:id_empresa,id_sucursal:id_sucursal,chk_dir_fac:chk_dir_fac,chk_dir_suc:chk_dir_suc},
				success: function(resultados)
				{
				$("#resultados_js").html(resultados);
				$("#txt_sucursal").val("");
				$("#txtarea_direccion").html("");
				$("#txt_codigo_postal").val("");
								
				$("#div_sucursal_ciudad").html('<select class="form-control" name="" id="select_sucursal_ciudad" disabled></select>');
				
				}
			});   
   }	
			
			
		return false;	
   
   
   };
 /////////GUARDA RELACION ///////////////////////////////////////////  
   function guardar_relacion(){
     
   var id_requisitor = document.getElementById("txt_id_requisitor").value;
   var id_vendedor = document.getElementById("txt_id_vendedor").value;


   
   jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_relacion.php",
				data: {id_requisitor:id_requisitor,id_vendedor:id_vendedor},
				success: function(resultados)
				
				{
								
				$("#resultados_js").html(resultados);		
						
					
				
				}
			});
		return false;	
   
   
   };
 ///////// agrega o elimina categorias a los articulos ///////////////////////////////////////////  
   function add_del_categoria(){
     
   var id_articulo = document.getElementById("txt_id_articulo").value;
   var id_reg_categoria = document.getElementById("txt_id_reg_categoria").value;
  // var id_empresa = document.getElementById("txt_art_id_empresa").value;
   var id_categoria = document.getElementById("txt_id_categoria").value;


   
   jQuery.ajax({ //
				type: "POST",
				url: "data/add_del_categoria.php",
				data: {id_articulo:id_articulo,id_reg_categoria:id_reg_categoria,id_categoria:id_categoria},
				success: function(resultados)
				
				{
								
				$("#resultados_js").html(resultados);		
						
					
				
				}
			});
		return false;	
   
   
   };
 /////////ELIMINA RELACION ///////////////////////////////////////////  
   function eliminar_relacion(){
     
   var id_relacion = document.getElementById("txt_id_relacion").value;
if (id_relacion)   
   jQuery.ajax({ //
				type: "POST",
				url: "data/guardar_relacion.php",
				data: {id_relacion:id_relacion},
				success: function(resultados)
				
				{
								
				$("#resultados_js").html(resultados);		
						
					
				
				}
			});
		return false;	
   
   
   }; 
   
   /////////  BUSCAR ARTICULOS MICROSIP ///////////////////////////////////////////  
   function BuscarArticuloMicrosip(clave_nombre){
  
   jQuery.ajax({ //
				type: "POST",
				url: "data/busca_articulo_microsip.php",
				data: {clave_nombre:clave_nombre},
				success: function(resultados)
				
				{
								
				$("#div_tabla_art_microsip").html(resultados);		
						
					
				
				}
			});
		return false;	
   
   
   };
  function sin_accion(){
	  return false;
  }


  
</script>

<!-- Header -->

<header class="hero overlay" style="background-image: url('assets/images/poly-blue.png');">
    <nav class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>
            <a href="admin.php" class="brand">
                <img src="assets/images/logo-allpart.png" alt="Knowledge">
            </a>
        </div>
		
        <div class="navbar-collapse collapse" id="navbar-collapse">
            <?php echo $menu_bar; ?>
        </div>
    </div>
</nav>
    
</header>


<script type="text/javascript">


</script>
<section class="topics">
    <div class="container">
    	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  
		  	<!-- SECCION -> USUARIOS -->
		  <div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="usuarios-esconder">
		      <h4 class="panel-title">
		      	<a role="button" data-toggle="collapse" data-parent="#accordion" href="#usuarios" aria-expanded="false" aria-controls="usuarios" onclick="mostrar_user();">
		        <div style="width:100%; height:20px;">
				
		          Usuarios<span class="caret pull-right"></span>
				  </div>
		        </a>
		      </h4>
		    </div>
			
		    <div id="usuarios" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="usuarios-esconder">
		      <div class="panel-body">
		        <div class="col-lg-12" >
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-5">
					<a href="#modal_nuevo_usuario" class="btn btn-primary agregar_nuevo_usuario" data-toggle="modal">Agregar usuario</a>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-7">
					<select class="form-control col-lg-5" name="select_tipo_mostrar" id="select_tipo_mostrar">
						<option value="0">Todos</option>
						<option value="1">Administradores</option>
						<option value="2">Compradores</option>
						<option value="3">Vendedores</option>
						<option value="4">Recolectores</option>
						<option value="5">Supervisores </option>
						<option value="11">Cuentas Por Cobrar </option>
					</select>
					</div>
					<div class="col-lg-6 col-md-6" name="relleno"> </div>
            		<!--modal nuevo usuario-->
            		<div class="modal fade" id="modal_nuevo_usuario" tabindex="-1" role="dialog">
                		<div class="modal-dialog" role="document">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Nuevo usuario
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<form action="" class="form-horizontal">
										<input type="hidden" value="" id="txt_id_usuario" /><!-- TXT CON EL ID DE USUARIO SELECCIONADO -->
					                   	<div class="form-group">
						                   		<label for="tipo_usuario" class="col-sm-4 control-label">Tipo de Usuario:</label>
						                   	<div class="col-sm-8">
						                   		<select class="form-control" name="" id="select_tipo">
						                   			<option value="5">Supervisor </option>
						                   			<option value="4">Almacenista</option>
						                   			<option value="3">Vendedor</option>
						                   			<option value="2">Comprador(Requisitor)</option>
						                   			<option value="1">Administrador</option>						
													<option value="11">Cuentas Por Cobrar </option>
													<option value="17">Admin traspasos</option>
						                   		</select>
												
											<a href="#" id="btn_subordinados" > Asignar Usuarios Supervisados</a><br/> 
											<div  id="div_lista_subordinados" class="list-group" style="max-height:150px; overflow-y: scroll; "></div>
												
											<a href="#" id="btn_add_recolector" >Selecciona los recolectores </a> 
											<div  id="div_lista_recolectores" class="list-group" style="max-height:150px; overflow-y: scroll; "></div>
											<br />
											<a href="#" id="btn_add_cc" >Selecciona los Centros de costos </a> 
											<div  id="div_lista_cc" class="list-group" style="max-height:150px; overflow-y: scroll; "></div>
											<label id="lblchk_autspend">
											<input type="checkbox" id="chk_autspend"> Autorizar limites de spend
											</label>
						                   	</div>
						                </div>
						                <div class="form-group">
						                   	<label for="username" class="col-sm-4 control-label">Nombre de Usuario:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="username" placeholder="Nombre de Usuario">
						                   	</div>
					                   	</div>
						                <div class="form-group">
						                   	<label for="nombre" class="col-sm-4 control-label">Nombre:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="nombre" placeholder="Nombre">
						                   	</div>
					                   	</div>
					                   	<div class="form-group">
						                   	<label for="apellido" class="col-sm-4 control-label">Apellido:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="apellido" placeholder="Apellido">
						                   	</div>
						                </div>
						                <div class="form-group">
						                   	<label for="contraseña" class="col-sm-4 control-label">Contraseña:</label>
						                   	<div class="col-sm-8">
						                   		<input type="password" class="form-control" id="contrasena" placeholder="Contraseña">
						                   	</div>
					                   	</div>
						                <div class="form-group">
						                   	<label for="empresa" class="col-sm-4 control-label">Empresa:</label>
						                   	<div class="col-sm-8">
										<!-- input almacena el id de empresa para la seleccion de la sucursal en el registro de usuarios -->
										<input id="txt_usuarios_id_empresa" type="hidden" value=""; />
											<select class="form-control" name="" id="select_empresa">
						                   			
													<?php 	
													$lista_empresas = lista_empresas();
															foreach ($lista_empresas as $id => $empresa){
																echo '<option value="'.$id.'" >'.$empresa.'</option>';
															};
													?>
													
						                   		</select>
						                   		<!-- <input type="text" class="form-control" id="empresa" placeholder="Empresa"> -->
													<a href="#" id="btn_new_empresa" onclick="nueva_empresa();"> Nueva Empresa</a>
													<div class="input-group" id="div_new_empresa">
														<input type="text" class="form-control" placeholder="Empresa Nueva" id="txt_empresa_new">
														<input type="text" class="form-control" placeholder="RFC" id="txt_empresa_rfc_new">
														<span class="input-group-btn">
															<button class="btn btn-info" type="button" onclick="guardar_empresa_new();">Guardar</button>
															<button class="btn btn-danger" type="button" onclick="cancel_empresa_new();">Cancelar</button>
														</span>
													</div>
						                   	</div>
					                   	</div>
					                   	<div class="form-group"> 
						                   	<label for="correo" class="col-sm-4 control-label">Sucursal:</label>
						                   	<div class="col-sm-8">
						                  <!--  		<select class="form-control" name="" id="select_sucursal">
						                   		</select>
								 ////////////////////////////////////////////////////////////////////////// -->
								<div id="result_list" > </div>
								<div id="div_list_sucursales" class="list-group" style="max-height:150px; overflow-y: scroll; "></div>
						                   	</div>
					                   	</div>
										<div class="form-group">
						                   	<label for="correo" class="col-sm-4 control-label">Correo:</label>
						                   	<div class="col-sm-8">
						                   		<input type="email" class="form-control" id="correo" placeholder="Correo">
						                   	</div>
					                   	</div>
					                   	<div class="form-group">
						                   	<label for="telefono" class="col-sm-4 control-label">Telefono:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="telefono" placeholder="Telefono">
						                   	</div>
					                    </div>
					                    <div class="form-group">
						                   	<label for="departamento" class="col-sm-4 control-label">Depertamento:</label>
						                   	<div class="col-sm-8">
												<select class="form-control" name="" id="select_departamento">
						                   			
													<?php 	
													/* $lista_departamentos = lista_departamentos();
															foreach ($lista_departamentos as $id => $departamento){
																echo '<option value="'.$id.'" >'.$departamento.'</option>';
															}; */
													?>
						                   		</select>
													<a href="#" id="btn_new_departamento" onclick="nuevo_departamento();" > Nuevo Departamento</a>
													<div class="input-group" id="div_new_departamento">
														<input type="text" class="form-control" placeholder="Departamento Nuevo" id="txt_departamento_new">
														<span class="input-group-btn">
															<button class="btn btn-info" type="button" onclick="guardar_departamento_new();">Guardar</button>
															<button class="btn btn-danger" type="button" onclick="cancel_departamento_new();">Cancelar</button>
														</span>
													</div>
						                   	</div>
					                    </div>
					                    <div class="form-group">
						                   	<label for="puesto" class="col-sm-4 control-label">Puesto:</label>
						                   	<div class="col-sm-8">
						                   		<select class="form-control" name="" id="select_puesto">
						                   			<?php 	
													$lista_puestos = lista_puestos();
															foreach ($lista_puestos as $id => $puesto){
																echo '<option value="'.$id.'" >'.$puesto.'</option>';
															};
													?>
						                   		</select>
													<a href="#" id="btn_new_puesto" onclick="nuevo_puesto();" > Nuevo Puesto</a>
													<div class="input-group" id="div_new_puesto">
														<input type="text" class="form-control" placeholder="Puesto Nuevo" id="txt_puesto_new">
														<span class="input-group-btn">
															<button class="btn btn-info" type="button" onclick="guardar_puesto_new();">Guardar</button>
															<button class="btn btn-danger" type="button" onclick="cancel_puesto_new();">Cancelar</button>
														</span>
													</div>
						                   
						                   	</div>
					                    </div>
					                    <div class="form-group">
						                   	<label for="Turno" class="col-sm-4 control-label">Turno:</label>
						                   	<div class="col-sm-8">
						                   		<select class="form-control" name="" id="select_turno">
						                   			<option value="1">Primero</option>
						                   			<option value="2">Segundo</option>
						                   			<option value="3">Tercero</option>
						                   		</select>
						                   	</div>
					                    </div>
				                    </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="guardar_new_user();">Guardar</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal nuevo usuario-->
				
				</div>
				<br><br>
				<div class="col-lg-12" id="div_tabla_usuarios" style="font-size:12px; overflow-y: scroll; max-height: 350px; font-size:12px; padding-top:10px;">
					<!-- AQUI VAN LOS RESULTADOS DE LA CONSULTA DE LOS USUARIOS-->
				</div>
		      </div>
		    </div>
		  </div>
		  
		  	<!-- SECCION -> ARTICULOS -->
		   <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingTwo">
		      <h4 class="panel-title">
		      	
		        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-articulos" aria-expanded="false" aria-controls="collapseTwo" onclick="">
				<div style="width:100%; height:20px;">
				<span class="caret pull-right"></span>
		          Articulos
				  </div>
		        </a>
		      </h4>
		    </div>
		    <div id="collapse-articulos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		      <div class="panel-body">
			 
				<div class="col-xs-4 col-sm-3 col-md-3">
					<a href="#modal_articulo" class="btn btn-primary agregar_nuevo_articulo" data-toggle="modal">Agregar Articulo Nuevo</a>
				</div>
			
				<div class="col-xs-6 col-sm-4 col-md-4">
						Empresa Cliente
						<select class="form-control col-md-6" name="" id="select_list_empresas"> <!---   select de lista de empresas en articulos ---->
						              
						</select>
				</div>
				<div class="col-sm-5 col-md-5">
				<a href="#modal_articulo_microsip" class="btn btn-primary agregar_nuevo_articulo" data-toggle="modal">Agregar Articulo Microsip</a>
				</div>
				
				
		        <div class="col-xs-12" id="div_tabla_articulos" style=" font-size:12px; padding-top:10px;">
					
				</div>
				
            		<!--modal nuevo Articulo-->
            		<div class="modal fade" id="modal_articulo" tabindex="-1" role="dialog">
                		<div class="modal-dialog" role="document">
                			<div class="modal-content">
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
						                   		<label for="select_art_empresa" class="col-sm-4 control-label">Para Empresa:</label>
						                   	<div class="col-sm-8">
						                   		<select class="form-control" name="" id="select_art_empresa">
						                   			<?php 	
													$lista_empresas2 = lista_empresas();
															foreach ($lista_empresas2 as $id => $empresa){
																echo '<option value="'.$id.'" >'.$empresa.'</option>';
															};
													?>
						                   		</select>
						                   	</div>
						                </div>
						                <div class="form-group">
						                   	<label for="txt_clave_empresa" class="col-sm-4 control-label">Clave Articulo (Cliente):</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_clave_empresa" placeholder="Clave Articulo Cliente">
						                   	</div>
					                   	</div>
						                <div class="form-group">
						                   	<label for="txt_clave_microsip" class="col-sm-4 control-label">Clave Microsip:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_clave_microsip" placeholder="Nombre">
						                   	</div>
					                   	</div>
					                   	<div class="form-group">
						                   	<label for="txt_nombre_articulo" class="col-sm-4 control-label">Nombre Articulo:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_nombre_articulo" placeholder="Nombre Articulo">
						                   	</div>
						                </div>
						                <div class="form-group">
						                   	<label for="txt_descripcion" class="col-sm-4 control-label">Descripcion:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_descripcion" placeholder="">
						                   	</div> 
					                   	</div> <!---->
						                <div class="form-group">
						                    <!---->	<label for="txt_descripcion" class="col-sm-4 control-label">Puntos y Existencia:</label>
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
						                <div class="form-group">
						                   <div class="col-sm-1"></div>
						                   								
											<input type="hidden" id="txt_id_categoria" value=""/>
									<div class="col-sm-4"> 
									<span > El articulo pertenece a: </span>									
									<div class="list-group " style="overflow-y: scroll; height: 100px;" id="div_reg_categorias">
									<!-- EL CONTENIDO ES LA LISTA DE CATEGORIAS REGISTRADAS A LOS ARTICULOS  -->
									  
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
									 <!-- EL CONTENIDO ES LA LISTA DE CATEGORIAS SE ESCRIBE CON PHP -->
									</div>
									<a href="#modal_categoria" class="agregar_nueva_categoria" data-toggle="modal">Agregar Categoria</a>
									</div>
					                   	</div>
						             
					                   
										<div class="form-group">
						                   	<label for="precio" class="col-sm-4 control-label">Precio:</label>
						                   	<div class="col-sm-8">
						                   		<input type="email" class="form-control" id="txt_precio" placeholder="Precio$">
						                   	</div>
					                   	</div>
					                   	<div class="form-group">
						                   	<label for="txt_imagen" class="col-sm-4 control-label">Imagen:</label>
						                   	<div class="col-sm-8">
						                   		<input type="file" class="form-control" id="txt_imagen" placeholder="imagen">
						                   	</div>
					                    </div>
					                    
					                   
				                    </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					<button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_guardar_articulo">Guardar</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal nuevo Articulo-->
					
					<!--modal nueva Categoria-->
            		<div class="modal fade" id="modal_categoria" tabindex="-1" role="dialog">
                		<div class="modal-dialog" role="document">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Nueva Categoria
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<form action="" class="form-horizontal" onsubmit="return sin_accion();">
										<input type="hidden" value="" id="txt_id_edit_categoria" /><!-- TXT CON EL ID DE ARTICULO SELECCIONADO -->
									
					                   	<div class="form-group">
						                   		<label for="select_cat_empresa" class="col-sm-4 control-label">Para Empresa:</label>
						                   	<div class="col-sm-8">
						                   		<select class="form-control" name="" id="select_cat_empresa">
						                   			<?php 	
													$lista_empresas2 = lista_empresas();
															foreach ($lista_empresas2 as $id => $empresa){
																echo '<option value="'.$id.'" >'.$empresa.'</option>';
															};
													?>
						                   		</select>
						                   	</div>
						                </div>
						                <div class="form-group">
						                   	<label for="txt_categoria" class="col-sm-4 control-label">Nombre Categoria</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_categoria" placeholder="Nombre de la Categoria">
						                   	</div>
					                   	</div>
						                 <!---->
						               
						             
					                    
					                   
				                    </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					<button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_guardar_categoria">Guardar</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal nueva Categoria-->
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
						                   		<label for="select_art_microsip_empresa" class="col-sm-4 control-label">Para Empresa:</label>
						                   	<div class="col-sm-8">
						                   		<select class="form-control" name="" id="select_art_microsip_empresa">
						                   			<?php 	
													$lista_empresas2 = lista_empresas();
															foreach ($lista_empresas2 as $id => $empresa){
																echo '<option value="'.$id.'" >'.$empresa.'</option>';
															};
													?>
						                   		</select>
						                   	</div>
						                </div>
						                <div class="form-group">
						                   	<label for="txt_buscar_art_microsip" class="col-sm-4 control-label">Articulo a buscar en microsip</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" id="txt_buscar_art_microsip" placeholder="Nombre o Clave" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"> 
												
						                   	</div>
					                   	</div> 
										<div class="form-group" id="div_tabla_art_microsip">
						                   	
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
		      </div>
		    
		      </div>
		    </div>
		 <!-- SECCION -> RELACION DE ATENCION A CLIENTES   -->
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingfour">
		      <h4 class="panel-title">
		      	
		        <a class="collapsed btn_relacion_atencion" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
					<div style="width:100%; height:20px;">	
				 Relacion de atención a clientes<span class="caret pull-right"></span>
		        </div>
				</a>
		      </h4>
		    </div>
		    <div id="collapsefour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfour">
		      <div class="panel-body bg-info" style="font-size:12px;">

		        <div class="row" >
		        	<div class="col-md-5">
		        		<div class="list-group">
						  <a href="#" class="list-group-item active">
						    Requisitores
						  </a>
						</div>
						<input type="hidden" id="txt_id_requisitor" value=""/>
						<div class="list-group" style="overflow-y: scroll; height: 250px;" id="div_requisitores">
						   
						</div>		        		
		        	</div>

					<div class="col-md-2">
						<button type="button" class="btn btn-info" onclick="guardar_relacion();">Agregar</button>
						<button type="button" class="btn btn-warning" onclick="eliminar_relacion();">Quitar</button>
					</div>

		        	<div class="col-md-5">
		        		<div class="list-group">
						  <a href="#" class="list-group-item active">
						    Vendedores
						  </a>
						</div>
						 <input type="hidden" id="txt_id_vendedor" value=""/>
						<div class="list-group" style="overflow-y: scroll; height: 250px;" id="div_vendedores">
						 
						
						</div>		        		
		        	</div>
		        </div>

		        <div class="row">
		        	<div class="col-md-12">
		        		<div class="list-group">
						  <a href="#" class="list-group-item active">
						    Asignaciones
						  </a>
						</div>
						<input type="hidden" id="txt_id_relacion" value=""/>
						<div class="list-group" style="overflow-y: scroll; height: 300px;" id="div_relaciones">
						
						 

						 
						</div>
		        	</div>
		        </div>

		      </div>
		    </div>
		  </div>
		  
		  <!-- SECCION -> CENTRO DE COSTOS -->
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingfour">
		      <h4 class="panel-title">
		      	
		        <a class="collapsed btn_list_cc_empresas" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_cc" aria-expanded="false" aria-controls="collapsefour">
					<div style="width:100%; height:20px;">	
				Centros de Costos<span class="caret pull-right"></span>
		        </div>
				</a>
		      </h4>
		    </div>
		    <div id="collapse_cc" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfour">
		      <div class="panel-body " style="font-size:12px;">

		        <div class="row" >
					<div class="col-md-3">
						<a href="#modal_nuevo_cc" class="btn btn-primary agregar_nuevo_cc" data-toggle="modal">Agregar Centro de Costos</a>
					</div>
		        	

		        	<div class="col-md-4">
		        	Empresa Cliente
						<select class="form-control col-md-5" name="" id="select_list_empresas_cc"> 
						<!---   select de lista de empresas en articulos ---->
						              
						</select>
						<input type="hidden" value="0" id="txt_cc_id_empresa" />		
		        	</div>
					<div class="col-xs-5" id="div_tabla_centro_costos" style=" font-size:12px; padding-top:10px;">
					
					</div>
					
					 <!--modal nuevo cc-->
            		<div class="modal fade" id="modal_nuevo_cc" tabindex="-1" role="dialog">
                		<div class="modal-dialog" role="document">
                			<div class="modal-content">
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Nuevo Centro de Costos
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<form action="" class="form-horizontal">
										<input type="hidden" value="" id="txt_cc_id" /><!-- TXT CON EL ID DE CENTRO DE COSTOS SELECCIONADO -->
					                   	<div class="form-group">
						                  <label for="select_cc_empresa" class="col-sm-4 control-label">Empresa:</label>
						                   	<div class="col-sm-8">
						                   		<select class="form-control" name="" id="select_cc_empresa">
						                   			
						                   		</select>
												
						                   	</div>
						                </div>
						                <div class="form-group">
						                   	<label for="txt_cc_nombre" class="col-sm-4 control-label">Centro de Costos:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_cc_nombre" placeholder="Nombre Centro de Costos">
						                   	</div>
					                   	</div>
						              
						           
					                   
				                    </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="guardar_new_cc();">Guardar</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal nuevo cc -->
		        </div>

		        

		      </div>
		    </div>
		  </div>
		  
		  <!-- SECCION -> EMPRESAS -->
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingTwo">
		      <h4 class="panel-title">
		      
		        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-empresas" aria-expanded="false" aria-controls="collapseTwo" onclick="mostrar_empresas();">	
				<div style="width:100%; height:20px;">
				<span class="caret pull-right"></span>
		          Empresas
				  </div>
		        </a>
		      </h4>
		    </div>
		    <div id="collapse-empresas" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		      <div class="panel-body">
			  <input id="txt_id_empresa" type="hidden" value=""/>
		        <div class="col-md-6" id="div_tabla_empresas" style="font-size:12px;">
					
				</div>
				
				<div align="center" id="div_sucursales" class="col-md-5">
				<p><a href="#modal_nueva_sucursal" class="btn btn-primary btn-sm agregar_nueva_sucursal " data-toggle="modal">Agregar sucursal</a></p>
					
					<div align="center" id="div_tabla_sucursales">  
					</div>	
				</div>
				 
            		<!--modal nueva Sucursal-->
            		<div class="modal fade" id="modal_nueva_sucursal" tabindex="-1" role="dialog">
                		<div class="modal-dialog" role="document">
                			<div class="modal-content"><!-- content de la ventana -->
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Nueva Sucursal
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<form action="" class="form-horizontal">
										<input type="hidden" value="" id="txt_id_sucursal" /><!-- TXT CON EL ID DE sucursal SELECCIONADO -->
					                  
						                <div class="form-group">
						                   	<label for="txt_sucursal" class="col-sm-4 control-label">Nombre Sucursal:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_sucursal" placeholder="Nombre de Sucursal">
						                   	</div>
					                   	</div>
						                <div class="form-group">
						                   	<label for="txtarea_direccion" class="col-sm-4 control-label">Direccion:</label>
						                   	<div class="col-sm-8">
						                   		<textarea type="text" class="form-control" id="txtarea_direccion" > </textarea>
						                   	</div>
					                   	</div>
					                   	<div class="form-group">
						                   	<label for="pais" class="col-sm-4 control-label">Pais:</label>
						                   	<div class="col-sm-8" id="div_sucursal_pais"><!-- div donde se muesta la lista de paises -->
						                   		<select class="form-control" name="" id="select_sucursal_pais" disabled>
						                   			
						                   		</select>
						                   	</div>
						                </div>
					                   	<div class="form-group">
						                   	<label for="estado" class="col-sm-4 control-label">Estado:</label>
						                   	<div class="col-sm-8" id="div_sucursal_estado"><!-- div donde se muesta la lista de estados -->
						                   		<select class="form-control" name="" id="select_sucursal_estado" disabled>
						                   			
						                   		</select>
						                   	</div>
						                </div>
					                   	<div class="form-group">
						                   	<label for="ciudad" class="col-sm-4 control-label">Ciudad:</label>
						                   	<div class="col-sm-8"  id="div_sucursal_ciudad">
						                   		<select class="form-control" name="" id="select_sucursal_ciudad" disabled>
						                   			
						                   		</select>
						                   	</div>
						                </div>
					                   	<div class="form-group">
						                   	<label for="txt_codigo_postal" class="col-sm-4 control-label">Codigo Postal:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_codigo_postal" placeholder="Codigo Postal">
						                   	</div>
						                </div>
					                   	<div class="form-group">
				<label for="txt_codigo_postal" class="col-sm-4 control-label">Usos para Direccion:</label>
						                   	<div class="col-sm-8">
				<div class="checkbox" ><label>
				<input type="checkbox" id="chk_dir_suc" checked class="checks_uso_dir"/> 
				Usar direccion para Sucursal
				</label></div>
				<div class="checkbox" ><label>
				<input type="checkbox" id="chk_dir_fac"  class="checks_uso_dir"/> 
				Usar direccion para Facturar
				</label></div>
				
						                   	</div>
						                </div>
						             
					                </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					<button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_guardar_sucursal" onclick="">Guardar</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal nueva sucursal -->
		<!--modal modificar empresas-->
            		<div class="modal fade" id="modal_modificar_empresa" tabindex="-1" role="dialog">
                		<div class="modal-dialog" role="document">
                			<div class="modal-content"><!-- content de la ventana -->
                				<!-- Header de la ventana -->
                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h3 class="modal-title">
                						Empresa
                					</h3>
                				</div>
                				<!-- Contenido de la ventana -->
                				<div class="modal-body">
                					<form action="" class="form-horizontal">
														                  
						                <div class="form-group">
						                   	<label for="txt_empresa" class="col-sm-4 control-label">Nombre Empresa:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_empresa" placeholder="Nombre Empresa">
						                   	</div>
					                   	</div>
						                <div class="form-group">
						                   	<label for="txt_rfc" class="col-sm-4 control-label">RFC:</label>
						                   	<div class="col-sm-8">
						                   		<input type="text" class="form-control" id="txt_rfc" placeholder="RFC">
						                   	</div>
					                   	</div>
					                   	
						             
					                </form>
                				</div>
                				<!-- Footer de la ventana -->
                				<div class="modal-footer">
                					<button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_guardar_empresa" onclick="">Guardar</button>
                					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                				</div>
                				
                			</div>
                		</div>
                	</div>
                	<!--final modal modificar empresas-->			
		      </div>
		    </div>
		  </div>
		  
		  	<!-- SECCION -> DEPARTAMENTOS Y PUESTOS-->
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingThree">
		      <h4 class="panel-title">
		      	
		        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" onclick="mostrar_depues();">
				<div style="width:100%; height:20px;">
				<span class="caret pull-right"></span>
		          Departamentos y Puestos
				  </div>
		        </a>
		      </h4>
		    </div>
		    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" >
		      <div class="panel-body">
			   <div class="col-lg-12" id="div_departamentos_puestos" style="font-size:12px;">
					
				</div>
		        
		      </div>
		    </div>
		  </div>
		  </div> <!-- termina panel-group de colapse --->
		</div>
        <div class="row">

			<div class=""  id="resultados_js"></div>

        </div>
        <br>
        <div class="row">

		</div>
    </div>
</section>
  <footer>
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
                       <!-- <li><a href="#">Buzon de sugerencias</a></li>
                        <li><a href="#">Contactos</a></li>
                       -->
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="copyright">
                        <p>© 2019 All Part Productos y Servicios</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/jquery-1.12.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
   <!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    -->
   <script type="text/javascript" charset="utf8" src="assets/js/datatable.js"></script>
	
	
	
	
	<script>
	$("#div_new_empresa").hide();
	$("#div_new_departamento").hide();
	$("#div_new_puesto").hide();
	
	$(document).ready(function(){
                $(".agregar_nuevo_usuario").click(function(){
                       	   
							   $("#txt_id_usuario").val("");
							   var id_usuario = document.getElementById("txt_id_usuario").value;
				$("#select_tipo").val("");
				$("#nombre").val("");
				$("#username").val("");
				$("#apellido").val("");
				$("#contrasena").val("");
				$("#select_empresa").val("");
				$("#select_sucursal").val("");
				$("#correo").val("");
				$("#telefono").val("");
				$("#select_departamento").val("");
				$("#select_puesto").val("");
				$("#select_turno").val("");
				$("#btn_subordinados").hide();
				$("#chk_autspend").prop("checked",false);
				$("#div_lista_subordinados").hide();
				$("#div_lista_subordinados").html("Debe guardar el usuario para despues poder agregar los subordinados");
				$("#btn_add_recolector").hide();
				$("#div_lista_recolectores").hide();
				$("#div_lista_recolectores").html("Debe guardar el usuario para despues poder agregar los recolectores");
				$("#btn_add_cc").hide();
				$("#div_lista_cc").hide();
				$("#div_lista_cc").html("Debe guardar el usuario para despues poder agregar los Centros de costos");
							var id_empresa = document.getElementById("select_empresa").value;
                         	$("#txt_usuarios_id_empresa").val(id_empresa);
							lista_sucursales(id_empresa);
							lista_subor_recolect();
			
				});
                $(".agregar_nuevo_cc").click(function(){
                  lista_art_empresas_cc_new();
				$("#select_cc_empresa").val("");
				$("#txt_cc_nombre").val("");
				
				
				});
				$("#select_tipo_mostrar").change(function(){
						mostrar_user();
                });
				$("#select_tipo").change(function(){
						//mostrar_user();
						var tipo = document.getElementById("select_tipo").value; 
						if (tipo == 5){
							$("#btn_subordinados").show();
							$("#div_lista_subordinados").show();
							$("#btn_add_recolector").show();
							$("#div_lista_recolectores").show();
							$("#btn_add_cc").show();
							$("#div_lista_cc").show();
							$("#lblchk_autspend").show();
						}
						else if (tipo == 2)
						{
							$("#btn_add_recolector").show();
							$("#div_lista_recolectores").show();
							$("#btn_add_cc").show();
							$("#div_lista_cc").show();
							$("#btn_subordinados").hide();
							$("#div_lista_subordinados").hide();
							$("#lblchk_autspend").hide();
							
							
						}
						else
						{
							$("#lblchk_autspend").hide();
							$("#btn_subordinados").hide();
							$("#div_lista_subordinados").hide();
							$("#btn_add_recolector").hide();
							$("#div_lista_recolectores").hide();
							$("#btn_add_cc").hide();
							$("#div_lista_cc").hide();
						}
						lista_subor_recolect();
						
                });
				$("#select_empresa").change(function(){
							var id_empresa = document.getElementById("select_empresa").value;
                         	$("#txt_usuarios_id_empresa").val(id_empresa);
							lista_sucursales(id_empresa);
							lista_departamentos(id_empresa);
							lista_subor_recolect();
                });
				$("#select_list_empresas").dblclick(function(){
							var id_empresa = document.getElementById("select_list_empresas").value;
                         	$("#txt_art_id_empresa").val(id_empresa);
						
							mostrar_articulos(id_empresa);
							lista_categorias();
						lista_reg_categorias();
							
                });
				$("#select_list_empresas").change(function(){
							var id_empresa = document.getElementById("select_list_empresas").value;
                         	$("#txt_art_id_empresa").val(id_empresa);
						
							mostrar_articulos(id_empresa);
							lista_categorias();
						lista_reg_categorias();
							
                });
				$("#select_list_empresas_cc").change(function(){
							var id_empresa = document.getElementById("select_list_empresas_cc").value;
                         	$("#txt_cc_id_empresa").val(id_empresa);
						
							mostrar_cc();
														
                });
				$("#select_art_empresa").change(function(){
							var id_empresa = document.getElementById("select_art_empresa").value;
                         	$("#txt_art_id_empresa").val(id_empresa);
						lista_categorias();
						lista_reg_categorias();
							//mostrar_articulos(id_empresa);
							
                });
				$("#btn_subordinados").click(function(){
                       	    //mostral modal para seleccionar a los subordinados
							
							$("#div_lista_subordinados").toggle("blind","",500);
                });
				$("#btn_add_recolector").click(function(){
                       	    //div para seleccionar a los recolectores
							
							$("#div_lista_recolectores").toggle("blind","",500);
                });
				$("#btn_add_cc").click(function(){
                       	    //div para seleccionar a los recolectores
							
							$("#div_lista_cc").toggle("blind","",500);
                });
				$("#btn_guardar_sucursal").click(function(){
                       	   
							   guardar_sucursal();
                });
				$("#btn_guardar_empresa").click(function(){
                       	   
							   modifica_empresa();
                });
				$("#btn_guardar_articulo").click(function(){
                       	   
							   guardar_articulo();
                });
				$("#add_del_categoria").click(function(){
                       	   
							   add_del_categoria();
                });
				$("#btn_guardar_categoria").click(function(){
                       	   
							   guardar_categoria();
                });
				
				$(".btn_relacion_atencion").click(function(){
                       	   
							list_relaciones();
							list_requisitores();
							list_vendedores();
                });
				$(".btn_list_cc_empresas").click(function(){
                       	   
							lista_art_empresas_cc();
							mostrar_cc();
                });
				$(".agregar_nueva_sucursal").click(function(){
				$("#txt_sucursal").val("");
				$("#txtarea_direccion").html("");
				$("#txt_codigo_postal").val("");
				$("#chk_dir_fac").prop("checked",false);
				
				
                      	   
					 $("#div_sucursal_ciudad").html('<select class="form-control" name="" id="select_sucursal_ciudad" disabled></select>');
                });
				$(".agregar_nuevo_articulo").click(function(){
							$("#txt_id_articulo").val("");
							$("#txt_id_articulo_microsip").val("");
							$("#txt_clave_empresa").val("");
							$("#txt_clave_microsip").val("");	
							$("#txt_nombre_articulo").val("");
							$("#txt_descripcion").val("");
							$("#select_art_empresa").val("");
							$("#txt_precio").val("");
							$("#txt_imagen").val("");
							jQuery("#modal_articulo .modal-header").html("Articulo Nuevo") ;
				
                });
				$(".agregar_nueva_categoria").click(function(){
							var id_empresa = document.getElementById("select_art_empresa").value;
							$("#txt_id_edit_categoria").val("");
							
							jQuery("#modal_categoria .modal-header").html("Nueva Categoria") ;
							$("#modal_categoria").on("shown.bs.modal", function() {
								$("#txt_categoria").val("");
								$("#txt_categoria").focus();
								$("#select_cat_empresa").val(id_empresa);
								});
						
				
                });
				$('#txt_categoria').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode == '13'){
						guardar_categoria();
						$("#modal_categoria").modal("hide");
						 
					}
				});
				$('#txt_buscar_art_microsip').keypress(function(event){
					var keycode = (event.keyCode ? event.keyCode : event.which);
					var nombre_clave = document.getElementById("txt_buscar_art_microsip").value;
					if(keycode == '13'){
						BuscarArticuloMicrosip(nombre_clave);
						
						 
					}
				});
				
				$("#div_lista_subordinados").hide();
				$("#div_lista_recolectores").hide();
				$("#div_lista_cc").hide();
				
		});
	
	list_relaciones();
	list_requisitores();
	list_vendedores();
	mostrar_user();
	mostrar_empresas();
	mostrar_depues();
	lista_paises();
	lista_estados();
	lista_art_empresas();
	</script>
</body>
</html>



