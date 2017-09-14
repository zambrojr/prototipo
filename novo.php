<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    
   
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />

    
    
  <meta charset="UTF-8">
  <title>iOS style sliding menu</title>
  

      <link rel="stylesheet" href="css/style.css">

  
   
<link rel="stylesheet" href="https://developers.google.com/maps/documentation/javascript/demos/demos.css">

<script src="https://code.jquery.com/jquery-1.10.2.js"></script>   
   
</head>

<body>
  <!-- Navigation -->
<nav id="slide-menu">
	<ul id="opcoesMenu">
		<li class="events" onclick="javaScript:document.location.href ='tela_inicial.html';">Trajetos</li>
		<li class="calendar"  onclick="javaScript:document.location.href ='conf.html';">Configurações</li>
		<li class="events">Controle dos Pais</li>
		<li class="events">Integração - Bike BH</li>
		<li class="events">Integração - BHBus</li>
		<li class="events">Convide seus amigos</li>
		<li class="logout">Logout</li>
	</ul>
    
    <div style="position:relative; float:left; padding-top:10px; display:none" id="promocoes">
         <div style=position:relative; float:left">
            <img style="width:95%;cursor:pointer" src='images/padaria_ze.png' onclick="javaScript:renderPadaria()";> 
        </div>
         
         <div style="position:relative; float:left">
            <img style="width:95%" src='images/farmacia.png'> 
        </div>         
    </div>
    
</nav>
<!-- Content panel -->
<div id="content">
	<div class="menu-trigger"></div>


<head>
<meta charset="UTF-8">
<title>Gerar Rotas</title>



</head>
<body>
<div style="margin-left:50px;position:relative">
<ul class="abas">
<!-- Aba: Novo Trajeto -->

</ul>
</div>


<br/>


 <div id="map" style="height:71%"></div>
 
 <div id="opcoesRota"></div>
 <div id="trajetosRota"></div>
 
<script>
        

