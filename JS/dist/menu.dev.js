"use strict";

var listElements = document.querySelectorAll('.list__button--click'); //menu desplegable

listElements.forEach(function (listElement) {
  listElement.addEventListener('click', function () {
    listElement.classList.toggle('arrow');
    var height = 0;
    var menu = listElement.nextElementSibling;
    var padre = listElement.parentElement;

    if (menu.clientHeight == '0') {
      height = menu.scrollHeight;
    }

    if (menu.clientHeight != '0') {}

    menu.style.height = "".concat(height, "px");
  });
}); //Cambio entre pestañas

var buttonContentPairs = [{
  selector: '.registrarCompra',
  contentId: 'contenidoRegistrarCompra'
}, {
  selector: '.buscarCompra',
  contentId: 'contenidoListaCompra'
}, {
  selector: '.btnEditar',
  contentId: 'modal'
}, {
  selector: '.registrarProducto',
  contentId: 'contenidoRegistrarProducto'
}, {
  selector: '.buscarProducto',
  contentId: 'contenidoListaProducto'
}, {
  selector: '.btnEditar',
  contentId: 'modal'
}, {
  selector: '.registrarEmpleado',
  contentId: 'contenidoRegistrarEmpleado'
}, {
  selector: '.buscarEmpleado',
  contentId: 'contenidoListaEmpleado'
}, {
  selector: '.btnEditar',
  contentId: 'modal'
}, {
  selector: '.registrarUsuario',
  contentId: 'contenidoRegistrarUsuario'
}, {
  selector: '.buscarUsuario',
  contentId: 'contenidoListaUsuario'
}, {
  selector: '.btnEditar',
  contentId: 'modal'
}, {
  selector: '.registrarCliente',
  contentId: 'contenidoRegistrarCliente'
}, {
  selector: '.buscarCliente',
  contentId: 'contenidoListaCliente'
}, {
  selector: '.btnEditar',
  contentId: 'modal'
}, {
  selector: '.registrarProveedor',
  contentId: 'contenidoRegistrarProveedor'
}, {
  selector: '.buscarProveedor',
  contentId: 'contenidoListaProveedor'
}, {
  selector: '.btnEditar',
  contentId: 'modal'
}, {
  selector: '.registrarCategoria',
  contentId: 'contenidoRegistrarCategoria'
}, {
  selector: '.buscarCategoria',
  contentId: 'contenidoListaCategoria'
}, {
  selector: '.btnEditar',
  contentId: 'modal'
}, {
  selector: '.registrarTurno',
  contentId: 'contenidoRegistrarTurno'
}, {
  selector: '.buscarTurno',
  contentId: 'contenidoListaTurno'
}, {
  selector: '.btnEditar',
  contentId: 'modal'
}, {
  selector: '.verReporteCompras',
  contentId: 'contenidoReportes'
}];
buttonContentPairs.forEach(function (pair) {
  var buttons = document.querySelectorAll(pair.selector);
  buttons.forEach(function (button) {
    button.addEventListener('click', function (event) {
      event.preventDefault();
      toggleContent(pair.contentId);
    });
  });
});

function toggleContent(contentId) {
  var contents = document.querySelectorAll('.contenedorContendido > div');
  contents.forEach(function (content) {
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

var btncanelar = document.querySelectorAll('.btncancelar');
btncanelar.forEach(function (btn) {
  btn.addEventListener('click', function () {
    toggleContent('contenidoInicio');
  });
});
var btncanelarActualizar = document.getElementById('btncancelarActualizarEmpleado');
btncanelarActualizar.addEventListener('click', function (Event) {
  Event.preventDefault();
  var dialogActualizar = document.getElementById('modalActualizarEmpleado');
  dialogActualizar.close();
});
var btncanelarActualizaru = document.getElementById('btncancelarActualizarUsuario');
btncanelarActualizaru.addEventListener('click', function (Event) {
  Event.preventDefault();
  var dialogActualizar = document.getElementById('modalActualizarUsuario');
  dialogActualizar.close();
});
var btncanelarActualizarc = document.getElementById('btncancelarActualizarCliente');
btncanelarActualizaru.addEventListener('click', function (Event) {
  Event.preventDefault();
  var dialogActualizar = document.getElementById('modalActualizarCliente');
  dialogActualizar.close();
});
var btncanelarActualizarp = document.getElementById('btncancelarActualizarProveedor');
btncanelarActualizaru.addEventListener('click', function (Event) {
  Event.preventDefault();
  var dialogActualizar = document.getElementById('modalActualizarProveedor');
  dialogActualizar.close();
});
var btncanelarActualizarcat = document.getElementById('btncancelarActualizarCategoria');
btncanelarActualizaru.addEventListener('click', function (Event) {
  Event.preventDefault();
  var dialogActualizar = document.getElementById('modalActualizarProveedor');
  dialogActualizar.close();
});
var btncanelarActualizartur = document.getElementById('btncancelarActualizarTurno');
btncanelarActualizaru.addEventListener('click', function (Event) {
  Event.preventDefault();
  var dialogActualizar = document.getElementById('modalActualizarTurno');
  dialogActualizar.close();
});
var btncanelarActualizarpro = document.getElementById('btncancelarActualizarProducto');
btncanelarActualizaru.addEventListener('click', function (Event) {
  Event.preventDefault();
  var dialogActualizar = document.getElementById('modalActualizarCompra');
  dialogActualizar.close();
});
var btncanelarActualizarCom = document.getElementById('btncancelarActualizarCompra');
btncanelarActualizar.addEventListener('click', function (Event) {
  Event.preventDefault();
  var dialogActualizar = document.getElementById('modalActualizarCompra');
  dialogActualizar.close();
});
//# sourceMappingURL=menu.dev.js.map
