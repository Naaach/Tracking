<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta author="Nacho">
		<title>Tracking	</title>
		<!-- CSS CDN -->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<!-- CSS -->
		<link rel="stylesheet" href="/css/normalize.css">
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/style.css">
	</head>
	<body>
		<header>
			<div class="container-fluid header-container">
				<div class="row">
					<div class="col-md-3 col-md-push-2 title">
						<h3><i class="fa fa-paper-plane"></i>Tracking</h3>
					</div>
					<div class="col-md-3 col-md-push-6">
						<button type="button" class="btn btn-info btn-addOrder-modal">Añadir pedido</button>
					</div>
				</div>
			</div>
		</header>
		<div id="content">
			<section id="orders">
				<div class="container">
					<div class="row">
						<!-- Panels view -->
						<div class="col-md-10 col-md-push-1">
							<div class="panel-group" id="panels-view"></div>
						</div>
						<!-- End of Panels view -->
					</div>
				</div>
			</section>
		</div>
	</body>
	<footer>
		<!-- modals background -->
		<div id="modal-bg"></div>
		<!-- add order modal view -->
		<section id="addOrder-modal">
			<div class="container">
				<div class="col-md-8 col-md-push-2">
					<form action="" class="addOrder">
						<div class="row">
							<div class="col-md-6">
								<div class="form-goup">
									<label for="addName">Introduce el nombre del producto:</label>
									<input type="text" name="addName" id="addName" class="form-control" placeholder="Nombre">
								</div>
								<div class="form-goup">
									<label for="addDate">Introduce la fecha del pedido:</label>
									<input type="text" name="addDate" id="addDate" class="form-control" placeholder="aaaa-mm-dd">
								</div>
								<div class="form-goup">
									<label for="addOrigin">Introduce el origen del envio</label>
									<input type="text" name="addOrigin" id="addOrigin" class="form-control" placeholder="País de origen">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-goup">
									<label for="addTracking">Introduce el Nº de tracking:</label>
									<input type="text" name="addTracking" id="addTracking" class="form-control" placeholder="Nº Tracking">
								</div>
								<div class="form-goup">
									<label for="addMaxDays">Plazo mximo de dias:</label>
									<input type="number" name="addMaxDays" id="addMaxDays" class="form-control" placeholder="Max dias">
								</div>
								<div class="form-goup">
									<label for="addStatus" >Introduce le nombre del producto:</label>
									<select id="addStatus" name="addStatus" class="form-control">
										<option value="0">Procesando</option>
										<option value="1">Enviado</option>
										<option value="2">En pais destinatario</option>
										<option value="3">En reparto</option>
										<option value="4">Entragado</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="addComment">Añade una descripción al producto</label>
									<textarea name="addComment" class="form-control" rows="7" placeholder="Comentario"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-danger closeModal">Cerrar</button>
								<button type="button" class="btn btn-success"  name="addOrder">Añadir</button>
								<button type="button" class="btn btn-info closeModal"  name="close" name="borrarAddOrder">Limpiar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</section>
		<!-- scripts -->
		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/js/notify.js"></script>
		<script type="text/javascript" src="/js/app.js"></script>
	</footer>
</html>