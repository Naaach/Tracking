$(document).ready(function() {

	/* Globals */
	var $body = $('body');

	/* Modals */
	var $addOrderModal = $('section#addOrder-modal');
	var $modalsBg = $('div#modal-bg');

	//Buttons
	var $btnAddOrder = $('button.btn-addOrder-modal');
	var $btnCloseModal = $('button.closeModal');


	$btnAddOrder.on('click', function(e) {
		$modalsBg.css({'display' : 'block'}).animate({'opacity' : 1}, 400);
		$addOrderModal.css({'display' : 'block'}).animate({'opacity' : 1}, 500);
		$body.css({'overflow-x' : 'hidden'});
	});

	//Cerrar el modal
	function cerrarModal(){
		$addOrderModal.animate({'opacity' : 0}, 400);
		$modalsBg.animate({'opacity' : 0}, 500, function() {
			$addOrderModal.css({'display' : 'none'});
			$(this).css({'display' : 'none'});
			$body.css({'overflow-x' : 'auto'});
		});	
	}
	$modalsBg.on('click', cerrarModal);
	$btnCloseModal.on('click', cerrarModal);

}); //document.ready

