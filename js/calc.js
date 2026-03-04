let vizit = 0, qty1, qty2, yesVizit = 100, noVizit = 0, oplata, nal = 2500, beznal = 3000;

function updateVizit() {
    vizit = document.getElementById("vizit").value;
    if (vizit === "yesVizit") {
        vizit = 100;
    } else if (vizit === "noVizit") {
        vizit = 0;
    }
    calculateTotal();
}

function updateOplata() {
    oplata = document.getElementById("oplata").value;
    if (oplata === "nal") {
        oplata = 2500;
    } else if (oplata === "beznal") {
        oplata = 3000;
    }
    calculateTotal();
}

function calculateTotal() {
    qty1 = document.getElementById("qty1").value;
    qty2 = document.getElementById("qty2").value;
    let grandTotal = 0;

    if (qty1 < 0) {
        document.getElementById("qty1").value = 0;
        qty1 = 0;
    }

    if (qty2 <= 0) {
        document.getElementById("qty2").value = 0;
        qty2 = 0;
    } else if (qty2 > 0 && qty2 <= 4) {
        document.getElementById("qty2").value = 4;
        qty2 = 3;
    } else {
        qty2 = document.getElementById("qty2").value;
    }

    if (vizit === 0) {
        grandTotal = qty2 * oplata;
    } else {
        grandTotal = (qty1 * vizit) + (qty2 * oplata);
    }

    document.getElementById("grandTotal").innerHTML = grandTotal + ' ₽';
}

document.getElementById("qty1").addEventListener("change", calculateTotal);
document.getElementById("qty2").addEventListener("change", calculateTotal);
document.getElementById("vizit").addEventListener("change", calculateTotal);
document.getElementById("oplata").addEventListener("change", calculateTotal);

updateOplata();
calculateTotal();