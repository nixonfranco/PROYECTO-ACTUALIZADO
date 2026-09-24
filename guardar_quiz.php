<?php
// guardar_quiz.php
// Recibe (por fetch/JS) el puntaje final del "Desafío de Conocimiento
// Nutricional" y lo guarda en la tabla resultados_quiz, asociado al
// usuario con sesión activa (usuarios normales y administradores).

session_start();
require_once "conexion.php";

header('Content-Type: application/json; charset=utf-8');

function responder($codigo, $arreglo) {
    http_response_code($codigo);
    echo json_encode($arreglo);
    exit;
}

// Solo usuarios con sesión iniciada pueden registrar resultados
if (!isset($_SESSION['usuario_id'])) {
    responder(401, ["ok" => false, "error" => "Debes iniciar sesión para guardar tu resultado."]);
}

$datos = json_decode(file_get_contents('php://input'), true);
if (!is_array($datos)) {
    responder(400, ["ok" => false, "error" => "No se recibieron datos."]);
}

$puntaje = isset($datos['puntaje']) ? (int) $datos['puntaje'] : -1;
$total = isset($datos['total']) ? (int) $datos['total'] : 0;

if ($puntaje < 0 || $total <= 0 || $puntaje > $total) {
    responder(400, ["ok" => false, "error" => "Datos incompletos o no válidos."]);
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO resultados_quiz (usuario_id, puntaje, total_preguntas) VALUES (?, ?, ?)"
    );
    $stmt->execute([$_SESSION['usuario_id'], $puntaje, $total]);

    responder(200, [
        "ok" => true,
        "resultado" => [
            "id" => $pdo->lastInsertId(),
            "puntaje" => $puntaje,
            "total" => $total,
            "fecha" => date("d/m/Y H:i"),
        ],
    ]);
} catch (PDOException $e) {
    error_log("Error al guardar resultado del quiz: " . $e->getMessage());
    if ($e->getCode() === '42S02') {
        responder(500, ["ok" => false, "error" => "Falta la tabla resultados_quiz. Ejecuta tablas_imc_quiz.sql en phpMyAdmin."]);
    }
    if ($e->getCode() === '23000') {
        responder(409, ["ok" => false, "error" => "Tu cuenta ya no existe. Vuelve a iniciar sesión."]);
    }
    responder(500, ["ok" => false, "error" => "No se pudo guardar el resultado."]);
}
