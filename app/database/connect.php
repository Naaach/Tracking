<?php 

$db_info = array(
	'user' => 'root',
	'password' => '',
	'server' => 'localhost',
	'db' => 'tracking',
	'db_table' => 'orders'
);


/**
 * @param  $data_dbn [array]
 * @return $con [mysqli connection]]
 */
function connect_db($data_db) {
	if ($con = @mysqli_connect($data_db['server'], $data_db['user'], $data_db['password'], $data_db['db'])) {
		return $con;	
	} else {
		print 
		'<div class="alert alert-danger">
			<strong>Alerta!</strong> No se pudo conectar con la Base de Datos: '. mysqli_connect_error() .'
		</div>';
	} 	
}


/**
 * @param  $con [mysqli connection]
 * @return [boolean]
 */
function close_db($con) {
	if (mysqli_close($con)) {
		return true;
	} else {
		print 
		'<div class="alert alert-danger">
			<strong>Alerta!</strong> No se pudo cerrar la conexion: '. mysqli_connect_error() .'
		</div>';
	}
}



