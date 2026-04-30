<?php
class Conversion {
    private static $unidades = ["", "uno", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve"];
    private static $decenas = ["", "diez", "veinte", "treinta", "cuarenta", "cincuenta", "sesenta", "setenta", "ochenta", "noventa"];
    private static $centenas = ["", "ciento", "doscientos", "trescientos", "cuatrocientos", "quinientos", "seiscientos", "setecientos", "ochocientos", "novecientos"];
    private static $especiales = [11 => "once", 12 => "doce", 13 => "trece", 14 => "catorce", 15 => "quince", 16 => "dieciseis", 17 => "diecisiete", 18 => "dieciocho", 19 => "diecinueve"];
    private static $especiales2 = [21 => "veintiun", 22 => "veintidos", 23 => "veintitres", 24 => "veinticuatro", 25 => "veinticinco", 26 => "veintiseis", 27 => "veintisiete", 28 => "veintiocho", 29 => "veintinueve"];

    public static function convertirNumeroALetras($numero, $tipo = '') {
        if ($numero == 0 || $numero < 0) {
            return $tipo == 'PESOS' ? "CERO PESOS" : "CERO DÓLARES";
        }

        $entero = floor($numero);
        $decimal = round(($numero - $entero) * 100);

        $letrasEntero = self::convertirParte($entero, true);
        $letrasDecimal = $decimal > 0 ? " con " . self::convertirParte($decimal) . " centavos" : "";

        $tipoMon = $tipo == 'PESOS' ? "/100 M.N." : "/100 USD";

        return strtoupper($letrasEntero . " " . $tipo ." " .$decimal.$tipoMon);
    }

    private static function convertirParte($numero, $esCantidad = false) {
        if ($numero == 100) {
            return "cien";
        }

        if ($numero < 10) {
            // Si es parte de una cantidad como "pesos", cambiar "uno" a "un"
            return $esCantidad && $numero == 1 ? "un" : self::$unidades[$numero];
        } elseif ($numero != 10 && $numero < 20) {
            return self::$especiales[$numero];
        } elseif ($numero > 20 && $numero < 30) {
            return self::$especiales2[$numero];
        } elseif ($numero == 20 || $numero < 100) {
            $decena = floor($numero / 10);
            $unidad = $numero % 10;
            return self::$decenas[$decena] . ($unidad > 0 ? " y " . self::convertirParte($unidad, $esCantidad) : "");
        } elseif ($numero < 1000) {
            $centena = floor($numero / 100);
            $resto = $numero % 100;
            return self::$centenas[$centena] . ($resto > 0 ? " " . self::convertirParte($resto, $esCantidad) : "");
        } elseif ($numero < 1000000) {
            $miles = floor($numero / 1000);
            $resto = $numero % 1000;
            return ($miles == 1 ? "mil" : self::convertirParte($miles, true) . " mil") . ($resto > 0 ? " " . self::convertirParte($resto, $esCantidad) : "");
        } elseif ($numero < 1000000000) {
            $millones = floor($numero / 1000000);
            $resto = $numero % 1000000;
            return ($millones == 1 ? "un millón" : self::convertirParte($millones, true) . " millones") . ($resto > 0 ? " " . self::convertirParte($resto, $esCantidad) : "");
        } else {
            return "Número demasiado grande";
        }
    }
}

?>
