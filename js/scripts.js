
$(document).ready(function () {
    $('input[type="number"].form-control').on('blur', function () {
        calcularBtn();
    });
});

function calcularBtn() {
    const dosis_kg = [];
    const rows = document.querySelectorAll('tbody.products tr');
    const allresult = JSON.parse(document.querySelector('input[name="allresult"]').value);
    const compounds = JSON.parse(document.querySelector('input[name="compounds"]').value);
    const sumatoria_componentes = {};
    let sumaDosis = 0;


    rows.forEach(row => {
        const input = row.querySelector('input[name^="dosis_kg"]');
        if (input && input.value !== '' && input.value > 0) {
            const recover_value = parseFloat(input.value);
            sumaDosis += recover_value;
        }
    });

    const tbodyProducts = document.querySelector('tbody.products');
    let totalRow = document.querySelector('tr.total-row');
    if (!totalRow) {
        totalRow = document.createElement('tr');
        totalRow.className = 'total-row';
        totalRow.innerHTML = `
            <td><strong>Total Dosis</strong></td>
            <td id="total-dosis">0.00</td>
        `;
        tbodyProducts.insertBefore(totalRow, document.querySelector('tr.submit-row'));
    }
    document.getElementById('total-dosis').textContent = sumaDosis.toFixed(2);

    rows.forEach(row => {
        const name = row.querySelector('td').textContent;
        const input = row.querySelector('input[name^="dosis_kg"]');
        if (input && input.value !== '' && input.value > 0) {
            const recover_value = parseFloat(input.value);
            if (recover_value > 0) {
                allresult.forEach(product => {
                    if (product.Name === name) {
                        const valor_calculado = product.Value * recover_value;
                        const exists = dosis_kg.some(item => item.Name === product.ProductID);

                        if (!exists) {
                            dosis_kg.push({ Name: product.ProductID, Value: recover_value, Symbol: product.Name });
                        }
                        
                        compounds.forEach(compound => {
                            if (compound.Symbol === product.Symbol) {
                                if (!sumatoria_componentes[compound.Symbol]) {
                                    sumatoria_componentes[compound.Symbol] = 0;
                                }
                                sumatoria_componentes[compound.Symbol] += valor_calculado;
                            }
                        });
                    }
                });
            }
        }
    });

    Object.keys(sumatoria_componentes).forEach(symbol => {
        const originalValue = sumatoria_componentes[symbol];
        const percentajeNutrient = originalValue / 10;
        const updatedValue = Math.round((1000 * percentajeNutrient) / sumaDosis);

        sumatoria_componentes[symbol] = updatedValue;
    });

    const tbody = document.querySelector('#sumatoria_componentes tbody');
    const filaTotales = tbody.querySelector('tr');
    filaTotales.innerHTML = `<td><strong> % EN 100 KG </strong></td>`;
   
    compounds.forEach(compound => {
        const valor = sumatoria_componentes[compound.Symbol] || 0;
        filaTotales.innerHTML += `<td>${valor.toFixed(2)}</td>`;
    });
}

document.getElementById('enviar-btn').addEventListener('click', (e) => {
    const dosis_kg = [];
    const rows = document.querySelectorAll('tbody.products tr');
    const allresult = JSON.parse(document.querySelector('input[name="allresult"]').value);
    const compounds = JSON.parse(document.querySelector('input[name="compounds"]').value);
    const sumatoria_componentes = {};
    const nombre = document.getElementById('nombre').value.trim();
    const empresa = document.getElementById('empresa').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    let sumaDosis = 0;

    if (!nombre || !empresa || !correo || !telefono) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...!',
            text: 'Por favor, complete todos los campos del formulario de contacto.',  
            })
        return;
    }

    if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(correo)) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...!',
            text: 'Por favor, ingrese un correo electrónico válido.',  
            })
        return;
    }

    rows.forEach(row => {
        const input = row.querySelector('input[name^="dosis_kg"]');
        if (input && input.value !== '' && input.value > 0) {
            const recover_value = parseFloat(input.value);
            sumaDosis += recover_value;
        }
    });

    const tbodyProducts = document.querySelector('tbody.products');
    let totalRow = document.querySelector('tr.total-row');
    if (!totalRow) {
        totalRow = document.createElement('tr');
        totalRow.className = 'total-row';
        totalRow.innerHTML = `
            <td><strong>Total Dosis</strong></td>
            <td id="total-dosis">0.00</td>
        `;
        tbodyProducts.insertBefore(totalRow, document.querySelector('tr.submit-row'));
    }
    document.getElementById('total-dosis').textContent = sumaDosis.toFixed(2);

    rows.forEach(row => {
        const name = row.querySelector('td').textContent;
        const input = row.querySelector('input[name^="dosis_kg"]');
        if (input && input.value !== '' && input.value > 0) {
            const recover_value = parseFloat(input.value);
            if (recover_value > 0) {
                allresult.forEach(product => {
                    if (product.Name === name) {
                        const valor_calculado = product.Value * recover_value;
                        const exists = dosis_kg.some(item => item.Name === product.ProductID);

                        if (!exists) {
                            dosis_kg.push({ Name: product.ProductID, Value: recover_value, Symbol: product.Name });
                        }
                        
                        compounds.forEach(compound => {
                            if (compound.Symbol === product.Symbol) {
                                if (!sumatoria_componentes[compound.Symbol]) {
                                    sumatoria_componentes[compound.Symbol] = 0;
                                }
                                sumatoria_componentes[compound.Symbol] += valor_calculado;
                            }
                        });
                    }
                });
            }
        }
    });

    Object.keys(sumatoria_componentes).forEach(symbol => {
        const originalValue = sumatoria_componentes[symbol];
        const updatedValue = ((originalValue / 10) * 1000) / sumaDosis;
        sumatoria_componentes[symbol] = updatedValue; // Actualizamos el valor en sumatoria_componentes
    });

    const tbody = document.querySelector('#sumatoria_componentes tbody');
    const filaTotales = tbody.querySelector('tr'); // Primera fila
    filaTotales.innerHTML = `<td><strong> % EN 100 KG </strong></td>`;
   
    compounds.forEach(compound => {
        const valor = sumatoria_componentes[compound.Symbol] || 0;
        filaTotales.innerHTML += `<td>${valor.toFixed(2)}</td>`;
    });

    $.ajax({
        url: '../dao/procesar_datos.php',
        type: 'POST',
        data: {
            nombre: nombre,
            empresa: empresa,
            correo: correo,
            telefono: telefono,
            sumaDosis: sumaDosis,
            dosis_kg: JSON.stringify(dosis_kg),
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#resultados').html(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Registro exitoso!',
                        text: 'Datos guardados correctamente.',  
                        })
                    
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...!',
                        text: result.message,  
                        })
                }
            } catch (e) {
                console.error('Error al procesar la respuesta:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...!',
                    text: 'Ocurrió un error al guardar los datos.',  
                    })
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...!',
                text: 'Error al enviar los datos.',  
                })
        }
    });
});


(function ($) {
    function floatLabel(inputType) {
        $(inputType).each(function () {
            var $this = $(this);
            $this.on("focus", function () {
                $this.next().addClass("active");
            });
            $this.on("blur", function () {
                if ($this.val().trim() === '') {
                    $this.next().removeClass("active");
                }
            });
        });
    }
    floatLabel(".floatLabel");
})(jQuery);














