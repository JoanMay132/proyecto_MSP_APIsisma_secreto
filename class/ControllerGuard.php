<?php
/*
 * Protección común para los controladores AJAX (se incluye justo después de session_start).
 * - Si la sesión expiró, responde un JSON claro en lugar de fallar con error 500.
 * - Cualquier excepción no atrapada (por ejemplo un error de base de datos) se registra
 *   en el log de PHP y se responde como JSON, para que la pantalla muestre el mensaje
 *   y no se quede esperando.
 */
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

if (!function_exists('guard_json_error')) {
    function guard_json_error(string $mensaje, array $extra = array()): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode(array_merge(array("Error" => $mensaje, "error" => $mensaje), $extra));
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && empty($_SESSION['id_usuario'])) {
    guard_json_error('TU SESIÓN EXPIRÓ. INICIA SESIÓN NUEVAMENTE (EN OTRA PESTAÑA) E INTENTA DE NUEVO.', array("session_expired" => true));
    exit;
}

set_exception_handler(function (Throwable $e) {
    error_log('[' . ($_SERVER['SCRIPT_NAME'] ?? '') . '] ' . get_class($e) . ': ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
    guard_json_error('NO SE PUDO GUARDAR. REVISA LOS DATOS (FECHAS, PDA, CANTIDAD, UNIDAD) E INTENTA DE NUEVO.');
});
