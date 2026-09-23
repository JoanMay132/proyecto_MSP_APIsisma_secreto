<?php

/**
 * Imágenes adjuntas a una orden de compra.
 *
 * Origenes:
 *  - Subidas desde el sistema: dependencias/img/ocompras/{pkocompra}/
 *  - Carpeta sincronizada de Dropbox en el servidor (opcional). Dentro de DROPBOX_DIR
 *    se buscan subcarpetas o archivos identificados con el folio de la OC, por ejemplo
 *    "MSP-C14-26", "C14-26" o "14-26" (el folio 14/26).
 */
class OcompraImagenes {

    // Ruta de la carpeta de Dropbox sincronizada en el servidor (una por empresa/sistema), p. ej.
    // 'C:/Users/Compras/Dropbox/OrdenesCompra'. También se puede definir con la
    // variable de entorno MSP_DROPBOX_OC_DIR. Vacío = deshabilitado.
    const DROPBOX_DIR = '';

    const EXTENSIONES = ['jpg', 'jpeg', 'png', 'webp'];
    const MAX_SUBIDA_BYTES = 15 * 1024 * 1024;
    const MAX_IMAGENES_PDF = 24;

    private int $pkocompra;
    private string $folio;

    public function __construct(int $pkocompra, string $folio) {
        $this->pkocompra = $pkocompra;
        $this->folio = $folio;
    }

    public static function dirSubidas(int $pkocompra): string {
        return dirname(__DIR__) . '/dependencias/img/ocompras/' . $pkocompra;
    }

    public static function dirDropbox(): string {
        $dir = getenv('MSP_DROPBOX_OC_DIR') ?: self::DROPBOX_DIR;
        return ($dir !== '' && is_dir($dir)) ? rtrim($dir, '/\\') : '';
    }

    /**
     * Lista de imágenes: [['id', 'origen' => 'sistema'|'dropbox', 'nombre', 'ruta']]
     */
    public function listar(): array {
        $imagenes = [];

        $dir = self::dirSubidas($this->pkocompra);
        if (is_dir($dir)) {
            $archivos = $this->archivosImagen($dir);
            usort($archivos, fn($a, $b) => filemtime($a) <=> filemtime($b));
            foreach ($archivos as $ruta) {
                $imagenes[] = $this->item('sistema', $ruta);
            }
        }

        foreach ($this->archivosDropbox() as $ruta) {
            $imagenes[] = $this->item('dropbox', $ruta);
        }

        return $imagenes;
    }

    public function buscar(string $id): ?array {
        foreach ($this->listar() as $img) {
            if (hash_equals($img['id'], $id)) {
                return $img;
            }
        }
        return null;
    }

    /**
     * Guarda una imagen subida (ya validada) como JPEG reducido. Devuelve un mensaje de error o null.
     */
    public function guardarSubida(string $tmp, string $nombreOriginal): ?string {
        if (!is_uploaded_file($tmp)) {
            return "Archivo no válido: $nombreOriginal";
        }
        if (filesize($tmp) > self::MAX_SUBIDA_BYTES) {
            return "La imagen $nombreOriginal supera los 15 MB";
        }

        $jpeg = self::jpegReducido($tmp, 1600, 85);
        if ($jpeg === null) {
            return "Formato no soportado: $nombreOriginal (use JPG, PNG o WEBP)";
        }

        $dir = self::dirSubidas($this->pkocompra);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
            return "No se pudo crear la carpeta de imágenes";
        }

        if (file_put_contents($dir . '/' . uniqid('', true) . '.jpg', $jpeg) === false) {
            return "No se pudo guardar $nombreOriginal";
        }

