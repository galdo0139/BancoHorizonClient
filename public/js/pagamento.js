let btnConfirma = document.querySelector(".confirma");
let codigoBoleto = document.querySelector("#codigo");

let campos = document.querySelector(".campos");
let vencimento = document.querySelector("#vencimento");
let valor = document.querySelector("#valor");
let juros = document.querySelector("#juros");
let desconto = document.querySelector("#desconto");
let total = document.querySelector("#valorTotal");



btnConfirma.onclick = function (event) {
    event.preventDefault();

    let numBoleto = codigoBoleto.value;
    

    ajax = new XMLHttpRequest();

    ajax.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //var resultado = JSON.parse(this.responseText);
            alert(this.responseText);
            //vencimento.value = resultado.dataVencimento;

            //valor.value = "R$ " + resultado.valor;
            //juros.value = "R$ " + "0,00";
            //desconto.value = "R$ " + "0,00";
            //total.value = resultado.valor;
            //btnConfirma.style.display = "none";
        }
    }
    ajax.open("GET", `?numBoleto=${numBoleto}`, true);
    ajax.send();
    
    
    campos.style.display = "block";
    
}