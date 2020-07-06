let btnConfirma = document.querySelector(".confirma");
let codigoBoleto = document.querySelector("#codigo");

let campos = document.querySelector(".campos");
let vencimento = document.querySelector("#vencimento");
let valor = document.querySelector("#valor");



btnConfirma.onclick = function (event) {
    event.preventDefault();

    let numBoleto = codigoBoleto.value;
    

    ajax = new XMLHttpRequest();

    ajax.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var resultado = JSON.parse(this.responseText);

            vencimento.value = resultado.dataVencimento;
            valor.value = resultado.valor;
        }
    }
    ajax.open("GET", `?numBoleto=${numBoleto}`, true);
    ajax.send();
    
    
    campos.style.display = "block";
    
}