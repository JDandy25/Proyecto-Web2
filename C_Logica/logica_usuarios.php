<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_usuarios.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_usuarios.php';

header('Content-Type: application/json');

$crudUsuario = new UsuarioDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        registrarUsuario();
        break;

    case 'GET':
        if (isset($_GET['action'])) { 
            switch ($_GET['action']) {
                case 'listar':
                    listarUsuarios();
                    break;
                case 'obtenerRoles':
                    obtenerRoles();
                    break;
                case 'obtenerUsuario':
                    if (isset($_GET['id'])) {
                        obtenerUsuarioPorId($_GET['id']);
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

            listarUsuarios(); // Si no hay acción, listar usuarios por defecto
        }
        break;

    case 'PUT':
        actualizarUsuario();
        break;

    case 'PATCH':
        actualizarEstado();
        break;

    case 'DELETE':
        eliminarUsuario();
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        break;
}

function registrarUsuario()
{
    global $crudUsuario;
    
    $usuario = new Usuario();

    $inputData = json_decode(file_get_contents('php://input'), true);

    $usuario->setUsuario($inputData['nombre_usuario'] ?? '');
    $usuario->setClave($inputData['contrasena'] ?? '');
    $usuario->setRol($inputData['id_rol'] ?? '');
    $usuario->setEstado($inputData['estado'] ?? '');


    try {
        $crudUsuario->insertar($usuario);
        $ultimo = $crudUsuario->obtenerUltimoUsuarioYRol();
        if ($ultimo) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Usuario registrado correctamente',
                'id_usuario' => $ultimo['id_usuario'],
                'nombre_rol' => $ultimo['nombre_rol']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se pudo obtener el usuario recién creado.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar usuario: ' . $e->getMessage()
        ]);
    }
}

function listarUsuarios()
{
    global $crudUsuario;

    try {
        $usuarios = $crudUsuario->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];

        foreach ($usuarios as $usuario) {
            $response['data'][] = [
                'id_usuario' => $usuario->getId(),
                'nombre' => $usuario->getUsuario(),
                'rol' => $usuario->getRol(),
                'estado' => $usuario->getEstado()
            ];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener usuarios: ' . $e->getMessage()
        ]);
    }
}

function actualizarUsuario()
{
    global $crudUsuario;

    $putData = json_decode(file_get_contents("php://input"), true);

    if ($putData === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudieron leer los datos de la solicitud'
        ]);
        return;
    }

    $usuario = new Usuario();
    $usuario->setId($putData['id_usuario_act'] ?? '');
    $usuario->setUsuario($putData['nombre_usuario_act'] ?? '');
    $usuario->setClave($putData['contrasena_act'] ?? '');
    $usuario->setRol($putData['id_rol_act'] ?? '');
    $usuario->setEstado($putData['estado_act'] ?? '');

    try {
        $crudUsuario->actualizar($usuario);
        echo json_encode([
            'status' => 'success',
            'message' => 'Usuario actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar usuario: ' . $e->getMessage()
        ]);
    }
}

function actualizarEstado()
{
    global $crudUsuario;

    $data = json_decode(file_get_contents('php://input'), true);
    // Aceptar tanto 'id_usuario' como 'id' como identificador
    $id = $data['id_usuario'] ?? $data['id'] ?? '';
    $estado = $data['estado'] ?? 'inactivo';

    if (empty($id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Falta el parámetro id_usuario o id.'
        ]);
        return;
    }

    try {
        $crudUsuario->actualizarEstado($id, $estado);
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

function eliminarUsuario()
{
    global $crudUsuario;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? '';

    try {
        $crudUsuario->eliminar($id);
        echo json_encode([
            'status' => 'success',
            'message' => 'Usuario eliminado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar usuario: ' . $e->getMessage()
        ]);
    }
}

function obtenerRoles() {
    global $crudUsuario;
    try {
        $roles = $crudUsuario->obtenerRoles();
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

function obtenerUsuarioPorId($id)
{
    global $crudUsuario;
    try {
        $usuario = $crudUsuario->obtenerPorId($id);
        if ($usuario) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'id_usuario' => $usuario->getId(),
                    'nombre' => $usuario->getUsuario(),
                    'id_rol' => $usuario->getRol(),
                    'estado' => $usuario->getEstado()
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Usuario no encontrado.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener usuario: ' . $e->getMessage()
        ]);
    }
    exit();
}