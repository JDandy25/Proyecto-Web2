<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_turnos.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_turnos.php';

header('Content-Type: application/json');

$crudTurno = new TurnoDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        registrarTurno();
        break;
    case 'GET':
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'listar':
                    listarTurnos();
                    break;
                case 'obtenerTurno':
                    if (isset($_GET['id'])) {
                        obtenerTurnoPorId($_GET['id']);
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
            listarTurnos();
        }
        break;
    case 'PUT':
        actualizarTurno();
        break;
    case 'PATCH':
        actualizarEstadoTurno();
        break;
    case 'DELETE':
        eliminarTurno();
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        break;
}

function registrarTurno()
{
    global $crudTurno;
    $turno = new Turno();
    $inputData = json_decode(file_get_contents('php://input'), true);
    $turno->setNombre($inputData['nombre'] ?? '');
    $turno->setHoraIngreso($inputData['horaIngreso'] ?? '');
    $turno->setHoraSalida($inputData['horaSalida'] ?? '');
    $turno->setEstado($inputData['estado'] ?? '');
    try {
        $crudTurno->insertar($turno);
        echo json_encode([
            'status' => 'success',
            'message' => 'Turno registrado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar turno: ' . $e->getMessage()
        ]);
    }
}

function listarTurnos()
{
    global $crudTurno;
    try {
        $turnos = $crudTurno->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];
        foreach ($turnos as $turno) {
            $response['data'][] = [
                'id_turno' => $turno->getIdTurno(),
                'nombre' => $turno->getNombre(),
                'horaIngreso' => $turno->getHoraIngreso(),
                'horaSalida' => $turno->getHoraSalida(),
                'estado' => $turno->getEstado()
            ];
        }
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener turnos: ' . $e->getMessage()
        ]);
    }
}

function actualizarTurno()
{
    global $crudTurno;
    $putData = json_decode(file_get_contents("php://input"), true);
    if ($putData === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudieron leer los datos de la solicitud'
        ]);
        return;
    }
    $turno = new Turno();
    $turno->setIdTurno($putData['id_turno_act'] ?? '');
    $turno->setNombre($putData['nombre_act'] ?? '');
    $turno->setHoraIngreso($putData['horaIngreso_act'] ?? '');
    $turno->setHoraSalida($putData['horaSalida_act'] ?? '');
    $turno->setEstado($putData['estado_act'] ?? '');
    try {
        $crudTurno->actualizar($turno);
        echo json_encode([
            'status' => 'success',
            'message' => 'Turno actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar turno: ' . $e->getMessage()
        ]);
    }
}

function actualizarEstadoTurno()
{
    global $crudTurno;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id_turno'] ?? $data['id'] ?? '';
    $estado = $data['estado'] ?? 'inactivo';
    if (empty($id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Falta el parámetro id_turno o id.'
        ]);
        return;
    }
    try {
        $crudTurno->actualizarEstado($id, $estado);
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

function eliminarTurno()
{
    global $crudTurno;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? '';
    try {
        $crudTurno->eliminar($id);
        echo json_encode([
            'status' => 'success',
            'message' => 'Turno eliminado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar turno: ' . $e->getMessage()
        ]);
    }
}

function obtenerTurnoPorId($id)
{
    global $crudTurno;
    try {
        $turno = $crudTurno->obtenerPorId($id);
        if ($turno) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'id_turno' => $turno->getIdTurno(),
                    'nombre' => $turno->getNombre(),
                    'horaIngreso' => $turno->getHoraIngreso(),
                    'horaSalida' => $turno->getHoraSalida(),
                    'estado' => $turno->getEstado()
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Turno no encontrado.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener turno: ' . $e->getMessage()
        ]);
    }
    exit();
}
