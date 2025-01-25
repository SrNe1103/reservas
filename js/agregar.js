function new_selection(n) {

    // Select element in dom to add new selection
    let section = document.querySelector('#add_section');
    section.setAttribute('class', 'visible');
    // Create new selection
    let type1 = document.createElement('select');
    type1.setAttribute('name', 'reserva_' + n);
    type1.setAttribute('id', 'reserva_' + n);
    type1.setAttribute('value', "C1")
    // Define options
    let type2 = document.createElement('option');
    // Loop to create 16 + 1 rooms
    for (let i=1; i<=16; i++) {
        let option = document.createElement('option');
        option.setAttribute('value', "C" + i);
        option.innerHTML = 'Cabaña ' + i;
        type1.appendChild(option);
    }
    let option = document.createElement('option');
    option.setAttribute('value', "S");
    option.innerHTML = 'Salón';
    type1.appendChild(option);

    // Add the new selection to the section
    section.appendChild(type1);
    // Create a button to delete the selection
    let del_button = document.createElement('button');
    del_button.setAttribute('type', 'button');
    del_button.setAttribute('id', 'del_reserva_' + n);
    del_button.innerHTML = 'Eliminar';
    // Add the button to the section
    section.appendChild(del_button);
    // Add a listener to the button
    del_button.addEventListener('click', function() {
        let delete_selection = document.querySelector('#reserva_' + n);
        let delete_button = document.querySelector('#del_reserva_' + n);
        let delete_br = document.querySelector('#br_' + n);
        delete_selection.remove();
        delete_button.remove();
        delete_br.remove();
    });

    let form_elements = document.querySelectorAll('select');
    for (let i=0;i<form_elements.length;i++){
        form_elements[i].addEventListener('click', update_preview)
        form_elements[i].addEventListener('blur', update_preview)
    }

    let br = document.createElement('br');
    br.setAttribute('id', 'br_' + n);
    section.appendChild(br)
}

function update_preview() {
    // select preview elements
    let preview = document.querySelectorAll('#preview span');
    // check if there are more than 1 reservation
    let extra = false
    let reserva = document.querySelectorAll('select');
    if (reserva.length > 1) {
        extra = true
    };
    // loop to update preview elements
    for (let i=0; i<(preview.length); i++) {

        let val_id = preview[i].getAttribute('id');
        val_id = val_id.slice(0,-2);

        if (val_id == 'saldo') {
            let total = document.querySelector('#total').value;
            let abono = document.querySelector('#abono').value;
            let saldo = total - abono;
            preview[i].innerHTML = "$" + saldo;
        } else if (val_id == 'reserva' && extra) { //if there are more than 1 reservation
            let real_value = document.querySelector('#' + val_id);
            preview[i].innerHTML = real_value.value;
            for (let j=1; j<reserva.length; j++){
                real_value = document.querySelector('#'+val_id+'_'+j)
                preview[i].innerHTML += ', ' + real_value.value;
            }
        //////// 
        } else {  
            let real_value = document.querySelector('#' + val_id);
            if (val_id == 'total' || val_id == 'abono' && real_value.value !== '') {
                preview[i].innerHTML = "$" + real_value.value;
            } else if (real_value.value === ''){
                preview[i].innerHTML = "Dato faltante"
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

let ini_date = document.querySelector('#ini_date');
let end_date = document.querySelector('#end_date');
const today = new Date().toJSON().slice(0, 10);
ini_date.setAttribute('min',today)
end_date.setAttribute('min',today)

// add listeners to update preview
let form_elements = document.querySelectorAll('input, select, textarea');
for (let i=0;i<form_elements.length;i++){
    form_elements[i].addEventListener('click', update_preview)
    form_elements[i].addEventListener('blur', update_preview)
}

update_preview()