        return null;
    }

    public function eliminar(string $id): bool {
        $img = $this->buscar($id);
        // Las de Dropbox se administran desde Dropbox.
        if ($img === null || $img['origen'] !== 'sistema') {
            return false;
        }
        return @unlink($img['ruta']);
    }

    /**
     * Imágenes listas para dompdf: [['src' => data URI, 'w' => px, 'h' => px]]
     */
    public function paraPdf(): array {
        $imagenes = array_slice($this->listar(), 0, self::MAX_IMAGENES_PDF);
        $unaSola = count($imagenes) === 1;
        [$cajaW, $cajaH] = $unaSola ? [380, 260] : [250, 190];

        $resultado = [];
        foreach ($imagenes as $img) {
            $jpeg = self::jpegReducido($img['ruta'], 1200, 80);
            if ($jpeg === null) {
                continue;
            }
            [$w, $h] = getimagesizefromstring($jpeg);
            $escala = min($cajaW / $w, $cajaH / $h, 1);
            $resultado[] = [
                'src' => 'data:image/jpeg;base64,' . base64_encode($jpeg),
                'w' => (int) round($w * $escala),
                'h' => (int) round($h * $escala),
            ];
        }

        return $resultado;
    }

    /**
     * Lee la imagen, corrige la orientación EXIF y la reduce a $maxLado px. Devuelve JPEG binario o null.
     */
    public static function jpegReducido(string $ruta, int $maxLado, int $calidad): ?string {
        $info = @getimagesize($ruta);
        if ($info === false) {
            return null;
        }

        switch ($info[2]) {
            case IMAGETYPE_JPEG: $img = @imagecreatefromjpeg($ruta); break;
            case IMAGETYPE_PNG:  $img = @imagecreatefrompng($ruta); break;
            case IMAGETYPE_WEBP: $img = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($ruta) : false; break;
            default: $img = false;
        }
        if ($img === false) {
            return null;
        }

        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($ruta);
            $angulo = [3 => 180, 6 => -90, 8 => 90][$exif['Orientation'] ?? 1] ?? 0;
            if ($angulo !== 0) {
                $rotada = imagerotate($img, $angulo, 0);
                imagedestroy($img);
                $img = $rotada;
            }
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $escala = min($maxLado / max($w, $h), 1);
        $nw = (int) round($w * $escala);
        $nh = (int) round($h * $escala);

        // Fondo blanco para PNG/WEBP con transparencia.
        $salida = imagecreatetruecolor($nw, $nh);
        imagefill($salida, 0, 0, imagecolorallocate($salida, 255, 255, 255));
        imagecopyresampled($salida, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagedestroy($img);

        ob_start();
        imagejpeg($salida, null, $calidad);
        $jpeg = ob_get_clean();
        imagedestroy($salida);

        return $jpeg !== '' ? $jpeg : null;
    }

    private function item(string $origen, string $ruta): array {
        return [
            'id' => sha1($origen . '|' . $ruta),
            'origen' => $origen,
            'nombre' => basename($ruta),
            'ruta' => $ruta,
        ];
    }

    private function archivosImagen(string $dir): array {
        $archivos = [];
        foreach (scandir($dir) ?: [] as $nombre) {
            $ruta = $dir . DIRECTORY_SEPARATOR . $nombre;
            if ($nombre[0] !== '.' && is_file($ruta)
                && in_array(strtolower(pathinfo($nombre, PATHINFO_EXTENSION)), self::EXTENSIONES, true)) {
                $archivos[] = $ruta;
            }
        }
        return $archivos;
    }

    /**
     * Busca en la carpeta de Dropbox: subcarpetas con el folio (todas sus imágenes) y
     * archivos sueltos cuyo nombre empiece con el folio (p. ej. "MSP-C14-26 (2).jpg").
     */
    private function archivosDropbox(): array {
        $raiz = self::dirDropbox();
        $clave = self::claveFolio($this->folio);
        if ($raiz === '' || $clave === '') {
            return [];
        }

        $archivos = [];
        foreach (scandir($raiz) ?: [] as $nombre) {
            $ruta = $raiz . DIRECTORY_SEPARATOR . $nombre;
            if ($nombre[0] !== '.' && is_dir($ruta) && self::claveFolio($nombre) === $clave) {
                array_push($archivos, ...$this->archivosImagen($ruta));
            }
        }
        foreach ($this->archivosImagen($raiz) as $ruta) {
            $base = self::claveFolio(pathinfo($ruta, PATHINFO_FILENAME));
            if ($base === $clave || str_starts_with($base, $clave . '-')) {
                $archivos[] = $ruta;
            }
        }

        natcasesort($archivos);
        return array_values($archivos);
    }

    /**
     * "MSP-C14/26", "OC MSP-C14-26", "C14_26", "MSP-A14-26" y "14-26" -> "14-26"
     * (el prefijo de letra depende de la empresa: C, A, etc.)
     */
    public static function claveFolio(string $texto): string {
        $texto = strtoupper(trim($texto));
        $texto = preg_replace('/[^A-Z0-9]+/', '-', $texto);
        $texto = preg_replace('/^(OC-?)?(MSP-?)?([A-Z]-?)?/', '', trim($texto, '-'));
        return trim($texto, '-');
    }
}
