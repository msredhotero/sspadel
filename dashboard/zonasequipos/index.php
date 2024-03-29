<?php

session_start();

if (!isset($_SESSION['usua_predio']))
{
	header('Location: ../../error.php');
} else {


include ('../../includes/funcionesUsuarios.php');
include ('../../includes/funciones.php');
include ('../../includes/funcionesHTML.php');
include ('../../includes/funcionesJugadores.php');
include ('../../includes/funcionesEquipos.php');
include ('../../includes/funcionesGrupos.php');
include ('../../includes/funcionesZonasEquipos.php');
include ('../../includes/funcionesDATOS.php');

$serviciosUsuario 	= new ServiciosUsuarios();
$serviciosHTML 		= new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosJugadores = new ServiciosJ();
$serviciosEquipos	= new ServiciosE();
$serviciosGrupos	= new ServiciosG();
$serviciosZonasEquipos	= new ServiciosZonasEquipos();
$serviciosDatos	= new ServiciosDatos();

$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu(utf8_encode($_SESSION['nombre_predio']),"ZonasEquipos",$_SESSION['refroll_predio'],utf8_encode($_SESSION['torneo_predio']));



$resHorarios = $serviciosFunciones->TraerHorarios($_SESSION['idtorneo_predio']);


/////////////////////// Opciones para la creacion del formulario  /////////////////////
$tabla 			= "dbtorneoge";

$lblCambio	 	= array("refgrupo","refequipo","reftorneo");
$lblreemplazo	= array("Categorias","Equipos","Torneo");

$resTipoTorneo 	= $serviciosFunciones->TraerTorneosActivo($_SESSION['idtorneo_predio']);

$cadRef = '';
$idtorneo = 0;
while ($rowTT = mysql_fetch_array($resTipoTorneo)) {
	$idtorneo = $rowTT[0];
	$cadRef = $cadRef.'<option value="'.$rowTT[0].'">'.utf8_encode($rowTT[1]).'</option>';
	
}


$resZonas 	= $serviciosGrupos->TraerGrupos();

$cadRef2 = '';
while ($rowZ = mysql_fetch_array($resZonas)) {
	$cadRef2 = $cadRef2.'<option value="'.$rowZ[0].'">'.utf8_encode($rowZ[1]).'</option>';
	
}


$resEquipos 	= $serviciosZonasEquipos->TraerEquiposSinZona();

$cadRef3 = '';
while ($rowE = mysql_fetch_array($resEquipos)) {
	$cadRef3 = $cadRef3.'<option value="'.$rowE[0].'">'.utf8_encode($rowE[1]).'</option>';
	
}

$refdescripcion = array(0 => $cadRef2,1=>$cadRef3,2=>$cadRef);
$refCampo	 	= array("refgrupo","refequipo","reftorneo"); 




$resEquiposRR 	= $serviciosEquipos->TraerEquipos();

$cadEquiposR = '';
while ($rowER = mysql_fetch_array($resEquiposRR)) {
	$cadEquiposR = $cadEquiposR.'<option value="'.$rowER[0].'">'.utf8_encode($rowER[1]).'</option>';
	
}


$refultimaFecha = $serviciosFunciones->TraerUltimaFechaActivo();

if (mysql_num_rows($refultimaFecha)>0) {
	$ultimaFecha = mysql_result($refultimaFecha,0,0);
} else {
	$ultimaFecha = 0;
}


$resFechas 	= $serviciosFunciones->TraerFecha();

$cadRefFR = '';
while ($rowFR = mysql_fetch_array($resFechas)) {
	$cadRefFR = $cadRefFR.'<option value="'.$rowFR[0].'">'.utf8_encode($rowFR[1]).'</option>';
	
}
//////////////////////////////////////////////  FIN de los opciones //////////////////////////




/////////////////////// Opciones para la creacion del view  /////////////////////
$cabeceras 		= "	<th>Categorias</th>
				<th>Equipos</th>
				<th>Torneo</th>
				<th>Prioridad</th>";

//////////////////////////////////////////////  FIN de los opciones //////////////////////////




$formulario 	= $serviciosFunciones->camposTabla("insertarZonasEquipos",$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);

$lstCargados 	= $serviciosFunciones->camposTablaView($cabeceras,$serviciosZonasEquipos->TraerEquiposZonas(),4);




if ($_SESSION['refroll_predio'] != 1) {

} else {

	
}


?>

<!DOCTYPE HTML>
<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">



<title>Gesti&oacute;n: Tres Sesenta F&uacute;tbol</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<link href="../../css/estiloDash.css" rel="stylesheet" type="text/css">
    

    
    <script type="text/javascript" src="../../js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="../../css/jquery-ui.css">

    <script src="../../js/jquery-ui.js"></script>
    
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css"/>
	<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <!-- Latest compiled and minified JavaScript -->
    <script src="../../bootstrap/js/bootstrap.min.js"></script>

	<style type="text/css">
		
  
		
	</style>
    
   
   <link href="../../css/perfect-scrollbar.css" rel="stylesheet">
      <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
      <script src="../../js/jquery.mousewheel.js"></script>
      <script src="../../js/perfect-scrollbar.js"></script>
      <script>
      jQuery(document).ready(function ($) {
        "use strict";
        $('#navigation').perfectScrollbar();
      });
    </script>
</head>

<body>

 
<?php echo $resMenu; ?>

<div id="content">

<h3>Zonas-Equipos</h3>

    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Carga de Zonas-Equipos</p>
        	
        </div>
    	<div class="cuerpoBox">
        	<div class="row">
            <form class="form-inline formulario" role="form">
    		<?php echo $formulario; ?>
            </div>
            <br>
            <hr>
            <h4>Prioridades de Turnos</h4>
            <div class="help-block">
            	* Recuerde que cero 0, significa que no puede jugar en ese horario
            </div>
            <div class="row">
            
            	<?php
					$i = 0;
					while ($rowH = mysql_fetch_array($resHorarios)) {
					$i = $i + 1;

				?>
            	<div class="form-group col-md-3">
                    <label class="control-label" style="text-align:left" for="refgrupo"><?php echo $rowH[1]; ?></label>
                    <div class="input-group col-md-12">
                        <select id="horario<?php echo $i; ?>" class="form-control" name="horario<?php echo $i; ?>">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="0">0</option>
                            
                        </select>
                        <input type="hidden" id="idhorario<?php echo $i; ?>" name="idhorario<?php echo $i; ?>" value="<?php echo $rowH[0]; ?>"/>
                    </div>
                </div>
                
                <?php
				
					}
				
				?>
                
               
            
            </div>
            
            
            <div class="row">
                <div class="col-md-12" align="center">
                <ul class="list-inline" style="margin-top:15px;">
                    <li>
                        <button type="button" class="btn btn-primary" id="cargar" style="margin-left:0px;">Guardar</button>
                    </li>
                </ul>
                </div>
            </div>
            </form>
            <br>
            <hr>
            <div class="row" style="padding:0 15px;">
            <h4>Reemplazar Equipos</h4>
            <div class="help-block">Recuerde que el equipo reemplazado se inhabilitará, y el equipo que lo reemplaza tomará los puntos, puntos de fairplay y goles en contra del ultimo en la tabla de posiciones</div>
            
                
                <div class="row">
                    <div class="col-md-12" align="center">
                    <ul class="list-inline" style="margin-top:15px;">
                        <li>
                            <button type="button" class="btn btn-warning" id="reemplazarA" style="margin-left:0px;">Ir a Reemplazar</button>
                        </li>
                    </ul>
                    </div>
                </div>
               
    	</div>
    </div>

    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Zonas-Equipos Cargados</p>
        	
        </div>
    	<div class="cuerpoBox">
        	<?php echo $lstCargados; ?>
    	</div>
    </div>
    
   
</div>

</div>


</div>


<div id="dialog2" title="Eliminar Equipo de la Zona">
    	<p class="alert alert-danger">
        	<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
            ¿Esta seguro que desea eliminar el Equipo de la Categoria?.<span id="proveedorEli"></span>
        </p>

        <input type="hidden" value="" id="idEliminar" name="idEliminar">
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	 <?php 
		echo $serviciosHTML->validacion($tabla);
	
	?>
	
	$('#refequipor').change(function() {
		$.ajax({
				data:  {refequipo: $('#refequipor').val(),
						reftorneo: <?php echo $_SESSION['idtorneo_predio']; ?>, 
						reffecha: <?php echo $ultimaFecha; ?>,  
						accion: 'TraerZonaPorTorneoEquipo'},
				url:   '../../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
						
				},
				success:  function (response) {
						
						if(response){
							resultObj = eval (response);
							$('#idequiporeemp').val(resultObj[1]);
							$('#ultimoequipo').html(resultObj[0]);
							$('#puntos').val(resultObj[2]);
							$('#puntosfp').val(resultObj[3]);
							$('#golesec').val(resultObj[4]);
							$('#datosequipoultimo').show(200);
						}else{
							$('#datosequipoultimo').hide(200);
						}
						
						url = "index.php";
						//$(location).attr('href',url);
						
				}
		});
		
	});
	
	
	 $('.varborrar').click(function(event){
		  usersid =  $(this).attr("id");
		  if (!isNaN(usersid)) {
			$("#idEliminar").val(usersid);
			$("#dialog2").dialog("open");

			
			//url = "index.php";
			//$(location).attr('href',url);
		  } else {
			alert("Error, vuelva a realizar la acción.");	
		  }
	});//fin del boton eliminar
	
	$('.varmodificar').click(function(event){
		  usersid =  $(this).attr("id");
		  if (!isNaN(usersid)) {
			url = "modificar.php?id=" + usersid;
			$(location).attr('href',url);
		  } else {
			alert("Error, vuelva a realizar la acción.");	
		  }
	});//fin del boton modificar

	 $( "#dialog2" ).dialog({
		 	
			    autoOpen: false,
			 	resizable: false,
				width:600,
				height:260,
				modal: true,
				buttons: {
				    "Eliminar": function() {
	
						$.ajax({
									data:  {id: $('#idEliminar').val(), accion: 'eliminarZonasEquipos'},
									url:   '../../ajax/ajax.php',
									type:  'post',
									beforeSend: function () {
											
									},
									success:  function (response) {
											
											$('.'+$('#idEliminar').val()).fadeOut( "slow", function() {
												$(this).remove();
											  });
											
											url = "index.php";
											$(location).attr('href',url);
											
									}
							});
						$( this ).dialog( "close" );
						$( this ).dialog( "close" );
							
				    },
				    Cancelar: function() {
						$( this ).dialog( "close" );
				    }
				}
		 
		 
	 		}); //fin del dialogo para eliminar
	
	
	//al enviar el formulario
    $('#cargar').click(function(){
		
		if (validador() == "")
        {
			//información del formulario
			var formData = new FormData($(".formulario")[0]);
			var message = "";
			//hacemos la petición ajax  
			$.ajax({
				url: '../../ajax/ajax.php',  
				type: 'POST',
				// Form data
				//datos del formulario
				data: formData,
				//necesario para subir archivos via ajax
				cache: false,
				contentType: false,
				processData: false,
				//mientras enviamos el archivo
				beforeSend: function(){
					$("#load").html('<img src="../../imagenes/load13.gif" width="50" height="50" />');       
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data == '') {
                                            $(".alert").removeClass("alert-danger");
											$(".alert").removeClass("alert-info");
                                            $(".alert").addClass("alert-success");
                                            $(".alert").html('<strong>Ok!</strong> Se cargo exitosamente el <strong>Equipo a la Categoria</strong>. ');
											$(".alert").delay(3000).queue(function(){
												/*aca lo que quiero hacer 
												  después de los 2 segundos de retraso*/
												$(this).dequeue(); //continúo con el siguiente ítem en la cola
												
											});
											$("#load").html('');
											url = "index.php";
											$(location).attr('href',url);
                                            
											
                                        } else {
                                        	$(".alert").removeClass("alert-danger");
                                            $(".alert").addClass("alert-danger");
                                            $(".alert").html('<strong>Error!</strong> '+data);
                                            $("#load").html('');
                                        }
				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
                    $("#load").html('');
				}
			});
		}
    });
	
	
	//para reemplazar
	$('#reemplazarA').click(function(event){
			
		url = "../reemplazos/";
		$(location).attr('href',url);

	});//fin del boton eliminar
    

});
</script>
<?php } ?>
</body>
</html>
