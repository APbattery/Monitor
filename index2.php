<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <title>Autopart Battery Monitor</title>
    <meta name="author" content="Bartłomiej Giera">
    <link rel="stylesheet" href="style.css">
     <style>
        #goalChangeButton{position:fixed;bottom:0;right:20%;background-color: #1f1f1f;overflow: hidden;opacity: 0.7;}
        #goalChangeButton2{position:fixed;bottom:0;right:23%;background-color: #1f1f1f;overflow: hidden;opacity: 0.7;}
        #goalChangeButton img,  #goalChangeButton2 img{width:30px;height:30px;padding:5px;padding-bottom:0px;opacity: 0.9;cursor: pointer;}
        body{background-image: url('bgAutopart.png');background-repeat:no-repeat;background-size:100%;display: flex;align-items: center;justify-content: center;min-height: 80vh;margin: 0;overflow:hidden;}
        #logo{position: fixed;top:5%;left:3%;width: 20%;}
        #batteryIMG{position: fixed;bottom:4%;right:2%;width: 18%;}
        #batteryIMG2{position: fixed;bottom:2%;left:2%;width: 12%;}
        #div{vertical-align:middle}
        
      
     </style>
</head>
<body>
    <img id="logo" src="autopartLogoBlank.png" alt="">
    <img id="batteryIMG" src="batteryIMG.png" alt="">
    <img id="batteryIMG2" src="batteryIMG3.PNG" alt="">
    <div id="div">
    <h1>LICZBA <span style="color: #C72F3B;">AKUMULATORÓW</span> WYPRODUKOWANA <span style="color:#286BB1" id="yearInfo"></span></h1>
    <div id="progressBar">
        <div id="progressCheck" style="">
        <p id="percentBar"></p>
        <div class="extra-divs">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    </div>
    </div>
    <h2><span style="color: #C72F3B;" id="batteryNum"></span> / <span style="color:#286BB1" id="goal"></span></h2>
    </div>


    <div id="goalChangeButton">
        <img onclick="changeGoal();" src="up-arrow-svgrepo.png" alt="" title="zmiana celu">   
    </div>
    <div id="goalChangeButton2">
        <a href="wykres-roczny.php">
        <img  src="up-arrow-svgrepo-com.png" alt="" title="zmiana wykresu">   
        </a>
        
    </div>
    <?php
   $serverName = "192.168.0.19";
   $connectionOptions = array(
       "Database" => "sl_Autopart",
       "UID" => "jstawarz",
       "PWD" => "MAN,too!21"
   );
   
   // Połączenie z bazą danych MSSQL
   $conn = sqlsrv_connect($serverName, $connectionOptions);
   
   // Sprawdzenie połączenia
   if ($conn === false) {
       die(print_r(sqlsrv_errors(), true));
   }
   
   // Zapytanie SQL pobierające aktualną liczbę wyprodukowanych akumulatorów
   $sql = "SELECT SUM(PWB_Montaz) AS PWB_MontazSum FROM wusr_vv_ap_PlanProdWysBro;";
   $result = sqlsrv_query($conn, $sql);
   
   if ($result === false) {
       die(print_r(sqlsrv_errors(), true));
   }
   
   if (sqlsrv_has_rows($result)) {
       $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
       $batteryNum = $row['PWB_MontazSum'];
   } else {
       $batteryNum = "Error";
   }
   
   // Zapytanie SQL pobierające aktualny cel na ten rok
   $sql2 = "SELECT celRocznyAkum_ApBatteryMonitor_test AS cel FROM wusr_ap_ApBatteryMonitorCel";
   $result2 = sqlsrv_query($conn, $sql2);
   
   if ($result2 === false) {
       die(print_r(sqlsrv_errors(), true));
   }
   
   if (sqlsrv_has_rows($result2)) {
       $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
       $ThisYearGoal = $row2['cel'];
   } else {
       $ThisYearGoal = "Error";
   }
   
   // Sprawdzenie czy ustawiono nowy cel i aktualizacja bazy danych
   if(isset($_COOKIE['newGoal'])) {
       $newGoal = $_COOKIE['newGoal'];
       if(!empty($newGoal) && is_numeric($newGoal)) {
           $sql3 = "UPDATE wusr_ap_ApBatteryMonitorCel SET celRocznyAkum_ApBatteryMonitor_test = ?";
           $params = array($newGoal);
           $stmt = sqlsrv_query($conn, $sql3, $params);
           if ($stmt === false) {
               die(print_r(sqlsrv_errors(), true));
           }
       }
   }
   
   // Zamknięcie połączenia z bazą danych
   sqlsrv_close($conn);
   
    ?>
   <script>
        function updateGoal() {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "", true); 
                    xhr.send();
                }
        
        var ThisYearGoal = "<?php echo $ThisYearGoal; ?>";
        document.getElementById("goal").innerHTML = ThisYearGoal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");

       
        var batteryCountValue = parseFloat("<?php echo $batteryNum; ?>"); //pobranie wartości liczby akumulatorów z php i przypisanie do zmiennej
        document.getElementById("batteryNum").innerHTML = (batteryCountValue.toFixed(0)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " "); //wyświetlanie aktualnej wyprodukowanej liczby akumulatorów w tym roku


        if((batteryCountValue / ThisYearGoal) * 100 <= 100)
            document.getElementById("progressCheck").style.width = (batteryCountValue / ThisYearGoal) * 100 + "%"; // pasek progressu
        else{
            document.getElementById("progressCheck").style.width = 100 + "%";
        }

        if((batteryCountValue / ThisYearGoal) * 100 < 8)
        {
            document.getElementById("percentBar").innerHTML = "";
        }
        else{
            document.getElementById("percentBar").innerHTML = ((batteryCountValue / ThisYearGoal) * 100).toFixed(1) + "%"; //wyświetlanie % celu
        }
      
        function changeGoal() {
            var newGoal = prompt("Podaj nowy cel (nie używaj spacji! przykładowa wartość: 2000000):") || "błędna wartość";
            
            if (!isNaN(newGoal)) {
                
                document.cookie = "newGoal=" + newGoal;
                ThisYearGoal = parseInt(newGoal);
                document.getElementById("goal").innerHTML = ThisYearGoal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                var batteryCountValue = "<?php echo $batteryNum; ?>"; //pobranie wartości liczby akumulatorów z php i przypisanie do zmiennej
                var batteryCountValue = parseFloat("<?php echo $batteryNum; ?>"); // Convert to a floating-point number
                document.getElementById("batteryNum").innerHTML = (batteryCountValue.toFixed(0)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " "); //wyświetlanie aktualnej wyprodukowanej liczby akumulatorów w tym roku


                if((batteryCountValue / ThisYearGoal) * 100 <= 100)
                    document.getElementById("progressCheck").style.width = (batteryCountValue / ThisYearGoal) * 100 + "%"; // pasek progressu
                else{
                    document.getElementById("progressCheck").style.width = 100 + "%";
                }

                if((batteryCountValue / ThisYearGoal) * 100 < 8)
                {
                    document.getElementById("percentBar").innerHTML = "";
                }
                else{
                    document.getElementById("percentBar").innerHTML = ((batteryCountValue / ThisYearGoal) * 100).toFixed(1) + "%"; //wyświetlanie % celu
                }
                updateGoal();
            } else {
                alert("Błędna Wartość!");
            }
        }

     
        var today = new Date(); // Uzyskanie dzisiejszej daty
        var year = today.getFullYear(); // Pobranie roku
        document.getElementById("yearInfo").innerHTML = "W "+ year; // wyswietlenie aktualnego roku 
     
   </script>
</body>
</html>
