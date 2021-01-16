<?php include("conexion.php"); 

		$id_articulo = $_POST['id_articulo']; // Articulo_id microsip
		
	  if ($id_articulo != ""){
		$precio = PrecioArticuloNef($id_articulo);
		$udm = UDMArticuloNef($id_articulo);
		$clave = ClaveArticuloNef($id_articulo);
		//$nombre = str_replace("\n","",str_replace('"','\"',NombreArticuloNef($id_articulo)));
		$existencia = ExistenciaMicrosipNef($id_articulo,19); // 19 = almacen general
	  
		$tabla_datos = '<table class=\" table \"><tr><th>Existencia</th><th>Unid. Med.</th><th>Precio Lista</th></tr> <tr><td>'.$existencia.'</td><td>'.$udm.'</td><td>'.$precio.'</td></tr></table>';
	  
		echo '<script>$(document).ready(function(){
						
			$("#div_datos_artnef").html("'.$tabla_datos.'");
			$("#txt_idart_nef").val("'.$id_articulo.'");
			$("#txt_clave_nef").val("'.$clave.'");
			$("#modal_cargando").modal("hide");
			
		});</script>';
	  
	  }
	  
	  ?>