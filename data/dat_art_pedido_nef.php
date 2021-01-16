<?php include("conexion.php"); 

		$id_articulo = $_POST['id_articulo']; // Articulo_id microsip
		
	  if ($id_articulo != ""){
		$precio = PrecioArticuloNef($id_articulo);
		$udm = UDMArticuloNef($id_articulo);
		$clave = ClaveArticuloNef($id_articulo);
		$nombre = str_replace('"','\"',NombreArticuloNef($id_articulo));
		$existencia = ExistenciaMicrosipNef($id_articulo,19); // 19 = almacen general
	  
		echo '<script>$(document).ready(function(){
			
			$("#txtadd_nombre_art_micro_nef").val("'.utf8_encode($nombre).'");
			$("#txtadd_clave_art_micro_nef").val("'.utf8_encode($clave).'");
			$("#txtadd_precio_nef").val("'.$precio.'");
			$("#txtadd_udm_nef").val("'.$udm.'");
			$("#td_addartudm_nef").html("'.$udm.'");
			$("#td_existencia_art_nef").html("Existencia = '.$existencia.'");
			//$("#td_addartudm_nef").attr("title","Existencia = '.$existencia.'");
			
			//$("#tdadd_importe_total").html("0.00");
			$("#txtadd_unidades_nef").focus();
			$("#modal_cargando").modal("hide");
		});</script>';
	  
	  }
	  
	  ?>