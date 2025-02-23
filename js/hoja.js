function tomorrow(date){ //funtcion to step one day 
    const newDate = new Date(date);
    newDate.setDate(date.getDate() + 1);
    return newDate;
}

function hoja_reservas(){
    let dias = $("#dias").val();
    $.ajax({
        url: "hoja_constructor.php",
        cache: false,
        method: "POST",
        data: {
            dias: dias //send a post with the span of days we want to check
        },        
        success: function(data){
            console.log(data);
            $("#hoja").empty(); //clear hoja de reservas
            $("#hoja").append("<tr>\n<th>Fecha</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th><th>C5</th><th>C6</th><th>C7</th><th>C8</th><th>C9</th><th>C10</th><th>C11</th><th>C12</th><th>C13</th><th>C14</th><th>C15</th><th>C16</th><th>Salón</th>\n</tr>"); //append headers of the table
            let hoy = new Date();
            let row_day = new Date(); //set a reference day for the hoja
            if (dias == 'todas'){
                dias = 90;
            }
            for (let i = 0; i<dias;i++){
                let row = "<tr>\n<td>" + row_day.toLocaleDateString('es-CL',{dateStyle: 'full'}) + "</td>"; //insert the date of the row
                for (let j = 1;j < 18;j++) { // insert each cabaña
                    row = row + "<td>";
                    data.forEach(reserva => { 
                        if (reserva.cab.includes(j) && new Date(reserva.inicio) <= row_day && new Date(reserva.final) > row_day){ // check if a reserva is in that cabaña and that day (in the row and column)
                            row = row + "<a href='view.php?id="+ encodeURI(reserva.reserva_id) +"'>" + reserva.nombre + "</a>"; // append with a link to the reservation info
                        }
                        console.log(reserva.cab.includes(j));
                        console.log(row_day);
                        console.log(reserva.inicio)
                        console.log(new Date(reserva.inicio))
                        console.log(new Date(reserva.final) > row_day)
                        
                    })
                    row = row + "</td>";
                    
                }
                row = row + "</tr>\n";
                $("#hoja").append(row);
                row_day = tomorrow(row_day);
                
                
            }
        }
        
    })
}


$(document).ready( hoja_reservas() ) // when the document is ready generate an hoja de reservas.