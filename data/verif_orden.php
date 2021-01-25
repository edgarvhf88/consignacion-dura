<?php include("conexion.php"); 
	$id_usuario_activo = "";
	if ((isset($_SESSION["logged_user"])) &&($_SESSION["logged_user"] != ''))
	{
		$id_usuario_activo = $_SESSION["logged_user"];
	}
		if ($id_usuario_activo != "")
		{
			$fecha_orden = $_POST['fecha_orden'];
			$folio_orden = $_POST['folio_orden'];
			$requisitor = $_POST['requisitor'];
			$comprador = $_POST['comprador'];
			$almacen = $_POST['almacen'];
			$orden_id = $_POST['orden_id'];
			verif_orden($id_usuario_activo,$folio_orden,$fecha_orden,$requisitor,$comprador,$almacen,$orden_id);
		}
		
	  		 
function verif_orden($id_usuario_activo,$folio_orden,$fecha_orden,$requisitor,$comprador,$almacen,$orden_id)
{ 
	global $conex;

		
	
	if ($orden_id == "")
	{ // si no existe el dato orden_id entonces verifica el folio de la orden
	
		$consulta = "SELECT 
				ord.id_oc as id_oc,
				ord.fecha_oc as fecha_oc,
				ord.fecha_creacion as fecha_creacion,
				ord.id_creador as id_creador,
				ord.requisitor as requisitor,
				ord.comprador as comprador,
				ord.almacen_id as almacen_id,
				ord.estatus as estatus,
				ord.req_factura as req_factura,
				alm.almacen as almacen
				FROM ordenes ord 
				LEFT JOIN almacenes alm ON alm.almacen_id = ord.almacen_id
				WHERE ord.folio = '$folio_orden' AND cancelado = 'N' ";
			$resultado = mysql_query($consulta, $conex) or die(mysql_error());
			$row = mysql_fetch_assoc($resultado);
			$total_rows = mysql_num_rows($resultado);
		if ($total_rows > 0)
		{ // si lo encuentra validara el estatus de la orden si esta con solicitud de factura no podra se modificada si no preguntara si desea continuar capturandola
			$fecha_oc = $row['fecha_oc'];
			$requisitor = $row['requisitor'];
			$comprador = $row['comprador'];
			$almacen_id = $row['almacen_id'];
			$id_oc = $row['id_oc'];
			//$estatus = $row['estatus'];
			$mostrar_btn = '';
			if ($row['estatus'] == 0)
			{
				$estatus = "Capturando";
			}
			else if ($row['estatus'] == 1)
			{
				$estatus = "Guardado";
			}
			else if ($row['estatus'] == 2) //
			{
				$estatus = "Remisionado";
			}
			else if ($row['estatus'] == 3) //
			{
				$estatus = "Facturado";
			}
			else if ($row['estatus'] == 4) //
			{
				$estatus = "Parcial";
			}
			if ($row['req_factura'] == "NO")
			{ 
				if ($almacen_id != "")
				{
					$mostrar_btn = '$("#btn_add_partida").show(); $("#btn_guardar_oc").show();    $("#btn_guardar_oc_abierta").show();           $("#btn_adjuntar_file").show();';
				}
				else
				{
					$mostrar_btn = '$("#btn_add_partida").hide();      $("#btn_guardar_oc").hide();    $("#btn_guardar_oc_abierta").hide();           $("#btn_adjuntar_file").hide();';
				}
				echo '<script> 
					var mostrar_dat = confirm("Ya se ha capturado este numero de orden, Desea continuar capturandola?");
					if (mostrar_dat == false)
					{	
						//alert("no se mostraran los datos cambie de numero de orden");
						//$("#txt_orden").focus(); 
						//$("#txt_orden").val(""); 
						orden_nueva();
					}
					else
					{ 
						$("#txt_fecha_orden").val("'.$fecha_oc.'");
						$("#txt_requisitor").val("'.$requisitor.'");
						$("#txt_comprador").val("'.$comprador.'");
						$("#select_almacen_oc").val("'.$almacen_id.'");
						$("#txt_orden_id").val("'.$id_oc.'");
						$("#td_estatus_oc").html("'.$estatus.'");
						$("#btn_cancelar_oc").show();
						'.$mostrar_btn.'
						lista_oc_det();
					}
					</script>'; 
			}
			else if ($row['req_factura'] == "SI")
			{
				echo '<script> 
				alert("La orden esta en proceso de facturacion, ya no se puede modificar a menos que le cancelen la solicitud de factura");
				orden_nueva();
				</script>';
			}	
			
		} 
		else /// sin resultados
		{
				//   INSERTA
				$fecha_hora_creacion = date("Y-m-d H:i:s");
				$estatus = "Capturando";
				$rec_fact = "NO";
			$insert_orden = "INSERT INTO ordenes (folio,fecha_creacion,id_creador,estatus,req_factura,almacen_id) VALUES 
			('$folio_orden','$fecha_hora_creacion','$id_usuario_activo','$estatus','$rec_fact','$almacen')";
			if (mysql_query($insert_orden, $conex) or die(mysql_error()))
			{
				$id_oc =  mysql_insert_id();
				//echo '<script> console.log("se inserto inventario_det"); </script>';
				echo '<script> 
				$("#btn_cancelar_oc").show();
				
				$("#txt_orden_id").val("'.$id_oc.'");
				$("#td_estatus_oc").html("Capturando");
				lista_oc_det();
			</script>'; 
			}	
				
					
				
		
		
		}
		
		mysql_free_result($resultado); 
	} 
	else
	{ // si orden_id contiene un valor id entonces solo actualiza los datos
		
		$consulta2 = "SELECT 
				ord.id_oc as id_oc,
				ord.req_factura as req_factura,
				alm.almacen as almacen
				FROM ordenes ord 
				LEFT JOIN almacenes alm ON alm.almacen_id = ord.almacen_id
				WHERE ord.folio = '$folio_orden' AND ord.id_oc <> '$orden_id' AND ord.cancelado = 'N'";
			$resultado2 = mysql_query($consulta2, $conex) or die(mysql_error());
			$row2 = mysql_fetch_assoc($resultado2);
			$total_rows2 = mysql_num_rows($resultado2);
			$folio_actual = folio_orden($orden_id);
		if ($total_rows2 > 0)
		{ 
			if ($row2['req_factura'] == "NO")
			{ 
				
				echo '<script> 
						
						alert("El folio que intenta asignar ya fue registrado anteriormente");
						$("#txt_orden").val("'.$folio_actual.'"); 
						$("#txt_orden").focus(); 
					
					</script>'; 
			}
			else if ($row2['req_factura'] == "SI")
			{
				echo '<script> 
				alert("La orden esta en proceso de facturacion, ya no se puede modificar a menos que le cancelen la solicitud de factura");
				$("#txt_orden").val("'.$folio_actual.'"); 
						$("#txt_orden").focus(); 
				</script>';
			}
		}
		else
		{ /// al actualizar lo actuliza los totales
			/// requerir calculo de totales
			$calc_totales = totales_orden($orden_id);
			$update_oc = "UPDATE ordenes 
								SET folio='$folio_orden',
								fecha_oc='$fecha_orden',
								requisitor='$requisitor',
								subtotal='$calc_totales',
								total='$calc_totales',
								comprador='$comprador',
								almacen_id='$almacen'
								WHERE id_oc='$orden_id'";
			
			if (mysql_query($update_oc, $conex) or die(mysql_error()))
			{
				if ($almacen != ""){
					echo '<script> $("#btn_add_partida").show();$("#btn_guardar_oc").show();$("#btn_guardar_oc_abierta").show();$("#btn_adjuntar_file").show(); </script>';
				}
				else
				{
					echo '<script> $("#btn_add_partida").hide();$("#btn_guardar_oc").hide();$("#btn_guardar_oc_abierta").hide();$("#btn_adjuntar_file").hide(); </script>';
				}
				
				echo '<script> $("#td_estatus_oc").html("Capturando"); </script>';
				//
			}	
		}
	}
 
}




?>
