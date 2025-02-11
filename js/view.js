function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$(document).ready(function (){
    let total = $('#total').html()
    let abono = $('#abono').html()
    let saldo = $('#saldo').html()
    total = numberWithCommas(total)
    abono = numberWithCommas(abono)
    saldo = numberWithCommas(saldo)
    $('#total').html('$'+total)
    $('#abono').html('$'+abono)
    $('#saldo').html('$'+saldo)
})
