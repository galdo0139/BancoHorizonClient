let btnConfirma = document.querySelector(".confirma");
let codigoBoleto = document.querySelector("#codigo");

let campos = document.querySelector(".campos");
let vencimento = document.querySelector("#vencimento");
let valor = document.querySelector("#valor");
let juros = document.querySelector("#juros");
let desconto = document.querySelector("#desconto");
let total = document.querySelector("#valorTotal");
let pagarEm = document.querySelector("#dataPagamento");



btnConfirma.onclick = function (event) {
    event.preventDefault();

    let numBoleto = codigoBoleto.value;
    

    ajax = new XMLHttpRequest();

    ajax.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var resultado = JSON.parse(this.responseText);
            
            vencimento.value = resultado.dataVencimento;
            alert(resultado.dataVencimento)

            valor.value = resultado.valor;
            juros.value = resultado.multaAtraso;
            desconto.value = resultado.valorDesconto;
            total.value = resultado.total;
            btnConfirma.style.display = "none";

            //pagarEm.focus();
        }
    }
    ajax.open("GET", `?numBoleto=${numBoleto}`, true);
    ajax.send();
    
    
    campos.style.display = "block";
    
}