<?php include("conexion.php"); 

		$id_empresa = $_POST['id_empresa'];
		$almacen_id = $_POST['almacen_id'];
				  
	  if ($almacen_id != ""){
			$lista_articulos_empresa = lista_art_micro_NEF($almacen_id,$id_empresa);
				//echo '<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_arti_pedido_nef" > ';
					echo '<script>$(document).ready(function(){
						$("#select_arti_pedido_nef").html("<option value=\"0\" selected disabled hidden>Seleccionar Articulo</option>");
						$("#select_arti_pedido_nef_asig").html("<option value=\"0\" selected disabled hidden>Seleccionar Articulo</option>");
						';	
				foreach($lista_articulos_empresa as $idart => $nombreart)
				{
				//echo '<option value='.$idart.' >'.$nombreart.'</option>';
				//$nombre_articulo = str_replace("'","\'",str_replace('"','\"',$nombreart));
				$nombre_articulo = str_replace('"','\"',$nombreart);
				$nombre_articulo = str_replace("\n","",$nombre_articulo);
				
				
				//if  (strlen($nombre_articulo) < 53){}
				
				echo '
						$("#select_arti_pedido_nef").append($("<option>").val("'.$idart.'").text("'.$nombre_articulo.'"));
						$("#select_arti_pedido_nef_asig").append($("<option>").val("'.$idart.'").text("'.$nombre_articulo.'"));
							';
				}
				
				//echo '</select> ';
				echo '
				//$("#select_arti_pedido_nef").addClass("selectpicker");
				//$("#select_arti_pedido_nef").attr("data-live-search", "true");
				$("#select_arti_pedido_nef").selectpicker("refresh");
				$("#select_arti_pedido_nef_asig").selectpicker("refresh");
				$("#txt_verif_carga_art_nef").val("1");
				$("#modal_cargando").modal("hide");
				
				});</script>';
	  }
	  
?>