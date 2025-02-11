function new_selection(n) {

    // Select element in dom to add new selection
    let section = document.querySelector('#add_section');
    section.setAttribute('class', 'visible');
    // Create new selection
    let type1 = document.createElement('select');
    type1.setAttribute('name', 'reserva' + n);
    type1.setAttribute('id', 'reserva' + n);
    type1.setAttribute('value', "1");
    // Define options
    let type2 = document.createElement('option');
    // Loop to create 16 + 1 rooms
    for (let i=1; i<=16; i++) {
        let option = document.createElement('option');
        option.setAttribute('value', + i);
        option.innerHTML = 'Cabaña ' + i;
        type1.appendChild(option);
    }
    let option = document.createElement('option');
    option.setAttribute('value', "17");
    option.innerHTML = 'Salón';
    type1.appendChild(option);

    // Add the new selection to the section
    section.appendChild(type1);
    // Create a button to delete the selection
    let del_button = document.createElement('button');
    del_button.setAttribute('type', 'button');
    del_button.setAttribute('id', 'del_reserva' + n);
    del_button.innerHTML = 'Eliminar';
    // Add the button to the section
    section.appendChild(del_button);
    // Add a listener to the button
    del_button.addEventListener('click', function() {
        let delete_selection = document.querySelector('#reserva' + n);
        let delete_button = document.querySelector('#del_reserva' + n);
        let delete_br = document.querySelector('#br' + n);
        delete_selection.remove();
        delete_button.remove();
        delete_br.remove();
    });

    let form_elements = document.querySelectorAll('select');
    for (let i=0;i<form_elements.length;i++){
        form_elements[i].setAttribute('onchange', 'update_preview()');
    }

    let br = document.createElement('br');
    br.setAttribute('id', 'br' + n);
    section.appendChild(br);
}

function update_preview() {
    let min = inicio.value;
    final.setAttribute('min',min);

    let cliente_id = document.querySelector('#cliente_id');
    // let check_reserva = document.querySelector('#check_reserva');
    // if (!isNaN(cliente_id.value)){
    //     check_reserva.innerHTML = "";
    // }

    // select preview elements
    let preview = document.querySelectorAll('#preview span');
    // check if there are more than 1 reservation
    let extra = false;
    let reserva = document.querySelectorAll('select');
    if (reserva.length > 2) {
        extra = true;
    };
    // loop to update preview elements
    for (let i=0; i<(preview.length); i++) {

        let val_id = preview[i].getAttribute('id');
        val_id = val_id.slice(0,-2);
        let n = 0
        if (val_id == 'saldo') {
            let total = document.querySelector('#total').value;
            let abono = document.querySelector('#abono').value;
            let saldo = total - abono;
            preview[i].innerHTML = "$" + saldo;
        } else if (val_id.includes('reserva')) { //if there are more than 1 reservation
            let real_value = document.querySelector('#reserva' + n);
            if (real_value == 17){
                preview[i].innerHTML = 'Salón';
            } else {
                preview[i].innerHTML = 'Cabaña ' + real_value.value;
            }
            if (extra){
                for (let j=1; j<reserva.length-1; j++){
                    real_value = document.querySelector('#reserva'+j);
                    if (real_value == 17){
                        preview[i].innerHTML += ', Salón';
                    } else {
                        preview[i].innerHTML += ', Cabaña' + real_value.value;
                    }
                    n += 1;
                }
            }
        //////// 
        } else {  
            let real_value = document.querySelector('#' + val_id);
            if (val_id == 'total' || val_id == 'abono' && real_value.value !== '') {
                preview[i].innerHTML = "$" + real_value.value;
            } else if (real_value.value === ''){
                preview[i].innerHTML = "Dato faltante";
            } else {
                preview[i].innerHTML = real_value.value;
            }
        }
    }
}

let n = 1;
// add listener to add button
let add_button = document.querySelector('#add_reserva');
add_button.addEventListener('click', function() {
    new_selection(n);
    n++;
});

let inicio = document.querySelector('#inicio');
let final = document.querySelector('#final');
const today = new Date();
let day= today.getDate();
let month = today.getMonth() + 1;
if (month<10){
    month = "0" + month;
}
if (day<10) {
    day = "0" + day;
}
let year = today.getFullYear();
let fecha = year + "-" + month + "-" + day;
inicio.setAttribute('min',fecha);
final.setAttribute('min',fecha);

// add listeners to update preview
let form_elements = document.querySelectorAll('input, select, textarea');
for (let i=0;i<form_elements.length;i++){
    form_elements[i].setAttribute('onchange', 'update_preview()');
}

update_preview()
