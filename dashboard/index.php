<?php

session_start();

if (!isset($_SESSION['usua_predio']))
{
	header('Location: ../error.php');
} else {


include ('../includes/funcionesUsuarios.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesFUNC.php');
include ('../includes/funciones.php');
include ('../includes/funcionesGrupos.php');
include ('../includes/funcionesDATOS.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFUNC = new ServiciosFUNC();
$serviciosFunciones = new Servicios();
$serviciosZonas = new ServiciosG();
$serviciosDatos = new ServiciosDatos();

$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_predio'],"Dashboard",$_SESSION['refroll_predio'],$_SESSION['torneo_predio']);



//////////////// Tipos de Torneo ///////////////////

$cadTT = '';
$resTorneos = $serviciosFunciones->traerTipoTorneoActivo();
while ($rowTT = mysql_fetch_array($resTorneos)) {
	$cadTT = $cadTT.'<option value="'.$rowTT[0].'">'.  utf8_encode($rowTT[1]).'</option>';	
}

///////////////////////////////////////////////////

//////////////// Tipos de Zonas ///////////////////

$cadZ = '';
$resZonas = $serviciosZonas->TraerGrupos();
while ($rowZ = mysql_fetch_array($resZonas)) {
	$cadZ = $cadZ.'<option value="'.$rowZ[0].'">'.$rowZ[1].'</option>';	
}

///////////////////////////////////////////////////


//////////////// Tipos de Fechas ///////////////////

$cadF = '';
$resFechas = $serviciosFunciones->TraerFecha();
while ($rowF = mysql_fetch_array($resFechas)) {
	$cadF = $cadF.'<option value="'.$rowF[0].'">'.$rowF[1].'</option>';	
}


?>

<!DOCTYPE HTML>
<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">



<title>Gestión: Tres Sesenta Fútbol</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<link href="../css/estiloDash.css" rel="stylesheet" type="text/css">
    

    
    <script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="../css/jquery-ui.css">

    <script src="../js/jquery-ui.js"></script>
    
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css"/>
	<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <!-- Latest compiled and minified JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>

	<style type="text/css">
		.table3 thead th{
			padding:6px 10px;
			text-transform:uppercase;
			color:#444;
			font-weight:bold;
			text-shadow:1px 1px 1px #fff;
			border-bottom:5px solid #444;
			background-color: #87EE75;
		}
		
		.table3 thead th:empty{
			background:transparent;
			border:none;
			
		}
	
	</style>
    
   
   <link href="../css/perfect-scrollbar.css" rel="stylesheet">
      <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
      <script src="../js/jquery.mousewheel.js"></script>
      <script src="../js/perfect-scrollbar.js"></script>
      <script>
      jQuery(document).ready(function ($) {
        "use strict";
        $('#navigation').perfectScrollbar();
      });
    </script>
</head>

<body>

 
<?php echo str_replace('..','../dashboard',$resMenu); ?>

<div id="content">

<h3>Dashboard</h3>

    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Fechas</p>
        	
        </div>
    	<div class="cuerpoBox">
    		<form class="form-inline formulario" role="form">
            	
                <div class="row">
                	<div class="form-group col-md-6">
                     <label class="control-label" style="text-align:left" for="torneo">Torneo</label>
                        <div class="input-group col-md-12">
                            <select id="reftorneo" class="form-control" name="reftorneo">
                                <option value="0">--Seleccione--</option>
                                <?php echo $cadTT; ?>
                                    
                            </select>
                        </div>
                    </div>
                    
                    
                    <div class="form-group col-md-6">
                     <label class="control-label" style="text-align:left" for="torneo">Zona</label>
                        <div class="input-group col-md-12">
                            <select id="refzona" class="form-control" name="refzona">
                                <option value="0">--Seleccione--</option>
                                
                                    
                            </select>
                        </div>
                    </div>
                    
                    
                    <div class="form-group col-md-6">
                     <label class="control-label" style="text-align:left" for="torneo">Fecha</label>
                        <div class="input-group col-md-12">
                            <select id="reffecha" class="form-control" name="reffecha">
                                <option value="0">--Seleccione--</option>
                                <?php echo $cadF; ?>
                                    
                            </select>
                        </div>
                    </div>
                
                </div>
                
                <div class="row">
                    <div class="alert"> </div>
                    <div id="load"> </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <ul class="list-inline" style="margin-top:15px;">
                        <li>
                       	 <button id="buscar" class="btn btn-primary" style="margin-left:0px;" type="button">Buscar</button>
                        </li>
                    </ul>
                    </div>
                </div>
            
            </form>
    	</div>
    </div>
    
    
    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Resultados</p>
        	
        </div>
    	<div class="cuerpoBox" id="resultados">
        
        </div>
        
    </div>
    
    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Posiciones</p>
        	
        </div>
    	<div class="cuerpoBox" id="posiciones">
        
        </div>
        
    </div>
    
    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Goleadores</p>
        	
        </div>
    	<div class="cuerpoBox" id="goleadores">
        
        </div>
        
    </div>
    
    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Amarillas</p>
        	
        </div>
    	<div class="cuerpoBox" id="amarillas">
        
        </div>
        
    </div>
    
    
    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">FairPlay</p>
        	
        </div>
    	<div class="cuerpoBox" id="fairplay">
        
        </div>
        
    </div>
    
    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Mejor Jugador</p>
        	
        </div>
    	<div class="cuerpoBox" id="mejorjugador">
        
        </div>
        
    </div>
    
    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Suspendidos</p>
        	
        </div>
    	<div class="cuerpoBox" id="suspendidos">
        
        </div>
        
    </div>
    
    
   
</div>


</div>




<script type="text/javascript">
$(document).ready(function(){
	
        function TraerZonas(refTorneo) {
            $.ajax({
                data:  {reftorneo: refTorneo,
                        accion: 'traerZonasPorTorneo'},
                url:   '../ajax/ajax.php',
                type:  'post',
                beforeSend: function () {

                },
                success:  function (response) {
                                $('#refzona').html(response);

                }
            });
        }
        
        $('#reftorneo').change(function() {
            TraerZonas($('#reftorneo').val());
        });
        
        TraerZonas($('#reftorneo').val());
        
	$('#buscar').click(function(e) {
        $.ajax({
				data:  {reftorneo: $('#reftorneo').val(),
						refzona: $('#refzona').val(),
						reffecha: $('#reffecha').val(),
						zona: $('#refzona option:selected').text(),
						accion: 'traerResultadosPorTorneoZonaFecha'},
				url:   '../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
						
				},
				success:  function (response) {
						$('#resultados').html(response);
						
				}
		});
		
		
		$.ajax({
				data:  {reftorneo: $('#reftorneo').val(),
						refzona: $('#refzona').val(),
						zona: $('#refzona option:selected').text(),
						reffecha: $('#reffecha').val(),
						accion: 'TraerFixturePorZonaTorneo'},
				url:   '../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
						
				},
				success:  function (response) {
						$('#posiciones').html(response);
						
				}
		});
		
		
		$.ajax({
				data:  {reftorneo: $('#reftorneo').val(),
						refzona: $('#refzona').val(),
						zona: $('#refzona option:selected').text(),
						reffecha: $('#reffecha').val(),
						accion: 'Goleadores'},
				url:   '../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
						
				},
				success:  function (response) {
						$('#goleadores').html(response);
						
				}
		});
		
		$.ajax({
				data:  {reftorneo: $('#reftorneo').val(),
						refzona: $('#refzona').val(),
						reffecha: $('#reffecha').val(),
						zona: $('#refzona option:selected').text(),
						accion: 'Suspendidos'},
				url:   '../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
						
				},
				success:  function (response) {
						$('#suspendidos').html(response);
						
				}
		});
		
		$.ajax({
				data:  {reftorneo: $('#reftorneo').val(),
						refzona: $('#refzona').val(),
						reffecha: $('#reffecha').val(),
						zona: $('#refzona option:selected').text(),
						accion: 'AmarillasAcumuladas'},
				url:   '../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
						
				},
				success:  function (response) {
						$('#amarillas').html(response);
						
				}
		});
		
		$.ajax({
				data:  {reftorneo: $('#reftorneo').val(),
						refzona: $('#refzona').val(),
						reffecha: $('#reffecha').val(),
						zona: $('#refzona option:selected').text(),
						accion: 'FairPlay'},
				url:   '../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
						
				},
				success:  function (response) {
						$('#fairplay').html(response);
						
				}
		});
		
		$.ajax({
				data:  {reftorneo: $('#reftorneo').val(),
						refzona: $('#refzona').val(),
						reffecha: $('#reffecha').val(),
						zona: $('#refzona option:selected').text(),
						accion: 'MejorJugador'},
				url:   '../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
						
				},
				success:  function (response) {
						$('#mejorjugador').html(response);
						
				}
		});
    });
	
	 $( '#dialogDetalle' ).dialog({
		autoOpen: false,
		resizable: false,
		width:800,
		height:740,
		modal: true,
		buttons: {
			"Ok": function() {
				$( this ).dialog( "close" );
			}
		}
	});

	 $( '#dialog2' ).dialog({
		autoOpen: false,
		resizable: false,
		width:800,
		height:740,
		modal: true,
		buttons: {
			"Ok": function() {
				$( this ).dialog( "close" );
			}
		}
	});
	

});
</script>
<?php } ?>
</body>
</html>
