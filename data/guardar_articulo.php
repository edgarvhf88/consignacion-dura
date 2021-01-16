
<?php include("conexion.php");


	  if ((isset($_POST['clave_empresa'])) && (isset($_POST['articulo'])) && (!isset($_POST['id_articulo']))){
		  //SI ES UN ARTICULO NUEVO 
				$articulo= $_POST['articulo'];
				$clave_empresa= $_POST['clave_empresa'];
				$clave_microsip= $_POST['clave_microsip'];
				$udm= $_POST['udm'];
				//$descripcion= $_POST['descripcion'];
				$descripcion= "";
				$precio= $_POST['precio'];
				//$imagen= $_POST['imagen'];
				$imagen= "";
				$empresa_id = $_POST['empresa_id']; 
				$min = $_POST['min'];
				$max = $_POST['max'];
				$reorden = $_POST['reorden'];
				$existencia = $_POST['existencia'];
				$almacen_id = $_POST['almacen_id'];
				$id_microsip = $_POST['id_articulo_microsip'];
		
				guardar($articulo,$clave_empresa,$clave_microsip,$descripcion,$precio,$imagen,$empresa_id,$min,$max,$reorden,$existencia,$id_microsip,$almacen_id,$udm);
	  } 
	  if ((isset($_POST['clave_empresa'])) && (isset($_POST['articulo'])) && (isset($_POST['id_articulo']))){
		  //SI SE VA A EDITAR ALGUN ARTICULO EXISTENTE
				$articulo= $_POST['articulo'];
				$clave_empresa= $_POST['clave_empresa'];
				$clave_microsip= $_POST['clave_microsip'];
				$udm= $_POST['udm'];
				//$descripcion= $_POST['descripcion'];
				$descripcion= "";
				$precio= $_POST['precio'];
				//$imagen= $_POST['imagen'];
				$imagen= "";
				$empresa_id = $_POST['empresa_id'];
				$min = $_POST['min'];
				$max = $_POST['max'];
				$reorden = $_POST['reorden'];
				$existencia = $_POST['existencia'];
				$almacen_id = $_POST['almacen_id'];
				$id_microsip = $_POST['id_articulo_microsip'];
				$id_articulo = $_POST['id_articulo'];
		
				actualizar($articulo,$clave_empresa,$clave_microsip,$descripcion,$precio,$imagen,$empresa_id,$id_articulo,$min,$max,$reorden,$existencia,$id_microsip,$almacen_id,$udm);
	  }
	
     function guardar($articulo,$clave_empresa,$clave_microsip,$descripcion,$precio,$imagen,$empresa_id,$min,$max,$reorden,$existencia,$id_microsip,$almacen_id,$udm){ // Funcion para agregar una relacion nueva
global  $conex;

		$consulta = "SELECT * 
					FROM articulos a
					LEFT JOIN existencias exis ON exis.id_articulo = a.id
					WHERE a.id_microsip = '$id_microsip' 
					and a.id_empresa = '$empresa_id'
					and exis.almacen_id = '$almacen_id'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);

		if ($total_rows > 0)
		{ // si ya existe un articulo con las claves
			echo '<script>
			alert("El Articulo ya esta registrado");
			
			</script>';
			
		}
		else 
		{

			$insert_articulo = "INSERT INTO articulos (id_empresa,id_microsip,clave_microsip,clave_empresa,nombre,descripcion,precio,src_img,unidad_medida)
								VALUES ('$empresa_id','$id_microsip','$clave_microsip','$clave_empresa','$articulo','$descripcion','$precio','$imagen','$udm')";
			if (mysql_query($insert_articulo, $conex) or die(mysql_error()))
			{
				$id_articulo_nevo = mysql_insert_id();
				$insert_punto = "INSERT INTO existencias (min,max,reorden,existencia_actual,id_articulo,almacen_id,articulo_id)
				VALUES ('$min','$max','$reorden','$existencia','$id_articulo_nevo','$almacen_id','$id_microsip')";
							if (mysql_query($insert_punto, $conex) or die(mysql_error())){}
				//$id_pedido =  mysql_insert_id();
				//echo 1;
				if ($imagen != ""){
									$src_img = $imagen;	
									$ruta = "assets/images/productos/emp-".$empresa_id."/".$src_img;	
									}else{
									$src_img = "sin_imagen.jpg";
									$ruta = "assets/images/".$src_img;	}
									
				$ocultar_check = '';					
						
				if ($existencia <= $reorden){
					if ($existencia <= $min){
						$clase = "danger"; /// URGENTE pedir	
						$desc_clase = "Urgente Surtir Material";
						$clase_func = "list_urgente";
						
					}else {
						$clase = "warning"; /// reordenar	
						$desc_clase = "Es Necesario Reordenar";
						$clase_func = "list_reorden";
					}
				}
				else if ($existencia > $max)
				{
					$clase = "info";// sobreInventariado
					$desc_clase = "Este Articulo se encuentra Sobre-Inventariado";
					$clase_func = "list_sobreinventario";
					$check_box = '';
					$ocultar_check = '$("#checkboxart_'.$id_articulo_nevo.'").hide();';	
				}
				else
				{
					$clase = "success";// Se encuentra en buen estatus dentro de los parametros
					$desc_clase = "Articulo Dentro de los Parametros";
					$clase_func = "list_bien";
				}
									
				
				$respuesta = '<script>
				
			 var table = document.getElementById("tbody_articulos");
				var row = table.insertRow(0);
				row.id = "trarticulo_'.$id_articulo_nevo.'";
				row.className = "lista_articulos";
				
				var cell0 = row.insertCell(0);				
				var cell1 = row.insertCell(1);				
				var cell2 = row.insertCell(2);
				var cell3 = row.insertCell(3);
				var cell4 = row.insertCell(4);
				var cell5 = row.insertCell(5);
				var cell6 = row.insertCell(6);
				var cell7 = row.insertCell(7);
				var cell8 = row.insertCell(8);
				var cell9 = row.insertCell(9);
				var cell10 = row.insertCell(10);
				var cell11 = row.insertCell(11);
				var cell12 = row.insertCell(12);
				
				cell0.id = "td_check_'.$id_articulo_nevo.'";
				cell1.id = "tdclaveempresa_'.$id_articulo_nevo.'";
				cell2.id = "tdclavemicrosip_'.$id_articulo_nevo.'";
				cell3.id = "tdarticulo_'.$id_articulo_nevo.'";
				cell4.id = "tdudm_'.$id_articulo_nevo.'";
				cell6.id = "tdprecio_'.$id_articulo_nevo.'";
				cell8.id = "tdmin_'.$id_articulo_nevo.'";
				cell9.id = "tdmax_'.$id_articulo_nevo.'";
				cell10.id = "tdreorden_'.$id_articulo_nevo.'";
				cell11.id = "tdexistencia_'.$id_articulo_nevo.'";
				cell12.id = "td_actualizar_'.$id_articulo_nevo.'";
				
				cell6.align = "right";
				
				cell0.innerHTML = "<div class=\"checkboxbtn lg col-lg-12\" id=\"checkboxbtn_'.$id_articulo_nevo.'\"><label><input type=\"checkbox\" id=\"checkboxart_'.$id_articulo_nevo.'\" checked class=\"chk_art_select\" style=\"\"/> </label></div>";
				cell1.innerHTML = "'.$clave_empresa.'";
				cell2.innerHTML = "'.$clave_microsip.'";
				cell3.innerHTML = "'.$articulo.'";
				cell4.innerHTML = "'.$descripcion.'";
				cell5.innerHTML = "'.EMPRESA_NOMBRE($empresa_id).'";
				cell6.innerHTML = "'.$precio.'";
				cell7.innerHTML = "<img src=\"'.$ruta.'\" width=\"50\" heigth=\"50\" id=\"imagen_'.$id_articulo_nevo.'\" class=\"imagenes\"><input id=\"art_c_empresa_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$clave_empresa.'\"/><input id=\"art_c_microsip_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$clave_microsip.'\"/><input id=\"art_articulo_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$articulo.'\"/><input id=\"art_descip_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$descripcion.'\"/>	<input id=\"art_empresa_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$empresa_id.'\"/><input id=\"art_precio_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$precio.'\"/><input id=\"art_imagen_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$imagen.'\"/>	<input id=\"art_min_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$min.'\"/><input id=\"art_max_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$max.'\"/><input id=\"art_reorden_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$reorden.'\"/><input id=\"art_existencia_'.$id_articulo_nevo.'\" type=\"hidden\" value=\"'.$existencia.'\"/>	";
				cell8.innerHTML = "'.$min.'";
				cell9.innerHTML = "'.$max.'";
				cell10.innerHTML = "'.$reorden.'";
				cell11.innerHTML = "'.$existencia.'";
				cell12.innerHTML = "<input type=\"button\" class=\"btn btn-info btn_actualiza\" id=\"btnact_'.$id_articulo_nevo.'\" value=\"Sincronizar\" />";
				
				'.$ocultar_check.'
				
			
			$("#modal_articulo").modal("hide");
			
			</script>';
			
			echo '<script>
			mostrar_articulos(11);
			$("#modal_articulo").modal("hide");
			$("#trbuscararticulomicrosip_'.$id_microsip.'").hide();
			</script>';
		
			}
		}
}
     function actualizar($articulo,$clave_empresa,$clave_microsip,$descripcion,$precio,$imagen,$empresa_id,$id_articulo,$min,$max,$reorden,$existencia,$id_microsip,$almacen_id,$udm){ // Funcion para agregar una relacion nueva
global  $conex;

			
			$update = "UPDATE articulos SET id_empresa='$empresa_id', nombre='$articulo', clave_empresa='$clave_empresa', clave_microsip='$clave_microsip', descripcion='$descripcion', precio='$precio', src_img='$imagen', unidad_medida='$udm' WHERE id='$id_articulo'";

					if (mysql_query($update, $conex) or die(mysql_error()))
					{
						$consulta_puntos = "SELECT * FROM existencias WHERE id_articulo = '$id_articulo' AND almacen_id = '$almacen_id' ";
						$resultado_puntos = mysql_query($consulta_puntos, $conex) or die(mysql_error());
						$row_puntos = mysql_fetch_assoc($resultado_puntos);
						$total_rows_puntos = mysql_num_rows($resultado_puntos);
				
						if ($total_rows_puntos > 0)
						{ // si encuentra un registro de existencia del articulo Actualiza
							$id_existencia = $row_puntos['id_existencia'];
							$update_punto = "UPDATE existencias SET min='$min', max='$max', reorden='$reorden', existencia_actual='$existencia' WHERE id_existencia='$id_existencia'";

								if (mysql_query($update_punto, $conex) or die(mysql_error())){
									$variable_log = 'console.log("se actualizo existecia '.$id_articulo.'")';	
								}else{
									$variable_log = 'console.log("NO se actualizo existecia ")';	
								}
						}else{
							/// si no lo encuetra inserta 
							$insert_punto = "INSERT INTO existencias (min,max,reorden,existencia_actual,id_articulo,almacen_id)
								VALUES ('$min','$max','$reorden','$existencia','$id_articulo','$almacen_id')";
							if (mysql_query($insert_punto, $conex) or die(mysql_error())){
							$variable_log = 'console.log("se inserto existecia")';	
							}else{
								$variable_log = 'console.log("NO se inserto existecia")';	
							}
							
						}
						if ($imagen != ""){
									$src_img = $imagen;	
									$ruta = "assets/images/productos/emp-".$id_empresa."/".$src_img;	
									}else{
									$src_img = "sin_imagen.jpg";
									$ruta = "assets/images/".$src_img;	}
							/// AGREGAR INPUSTS HIDDEN DE LA TABLA 	


							
						echo '<script>
				
						
						'.$variable_log.'
						
						$("#tdarticulo_'.$id_articulo.'").html("'.$articulo.'");
						$("#tdclaveempresa_'.$id_articulo.'").html("'.$clave_empresa.'");
						$("#tdclavemicrosip_'.$id_articulo.'").html("'.$clave_microsip.'");
						$("#tdudm_'.$id_articulo.'").html("'.$udm.'");
						$("#tdprecio_'.$id_articulo.'").html("'.$precio.'");
						$("#imagen_'.$id_articulo.'").attr("src","'.$ruta.'");
						$("#tdmin_'.$id_articulo.'").html("'.$min.'");
						$("#tdmax_'.$id_articulo.'").html("'.$max.'");
						$("#tdreorden_'.$id_articulo.'").html("'.$reorden.'");
						$("#tdexistencia_'.$id_articulo.'").html("'.$existencia.'");
						
						$("#art_articulo_'.$id_articulo.'").val("'.$articulo.'");
						$("#art_c_empresa_'.$id_articulo.'").val("'.$clave_empresa.'");
						$("#art_c_microsip_'.$id_articulo.'").val("'.$clave_microsip.'");
						$("#art_descip_'.$id_articulo.'").val("'.$descripcion.'");
						$("#art_precio_'.$id_articulo.'").val("'.$precio.'");
						$("#art_imagen_'.$id_articulo.'").val("'.$imagen.'");
						$("#art_min_'.$id_articulo.'").val("'.$min.'");
						$("#art_max_'.$id_articulo.'").val("'.$max.'");
						$("#art_reorden_'.$id_articulo.'").val("'.$reorden.'");
						$("#art_existencia_'.$id_articulo.'").val("'.$existencia.'");
						
						</script>';
						
					}
		
}


function eliminar($id_relacion){ // Funcion para elimnar 
global  $conex;

$delete_relacion = "DELETE FROM relaciones WHERE id_relacion = $id_relacion ";
		if (mysql_query($delete_relacion, $conex) or die(mysql_error()))
		{
			echo '<script>
				list_relaciones();
				
				</script>';
			
		}
		else 
		{
			//echo 0;
		}

}
?>