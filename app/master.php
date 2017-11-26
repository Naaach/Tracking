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
		(`id`, `name`, `status`, `last_status`, `comment`, `order_date`, `max_time`, `last_edit`, `tracking_number`, `origin`, `shop`, `tracking_web`)
		VALUES (NULL, '$nombre', $status, '', '$comment', '$order_date', $max_time, '$last_edit', '$tracking_number', '$origin', '$shop', '$tracking_web');";

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
		print false;

	}

	close_db($cxn);
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

/**
 * Get the date order fron the server
 * @param  [int] $id id of the row
 * @return [JSON]
 */
function get_data_from_id($id) {
	global $cxn, $table;

	$query = "SELECT * FROM $table WHERE id = $id LIMIT 1;";
	if ($result = mysqli_query($cxn, $query)) {
		$rows = array();
		while($row = mysqli_fetch_array($result)) {
			$rows[] = $row;
		}

		print json_encode($rows);
	} else {
		print false;
	}

	close_db($cxn);
}

function update_order($attrs){
	global $cxn, $table;

	$id = $attrs['orderID'];
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

	$query = "UPDATE `orders` SET `name` = '$nombre', `status` = $status, `last_status` = '', `comment` = '$comment', `order_date` = '$order_date', `max_time` = $max_time, `last_edit` = '$last_edit', `tracking_number` = '$tracking_number', `shop` = '$shop', `origin` = '$origin', `tracking_web` = '$tracking_web' WHERE id = $id LIMIT 1;";


	if (mysqli_query($cxn, $query)) {
		$res = array(
			'resStatus' => true
		);

		print json_encode($res);
	} else {
		$res = array(
			'query' => $query,
			'resStatus' => false
		);

		print json_encode($res);

	}
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
			case 'updateOrder':
				update_order($_POST);
				break;
		default:
			echo false;
			break;
	}

}

if (isset($_GET['ajaxRequest'])) {

	switch ($_GET['ajaxRequest']) {
		case 'dataFromId':
			$id = $_GET['orderID'];
			get_data_from_id($id);
			break;
		default:
			echo false;
			break;
	}

}
