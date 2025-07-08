
let listElements = document.querySelectorAll('.list__button--click');


//menu desplegable

listElements.forEach(listElement => {
    listElement.addEventListener('click', () => {
        listElement.classList.toggle('arrow');

        let height = 0;

        let menu = listElement.nextElementSibling;
        let padre = listElement.parentElement;

        if (menu.clientHeight == '0') {
            height = menu.scrollHeight;

        }

        if (menu.clientHeight != '0') {

        }

        menu.style.height = `${height}px`;

    })
});


//Cambio entre pestañas

const buttonContentPairs = [

    { selector: '.registrarCompra', contentId: 'contenidoRegistrarCompra' },
    { selector: '.buscarCompra', contentId: 'contenidoListaCompra' },
    { selector: '.btnEditar', contentId: 'modal' },

    { selector: '.registrarProducto', contentId: 'contenidoRegistrarProducto' },
    { selector: '.buscarProducto', contentId: 'contenidoListaProducto' },
    { selector: '.btnEditar', contentId: 'modal' },

    { selector: '.registrarEmpleado', contentId: 'contenidoRegistrarEmpleado' },
    { selector: '.buscarEmpleado', contentId: 'contenidoListaEmpleado' },
    { selector: '.btnEditar', contentId: 'modal' },

    { selector: '.registrarUsuario', contentId: 'contenidoRegistrarUsuario' },
    { selector: '.buscarUsuario', contentId: 'contenidoListaUsuario' },
    { selector: '.btnEditar', contentId: 'modal' },

    { selector: '.registrarCliente', contentId: 'contenidoRegistrarCliente' },
    { selector: '.buscarCliente', contentId: 'contenidoListaCliente' },
    { selector: '.btnEditar', contentId: 'modal' },

    { selector: '.registrarProveedor', contentId: 'contenidoRegistrarProveedor' },
    { selector: '.buscarProveedor', contentId: 'contenidoListaProveedor' },
    { selector: '.btnEditar', contentId: 'modal' },

    { selector: '.registrarCategoria', contentId: 'contenidoRegistrarCategoria' },
    { selector: '.buscarCategoria', contentId: 'contenidoListaCategoria' },
    { selector: '.btnEditar', contentId: 'modal' },

    { selector: '.registrarTurno', contentId: 'contenidoRegistrarTurno' },
    { selector: '.buscarTurno', contentId: 'contenidoListaTurno' },
    { selector: '.btnEditar', contentId: 'modal' },

];

buttonContentPairs.forEach(pair => {
    const buttons = document.querySelectorAll(pair.selector);
    buttons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            toggleContent(pair.contentId);
        });
    });
});

function toggleContent(contentId) {
    const contents = document.querySelectorAll('.contenedorContendido > div');
    contents.forEach(content => {
        if (content.classList.contains(contentId)) {
            // Cambia entre mostrar y ocultar el contenido actual
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');

            }
        } else {
            // Oculta todos los demás contenidos
            content.classList.add('hidden');

        }
    });
}


const btncanelar = document.querySelectorAll('.btncancelar');

btncanelar.forEach(btn => {
    btn.addEventListener('click', () => {
        toggleContent('contenidoInicio')
    });
});


const btncanelarActualizar = document.getElementById('btncancelarActualizarEmpleado');

btncanelarActualizar.addEventListener('click', (Event) => {
    Event.preventDefault();
    const dialogActualizar = document.getElementById('modalActualizarEmpleado');
    dialogActualizar.close();
});

const btncanelarActualizaru = document.getElementById('btncancelarActualizarUsuario');

btncanelarActualizaru.addEventListener('click', (Event) => {
    Event.preventDefault();
    const dialogActualizar = document.getElementById('modalActualizarUsuario');
    dialogActualizar.close();
});

const btncanelarActualizarc = document.getElementById('btncancelarActualizarCliente');

btncanelarActualizaru.addEventListener('click', (Event) => {
    Event.preventDefault();
    const dialogActualizar = document.getElementById('modalActualizarCliente');
    dialogActualizar.close();
});

const btncanelarActualizarp = document.getElementById('btncancelarActualizarProveedor');

btncanelarActualizaru.addEventListener('click', (Event) => {
    Event.preventDefault();
    const dialogActualizar = document.getElementById('modalActualizarProveedor');
    dialogActualizar.close();
});

const btncanelarActualizarcat = document.getElementById('btncancelarActualizarCategoria');

btncanelarActualizaru.addEventListener('click', (Event) => {
    Event.preventDefault();
    const dialogActualizar = document.getElementById('modalActualizarProveedor');
    dialogActualizar.close();
});

const btncanelarActualizartur = document.getElementById('btncancelarActualizarTurno');

btncanelarActualizaru.addEventListener('click', (Event) => {
    Event.preventDefault();
    const dialogActualizar = document.getElementById('modalActualizarTurno');
    dialogActualizar.close();
});

const btncanelarActualizarpro = document.getElementById('btncancelarActualizarProducto');

btncanelarActualizaru.addEventListener('click', (Event) => {
    Event.preventDefault();
    const dialogActualizar = document.getElementById('modalActualizarCompra');
    dialogActualizar.close();
});


const btncanelarActualizarCom = document.getElementById('btncancelarActualizarCompra');

btncanelarActualizar.addEventListener('click', (Event) => {
    Event.preventDefault();
    const dialogActualizar = document.getElementById('modalActualizarCompra');
    dialogActualizar.close();
});