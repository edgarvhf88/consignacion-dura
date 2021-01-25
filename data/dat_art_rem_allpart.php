<?php include("conexion.php"); 

		$id_articulo = $_POST['id_articulo']; // Articulo_id microsip
		
	  if ($id_articulo != ""){
		$precio = PrecioArticulo_dura($id_articulo);
		$udm = UDMArticulo_dura($id_articulo);
		//$clave = ClaveArticulo_dura($id_articulo);
		//$nombre = str_replace('"','\"',NombreArticulo_dura($id_articulo));
		//$existencia = ExistenciaMicrosip($id_articulo,19); // 19 = almacen general
	  
		
		//extraje valores innecesarios
		////$("#txtadd_nombre_art_micro_allpart").val("'.utf8_encode($nombre).'");
			//$("#txtadd_clave_art_micro_allpart").val("'.utf8_encode($clave).'");
			//$("#td_existencia_art_allpart").html("Existencia = '.$existencia.'");
			//$("#td_addartudm_nef").attr("title","Existencia = '.$existencia.'");
			
			//$("#tdadd_importe_total_allpart").html("0.00");
		echo '<script>$(document).ready(function(){
			
			
			$("#txtadd_precio_allpart").val("'.$precio.'");
			$("#txtadd_udm_allpart").val("'.$udm.'");
			$("#td_addartudm_allpart").html("'.$udm.'");
			
			
			$("#modal_cargando").modal("hide");
			$("#txtadd_unidades_allpart").focus();
		});</script>';
	  
	  }
	  
	  ?>