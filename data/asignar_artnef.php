<?php include("conexion.php"); 

		$id_articulo = $_POST['id_articulo']; // id articulo sistema
		$id_art_nef = $_POST['id_art_nef']; // Articulo_id microsip
		$clave_nef = $_POST['clave_nef']; // Articulo_id microsip
		
		if (($id_articulo != "") && ($id_art_nef != "")){
			  
			$update = "UPDATE articulos 
								SET id_microsip_nef='$id_art_nef', clave_microsip_nef = '$clave_nef' 
								WHERE id='$id_articulo'";
			
			if (mysql_query($update, $conex) or die(mysql_error()))
			{
				echo '<script>
					$(document).ready(function(){
						cargar_lista_pedido_nef();
						$("#modal_cargando").modal("hide");
						$("#modal_asig_artnef").modal("hide");
					});
				</script>';
			}
	  	  
		}else { echo '<script>
					$(document).ready(function(){
						
						$("#modal_cargando").modal("hide");
						alert("No se pudo relacionar el articulo por falta de datos!");
					});
				</script>';}
	  
	  ?>