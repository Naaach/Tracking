<?php 

/**
 * Create a panel tab whith data grom dataabse
 * @param  [int] $id              
 * @param  [String] $name         
 * @param  [int] $status          
 * @param  [String] $comment      
 * @param  [date] $order_date     
 * @param  [date] $max_time       
 * @param  [date] $last_edit      
 * @param  [String] $tracking_number
 * @param  [String] $origin         
 * @param  [String] $tracking_web   
 * @return [HTML]                
 */
function create_table_panel($id, $name, $status, $comment, $order_date, $max_time, $last_edit, $tracking_number, $origin, $tracking_web) {
	return "
		<div class=\"panel panel-default\" id=\"panel-". $id ."\">
			<div class=\"panel-heading\">
				<h4 class=\"panel-title\">
				<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse" . $id . "\">". $name ."</a>
				<span class=\"tab-heading-status\" data-status=\"". $status ."\">Estado: <span class=\"circle\"></span></span>
				</h4>
			</div>
			<div id=\"collapse" . $id . "\" class=\"panel-collapse collapse\">
				<div class=\"panel-body\">
					<!-- Fechas -->
					<div class=\"row order-time\">
						<div class=\"col-md-3 text-center\">
							<h5>Fecha de pedido</h5>
							<br>
							<span>" . $order_date ."</span>
						</div>
						<div class=\"col-md-3 text-center\">
							<h5>Fecha limite</h5>
							<br>
							<span>" . $max_time ."</span>
						</div>
						<div class=\"col-md-3 text-center\" id=\"rest-order-time\">
							<h5>Restante</h5>
							<br>
							<span></span>
						</div>
						<div class=\"col-md-3 text-center\">
							<h5>Ultima actualización</h5>
							<br>
							<span>" . $last_edit ."</span>
						</div>
					</div>
					<!-- Info -->
					<div class=\"row info\">
						<!-- Comentarios -->
						<div class=\"col-md-6\" id=\"allInfo\">
							<h4>Datos de la compañia</h4>
							<div class=\"info\" id=\"tracking-container-". $id ."\">
							</div>
						</div>
						<div class=\"col-md-3\">
							<h4>Decripcción</h4>
							<p>" . $comment ."</p>
						</div>
						<!-- tracking -->
						<div class=\"col-md-3 tracking\">
							<div class=\"country\">
								<h5>País</h5>
								<span>" . $origin ."</span>
							</div>
							<div class=\"number\">
								<h5>Nº de seguimiento</h5>
								<span data-container-id=\"". $id ."\">" . $tracking_number . "</span>
							</div>
							<div class=\"Web\">
								<h5>Seguimiento web</h5>
								<a href=\"" . $tracking_web . "\" target=\"_blank\">Ver en la web</a>
							</div>
						</div>
					</div>
					<!-- Actions -->
					<div class=\"row actions\">
						<div class=\"col-md-12 text-center\">
							<button type=\"button\" class=\"btn btn-info\" data-id=\"" . $id ."\">Editar</button>
							<button type=\"button\" class=\"btn btn-success\" data-id=\"" . $id ."\">Recibido</button>
							<button type=\"button\" class=\"btn btn-primary\" data-id=\"" . $id ."\" >Problemas</button>
							<button type=\"button\" class=\"btn btn-danger deleteOrder\" data-id=\"" . $id ."\">Borrar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	";
}