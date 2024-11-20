//Variables
const carrito = document.getElementById("carrito"),
    listaProductos = document.getElementById("listaProductos"),
    contenedorCarrito = document.querySelector('.buy-card .lista_de_productos'),
    vaciarCarritoBtn = document.querySelector('#vaciar_carrito'),
    pagarbtn = document.querySelector('#pagar_Ahora');

let articulosCarrito = [];

// Cargar carrito desde localStorage al iniciar
document.addEventListener('DOMContentLoaded', () => {
    const carritoGuardado = localStorage.getItem('carrito');
    if (carritoGuardado) {
        articulosCarrito = JSON.parse(carritoGuardado);
        carritoHTML();
    }
    registrarEventsListeners();
});

document.getElementById('filtro-btn').addEventListener('click', function() {
    var filtroMenu = document.getElementById('filtro-menu');
    console.log('Filtro Button Clicked');  // Verificar si se detecta el clic
    if (filtroMenu.style.display === 'block') {
        filtroMenu.style.display = 'none';
    } else {
        filtroMenu.style.display = 'block';
    }
});


function registrarEventsListeners() {
    //Cuando yo le de click "agregar al carrito de compras"
    listaProductos.addEventListener('click', agregarProducto);

    //Eliminar cursos del carrito
    carrito.addEventListener('click', eliminarProducto);
    
    // Unificar los event listeners del vaciar carrito
    vaciarCarritoBtn.addEventListener('click', vaciarCarrito);

    //PAGAR 
    pagarbtn.addEventListener('click', finalizar_compra);
}

function cerrarSesion() {
    window.location.href = 'logout.php';
}

function vaciarCarrito() {
    articulosCarrito = [];
    localStorage.removeItem('carrito');
    limpiarHTML();
    mostrarMensaje('Carrito vaciado', 'info');
}

function agregarProducto(e) {
    if (e.target.classList.contains("agregar-carrito")) {
        const productoSeleccionado = e.target.parentElement.parentElement;
        leerInfo(productoSeleccionado);
        mostrarMensaje('Producto agregado al carrito', 'success');
    }
}

function eliminarProducto(e) {
    if (e.target.classList.contains("borrar-producto")) {
        const productoId = e.target.getAttribute('data-id');
        articulosCarrito = articulosCarrito.filter(producto => producto.id != productoId);
        guardarCarrito();
        carritoHTML();
        mostrarMensaje('Producto eliminado', 'info');
    }
}

function leerInfo(producto) {
    const infoProducto = {
        imagen: producto.querySelector('img').src,
        titulo: producto.querySelector('h3').textContent,
        precio: producto.querySelector('.descuento').textContent,
        id: producto.querySelector('button').getAttribute('data-id'),
        cantidad: 1
    }

    const existe = articulosCarrito.some(producto => producto.id === infoProducto.id);

    if (existe) {
        const productos = articulosCarrito.map(producto => {
            if (producto.id === infoProducto.id) {
                producto.cantidad++;
                return producto;
            }
            return producto;
        });
        articulosCarrito = [...productos];
    } else {
        articulosCarrito = [...articulosCarrito, infoProducto];
    }
    
    guardarCarrito();
    carritoHTML();
}

function carritoHTML() {
    limpiarHTML();
    
    articulosCarrito.forEach(producto => {
        const fila = document.createElement('div');
        fila.innerHTML = `
            <img src="${producto.imagen}" alt="${producto.titulo}">
            <p>${producto.titulo}</p>
            <p>${producto.precio}</p>
            <p>${producto.cantidad}</p>
            <p><span class="borrar-producto" data-id="${producto.id}">X</span></p>
        `;
        contenedorCarrito.appendChild(fila);
    });
}

function limpiarHTML() {
    while (contenedorCarrito.firstChild) {
        contenedorCarrito.removeChild(contenedorCarrito.firstChild);
    }
}

function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(articulosCarrito));
}

async function finalizar_compra() {
    if (articulosCarrito.length === 0) {
        mostrarMensaje('El carrito está vacío. No se puede procesar el pedido.', 'error');
        return;
    }

    const usuarioId = 1; // Cambia esto según cómo obtienes el ID del usuario
    const fechaPedido = new Date().toISOString(); // Fecha en formato ISO
    const total = articulosCarrito.reduce((acc, item) => acc + parseFloat(item.precio) * item.cantidad, 0);

    const pedido = {
        usuario_id: usuarioId,
        fecha_pedido: fechaPedido,
        estado: "Pendiente",
        total,
        articulos: articulosCarrito // Enviar también los productos del carrito
    };

    try {
        const response = await fetch('crearpedido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(pedido)
        });

        if (response.ok) {
            // Limpiar el carrito y mostrar mensaje
            vaciarCarrito();
            mostrarMensaje('Pedido realizado con éxito.', 'success');
            mostrarPopup('Tu pedido ha sido procesado correctamente.');
        } else {
            mostrarMensaje('Hubo un problema al procesar el pedido.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarMensaje('Error al procesar el pedido.', 'error');
    }
}

function mostrarPopup(mensaje) {
    const popup = document.createElement('div');
    popup.classList.add('popup');
    popup.textContent = mensaje;

    document.body.appendChild(popup);

    setTimeout(() => {
        popup.remove();
    }, 3000); // Elimina el popup después de 3 segundos
}
