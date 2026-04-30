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


}
