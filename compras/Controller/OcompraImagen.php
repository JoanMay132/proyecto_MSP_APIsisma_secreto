<?php @session_start();
include_once "../../controlador/conexion.php";
include_once "../../controlador/Ocompra.php";
include_once "../../class/OcompraImagenes.php";

//Imágenes de la orden de compra que se insertan en el PDF (debajo de observaciones)

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(["error" => "La sesión ha expirado, vuelva a iniciar sesión"]);
    return false;
}

$accion = $_REQUEST['accion'] ?? '';
$pkocompra = (int) base64_decode($_REQUEST['ocompra'] ?? '');

$oCompra = new Ocompra();
$orden = $pkocompra > 0 ? $oCompra->GetData($pkocompra) : false;
if (!$orden) {
    echo json_encode(["error" => "La orden de compra no es válida"]);
    return false;
}

$oImagenes = new OcompraImagenes($pkocompra, (string) $orden['folio']);

#region Miniatura para la pantalla de edición
if ($accion === 'ver') {
    $img = $oImagenes->buscar((string) ($_GET['id'] ?? ''));
    $jpeg = $img ? OcompraImagenes::jpegReducido($img['ruta'], 400, 75) : null;
    if ($jpeg === null) {
        http_response_code(404);
        return false;
    }
    header('Content-Type: image/jpeg');
    header('Cache-Control: private, max-age=3600');
    echo $jpeg;
    return true;
}
#endregion

header('Content-Type: application/json; charset=utf-8');

#region Subir imágenes
if ($accion === 'subir' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $errores = [];
    $archivos = $_FILES['imagenes'] ?? null;

    if (!$archivos || !is_array($archivos['name'])) {
        echo json_encode(["error" => "No se seleccionó ninguna imagen"]);
        return false;
    }

    foreach ($archivos['name'] as $i => $nombre) {
        if ($archivos['error'][$i] !== UPLOAD_ERR_OK) {
            $errores[] = "Error al cargar $nombre";
            continue;
        }
        $error = $oImagenes->guardarSubida($archivos['tmp_name'][$i], $nombre);
        if ($error !== null) {
            $errores[] = $error;
        }
    }

    echo json_encode(["success" => true, "errores" => $errores]);
    return true;
}
#endregion

#region Eliminar imagen (solo las subidas desde el sistema)
if ($accion === 'eliminar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($oImagenes->eliminar((string) ($_POST['id'] ?? ''))) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "No se pudo eliminar la imagen"]);
    }
    return true;
}
#endregion

#region Listado
$lista = array_map(function ($img) use ($orden) {
    return [
        "id" => $img['id'],
        "origen" => $img['origen'],
        "nombre" => $img['nombre'],
        "url" => "Controller/OcompraImagen.php?accion=ver&ocompra=" . urlencode(base64_encode($orden['pkocompra'])) . "&id=" . $img['id'],
    ];
}, $oImagenes->listar());

echo json_encode([
    "imagenes" => $lista,
    "dropbox" => OcompraImagenes::dirDropbox() !== '',
    "maxPdf" => OcompraImagenes::MAX_IMAGENES_PDF,
]);
#endregion
