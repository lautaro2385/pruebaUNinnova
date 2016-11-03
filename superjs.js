var zero = ['zero'];
var unit = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
var special = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
var ten = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
var hundred = ['hundred'];
var power = ['thousand', 'million', 'billion'];

var valid = ["", "C", "S", "T", "U", "TU", "UH", "UHS", "UHT", "UHU", "UHTU"];

function isValid(word) {
    if (word === '')
        return false;
    var pa = word.toLowerCase().trim().split(" ");
    var tot = "";

    for (i in pa) {
        tot = tot + category(pa[i]);
    }

    var tot2 = tot.split("Z");

    for (i in tot2) {
        if (valid.indexOf(tot2[i]) < 0) {
            return false;
        }
    }

    return true;
}

function category(p) {
    if (p === 'and') {
        return '';
    }
    if (unit.indexOf(p) >= 0) {
        return "U";
    }
    if (special.indexOf(p) >= 0) {
        return "S";
    }
    if (ten.indexOf(p) >= 0) {
        return "T";
    }
    if (hundred.indexOf(p) >= 0) {
        return "H";
    }
    if (power.indexOf(p) >= 0) {
        return "Z";
    }
    if (zero.indexOf(p) >= 0) {
        return "C";
    }
}

function calcular() {
    var num1 = document.getElementById('numero1');
    var num2 = document.getElementById('numero2');
    var resultado = document.getElementById('resultado');
    var resText = "El resultado es: ";

    if (!isValid(num1.value)) {
        alert('El n\xfamero 1 NO es un n\xfamero v\xe1lido!!')
        num1.focus();
        resultado.innerText = resText + "zero";
        return;
    }

    if (!isValid(num2.value)) {
        alert('El n\xfamero 2 NO es un n\xfamero v\xe1lido!!')
        num2.focus();
        resultado.innerText = resText + "zero";
        return;
    }

    $.ajax({
        url: 'index.php',
        type: 'GET',
        data: {numberone: num1.value.trim(), numbertwo: num2.value.trim()},
        success: function (data) {
            var obj = JSON.parse(data);
            resultado.innerText = resText + obj.result;
        },
        error: function () {
            alert('fallo la solicitud!!');
        }
    });

}