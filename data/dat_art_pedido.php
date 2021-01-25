<?php include("conexion.php"); 

		$id_articulo = $_POST['id_articulo']; // Articulo_id microsip
		
	  if ($id_articulo != ""){
		$precio = "";//PrecioArticulo($id_articulo);
		$udm = UDMArticulo($id_articulo);
		$clave = str_replace('"','\"', ClaveArticulo($id_articulo));;
		$nombre = str_replace('"','\"',NombreArticulo($id_articulo));
		$existencia = ExistenciaMicrosip($id_articulo,19); // 19 = almacen general
	  
		echo '<script>$(document).ready(function(){
			
			$("#txtadd_nombre_art_micro").val("'.utf8_encode($nombre).'");
			$("#txtadd_clave_art_micro").val("'.utf8_encode($clave).'");
			$("#txtadd_precio").val("'.$precio.'");
			$("#txtadd_udm").val("'.$udm.'");
			$("#td_addartudm").html("'.$udm.'");
			$("#td_existencia_art").html("Existencia = '.$existencia.'");
			$("#td_existencia_art").attr("title", "Existencia en Almacen General 1");
			//$("#td_addartudm").attr("title","Existencia = '.$existencia.'");
			
			//$("#tdadd_importe_total").html("0.00");
			$("#txtadd_unidades").focus();
			$("#modal_cargando").modal("hide");
			
		});</script>';
	  
	  }
	  
	  ?>