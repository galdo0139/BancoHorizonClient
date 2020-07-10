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
            //console.log(this.responseText);
            if (resultado.resultado) {
                vencimento.value = resultado.dataVencimento;
                valor.value = resultado.valorMoeda;
                juros.value = resultado.multaAtraso;
                desconto.value = resultado.valorDescontoMoeda;
                total.value = resultado.totalMoeda;
                btnConfirma.style.display = "none";
                campos.style.display = "block";
            } else {
                alert('boleto não encontrado')
            }

            
        }
    }
    ajax.open("GET", `?numBoleto=${numBoleto}`, true);
    ajax.send();
    
    
    
    
}