var ThisYearGoal = "<?php echo $ThisYearGoal; ?>";
document.getElementById("goal").innerHTML = ThisYearGoal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");

var batteryCountValue = "<?php echo $batteryNum; ?>"; //pobranie wartości liczby akumulatorów z php i przypisanie do zmiennej
document.getElementById("batteryNum").innerHTML = batteryCountValue.replace(/\B(?=(\d{3})+(?!\d))/g, " "); //wyświetlanie aktualnej wyprodukowanej liczby akumulatorów w tym roku

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
    var newGoal = prompt("Podaj nowy cel (nie używaj spacji! przykładowa wartość: 2000000):");
    if (!isNaN(newGoal)) {
        
        document.cookie = "newGoal=" + newGoal;
        ThisYearGoal = parseInt(newGoal);
        document.getElementById("goal").innerHTML = ThisYearGoal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        var batteryCountValue = "<?php echo $batteryNum; ?>"; //pobranie wartości liczby akumulatorów z php i przypisanie do zmiennej
        document.getElementById("batteryNum").innerHTML = batteryCountValue.replace(/\B(?=(\d{3})+(?!\d))/g, " "); //wyświetlanie aktualnej wyprodukowanej liczby akumulatorów w tym roku

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
    } else {
        alert("Błędna Wartość!");
    }
}


var today = new Date(); // Uzyskanie dzisiejszej daty
var year = today.getFullYear(); // Pobranie roku
document.getElementById("yearInfo").innerHTML = "W "+ year; // wyswietlenie aktualnego roku 
setTimeout(function() {
location.reload();
}, 120000); //aktualizacja danych co 2 minuty