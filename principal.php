<?php 
session_start();
if(!isset($_SESSION["Suser"])){
	header("location:login.php");
} else {
?>
<html>
<head>
    <style>
        .limites{
            position: relative;
            margin: auto;
            width: 984px;
            height: 713px;
        }
        .etiqueta{
            position: relative;
            display: inline-block;
            text-decoration: none;
            width: 100px;
            height: 30px;
            text-align: center;
            padding: 12px 0px 0px 0px;
            color: white;
            border: 3px solid;
            border-color: black;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            z-index: 2;
            background-color: #959595;
        }
        .lamina{
            position: absolute;
            width: 972px;
            height: 724px;
            top: 45px;
            padding: 0px 12px 0px 12px;
            visibility: hidden;
            border: 3px solid;
            border-color: black;
            background-color: #959595;
        }
        .etiquetaActiva{
            border-bottom-color: #959595;
        }
        .laminaActiva{
            visibility: visible;
        }
        .display{
            color: white;
            border: 2px solid;
            border-color: #404040;
            background-color: black;
        }
        .usuario{
            position: relative;
            top: -30px;
        }
        .mapa{
            width: 700px;
            padding: 12px 0px 0px 0px;
        }
        .flechaArriba{
            position: absolute;
            top: 24px;
            left: 810px;
        }
        .flechaIzquierda{
            position: absolute;
            top: 96px;
            left: 738px;
        }
        .flechaDerecha{
            position: absolute;
            top: 96px;
            left: 882px;
        }
        .flechaAbajo{
            position: absolute;
            top: 168px;
            left: 810px;
        }
        .botonVelocidad{
            position: absolute;
            top: 96px;
            left: 810px;
        }
        .botonMas{
            position: absolute;
            top: 252px;
            left: 774px;
        }
        .botonMenos{
            position: absolute;
            top: 252px;
            left: 846px;
        }
        .blanco{color: white}
    </style>
    <title>Estratega</title>
    <link rel="icon" type"image/png" href="favicon.png">
</head>
<?php
    $con=mysqli_connect('localhost','root','') or die(mysqli_error());
	mysqli_select_db($con, 'estratega') or die("cannot select DB");

	$mapa = mysqli_query($con, "SELECT tipo FROM mapa");
	$listaAuxiliar = array();
	while($r = mysqli_fetch_assoc($mapa)){
	    $listaAuxiliar[] = $r["tipo"];
	}
	if(isset($_POST["cx"])){
	  $_SESSION['CoordenadaX']=$_POST["cx"];
	  $_SESSION['CoordenadaY']=$_POST["cy"];
	}
	else{
	  $_SESSION['CoordenadaX']=1;
	  $_SESSION['CoordenadaY']=1;
	}
	if(isset($_POST["la"])){
	  $_SESSION['Lamina']=$_POST["la"];
	}
	else{
	  $_SESSION['Lamina']=0;
	}
?>
<script lenguage="JavaScript">
        var lamina = 0;
        var coordenadaX = 1;
        var coordenadaY = 1;
        var vista = 25;
        var velocidad = 1;
        var datosMapa = <?php echo json_encode($listaAuxiliar);?>;
        function ClickaLamina(n) {
            var lams = document.getElementsByClassName("lamina");
            var etis = document.getElementsByClassName("etiqueta");
            for (i = 0; i < lams.length; i++){
                if(lams[i].className.includes("laminaActiva")){
                    lams[i].className = lams[i].className.replace(" laminaActiva", "");
                    etis[i].className = etis[i].className.replace(" etiquetaActiva", "");
                    break;
                }
            }
            lams[n].className += " laminaActiva";
            etis[n].className += " etiquetaActiva";
            localStorage.setItem("lamina", n);
        }
        function EnviaValor() {
            var valor = document.getElementById("input1");
            alert(valor.value)
        }

        function PreSubmit(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                EnviaValor();
            }
        }
        function creaMapa (){
          var mapaDiv = document.getElementById("mapa");
          while(mapaDiv.firstChild){
            mapaDiv.removeChild(mapaDiv.firstChild);
          }
          for(j=0;j<vista;j++){
            for(i=0;i<vista;i++){
              var x = document.createElement("IMG");
              // Esta formula de abajo es la magia. Es muy larga de explicar asi que preguntadme.
              if(datosMapa[(coordenadaX -1 +i) + (coordenadaY -1 +j)*Math.sqrt(datosMapa.length)] != 1){
                x.setAttribute("src", "mapa/llano.png");
              }
              else{
                x.setAttribute("src", "mapa/ciudad.png");
              }
              x.setAttribute("data-corx", coordenadaX + i);
              x.setAttribute("data-cory", coordenadaY + j);
              x.setAttribute("onclick", "fijaCasilla(this.getAttribute('data-corx'),this.getAttribute('data-cory'))");
              x.setAttribute("width", 700/vista);
              x.setAttribute("height", 700/vista);
              mapaDiv.appendChild(x);
            }
          }
        }
        function fijaCasilla (x,y){
          ClickaLamina(1);
          document.getElementById("cx").value = x;
          document.getElementById("cy").value = y;
          document.getElementById("Fcordenadas").submit();
        }
        function cambiaVelocidad (){
          if(velocidad<25){
            velocidad = velocidad*5;
          }
          else{
            velocidad = 1;
          }
          if(velocidad == 1){
            document.getElementById("iconoVel").src="mapa/v1.png";
          }
          else if(velocidad == 5){
            document.getElementById("iconoVel").src="mapa/v2.png";
          }
          else if(velocidad == 25){
            document.getElementById("iconoVel").src="mapa/v3.png";
          }
        }
        function arriba (){
          if(coordenadaY != 1){
            if(coordenadaY > velocidad){
              coordenadaY = coordenadaY - velocidad;
            }
            else{
              coordenadaY = 1;
            }
            checkVertical();
            creaMapa();
          }
        }
        function izquierda (){
          if(coordenadaX != 1){
            if(coordenadaX > velocidad){
              coordenadaX = coordenadaX - velocidad;
            }
            else{
              coordenadaX = 1;
            }
            checkHorizontal();
            creaMapa();
          }
        }
        function derecha (){
          if(coordenadaX + vista != 101){
            if(coordenadaX + vista <= 101 - velocidad){
              coordenadaX = coordenadaX + velocidad;
            }
            else{
              coordenadaX = 101 - vista;
            }
            checkHorizontal();
            creaMapa();
          }
        }
        function abajo (){
          if(coordenadaY + vista != 101){
            if(coordenadaY + vista <= 101 - velocidad){
              coordenadaY = coordenadaY + velocidad;
            }
            else{
              coordenadaY = 101 - vista;
            }
            checkVertical();
            creaMapa();
          }
        }
        function mas (){
          if(vista>25){
            vista = vista/2;
          }
          checkHorizontal();
          checkVertical();
          checkVista();
          creaMapa();
        }
        function menos (){
          if(vista<100){
            vista = vista*2;
          }
          if(coordenadaX + vista > 101){
            coordenadaX = 101 - vista;
          }
          if(coordenadaY + vista > 101){
            coordenadaY = 101 - vista;
          }
          checkHorizontal();
          checkVertical();
          checkVista();
          creaMapa();
        }
        function checkHorizontal (){
          if(coordenadaX == 1){
            document.getElementById("flechaIzquierda").src="mapa/llano.png";
          }
          else{
            document.getElementById("flechaIzquierda").src="mapa/flechaIzquierda.png";
          }
          if(coordenadaX + vista == 101){
            document.getElementById("flechaDerecha").src="mapa/llano.png";
          }
          else{
            document.getElementById("flechaDerecha").src="mapa/flechaDerecha.png";
          }
        }
        function checkVertical (){
          if(coordenadaY == 1){
            document.getElementById("flechaArriba").src="mapa/llano.png";
          }
          else{
            document.getElementById("flechaArriba").src="mapa/flechaArriba.png";
          }
          if(coordenadaY + vista == 101){
            document.getElementById("flechaAbajo").src="mapa/llano.png";
          }
          else{
            document.getElementById("flechaAbajo").src="mapa/flechaAbajo.png";
          }
        }
        function checkVista (){
          if(vista==100){
            document.getElementById("botonMenos").src="mapa/llano.png";
          }
          else{
            document.getElementById("botonMenos").src="mapa/menos.png";
          }
          if(vista==25){
            document.getElementById("botonMas").src="mapa/llano.png";
          }
          else{
            document.getElementById("botonMas").src="mapa/mas.png";
          }
        }
    </script>
