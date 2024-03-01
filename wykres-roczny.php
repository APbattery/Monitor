<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-image: url('bgAutopart.png');
            background-repeat: no-repeat;
            background-size: cover; /* Aby tło wypełniło cały obszar */
            background-attachment: fixed; /* Ustawienie tła jako nieruchome */
            overflow-y: auto; /* Umożliwienie przewijania treści */
            margin: 0;
            padding: 0;
        }

        canvas {
            font-size: 20px;
        }

        .chart-container {
            margin: auto;
            margin-top: 14%;
            height: 50vh;
            width: 70vw;
            display: block;
        }

        #logo {
            position: fixed;
            top: 5%;
            left: 3%;
            width: 20%;
        }

        #goalChangeButton2 {
            position: fixed;
            bottom: 0;
            right: 23%;
            background-color: #1f1f1f;
            overflow: hidden;
            opacity: 0.7;
        }

        #goalChangeButton2 img {
            width: 30px;
            height: 30px;
            padding: 5px;
            padding-bottom: 0px;
            opacity: 0.9;
            cursor: pointer;
        }

        body::-webkit-scrollbar {
            width: 0; /* Szerokość paska przewijania */
            height: 0; /* Wysokość paska przewijania (dla poziomego) */
            display: none; /* Ukrycie paska przewijania */
        }
    </style>
</head>
<body>
    <img id="logo" src="autopartLogoBlank.png" alt="">

    <div class="chart-container">
        <canvas id="chart"></canvas>
    </div>
  
   
    <div id="goalChangeButton2">
        <a href="index.php">
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

$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check connection
if ($conn === false) {
    die( print_r( sqlsrv_errors(), true));
}

// Zapytanie SQL
$sql = "SELECT PWB_Montaz AS PWB_MontazSum FROM wusr_vv_ap_PlanProdWysBro";
$result = sqlsrv_query($conn, $sql);

// Utwórz tablicę na dane do wykresu
$data = array();
if ($result !== false) {
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row["PWB_MontazSum"];
    }
} else {
    echo "Brak danych w bazie";
}

// Zamknięcie połączenia
sqlsrv_close($conn);
?>


<!-- Osadź dane w JavaScript -->
<script>
        var data = {
            labels: ["Sty", "Lut", "Mar", "Kwi", "Maj", "Cze", "Lip", "Sie", "Wrz", "Paź", "Lis", "Gru"],
            datasets: [{
                label: "Wykres Roczny Produkcji Akumulatorów",
                backgroundColor: "rgba(199,47,59,0.5)",
                borderColor: "rgba(199,47,59,1)",
                borderWidth: 2,
                hoverBackgroundColor: "rgba(199,47,59,0.8)",
                hoverBorderColor: "rgba(199,47,59,1)",
                data: [<?php echo implode(",", $data); ?>],
            }]
        };


        var options = {
            maintainAspectRatio: false,
            plugins: {
                colors: {
                    forceOverride: true
                }
            },
            scales: {
                y: {
                    stacked: true,
                    beginAtZero: false,
                    min: 100000, // Ustawienie minimum osi Y na 100 000
                    grid: {
                        display: true,
                        color: "rgba(255,99,132,0.2)"
                    },
                    ticks: {
                        font:{
                            size: 30
                        } 
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font:{
                            size: 30
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 30 
                        }
                    }
                }
            }
        };

        var chart = new Chart('chart', {
            type: 'bar',
            options: options,
            data: data
        });
    </script>
</body>
</html>
