document.addEventListener("DOMContentLoaded", () => {
    const table = document.getElementById("tabla-clientes");
    const rows = Array.from(table.querySelectorAll("tbody tr"));
    const filters = {
        nombre: document.getElementById("filtro-nombre"),
        empresa: document.getElementById("filtro-empresa"),
        correo: document.getElementById("filtro-correo"),
        telefono: document.getElementById("filtro-telefono"),
    };

    Object.values(filters).forEach(input => {
        input.addEventListener("input", () => {
            const filterValues = {
                nombre: filters.nombre.value.toLowerCase(),
                empresa: filters.empresa.value.toLowerCase(),
                correo: filters.correo.value.toLowerCase(),
                telefono: filters.telefono.value.toLowerCase(),
            };

            rows.forEach(row => {
                const cells = row.querySelectorAll("td");
                const rowValues = {
                    nombre: cells[0]?.textContent.toLowerCase() || "",
                    empresa: cells[1]?.textContent.toLowerCase() || "",
                    correo: cells[2]?.textContent.toLowerCase() || "",
                    telefono: cells[3]?.textContent.toLowerCase() || "",
                };

                const isMatch = Object.keys(filterValues).every(
                    key => rowValues[key].includes(filterValues[key])
                );

                row.style.display = isMatch ? "" : "none";
            });
        });
    });
});

document.addEventListener("click", function (event) {
    const button = event.target.closest(".deleteForm button");
    if (button) {
        event.preventDefault();
        const form = button.closest(".deleteForm");
        
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¡No podrás revertir esta acción!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
});