<body BGCOLOR=#404040 onload="creaMapa(),checkHorizontal(),checkVertical(),checkVista(),ClickaLamina(localStorage.getItem('lamina'))">
    <form action="" method="POST" name="Fcordenadas" id="Fcordenadas">
      <input type="hidden" id="cx" name="cx">
      <input type="hidden" id="cy" name="cy">
    </form>
    <form>
    <div class="limites">
        &nbsp&nbsp<a href="#" class="etiqueta etiquetaActiva" onclick="ClickaLamina(0);">Global</a>
        <div class="lamina laminaActiva">
            <div id="mapa" class="mapa">
            </div>
            <div class="flechaArriba">
                <img src="mapa/flechaArriba.png" height="60" width="60" onclick="arriba()" id="flechaArriba">
            </div>
            <div class="flechaIzquierda">
                <img src="mapa/flechaIzquierda.png" height="60" width="60" onclick="izquierda()" id="flechaIzquierda">
            </div>
            <div class="botonVelocidad">
                <img src="mapa/v1.png" height="60" width="60" onclick="cambiaVelocidad()" id="iconoVel">
            </div>
            <div class="flechaDerecha">
                <img src="mapa/flechaDerecha.png" height="60" width="60" onclick="derecha()" id="flechaDerecha">
            </div>
            <div class="flechaAbajo">
                <img src="mapa/flechaAbajo.png" height="60" width="60" onclick="abajo()" id="flechaAbajo">
            </div>
            <div class="botonMas">
                <img src="mapa/mas.png" height="60" width="60" onclick="mas()" id="botonMas">
            </div>
            <div class="botonMenos">
                <img src="mapa/menos.png" height="60" width="60" onclick="menos()" id="botonMenos">
            </div>
        </div>
        <a href="#" class="etiqueta" onclick="ClickaLamina(1);">Local</a>
        <div class="lamina">
            <p class="display"><?="(",$_SESSION['CoordenadaX'],",",$_SESSION['CoordenadaY'],")"?></p>
            <input type="text" id="input1" maxlength="4" onkeydown="PreSubmit()">
            <input type="button" id="input2" value="Ejecutar" onclick="EnviaValor()"/>
        </div>
        <a href="#" class="etiqueta" onclick="ClickaLamina(2);">Ordenes</a>
        <div class="lamina">    
            <p>Bite main frieund</p>
            <input type="checkbox" id="input3" value="Acepta"> Acepta los términos<br>
        </div>
        <div class="usuario" align="right"><a href="logout.php" class="blanco"><?=$_SESSION['Suser'];?></a></div>
    </div>
    </form>
</body>
</html>
<?php
}
?>