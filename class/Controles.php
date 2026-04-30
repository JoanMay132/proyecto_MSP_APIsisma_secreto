<?php

enum Controls: int{
    case sucursal = 1;
    case clientes = 2;
    case proveedores = 3;
    case empleado = 4;
    case revpreeliminar = 5;
    case cotizacion = 6;
    case ot = 7;
    case entregas = 8;
    case materiales = 9;
    case manobra = 10;
    case maquinaria = 11;
    case adicionales = 12;
    case analisiscosto = 13;

}

enum Operacion : int{
    case modifica = 1;
    case consulta = 2;
    case costo = 3;
}
