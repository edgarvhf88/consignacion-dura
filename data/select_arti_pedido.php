<?php include("conexion.php"); 

		$id_empresa = $_POST['id_empresa'];
		$almacen_id = $_POST['almacen_id'];
				  
	  if ($almacen_id != ""){
			$lista_articulos_empresa = lista_art_micro($almacen_id,$id_empresa);
				//echo '<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_arti_pedido" > ';
					echo '<script>$(document).ready(function(){
						$("#select_arti_pedido").html("<option value=\"0\" selected disabled hidden>Seleccionar Articulo</option>");
						';	
				foreach($lista_articulos_empresa as $idart => $nombreart)
				{
				//echo '<option value='.$idart.' >'.$nombreart.'</option>';
				$nombre_articulo = str_replace('"','\"',$nombreart);
				
				echo '
						$("#select_arti_pedido").append($("<option>").val("'.$idart.'").text("'.$nombre_articulo.'"));
							';
				}
				//echo '</select> ';
				echo '
				//$("#select_arti_pedido").addClass("selectpicker");
				//$("#select_arti_pedido").attr("data-live-search", "true");
				$("#select_arti_pedido").selectpicker("refresh");
				$("#txt_verif_carga_art_traspaso").val("1");
				$("#modal_cargando").modal("hide");
				
				});</script>';
	  }
	  
?>