function addProduct() {
    const container = document.getElementById('productos-container');
    const template = document.getElementById('product-template');
    const clone = template.content.cloneNode(true);
    const select = clone.querySelector('select');

    // Limpiar y llenar el select con productos
    select.innerHTML = '<option value="">Elija el producto</option>';
    productosDisponibles.forEach(producto => {
        const option = document.createElement('option');
        option.value = producto.id_inventario;
        option.textContent = producto.nombre_producto;
        select.appendChild(option);
    });

    container.appendChild(clone);
    }

    function removeProduct(element) {
        const row = element.closest('.product-row');
        if (document.querySelectorAll('.product-row').length > 1) {
            row.remove();
        } else {
            alert("Debe haber al menos un producto");
        }
    }

    // Llenar el primer select que ya está en el HTML
    document.addEventListener('DOMContentLoaded', () => {
    const firstSelect = document.querySelector('#productos-container select');
    firstSelect.innerHTML = '<option value="">Elija el producto</option>';
    productosDisponibles.forEach(producto => {
        const option = document.createElement('option');
        option.value = producto.id_inventario;
        option.textContent = producto.nombre_producto;
        firstSelect.appendChild(option);
        });
    });
       