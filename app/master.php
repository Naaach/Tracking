<?php

//Conexion con la base de datos
include('database/connect.php');

//Simple dom parser
include('third-party/simple_html_dom.php');

//snippets
include('snippets/tab-panel.php');

//Globals
$post = $_POST;


//Abrir conexion
$cxn = connect_db($db_info);
$table = $db_info['db_table'];



////////////////////////////////////
//		AJAX REQUEST
///////////////////////////////////

/**
 * Añadir un nuevo pedido a la base de datos
 * @param  [Array] $attrs [$_POST]
 * @return [HTML]        [order-panel]
 */
function newOrder($attrs) {
	global $cxn;

	if (!empty($attrs)) {

		$nombre = $attrs['addName'];
		$status = $attrs['addStatus'];
		$comment = $attrs['addComment'];
		$order_date = $attrs['addDate'];
		$origin = $attrs['addOrigin'];
		$shop = $attrs['addShop'];
		$tracking_number = $attrs['addTracking'];
		$max_time = $attrs['addMaxDays'];
		$tracking_web = "http://www.17track.net/es/track?nums=" . $tracking_number;
		$last_edit = date("Y-m-d H:i:s");
		

		$query = "INSERT INTO `orders` 
		(`id`, `name`, `status`, `comment`, `order_date`, `max_time`, `last_edit`, `tracking_number`, `origin`, `shop`, `tracking_web`) 
		VALUES (NULL, '$nombre', $status, '$comment', '$order_date', $max_time, '$last_edit', '$tracking_number', '$origin', '$shop', '$tracking_web');";
		
		if (mysqli_query($cxn, $query)) {
			//Recuperar los datos del registro introducido
			$query = "SELECT * FROM orders ORDER BY id DESC LIMIT 1;";

			if ($result = mysqli_query($cxn, $query)) {
				//While fetch_array
				while($row = mysqli_fetch_array($result)) {
					print create_table_panel($row['id'], $row['name'], $row['status'], $row['comment'], $row['order_date'], $row['max_time'], $row['last_edit'], $row['tracking_number'], $row['origin'], $row['shop'], $row['tracking_web']);
				} // End of while

			} else {
				echo false;
			}

			//Close conexion
			close_db($cxn);

		} else {
			echo false;
		}

	} else {
		echo false;
	}
}

if (isset($post['addOrder'])) {
	newOrder($cxn, $post);
}

/**
 * load data form database
 * 
 * @param  $conexion [mysqli conexion]
 * @param  $table [db table name]
 * @return $result [query result]
 */
function load_data() {
	global $cxn, $table;
	$query = "SELECT * FROM $table ORDER BY id DESC;";

	if ($result = mysqli_query($cxn, $query)) {

		//While fetch_array
		while($row = mysqli_fetch_array($result)) {
			print create_table_panel($row['id'], $row['name'], $row['status'], $row['comment'], $row['order_date'], $row['max_time'], $row['last_edit'], $row['tracking_number'], $row['origin'], $row['shop'], $row['tracking_web']);
		} // End of while

	} else {
		print 
		'<div class="alert alert-danger">
			<strong>Alerta!</strong> No se pudo obtener los datos: '. mysqli_error($conexion) .'
		</div>';
	}
}

/**
 * Delete order from database
 * 
 * @param  $id [Integer]
 * @return [boolena]
 */
function delete_order($id) {
	global $cxn, $table;

	$query = "DELETE FROM $table WHERE id = $id LIMIT 1;";

	if (mysqli_query($cxn, $query)) {
		echo true;
	} else {
		echo false;
	}

	close_db($cxn);
}

//Call functuons on ajax request
if (isset($_POST['ajaxRequest'])) {

	switch ($_POST['ajaxRequest']) {
		case 'addOrder':
			newOrder($post);
			break;
		case 'loadData':
			load_data();
			break;
		case 'deleteOrder':
			$id = $_POST['orderID'];
			delete_order($id);
			break;
		default:
			echo false;
			break;
	}

}