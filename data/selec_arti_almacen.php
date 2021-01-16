<?php include("conexion.php"); 

		$id_empresa = $_POST['id_empresa'];
		$almacen_id = $_POST['almacen_id'];
				  
	  if ($almacen_id != ""){
			$lista_articulos_empresa = busca_art($almacen_id,$id_empresa);
				//echo '<select class="selectpicker form-control form-control-sm" data-live-search="true" id="select_arti_oc" > ';
					echo '<script>$(document).ready(function(){
						$("#select_arti_oc").html("<option value=\"0\" selected disabled hidden>Seleccionar Articulo</option>");
						';	
				foreach($lista_articulos_empresa as $idart => $nombreart)
				{
				//echo '<option value='.$idart.' >'.$nombreart.'</option>';
				
				echo '
						$("#select_arti_oc").append($("<option>").val("'.$idart.'").text("'.$nombreart.'"));
							';
				}
				//echo '</select> ';
				echo '
				//$("#select_arti_oc").addClass("selectpicker");
				//$("#select_arti_oc").attr("data-live-search", "true");
				$("#select_arti_oc").selectpicker("refresh");
				
				});</script>';
	  }
	  
?>