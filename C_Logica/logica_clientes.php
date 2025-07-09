<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_clientes.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_clientes.php';

header('Content-Type: application/json');

$crudCliente = new ClienteDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        registrarCliente();
        break;

    case 'GET':
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'listar':
                    listarClientes();
                    break;
                case 'obtenerCliente':
                    if (isset($_GET['id'])) {
                        obtenerClientePorId($_GET['id']);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Falta el parámetro id.'
                        ]);
                    }
                    break;
                default:
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Acción GET no reconocida.'
                    ]);
                    break;
            }
        } else {
            listarClientes();
        }
        break;

    case 'PUT':
        actualizarCliente();
        break;

    case 'PATCH':
        actualizarEstadoCliente();
        break;

    case 'DELETE':
        eliminarCliente();
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        break;
}

function registrarCliente()
{
    global $crudCliente;
    $cliente = new Cliente();
    $inputData = json_decode(file_get_contents('php://input'), true);
    $cliente->setNombre($inputData['nombre'] ?? '');
    $cliente->setApellidos($inputData['apellidos'] ?? '');
    $cliente->setDni($inputData['dni'] ?? '');
    $cliente->setCorreo($inputData['correo'] ?? '');
    $cliente->setTelefono($inputData['telefono'] ?? '');
    $cliente->setDireccion($inputData['direccion'] ?? '');
    $cliente->setEstado($inputData['estado'] ?? '');
    try {
        $crudCliente->insertar($cliente);
        echo json_encode([
            'status' => 'success',
            'message' => 'Cliente registrado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar cliente: ' . $e->getMessage()
        ]);
    }
}

function listarClientes()
{
    global $crudCliente;
    try {
        $clientes = $crudCliente->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];
        foreach ($clientes as $cliente) {
            $response['data'][] = [
                'id_cliente' => $cliente->getIdCliente(),
                'nombre' => $cliente->getNombre(),
                'apellidos' => $cliente->getApellidos(),
                'dni' => $cliente->getDni(),
                'correo' => $cliente->getCorreo(),
                'telefono' => $cliente->getTelefono(),
                'direccion' => $cliente->getDireccion(),
                'estado' => $cliente->getEstado()
            ];
        }
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener clientes: ' . $e->getMessage()
        ]);
    }
}

function actualizarCliente()
{
    global $crudCliente;
    $putData = json_decode(file_get_contents("php://input"), true);
    if ($putData === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudieron leer los datos de la solicitud'
        ]);
        return;
    }
    $cliente = new Cliente();
    $cliente->setIdCliente($putData['id_cliente_act'] ?? '');
    $cliente->setNombre($putData['nombre_act'] ?? '');
    $cliente->setApellidos($putData['apellidos_act'] ?? '');
    $cliente->setDni($putData['dni_act'] ?? '');
    $cliente->setCorreo($putData['correo_act'] ?? '');
    $cliente->setTelefono($putData['telefono_act'] ?? '');
    $cliente->setDireccion($putData['direccion_act'] ?? '');
    $cliente->setEstado($putData['estado_act'] ?? '');
    try {
        $crudCliente->actualizar($cliente);
        echo json_encode([
            'status' => 'success',
            'message' => 'Cliente actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar cliente: ' . $e->getMessage()
        ]);
    }
}

function actualizarEstadoCliente()
{
    global $crudCliente;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id_cliente'] ?? $data['id'] ?? '';
    $estado = $data['estado'] ?? 'inactivo';
    if (empty($id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Falta el parámetro id_cliente o id.'
        ]);
        return;
    }
    try {
        $crudCliente->actualizarEstado($id, $estado);
        echo json_encode([
            'status' => 'success',
            'message' => 'Estado actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar estado: ' . $e->getMessage()
        ]);
    }
}

function eliminarCliente()
{
    global $crudCliente;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? '';
    try {
        $crudCliente->eliminar($id);
        echo json_encode([
            'status' => 'success',
            'message' => 'Cliente eliminado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar cliente: ' . $e->getMessage()
        ]);
    }
}

function obtenerClientePorId($id)
{
    global $crudCliente;
    try {
        $cliente = $crudCliente->obtenerPorId($id);
        if ($cliente) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'id_cliente' => $cliente->getIdCliente(),
                    'nombre' => $cliente->getNombre(),
                    'apellidos' => $cliente->getApellidos(),
                    'dni' => $cliente->getDni(),
                    'correo' => $cliente->getCorreo(),
                    'telefono' => $cliente->getTelefono(),
                    'direccion' => $cliente->getDireccion(),
                    'estado' => $cliente->getEstado()
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Cliente no encontrado.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener cliente: ' . $e->getMessage()
        ]);
    }
    exit();
}