var map;
var directionsDisplay;
var directionsService ;

 var panorama;
 var caminho = [];
 var caminhoRota = [];
 var indiceCaminho;
        var iconBase = './images/';
        var icons = {
            roadtype_gravel: {
              icon: iconBase+'roadtype_gravel.png'
            },
            powerlinepole: {
              icon: iconBase+'powerlinepole.png'
            },
            construction: {
              icon: iconBase+'construction.png'
            },
            stairs:{
                  icon: iconBase+'stairs.png'
            },
            riparianhabitat:{
               icon: iconBase+'riparianhabitat.png'
            },
            accesdenied:{
               icon: iconBase+'accesdenied.png'
            },
            walking:{
               icon: iconBase+'290d13f65cdc061efb62eb67c680a082.png'
            },
            points:{
               icon: iconBase+'cifrao.png'
            },
            zombie:{
               icon: iconBase+'DSC08356.png'
            },
            nois:{
               icon: iconBase+'DSC08356.png'
            },
			cifraooo:{
               icon: iconBase+'cifraooo.png'
            },
            
            
        };

		
        function renderPadaria()
        {
            var positionm =  new google.maps.LatLng(-19.889540, -43.956496)
            var marker = new google.maps.Marker({
                 position: positionm,
                 icon: icons["cifraooo"].icon,
                 map: map
            });  		
        }		
		
		
        function loadRoute()
        {
            directionsDisplay.setDirections({routes: []});
             directionsDisplay.setMap(null);
             
			 
             <?php
			 
			 $bike= file_get_contents("./bike.txt");
             
			 if($bike == "true"){
				 
				  echo "iniciaRotaBike('".$_GET["origem"]."', '".$_GET["destino"]."');";
				 
			 } else {
				 if($_GET["destino"] == 'Prodabel - Belo Horizonte'){
					 echo "iniciaRota('".$_GET["origem"]."', 'Av dos Andradas 391 - Belo Horizonte');";
					 echo "iniciaRotaD('Av dos Andradas 391 - Belo Horizonte', '".$_GET["destino"]."');";
				 } else {
					 echo "iniciaRotaD('".$_GET["origem"]."', '".$_GET["destino"]."');";
				 }
			 }
			 
             ?>
			 
			 

			 
			 
            
        }



        function geraInstrucoesRota(rota,indice)
        {
            var caminho = "";
            jQuery.each( rota.steps, function( i, val ) {
              //alert();
              caminho = caminho+"<div>"+val.instructions+"</div>";
            });               
            return "<div id ='instrucoesrota"+indice+"' style='display:none'>"+caminho+"</div>";
            //javaScript:
        }


        function geraOpcaoRota(rota,indice, color)
        {
            return "<div onclick='iniciaCaminhoRotaStreetView("+indice+");' style='background-color:"+color+";cursor:pointer;border-radius: 5px;    border: 2px solid #73AD21;    margin: 15px;    height: 25px;float:; width:300px; position:relative;padding:2px;'><div style='float:left;position:relative;padding:1px;'>Iniciar rota: "+rota.distance.text+"</div><div style='width:100px;float:left;position:relative;padding-left:10px'><img style='width:20%;height:20%' src='"+icons["walking"].icon+"'/></div></div>";
            //javaScript:
        }


        function iniciaCaminhoRotaStreetView(indiceCaminhoEscolhido)
        {
            document.getElementById('instrucoesrota'+indiceCaminhoEscolhido).style.display="";
            document.getElementById('opcoesRota').style.display="none";
            
            google.maps.event.addListener(panorama, "closeclick", function(event) { 
                document.getElementById('instrucoesrota'+indiceCaminhoEscolhido).style.display="none";
                document.getElementById('opcoesRota').style.display="";
            });
            
            caminhoRota = [];
            indiceCaminho=0;
            jQuery.each( caminho[indiceCaminhoEscolhido], function( i, val ) {
              console.log(val.lng());
                caminhoRota.push( {lat: Number(val.lat()), lng: Number(val.lng())});      
            });       
            fazCaminho();
        }


        function fazCaminho()
        {
            panorama.setVisible(true);
            panorama.setPosition(caminhoRota[indiceCaminho]);
            indiceCaminho++;
        }


        function iniciaRotaD(origem, destino)
        {
            
            var requestdTres = {
                destination: destino,
                origin: origem,
                travelMode: 'TRANSIT',
                provideRouteAlternatives: false,
            };
            
            directionsService.route(requestdTres, function(response, status) {
                
                
              if (status == 'OK') {
                  
                  
                for (var i = 0, len = response.routes.length; i < len; i++) {
                    
                //  response.routes[i].legs[0].steps.splice(1, 1);
                 // response.routes[i].legs[0].steps[0].end_location = response.routes[i].legs[0].steps[1].start_location;
                    
                   response.routes[i].legs[0].steps.forEach(logLegs);
                }

              }
              
              
            }); 
        
        
        var features = [
            
                    <?php
                    foreach(explode("\n",file_get_contents("pontos.txt")) as $linha) : 
                        $cords = explode(";", $linha);
                    ?>
                        {
                        position: new google.maps.LatLng(<?=$cords[0]?>),
                        type: '<?=$cords[1]?>'
                        },
                        <?php endforeach; ?>
                ];

                // Create markers.
                features.forEach(function(feature) {
                  var marker = new google.maps.Marker({
                    position: feature.position,
                    icon: icons[feature.type].icon,
                    map: map
                  });
                });	        
        
        }





        function iniciaRota(origem, destino)
        {
            
            var requestdTres = {
                destination: destino,
                origin: origem,
                travelMode: 'WALKING',
                provideRouteAlternatives: true,
            };
            
            directionsService.route(requestdTres, function(response, status) {
                
                caminho = [];
                document.getElementById('opcoesRota').innerHTML='';
                document.getElementById('trajetosRota').innerHTML='';
                
              if (status == 'OK') {
                  
                  
                /*for (var i = 0, len = response.routes.length; i < len; i++) {
                    
                  response.routes[i].legs[0].steps.splice(1, 1);
                  response.routes[i].legs[0].steps[0].end_location = response.routes[i].legs[0].steps[1].start_location;
                    
                   response.routes[i].legs[0].steps.forEach(logLegs);
                }*/
            

	           for (var i = 0, len = response.routes.length; i < len; i++) {
                        
                        caminho.push(response.routes[i].overview_path);
	                if( i == 0){
	                    var color = "#ff1e00";
	                } else if( i == 1){
			    var color = "#0b700b";	
	                } else {
	                    var color = "#ffa400";
	                }
                        
                        document.getElementById('opcoesRota').innerHTML=document.getElementById('opcoesRota').innerHTML+geraOpcaoRota(response.routes[i].legs[0], i, color);
                        document.getElementById('trajetosRota').innerHTML=document.getElementById('trajetosRota').innerHTML+geraInstrucoesRota(response.routes[i].legs[0], i);
                        
                        
	                new google.maps.DirectionsRenderer({
	                    map: map,
	                    draggable: true,
	                    directions: response,
	                    routeIndex: i,
	                    polylineOptions: { strokeColor: color }
	                });
	            }
           
              }
              
              
            }); 
        
        
        var features = [
            
                    <?php
                    foreach(explode("\n",file_get_contents("pontos.txt")) as $linha) : 
                        $cords = explode(";", $linha);
                    ?>
                        {
                        position: new google.maps.LatLng(<?=$cords[0]?>),
                        type: '<?=$cords[1]?>'
                        },
                        <?php endforeach; ?>
                ];

                // Create markers.
                features.forEach(function(feature) {
                  var marker = new google.maps.Marker({
                    position: feature.position,
                    icon: icons[feature.type].icon,
                    map: map
                  });
                });	        
        
        }


		
		
		
		
		
		
        function iniciaRotaBike(origem, destino)
        {
            
            var requestdTres = {
                destination: destino,
                origin: origem,
                travelMode: 'BICYCLING',
                provideRouteAlternatives: false,
            };
            
            directionsService.route(requestdTres, function(response, status) {
                
                caminho = [];
                document.getElementById('opcoesRota').innerHTML='';
                
              if (status == 'OK') {
                  
                  
             caminho.push(response.routes[0].overview_path);

	           for (var i = 0, len = response.routes.length; i < len; i++) {
                        
                        
                        document.getElementById('opcoesRota').innerHTML=document.getElementById('opcoesRota').innerHTML+geraOpcaoRota(response.routes[i].legs[0], i, "#0b700b")+geraInstrucoesRota(response.routes[i].legs[0], i);
                        
                        
	                new google.maps.DirectionsRenderer({
	                    map: map,
	                    draggable: true,
	                    directions: response,
	                    routeIndex: 0,
	                    polylineOptions: { strokeColor: "#0b700b" }
	                });
	            }
           
              }
              
              
            }); 
        
        
       
        
        }		
		
		
		
		


