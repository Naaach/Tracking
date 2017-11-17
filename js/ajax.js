/////////////////////
//		AJAX 
////////////////////

/* ____Cargar los datos de la bbdd____ */
$.ajax({
	data: {
		'ajaxRequest' : 'loadData'
	},
	url: '/app/master.php',
	type: 'POST',

	success: function(response) {
		if (response) {
			//inner the HTML on the view
			$('#panels-view').html(response);
			deleteOrderAjax();
		} else {
			$('#panels-view').hmtl('No hay datos para mostrar');
		}
	}
});


/* Borrar un registro de la base de datos */
function deleteOrderAjax() {

	var $btnDeleteOrder = $('div.panel-body div.actions button.deleteOrder');
	$btnDeleteOrder.on('click', function (e) {

		let id = $(this).attr('data-id');
		let pregunta = confirm("¿Seguro que quieres borrar el pedido?");

		if (pregunta) {

			$.ajax({
				data: {
					'ajaxRequest' : 'deleteOrder',
					'orderID' : id}
					,
				url:'/app/master.php',
				type: 'POST',
				
				success: function(response) {
					if (response) {
						//Hide the delete tab
						$('div#panel-' + id).animate({'height' : 0 + 'px'}, 1000, function() {
							$(this).css({'display' : 'none'});
						});
					} else {
						alert("no se ha podido borrar el campo: " + id + response);
						console.log(response);
					}
				}
			});
		}
	});

}

