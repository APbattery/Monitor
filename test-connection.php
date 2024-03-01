<style>
    body{font-size:40px}
</style>
<?php
$serverName = "192.168.0.19";
$connectionOptions = array(
    "Database" => "sl_Autopart",
    "UID" => "jstawarz",
    "PWD" => "MAN,too!21"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);


$sql = "SELECT ROUND(SUM(PWB_Montaz), 1) AS PWB_MontazSum FROM wusr_vv_ap_PlanProdWysBro;";

$result = sqlsrv_query($conn, $sql);
if ($result === false) {
    echo "Błąd zapytania: ";
    die(print_r(sqlsrv_errors(), true));
} else {
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        // Skonwertowanie wyniku na liczbę i usunięcie miejsc po przecinku
        $rounded_sum = number_format($row['PWB_MontazSum'], 0, '.', '');
        
        // Wyświetlenie wyniku
        echo "PWB_MontazSum: " . $rounded_sum . "<br>";
        echo "--------------------------------<br>";
}}

sqlsrv_free_stmt($result);
sqlsrv_close($conn);
?>

<script>
     setTimeout(function() {
        location.reload();
        }, 60000); // aktualizacja co minute danych
</script>