function fazRota(element, index, array) {
    //console.log("a[" + index + "] = " + element);
    var temp = new String(element);
    temp = temp.replace("(","");
    temp = temp.replace(")","");
    var arr = temp.split(",");
    var ps = index*100;
    
    console.log(ps+', '+arr[1]+', '+arr[0]+', 300,');
    //caminho.push( {lat: Number(arr[0]), lng: Number(arr[1])});
      
}        



function logLegs(element, index, array) {

	if(element.travel_mode == "WALKING")
        {
	        var request = {
	            destination: element.end_location.lat()+','+element.end_location.lng(),
	            origin: element.start_location.lat()+','+element.start_location.lng(),
	            travelMode: 'WALKING',
	            provideRouteAlternatives: true,
	        };

                   directionsService.route(request, function(response, status) {
                   
                   //caminho = [];
                   //indiceCaminho = 0;
                   
	          if (status == 'OK') 
                  {

	            for (var i = 0, len = response.routes.length; i < len; i++) 
                    {
                        caminho.push(response.routes[i].overview_path);

                        if( i == 0){
	                    color = "#ff1e00";
	                } else if( i == 1){
			    var color = "#0b700b";	
	                } else {
	                    color = "#ffa400";
	                }
	                
	                new google.maps.DirectionsRenderer({
	                    map: map,
	                    draggable: true,
	                    directions: response,
	                    routeIndex: i,
	                    polylineOptions: { strokeColor: color }
	                });
	            }			
	          }
	        });

                if( index == 0 ){
                    var latLng = new google.maps.LatLng(element.start_location.lat(), element.start_location.lng());
                    map.setCenter(latLng);  
                }

	} else{


	        var requestdTres = {
	           destination: element.end_location.lat()+','+element.end_location.lng(),
	            origin: element.start_location.lat()+','+element.start_location.lng(),
	            travelMode: 'TRANSIT',
	            provideRouteAlternatives: false,
	        };

                directionsService.route(requestdTres, function(response, status) {
                    if (status == 'OK') {
                        for (var i = 0, len = response.routes.length; i < len; i++) {

                            new google.maps.DirectionsRenderer({
                                 map: map,
                                 directions: response,
                                 routeIndex: i,
                                 polylineOptions: { strokeColor: '#000000' }
                             });


                        }			
                    }
	        });


	}

}   


        
        
        
        
        
    function CenterControl(controlDiv, map) {

            // Set CSS for the control interior.
            var controlText = document.createElement('div');
            controlText.style.lineHeight = '38px';
            controlText.style.paddingLeft = '5px';
            controlText.style.paddingRight = '5px';
            controlText.style.cursor = 'pointer';
            controlText.innerHTML = "<img style='width:5%; width:5%' src='"+icons['walking'].icon+"'>";
            controlDiv.appendChild(controlText);

            // Setup the click event listeners: simply set the map to Chicago.
            controlText.addEventListener('click', function() {
              fazCaminho();
            });

    }        
    
    
    function promocoes()
    {
        document.getElementById('promocoes').style.display="";
        document.getElementById('opcoesMenu').style.display="none";
        
        
	var $body = document.body
	, $menu_trigger = $body.getElementsByClassName('menu-trigger')[0];

	
	
			$body.className = ( $body.className == 'menu-active' )? '' : 'menu-active';
	
	
        
        
        
    }
    
    function TrocaPontos(controlDiv, map) {

            // Set CSS for the control interior.
            var controlText = document.createElement('div');
            controlText.style.lineHeight = '38px';
            controlText.style.paddingLeft = '5px';
            controlText.style.paddingRight = '5px';
            controlText.style.cursor = 'pointer';
            controlText.innerHTML = "<img style='width:30%; width:30%' src='"+icons['points'].icon+"'>";
            controlDiv.appendChild(controlText);

            // Setup the click event listeners: simply set the map to Chicago.
            controlText.addEventListener('click', function() {
              promocoes();
            });

    }         
    
    
    
        
        
        function addContextClick(controlTextD, icon)
        {
            // Setup the click event listeners: simply set the map to Chicago.
            controlTextD.addEventListener('click', function() {
              //panorama.getPosition().;
              
                var lat = Number(panorama.getPosition().lat())+0.000119;
                var lng = Number(panorama.getPosition().lng())+0.000065;
                 var positionm =  new google.maps.LatLng(lat, lng)
              

                var marker = new google.maps.Marker({
                    position: positionm,
                    icon: icons[icon].icon,
                    map: map
                  });              
              
                var url = './new.php';
                $.getJSON(url, { lat: lat, lng: lng, icon: icon }, function(data) {		
                    if(data.resultado=="1"){
                        
                    } else{
                    }
                });              
              
            });            
        }
        
        
        
    function criaDivIcone(controlUI, icone)
    {
            // Set CSS for the control interior.
            var controlText = document.createElement('div');
            controlText.style.paddingLeft = '5px';
            controlText.style.paddingRight = '5px';
            controlText.style.float = 'left';
            controlText.innerHTML = "<img src='"+icons[icone].icon+"'>";
            controlUI.appendChild(controlText);
            addContextClick(controlText, icone);
        
    }
        
        
    function RightControl(controlDiv, map) {

            // Set CSS for the control border.
            var controlUI = document.createElement('div');
            controlUI.style.backgroundColor = '#fff';
            controlUI.style.border = '2px solid #fff';
            controlUI.style.borderRadius = '3px';
            controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
            controlUI.style.cursor = 'pointer';
            controlUI.style.marginBottom = '22px';
            controlUI.style.height = '35px';
            controlUI.style.textAlign = 'center';
            controlDiv.appendChild(controlUI);
            
            
            criaDivIcone(controlUI, 'roadtype_gravel');
            criaDivIcone(controlUI, 'powerlinepole');
            criaDivIcone(controlUI, 'construction');
            criaDivIcone(controlUI, 'stairs');
            criaDivIcone(controlUI, 'riparianhabitat');
            criaDivIcone(controlUI, 'accesdenied');
    }          
        
        
        
        
      function initMap() {


                map = new google.maps.Map(document.getElementById('map'), {
                  center: 'Prodabel - Caiï¿½ara, Belo Horizonte - MG',
                  scrollwheel: true,
                  zoom: 12,
                  disableDefaultUI: true
                });

                directionsDisplay = new google.maps.DirectionsRenderer({
                  map: map
                });

                directionsService = new google.maps.DirectionsService();


                panorama = map.getStreetView();

                var centerControlDiv = document.createElement('div');
                var centerControl = new CenterControl(centerControlDiv, map);
                centerControlDiv.index = 1;	
                panorama.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(centerControlDiv);


                var rightControlDiv = document.createElement('div');
                var rightControl = new RightControl(rightControlDiv, map);
                rightControlDiv.index = 1;	
                panorama.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(rightControlDiv);
		
                var centerControlDiv = document.createElement('div');
                var centerControl = new TrocaPontos(centerControlDiv, map);
                centerControlDiv.index = 1;	
                panorama.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);                
                
                /*google.maps.event.addListener(panorama,'visible_changed',function(){
                    alert('streetview is ' +(this.getVisible()?'open':'closed'));
                });                */
                loadRoute();
      }

    </script>
    
</body>

</div>
  


</body>
   <script src="js/index.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcO10hDOCRqMnBXFR2D3v17O9HegTxSbg&callback=initMap" async defer></script>				
    <script type="text/javascript">
           
        

    </script>

</html>


