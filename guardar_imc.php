<?php
// guardar_imc.php
// Recibe (por fetch/JS) el resultado de la calculadora de IMC/TMB y lo
// guarda en la tabla resultados_imc, asociado al usuario con sesión activa
// (funciona igual para usuarios normales y administradores).

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

$peso = isset($datos['peso']) ? (float) $datos['peso'] : 0;
$estatura = isset($datos['estatura']) ? (float) $datos['estatura'] : 0;
if ($estatura > 3) { $estatura = $estatura / 100; } // por si llegara en centímetros
$genero = strtolower(trim($datos['genero'] ?? ''));
$imc = isset($datos['imc']) ? (float) $datos['imc'] : 0;
$clasificacion = isset($datos['clasificacion']) ? trim($datos['clasificacion']) : '';
$clasificacion = mb_substr($clasificacion, 0, 120); // la columna admite máximo 120 caracteres
$tmb = isset($datos['tmb']) ? (int) $datos['tmb'] : 0;

if (!in_array($genero, ['masculino', 'femenino'], true) || $clasificacion === '') {
    responder(400, ["ok" => false, "error" => "Datos incompletos o no válidos."]);
}
// Rangos razonables (también evitan desbordar las columnas DECIMAL de la tabla)
if ($peso < 1 || $peso > 500 || $estatura < 0.3 || $estatura > 2.8 || $imc < 1 || $imc > 150 || $tmb <= 0) {
    responder(400, ["ok" => false, "error" => "Los valores están fuera de un rango válido. Revisa peso (kg) y estatura (m)."]);
}

$peso = round($peso, 2);
$estatura = round($estatura, 2);
$imc = round($imc, 2);

try {
    $stmt = $pdo->prepare(
        "INSERT INTO resultados_imc (usuario_id, peso, estatura, genero, imc, clasificacion, tmb) VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$_SESSION['usuario_id'], $peso, $estatura, $genero, $imc, $clasificacion, $tmb]);

    responder(200, [
        "ok" => true,
        "resultado" => [
            "id" => $pdo->lastInsertId(),
            "peso" => $peso,
            "estatura" => $estatura,
            "imc" => number_format($imc, 1, '.', ''),
            "clasificacion" => $clasificacion,
            "tmb" => $tmb,
            "fecha" => date("d/m/Y H:i"),
        ],
    ]);
} catch (PDOException $e) {
    error_log("Error al guardar resultado de IMC: " . $e->getMessage());
    if ($e->getCode() === '42S02') {
        responder(500, ["ok" => false, "error" => "Falta la tabla resultados_imc. Ejecuta tablas_imc_quiz.sql en phpMyAdmin."]);
    }
    if ($e->getCode() === '23000') {
        responder(409, ["ok" => false, "error" => "Tu cuenta ya no existe. Vuelve a iniciar sesión."]);
    }
    responder(500, ["ok" => false, "error" => "No se pudo guardar el resultado."]);
}
