<?php
    class Fecha
    {
     
        public static function convertir($fecha) {

             // Array con los nombres de los meses en español
            $meses_espanol = array(
                'Jan' => 'ene',
                'Feb' => 'feb',
                'Mar' => 'mar',
                'Apr' => 'abr',
                'May' => 'may',
                'Jun' => 'jun',
                'Jul' => 'jul',
                'Aug' => 'ago',
                'Sep' => 'sep',
                'Oct' => 'oct',
                'Nov' => 'nov',
                'Dec' => 'dic'
            );

            $timestamp = strtotime($fecha);

            // Formatear el timestamp en el formato deseado
            $cfecha = date('d-M-y', $timestamp);
            return strtr($cfecha, $meses_espanol);
        }
    }
?>