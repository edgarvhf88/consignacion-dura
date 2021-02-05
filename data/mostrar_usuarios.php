<?php include("conexion.php"); 

		$id_usuario = '';
				  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
	  $tipo_usuario = $_POST['tipo'];
      
		mostrar_usuarios($tipo_usuario);
		}
     function mostrar_usuarios($tipo_usuario){ 
global $database_conexion, $conex;

if ($tipo_usuario == '0'){
$consulta_usuarios = "SELECT * FROM usuarios order by tipo_usuario asc";
}
else
{
$consulta_usuarios = "SELECT * FROM usuarios WHERE tipo_usuario='$tipo_usuario' order by tipo_usuario asc";	
}
$resultado = mysql_query($consulta_usuarios, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);


if ($total_rows > 0){ // con resultados


echo '<div class="col-md-12">
				                    <table id="mostrar_usuarios" class="table table-striped table-responsive table-bordered table-hover">
				                    	<thead>
				                    		<tr class="info">
				                    			<th>Empresa</th>
				                    			<th>Tipo de Usuario</th>
				                    			<th>Nombre</th>
				                    			<th>Apellido</th>
				                    			<th>Nombre de usuario</th>
				                    			<th>Correo</th>
				                    			<th>Correo</th>
				                    			<th>Contraseña</th>
				                    			<th>Departamento</th>
				                    		</tr>
				                    	</thead><tbody>';
				                    		$tipo_usuario = '';						
				                    		while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
				                    		{
				                    		$contrasena = $row2['contrasena'];
				                    		switch($row2['tipo_usuario'])
				                    		{
				                    			case 1:
				                    			$tipo_usuario = "Admin";
												$contrasena = "************";
				                    			break;
				                    			case 2:
				                    			$tipo_usuario = "Comprador(Requisitor)";
				                    			break;
				                    			case 3:
				                    			$tipo_usuario = "Surtidor";
				                    			break;
				                    			case 4:
				                    			$tipo_usuario = "Almacensita";
				                    			break;
				                    			case 5:
				                    			$tipo_usuario = "Supervisor";
				                    			break;
				                    			case 11:
				                    			$tipo_usuario = "Facturacion";
				                    			break;
				                    			case 17:
				                    			$tipo_usuario = "Admin Traspasos"; // Facturacion y Traspasos
				                    			break;
				                    			
				                    		}
				                    			
				                    		 echo '<tr id="trusuario_'.$row2['id'].'" class="lista_usuarios">
											 
				                    			<td>'.EMPRESA_NOMBRE($row2['id_empresa']).'</td>
				                    			<td>'.$tipo_usuario.'</td>
				                    			<td>'.$row2['nombre'].'</td>
				                    			<td>'.$row2['apellido'].'</td>
				                    			<td>'.$row2['username'].'</td>
				                    			<td>'.$row2['correo'].'</td>
				                    			<td>'.$contrasena.'</td>
				                    			<td>'.PUESTO_NOMBRE($row2['id_puesto']).'</td>
				                    			<td>'.DEPARTAMENTO_NOMBRE($row2['id_departamento']).'</td>
				                    		</tr>
				                    												';
				                    							
				                    							
				                    					
				                    		
				                    							
				                    		}				
				                    		 echo ' </tbody></table></div>';
 
 echo '<script> 
	$(document).ready(function(){
                $(".lista_usuarios").dblclick(function(){
                               var tr_id = $(this).attr("id")
                         							   
							   var arr_id = tr_id.split("_");
							   var id_usuario = arr_id[1];
							   
							   $("#txt_id_usuario").val(id_usuario);
							   datos_usuario(id_usuario);
							   
							   
                });
                $("#mostrar_usuarios").DataTable();
				
		});
</script>';  

 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No existen Usuarios Registrados</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>





                    	
                    	
                