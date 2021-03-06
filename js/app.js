'use strict';

$(document).ready(function() {

	/* Globals */
	var $body = $('body');

	/* Modals */
	var $addOrderModal = $('section#addOrder-modal');
	var $modalsBg = $('div#modal-bg');

	// form
	var $btnAddOrder = $('section#addOrder-modal form button[name="addOrder"]');
	//Obtener los campos del formulario
	let name = $('section#addOrder-modal form input#addName'),
			date = $('section#addOrder-modal form input#addDate'),
			origin = $('section#addOrder-modal form input#addOrigin'),
			shop = $('section#addOrder-modal form select#addShop'),
			tracking_number = $('section#addOrder-modal form input#addTracking'),
			max_days = $('section#addOrder-modal form input#addMaxDays'),
			status = $('section#addOrder-modal form select#addStatus'),
			comment = $('section#addOrder-modal form textarea[name="addComment"]');

	//Buttons
	var $btnAddOrderModal = $('button.btn-addOrder-modal');
	var $btnCloseModal = $('button.closeModal');



	$btnAddOrderModal.click(openModal);

	function openModal() {
		$modalsBg.css({'display' : 'block'}).animate({'opacity' : 1}, 400);
		$addOrderModal.css({'display' : 'block'}).animate({'opacity' : 1}, 500);
		$body.css({'overflow-x' : 'hidden'});
	}

	//Cerrar el modal
	function cerrarModal(){
		$addOrderModal.animate({'opacity' : 0}, 400);
		$modalsBg.animate({'opacity' : 0}, 500, function() {
			$addOrderModal.css({'display' : 'none'});
			$(this).css({'display' : 'none'});
			$body.css({'overflow' : 'auto'});
		});

		$btnAddOrder.attr({
			'data-update': false,
			'data-id': ""
		});
	}


	$modalsBg.click(cerrarModal);
	$btnCloseModal.click(cerrarModal);

	/* Notify.js defaults */
	$.notify.defaults({
	  position: 'top right',
	  globalPosition: 'bottom left',
	  showAnimation: 'slideDown',
	  showDuration: 1000,
	  hideAnimation: 'slideUp',
	  hideDuration: 200
	});

	/* __17Track API COnfiguration */

	/**
	 * configure the track sistem for panel
	 * @param  {[type]} id [description]
	 * @return {[type]}    [description]
	 */
	function doTrack(id) {
		let panel = document.querySelector('div'+ id +' div.panel-body > div.info > div.tracking > div.number > span');

		let containerID = panel.getAttribute('data-container-id');
		let number = panel.innerHTML;

		YQV5.trackSingle({
        YQ_ContainerId: "tracking-container-" + containerID,
        YQ_Height: 300,
        YQ_Fc: "0",
        YQ_Lang: "es",
        YQ_Num: number
    });
	}

	/**
	 * Load tracking system on open panel
	 * @param  {$element} el jQuery element
	 * @return void
	 */
	function loadTrackOnOpen(el) {
		let aria_expanded = $(this).attr('aria-expanded');
		if (aria_expanded || aria_expanded == undefined ) {
			let id = $(el).attr('href');
			doTrack(id);
		}
	}

	/**
	 * Check if the tab has a iframe
	 * @param  {DOM element}  el - tab-heading
	 * @return {Boolean}
	 */
	function hasTrackingWindow(el) {
		let containerId = "#tracking-container-" + el.attr('href').substring(9, el.attr('href').length);
		return ($(containerId).children().length > 0);
	}

	/**
	 * Delete the iframe on the tab
	 * @param  {DOM element} el - tab-heading
	 * @return {[type]}
	 */
	function deleteTrackingWindow(el) {
		let containerId = "#tracking-container-" + el.attr('href').substring(9, el.attr('href').length);
		$(containerId).children('iframe').remove();
	}

	function timingOrder(id) {
		let timeRow = $('div'+ id +' div.order-time'),
				order_date = timeRow.find('div.order_date span').html().split('-'),
				max_time = timeRow.find('div.max_time span').html(),
				rest_time = timeRow.find('div.rest-order-time span');

		let limitDate = new Date(order_date[0], (order_date[1]-1), order_date[2]);
		limitDate.setDate(limitDate.getDate() + parseInt(max_time));

		setInterval(function() {
			let actualDate = new Date();

			var restTime =  limitDate.getTime() - actualDate.getTime();

			let days = Math.floor(restTime / (1000 * 60 * 60 * 24)),
		  		hours = Math.floor((restTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
		  		minutes = Math.floor((restTime % (1000 * 60 * 60)) / (1000 * 60)),
		  		seconds = Math.floor((restTime % (1000 * 60)) / 1000);

			rest_time.html(days + ' - ' + hours +':'+ minutes +':'+ seconds);

		}, 1000);

	}

	function editOrder() {
		let $btnEditOrder = $('button.modal-edit');
		$btnEditOrder.click(function(event) {
			let id = $(this).attr('data-id');
			$btnAddOrder.attr({
				'data-update': true,
				'data-id': id
			});
			getDataFromId(id);
		});
	}

	/**
	 * Execute function to a especific panel when it opens
	 * @return void
	 */
	function onOpenPanel() {
		let panel = $('div.panel-heading > h4.panel-title > a');

		panel.on('click', function(e) {

			let self = $(this);
			let id = self.attr('href');

			// Show thw tracking info
			if (hasTrackingWindow(self)) {
				deleteTrackingWindow(self);
			} else {
				setTimeout(loadTrackOnOpen, 120, self);
			}

			// Edit order
			editOrder();

			// Refresh the delete function
			deleteOrderAjax();

			// Start timing the rest for the delivery
			timingOrder(id);

		});

	}

	/* ____Validar los datos del formulario y enviar al servidor____ */
	$btnAddOrder.click(function(event) {

		let isUpdate = $(this).attr('data-update');
		let formValues = validateForm();
		let isValidForm = formValues.formStatus;

		if (isValidForm) {
			if (eval(isUpdate)) {
				// Update especific row
				formValues.id = $btnAddOrder.attr('data-id');
				updateOrderAjax(formValues);
			} else {
				// Add a new order
				addOrderAjax(formValues);
			}
		}

	});	// btnAddOrder

	/**
	 * Validate form fields
	 * @return {obj} params of the form
	 */
	function validateForm() {

		//Obtener los valores de los campos
		let nameVal = name.val(),
				dateVal = date.val(),
				originVal = origin.val(),
				shopVal = shop.val(),
				tracking_numberVal = tracking_number.val(),
				max_daysVal = max_days.val(),
				statusVal = status.val(),
				commnetVal = comment.val() || "";

		//Comprobantes
		let formularioValido = true,
				nameValido,
				dateValido,
				originValido,
				shopValido,
				tracking_numberValido,
				max_daysValido,
				statusValido,
				commnetValido;

		//nombre
		if (!nameVal) {
			name.addClass('inputWarning');
			nameValido = false;
		} else {
			name.removeClass('inputWarning');
			nameValido = true;
		}

		//Fecha de pedido
		if (!dateVal) {
			date.addClass('inputWarning');
			dateValido = false;
		} else {
			dateVal = dateVal.split('-');
			let d = new Date(dateVal[0], dateVal[1], dateVal[2]);

			if (d.getMonth() == dateVal[1]) {
				dateVal = dateVal.join('-');
				date.removeClass('inputWarning');
				dateValido = true;
			} else {
				dateValido = false;
				date.val("");
				date.addClass('inputWarning');
				date.attr('placeholder', 'Fecha invalida: aaaa-mm-dd');
			}
		}

		//Pais de origen
		if (!originVal) {
			origin.addClass('inputWarning');
			originValido = false;
		} else {
			origin.removeClass('inputWarning');
			originValido = true;
		}

		//Tienda Valida
		if (!shopVal) {
			shop.addClass('inputWarning');
			shopValido = false;
		} else {
			shop.removeClass('inputWarning');
			shopValido = true;
		}

		//Numero de tracking
		if (!tracking_numberVal) {
			tracking_number.addClass('inputWarning');
			tracking_numberValido = false;
		} else {
			tracking_number.removeClass('inputWarning');
			tracking_numberValido = true;
		}

		//Maximo de dias
		if (!max_daysVal) {
			max_daysValido = false;
			max_days.addClass('inputWarning');
		} else {
			if (!isNaN(parseInt(max_daysVal))) {
				max_daysValido = true;
				max_days.removeClass('inputWarning');
			} else {
				max_daysValido = false;
				max_days.addClass('inputWarning');
			}
		}

		//Estado
		if (!isNaN(parseInt(statusVal))) {
			statusValido = true;
			status.removeClass('inputWarning');

		} else {
			statusValido = false;
			status.addClass('inputWarning');
		}

		//Confirmar los datos del formularios
		if (nameValido && dateValido && originValido && shopValido && tracking_numberValido && max_daysValido && statusValido) {
			return {
				formStatus: true,
				nameVal: nameVal,
				statusVal: statusVal,
				commnetVal: commnetVal,
				dateVal: dateVal,
				originVal: originVal,
				shopVal: shopVal,
				tracking_numberVal: tracking_numberVal,
				max_daysVal: max_daysVal
			};
		} else {
			return {
				formStatus: false
			};
		}

	}

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

				//Functions when the panel is open
				onOpenPanel();
			} else {
				$('#panels-view').html('No hay datos para mostrar');

				//Notification
				$.notify("Error cargando en contenido", {
					className: "error"
				});
			}
		}
	});

	/* ____Borrar un registro de la base de datos____ */
	function deleteOrderAjax() {

		var $btnDeleteOrder = $('div.panel-body div.actions button.deleteOrder');

		$btnDeleteOrder.unbind('click');
		$btnDeleteOrder.click(function (e) {

			let id = $(this).attr('data-id');
			let pregunta = confirm("¿Seguro que quieres borrar el pedido?");

			if (pregunta) {

				$.ajax({
					data: {
						'ajaxRequest' : 'deleteOrder',
						'orderID' : id
					},

					url:'/app/master.php',
					type: 'POST',

					success: function(response) {
						if (response) {
							//Hide the delete tab
							$('div#panel-' + id).animate({'height' : 0 + 'px'}, 1000, function() {
								$(this).css({'display' : 'none'});
							});

							//Notification
							$.notify("El pedido ha sido borrado de la lista", {
								className: "success"
							});

						} else {
							alert("no se ha podido borrar el campo: " + id + response);

							//Notification
							$.notify("Ocurrio un error durante el borrado", {
								className: "error"
							});

						}
					}
				});
			}
		});
	}

	/* ____[JSON] Obtener los datos de un pedido____ */
	function getDataFromId(id) {
		$.ajax({
			data: {
				ajaxRequest: 'dataFromId',
				orderID: id
			},
			dataType: 'json',
			url: '/app/master.php',
			type: 'GET',
			success: function(response) {
				if (response) {
					let order = response[0];

					openModal();

					// modificar los valores
					name.val(order.name);
					date.val(order.order_date);
					origin.val(order.origin);
					shop.val(order.shop);
					tracking_number.val(order.tracking_number);
					max_days.val(parseInt(order.max_time));
					status.val(order.status);
					comment.val(order.comment);

				} else {
					//Notification
					$.notify("Ocurrio un error obteniendo los datos", {
						className: "error"
					});
				}
			}

		});
	}

	/* ____Enviar los datos para un nuevo reguistro____ */
	function addOrderAjax(values) {
		$.ajax({
				data: {
					ajaxRequest: 'addOrder',
					addName: values.nameVal,
					addStatus: values.statusVal,
					addComment: values.commnetVal,
					addDate: values.dateVal,
					addOrigin: values.originVal,
					addShop: values.shopVal,
					addTracking: values.tracking_numberVal,
					addMaxDays: values.max_daysVal
				},

				url:'/app/master.php',
				type: 'POST',

				success: function(response) {
					if (response) {
						$('#panels-view').prepend(response);

						//Notification
						$.notify("Nuevo pedido insertado", {
							className: "success"
						});

						//cerrar el modal
						cerrarModal();

						//Functions when the panel is open
						onOpenPanel();

					} else {
						//Notification
						$.notify("Ocurrio un error insertando el pedido", {
							className: "error"
						});
					}
				}
			});
	}

	/* ____Update ordrer ajax____ */
	function updateOrderAjax(values) {

		$.ajax({
			data: {
				ajaxRequest: 'updateOrder',
				orderID: values.id,
				addName: values.nameVal,
				addStatus: values.statusVal,
				addComment: values.commnetVal,
				addDate: values.dateVal,
				addOrigin: values.originVal,
				addShop: values.shopVal,
				addTracking: values.tracking_numberVal,
				addMaxDays: values.max_daysVal
			},
			type: 'POST',
			url: '/app/master.php',
			dataType: 'json',
			success: function(response) {
				if (response.resStatus) {

					cerrarModal();
					//Notification
					$.notify("Tu pedido se ha actualizado correctamente", {
						className: "success"
					});

				} else {
					//Notification
					$.notify("Ocurrio un error insertando el pedido", {
						className: "error"
					});
				}
			}

		});
	}

	/////////////////////
	//		WORKERS
	////////////////////

}); //document.ready
