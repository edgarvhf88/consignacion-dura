<html>
<?php include("../data/conexion.php"); ?>
    <head>
        <title></title>
        <link href="https://fonts.googleapis.com/css?family=Baloo+Bhaina" rel="stylesheet">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/estilos.css">
		<!-- -->
		
    </head>
<body>
   <script>
     function lista_categorias(){
var id_empresa = document.getElementById("select_empresa").value;

$("#div_id_empresa").html(id_empresa);
   jQuery.ajax({ 
				type: "POST",
				url: "categorias.php",
				data: {id_empresa:id_empresa},
				success: function(resultados)
				
				{ 
								
				$("#div_categorias").html(resultados);		
			
				}
			});
		return false;	
   
   
   };
   
   </script>  
    <center><strong><label class="titulo">Subir lista de articulos por empresa</label></strong></center>
    <p>
        <form action="subir_art_gen_dura.php" method="POST" enctype="multipart/form-data">
            <center>
			<h5>El archivo CSV deve contener 11 columnas(Clave microsip, Clave Cliente, Nombre, Descripcion, precio, nombre de la imagen, unidad de medida, Existencia, Maximo, Minimo y Reorden) <h6><a href='formato_lista_articulos_subir.csv'>Descargar csv en blanco</a></h6> </h5>
			<select class="form-control" name="select_empresa" id="select_empresa">
				<?php 	
					$lista_empresas = lista_empresas();
					foreach ($lista_empresas as $id => $empresa){
						echo '<option value="'.$id.'" >'.$empresa.'</option>';
					};
				?>
			</select>
												
			<select id="select_almacen" name="select_almacen" class="select form-control ">
				<?php 
					$lista_almacenes = lista_almacenes_consigna();
					foreach($lista_almacenes as $id_almacen => $almacen)
					{
					echo'<option value="'.$id_almacen.'">'.$almacen.'</option>';			
					}
				?>
			</select>
			<div class="list-group " style="overflow-y: scroll; height: 100px; width:350px;" id="div_categorias"></div><div id="div_id_empresa"></div>
			<input type="hidden" value="" id="txt_categorias" name="txt_categorias"id="txt_categorias"/>
            <table>
                <tr>
                    <td class="letra" width="250"><strong>Subir Archivo CSV:</strong></td>  
                    <td><input type="file" name="foto" id="foto"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="enviar" value="SUBIR" class="boton"></td>
                </tr>            
                </table>
            </center>
        </form>    
      <!--*************************************************************************************************** - -->       
    <center><strong><label class="titulo">Eliminar articulos de empresa</label></strong></center>
    <p>
        <form action="eliminar_art_empresa.php" method="POST" enctype="multipart/form-data">
            <center>
			<select class="form-control" name="select_empresa_delete" id="select_empresa_delete">
						                   			
													<?php 	
													$lista_empresas = lista_empresas();
															foreach ($lista_empresas as $id => $empresa){
																echo '<option value="'.$id.'" >'.$empresa.'</option>';
															};
													?>
													
						                   		</select>
			<select class="form-control" name="select_delete" id="select_delete">
			<option value="1" >Eliminar Articulos en la Empresa</option>';
			<option value="2" >Eliminar Movimientos en la Empresa</option>';
			</select>
			
            <table>
             
                <tr>
                    <td colspan="3" align="center"><input type="submit" name="enviar_delete" value="Eliminar Articulos" class="boton"></td>
                </tr>            
                </table>
            </center>
        </form>    
      <!--*************************************************************************************************** - -->  
	  
 <center><strong><label class="titulo">Clonar Almacen</label></strong></center>
    <p>
        <form action="clonar_almacen.php" method="POST" enctype="multipart/form-data">
            <center>
            <table>
                <tr>
                    <select class="select form-control" name="select_almacenes" id="select_almacenes">
					              			
						<?php 	
						
						$lista_almacenes = lista_almacenes_microsip();
						printf($lista_almacenes);
								foreach ($lista_almacenes as $id => $almacen){
									echo '<option value="'.$id.'" >'.$almacen.'</option>';
								};
						?>
					
					</select>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="clonar" value="CLONAR ALMACEN" class="boton"></td>
                </tr>            
                </table>
            </center>
        </form>    
      
      <!--*************************************************************************************************** - -->  
	  
   <!-- <center><strong><label class="titulo">IMPORTAR REGISTROS DESDE ARCHIVO .CSV</label></strong></center>
    <p>
        <form action="subir_archivo.php" method="POST" enctype="multipart/form-data">
            <center>
            <table>
                <tr>
                    <td class="letra" width="250"><strong>Subir Archivo CSV:</strong></td>  
                    <td><input type="file" name="foto" id="foto"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="enviar" value="SUBIR" class="boton"></td>
                </tr>            
                </table>
            </center>
        </form>    
      *************************************************************************************************** - -->    

	<script src="../assets/js/jquery-1.12.3.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
     
	<script> 
	$(document).ready(function(){
		$("#select_empresa").change(function(){
			
			lista_categorias();
			});			
    });
	
	lista_categorias();
	</script>
	 
    </body>
</html>
