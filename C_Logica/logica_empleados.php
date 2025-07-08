<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_empleados.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_empleados.php';

header('Content-Type: application/json');

$crudEmpleado = new EmpleadoDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        registrarEmpleado();
        break;

    case 'GET':
        if (isset($_GET['action'])) { 
            switch ($_GET['action']) {
                case 'listar':
                    listarEmpleados();
                    break;
                case 'obtener_roles_turnos':
                    obtenerRolesYTurnos();
                    break;
                case 'obtener_roles':
                    obtenerRoles();
                    break;
                case 'obtener_turnos':
                    obtenerTurnos();
                    break;
                case 'obtener_id_usuario':
                    obtenerIdUsuarioPorEmpleado();
                    break;
                default:
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Acción GET no reconocida.'
                    ]);
                    break;
            }
        } else {

            listarEmpleados(); 
        }
        break;

    case 'PUT':
        actualizarEmpleado();
        break;

    case 'PATCH':
        actualizarEstado();
        break;

    case 'DELETE':
        eliminarEmpleado();
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        break;
}

function registrarEmpleado()
{
    global $crudEmpleado;

    $empleado = new Empleado();

    $empleado->setNombre($_POST['nombre'] ?? '');
    $empleado->setApellidoPaterno($_POST['apePater'] ?? '');
    $empleado->setApellidoMaterno($_POST['apeMater'] ?? '');
    $empleado->setDni($_POST['dni'] ?? '');
    $empleado->setDireccion($_POST['direccion'] ?? '');
    $empleado->setTelefono($_POST['telefono'] ?? '');
    $empleado->setCorreo($_POST['correo'] ?? '');
    $empleado->setTipoEmpleado($_POST['id_usuario'] ?? ''); // id_usuario = id del usuario (rol)
    $empleado->setTurno($_POST['id_turno'] ?? ''); // id_turno
    $empleado->setEstado($_POST['estado'] ?? '1');

    try {
        $crudEmpleado->insertar($empleado);
        echo json_encode([
            'status' => 'success',
            'message' => 'Empleado registrado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar empleado: ' . $e->getMessage()
        ]);
    }
}


function obtenerRolesYTurnos() {
    global $crudEmpleado; 

    try {
        $roles = $crudEmpleado->obtenerRoles();
        $turnos = $crudEmpleado->obtenerTurnos();

        echo json_encode([
            'status' => 'success',
            'roles' => $roles,
            'turnos' => $turnos
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener roles y turnos: ' . $e->getMessage()
        ]);
    }
    exit(); 
}

function obtenerRoles() {
    global $crudEmpleado;
    try {
        $roles = $crudEmpleado->obtenerRoles();
        echo json_encode([
            'status' => 'success',
            'roles' => $roles
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener roles: ' . $e->getMessage()
        ]);
    }
    exit();
}

function obtenerTurnos() {
    global $crudEmpleado;
    try {
        $turnos = $crudEmpleado->obtenerTurnos();
        echo json_encode([
            'status' => 'success',
            'turnos' => $turnos
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener turnos: ' . $e->getMessage()
        ]);
    }
    exit();
}

function listarEmpleados()
{
    global $crudEmpleado;

    try {
        $empleados = $crudEmpleado->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];

        foreach ($empleados as $empleado) {
            $response['data'][] = [
                'id' => $empleado->getId(),
                'nombre' => $empleado->getNombre(),
                'apePater' => $empleado->getApellidoPaterno(),
                'apeMater' => $empleado->getApellidoMaterno(),
                'dni' => $empleado->getDni(),
                'direccion' => $empleado->getDireccion(),
                'telefono' => $empleado->getTelefono(),
                'correo' => $empleado->getCorreo(),
                'id_usuario' => $empleado->getIdUsuario(), // id del usuario (rol)
                'rol' => $empleado->getTipoEmpleado(), // nombre del rol
                'turno' => $empleado->getTurno(),      // nombre del turno
                'estado' => $empleado->getEstado()
            ];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener empleados: ' . $e->getMessage()
        ]);
    }
}

function actualizarEmpleado()
{
    global $crudEmpleado;

    $putData = json_decode(file_get_contents("php://input"), true);

    if ($putData === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudieron leer los datos de la solicitud'
        ]);
        return;
    }

    $empleado = new Empleado();
    $empleado->setId($putData['idActualizarEmpleado'] ?? '');
    $empleado->setNombre($putData['nombre'] ?? '');
    $empleado->setApellidoPaterno($putData['apePater'] ?? '');
    $empleado->setApellidoMaterno($putData['apeMater'] ?? '');
    $empleado->setDni($putData['dni'] ?? '');
    $empleado->setDireccion($putData['direccion'] ?? '');
    $empleado->setTelefono($putData['telefono'] ?? '');
    $empleado->setCorreo($putData['correo'] ?? '');
    $empleado->setTipoEmpleado($putData['id_usuario'] ?? '');
    $empleado->setTurno($putData['id_turno'] ?? '');
    $empleado->setEstado($putData['estado'] ?? '');

    try {
        $crudEmpleado->actualizar($empleado);
        echo json_encode([
            'status' => 'success',
            'message' => 'Empleado actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar empleado: ' . $e->getMessage()
        ]);
    }
}

function actualizarEstado()
{
    global $crudEmpleado;

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';
    $estado = $data['estado'] ?? 'inactivo';

    try {
        $crudEmpleado->actualizarEstado($id, $estado);
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

function eliminarEmpleado()
{
    global $crudEmpleado;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? '';

    try {
        $crudEmpleado->eliminar($id);
        echo json_encode([
            'status' => 'success',
            'message' => 'Empleado eliminado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar empleado: ' . $e->getMessage()
        ]);
    }
}

function obtenerIdUsuarioPorEmpleado() {
    global $crudEmpleado;
    $id_empleado = $_GET['id_empleado'] ?? null;
    if (!$id_empleado) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID de empleado no proporcionado.'
        ]);
        return;
    }
    $id_usuario = $crudEmpleado->obtenerIdUsuarioPorIdEmpleado($id_empleado);
    echo json_encode([
        'status' => 'success',
        'id_usuario' => $id_usuario
    ]);
}
