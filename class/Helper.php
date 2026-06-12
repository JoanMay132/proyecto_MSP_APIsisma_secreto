<?php
class Helper{

    public static function val_input($data){
        $data = trim($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        return $data;
    }

    public static function val_correo($data){
        $data = trim($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        $data = filter_var($data,FILTER_SANITIZE_EMAIL);

        return $data;
    }

    public static function conver_float($data)
    {
        $cantidad_sin_formato = str_replace(['$', ','], '', $data);
        $numero_float = floatval($cantidad_sin_formato);
        return $numero_float;
    }

    public static function float($data)
    {
        $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data = floatval($data);

        return $data;
    }

    public static function porcentaje($data) {
        /*$locale = 'es_MX';

        try {
            // Formatear como porcentaje
            $percentageFormatter = new NumberFormatter($locale, NumberFormatter::PERCENT);
            $formattedPercentage = $percentageFormatter->format($data);
            
            if ($formattedPercentage === false) {
                throw new Exception('Error al formatear el porcentaje: ' . $percentageFormatter->getErrorMessage());
            }
            
            return $formattedPercentage;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }*/
        // Asegúrate de que el valor es numérico
        if (!is_numeric($data)) {
            return 'Error: El valor proporcionado no es numérico';
        }

        // Multiplicar por 100 y redondear a dos decimales
        $percentage = round($data * 100, 2);

        // Añadir el símbolo de porcentaje y formatear en español
        $formattedPercentage = number_format($percentage, 2, ',', '.') . '%';

        return $formattedPercentage;
    }

    public static function projectRootForPdf(): string {
        return realpath(dirname(__DIR__)) ?: dirname(__DIR__);
    }

    private static function imageToDataUri(string $path): string {
        if (!is_readable($path)) {
            return '';
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext === 'png' && function_exists('imagecreatefrompng')) {
            $image = @imagecreatefrompng($path);
            if ($image !== false) {
                $width = imagesx($image);
                $height = imagesy($image);
                $rgb = imagecreatetruecolor($width, $height);
                $white = imagecolorallocate($rgb, 255, 255, 255);
                imagefill($rgb, 0, 0, $white);
                imagecopy($rgb, $image, 0, 0, 0, 0, $width, $height);
                ob_start();
                imagejpeg($rgb, null, 92);
                $jpeg = ob_get_clean();
                imagedestroy($image);
                imagedestroy($rgb);
                if ($jpeg !== false && $jpeg !== '') {
                    return 'data:image/jpeg;base64,' . base64_encode($jpeg);
                }
            }
        }
        if ($ext === 'png') {
            return 'data:image/png;base64,' . base64_encode(file_get_contents($path));
        }
        if ($ext === 'svg') {
            return 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($path));
        }

        return '';
    }

    public static function logoSrcForPdf(): string {
        $imgDir = self::projectRootForPdf() . '/dependencias/img';
        foreach (['logo-msp.png', 'MSPlogo.png', 'Logo_Premium_Maquinados-04.png', 'Logo_Premium_Maquinados-04.svg'] as $file) {
            $uri = self::imageToDataUri($imgDir . '/' . $file);
            if ($uri !== '') {
                return $uri;
            }
        }

        return '';
    }

    public static function configureDompdf($dompdf): void {
        $root = self::projectRootForPdf();
        $tempDir = $root . '/dependencias/dompdf/tmp';
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0775, true);
        }

        $options = $dompdf->getOptions();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->setChroot($root);
        if (is_dir($tempDir) && is_writable($tempDir)) {
            $options->setTempDir($tempDir);
        }
        $dompdf->setOptions($options);
    }

    public static function lineasFirmaDigitalFormato(string $nombre, string $email = 'ventas01@petromaquinados.com'): array {
        $partes = array_values(array_filter(preg_split('/\s+/', trim(strtoupper($nombre)))));
        $n = count($partes);

        if ($n === 0) {
            return [];
        }

        if ($n === 1) {
            return [
                'Firmado digitalmente por ' . $partes[0],
                'DN: cn=' . $partes[0],
                'gcn=' . $partes[0] . ' c=MX Mexico l=MX Mexico',
                'o=MSP MAQUINADOS Y SERVICIOS PETROLEROS SA DE CV',
                'e=' . $email,
                'Motivo: Apruebo este documento',
            ];
        }

        $lineas = [
            'Firmado digitalmente por ' . $partes[0],
            implode(' ', array_slice($partes, 1)),
            'DN: cn=' . implode(' ', array_slice($partes, 0, $n - 1)),
            $partes[$n - 1] . ' gcn=' . $partes[0] . ' ' . $partes[1],
            $partes[$n - 2] . ' ' . $partes[$n - 1] . ' c=MX Mexico',
            'l=MX Mexico o=MSP MAQUINADOS Y',
            'SERVICIOS PETROLEROS SA DE CV',
            'e=' . $email,
            'Motivo: Apruebo este documento',
        ];

        return $lineas;
    }

    public static function htmlFirmaDigital(array $lineas): string {
        $html = '';
        foreach ($lineas as $linea) {
            $html .= '<p>' . htmlspecialchars($linea, ENT_QUOTES, 'UTF-8') . '</p>';
        }

        return $html;
    }


}
