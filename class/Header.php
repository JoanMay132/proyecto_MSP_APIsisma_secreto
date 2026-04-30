<?php
//Array simulando una tabla de la base de datos, en lo que se crea la tabla real
$letras = "MSP-A"; 

$header = [
  // Cotización
    ["pkheader" => 1, "sucursal" => 1, "control" => 6, "texto1" => "Formulario - Sistema de Gestión de Calidad / Villahermosa / Tabasco",
    "texto2" => "Cotización","texto3" => "MSP-50-40-01 Rev. Orig.", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
    "descripcion" => "CALLE OCHO, LT-1-C MZA-III, FRACCIONAMIENTO DEIT,<br>
 RIA. ANACLETO CANABAL 1RA. SECC.<br>
 CENTRO, TABASCO, CP. 86287<br>
 TEL: (993) 337 9968", "correo" => "ventas01@mspetroleros.com", "formato" => "MSP-50-40-01", "revision" => "REV. Orig", "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],

 // Oreden de Trabajo
    ["pkheader" => 2, "sucursal" => 1, "control" => 7, "texto1" => "Formulario - Sistema de Gestión de Calidad / Villahermosa / Tabasco",
    "texto2" => "Orden de Trabajo","texto3" => "MSP-30-40-03 Rev. Orig.", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
    "descripcion" => "CALLE OCHO, LT-1-C MZA-III, FRACCIONAMIENTO DEIT,<br>
 RIA. ANACLETO CANABAL 1RA. SECC.<br>
 CENTRO, TABASCO, CP. 86287<br>
 TEL: (993) 337 9968", "correo" => "ventas01@mspetroleros.com", "formato" => "MSP-30-40-03", "revision" => "REV. Orig.", "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],

 // Entrega
 ["pkheader" => 3, "sucursal" => 1, "control" => 8, "texto1" => "Formulario - Sistema de Gestión de Calidad / Villahermosa / Tabasco",
    "texto2" => "Entrega de Servicios / Materiales / Productos","texto3" => "MSP-50-40-07 Rev. Orig.", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
    "descripcion" => "CALLE OCHO, LT-1-C MZA-III, FRACCIONAMIENTO DEIT,<br>
 RIA. ANACLETO CANABAL 1RA. SECC.<br>
 CENTRO, TABASCO, CP. 86287<br>
 TEL: (993) 337 9968", "correo" => "ventas01@mspetroleros.com", "revision" => "REV. Orig","formato" => "MSP-50-40-07",  "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],

 ["pkheader" => 4, "sucursal" => 1, "control" => 14, "texto1" => "",
 "texto2" => "","texto3" => "MSP-40-40-02", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
 "descripcion" => "CALLE OCHO, LT-1-C MZA-III, FRACCIONAMIENTO DEIT,<br>
 RIA. ANACLETO CANABAL 1RA. SECC.<br>
 CENTRO, TABASCO, CP. 86287<br>
 TEL: (993) 337 9968", "correo" => "ventas01@mspetroleros.com", "formato" => "MSP-40-40-02", "revision" => "REV. Orig", 
 "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales", "nota" => "POR AUSENCIA DEL GERENTE GRAL. PUEDE FIRMAR GERENTE OPERATIVO"],

 ["pkheader" => 5, "sucursal" => 1, "control" => 15, "texto1" => "",
 "texto2" => "","texto3" => "MSP-40-40-03", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
 "descripcion" => "CALLE OCHO, LT-1-C MZA-III, FRACCIONAMIENTO DEIT,<br>
 RIA. ANACLETO CANABAL 1RA. SECC.<br>
 CENTRO, TABASCO, CP. 86287<br>
 TEL: (993) 337 9968", "correo" => "ventas01@mspetroleros.com", "formato" => "MSP-40-40-03", "revision" => "REV. Orig", 
 "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales","nota" => "POR AUSENCIA DEL GERENTE GRAL. PUEDE FIRMAR GERENTE OPERATIVO"],

   //SUCURSAL 2 BASE BELISARIO

  //  Cotización
 ["pkheader" => 6, "sucursal" => 2, "control" => 6, "texto1" => " Formulario - Sistema de Gestión de Calidad / Cd. del Carmen / Campeche",
 "texto2" => "Cotización","texto3" => "MSP-50-40-01 Rev. 1", "texto4" => "BELISARIO", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
 "descripcion" => "Calle Campeche No.9 por Quintana Roo y Chiapas,<br>
  Col. Belisario Domínguez<br>
  C.P. 24150, Cd. del Carmen, Campeche.<br>
   TEL: 938011809032", "correo" => "ventas02@mspetroleros.com", "formato" => "MSP-50-40-01", "revision" => "REV. Orig", "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],

   // Orden de trabajo
   ["pkheader" => 7, "sucursal" => 2, "control" => 7, "texto1" => " Formulario - Sistema de Gestión de Calidad / Cd. del Carmen / Campeche",
 "texto2" => "Orden de Trabajo","texto3" => "MSP-30-40-03 Rev. 1", "texto4" => "BELISARIO", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
 "descripcion" => "Calle Campeche No.9 por Quintana Roo y Chiapas,<br>
  Col. Belisario Domínguez<br>
  C.P. 24150, Cd. del Carmen, Campeche.<br>
   TEL: 938011809032", "correo" => "ventas02@mspetroleros.com", "formato" => "MSP-30-40-03", "revision" => "REV. Orig.", "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],

   //Entrega
   ["pkheader" => 8, "sucursal" => 2, "control" => 8, "texto1" => " Formulario - Sistema de Gestión de Calidad / Cd. del Carmen / Campeche",
   "texto2" => "Entrega de Servicios / Materiales / Productos","texto3" => "MSP-50-40-07 Rev. 1", "texto4" => "BELISARIO", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
   "descripcion" => "Calle Campeche No.9 por Quintana Roo y Chiapas,<br>
   Col. Belisario Domínguez<br>
   C.P. 24150, Cd. del Carmen, Campeche.<br>
   TEL: 938011809032", "correo" => "ventas02@mspetroleros.com", "revision" => "REV. Orig","formato" => "MSP-50-40-07",  "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],

   //Requisición
   ["pkheader" => 9, "sucursal" => 2, "control" => 14, "texto1" => "",
   "texto2" => "","texto3" => "MSP-40-40-02", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
   "descripcion" => "CALLE OCHO, LT-1-C MZA-III, FRACCIONAMIENTO DEIT,<br>
   RIA. ANACLETO CANABAL 1RA. SECC.<br>
   CENTRO, TABASCO, CP. 86287<br>
   TEL: (993) 337 9968", "correo" => "ventas01@mspetroleros.com", "formato" => "MSP-40-40-02", "revision" => "REV. Orig", 
   "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales", "nota" => "POR AUSENCIA DEL GERENTE GRAL. PUEDE FIRMAR JEFE DE PRODUCCION"],
  
   //Orden de compra
   ["pkheader" => 10, "sucursal" => 2, "control" => 15, "texto1" => "",
   "texto2" => "","texto3" => "MSP-40-40-03", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
   "descripcion" => "CALLE OCHO, LT-1-C MZA-III, FRACCIONAMIENTO DEIT,<br>
   RIA. ANACLETO CANABAL 1RA. SECC.<br>
   CENTRO, TABASCO, CP. 86287<br>
   TEL: (993) 337 9968", "correo" => "ventas01@mspetroleros.com", "formato" => "MSP-40-40-03", "revision" => "REV. Orig", 
   "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales","nota" => "POR AUSENCIA DEL GERENTE GRAL. PUEDE FIRMAR JEFE DE PRODUCCION"],

   //SUCURSAL 3 BASE 49

   ["pkheader" => 11, "sucursal" => 3, "control" => 6, "texto1" => " Formulario - Sistema de Gestión de Calidad / Cd. del Carmen / Campeche",
   "texto2" => "Cotización","texto3" => "MSP-50-40-01 Rev. 1", "texto4" => "BASE 49", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
   "descripcion" => "Calle Campeche No.9 por Quintana Roo y Chiapas,<br>
    Col. Belisario Domínguez<br>
    C.P. 24150, Cd. del Carmen, Campeche.<br>
     TEL: 938011809032", "correo" => "ventas02@mspetroleros.com", "formato" => "MSP-50-40-01", "revision" => "REV. Orig", "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],
  
     ["pkheader" => 12, "sucursal" => 3, "control" => 7, "texto1" => " Formulario - Sistema de Gestión de Calidad / Cd. del Carmen / Campeche",
   "texto2" => "Orden de Trabajo","texto3" => "MSP-30-40-03 Rev. 1", "texto4" => "BASE 49", "titulodesc" => "MSP MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
   "descripcion" => "Calle Campeche No.9 por Quintana Roo y Chiapas,<br>
    Col. Belisario Domínguez<br>
    C.P. 24150, Cd. del Carmen, Campeche.<br>
     TEL: 938011809032", "correo" => "ventas02@mspetroleros.com", "formato" => "MSP-30-40-03", "revision" => "REV. Orig.", "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],
  
     ["pkheader" => 13, "sucursal" => 3, "control" => 8, "texto1" => " Formulario - Sistema de Gestión de Calidad / Cd. del Carmen / Campeche",
     "texto2" => "Entrega de Servicios / Materiales / Productos","texto3" => "MSP-50-40-07 Rev. 1", "texto4" => "BASE 49", "titulodesc" => "MSP MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
     "descripcion" => "Calle Campeche No.9 por Quintana Roo y Chiapas,<br>
     Col. Belisario Domínguez<br>
     C.P. 24150, Cd. del Carmen, Campeche.<br>
     TEL: 938011809032", "correo" => "ventas02@mspetroleros.com", "revision" => "REV. Orig","formato" => "MSP-50-40-07",  "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],
  
     ["pkheader" => 14, "sucursal" => 3, "control" => 14, "texto1" => "",
     "texto2" => "","texto3" => "MSP-40-40-02", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
     "descripcion" => "CALLE OCHO, LT-1-C MZA-III, FRACCIONAMIENTO DEIT,<br>
     RIA. ANACLETO CANABAL 1RA. SECC.<br>
     CENTRO, TABASCO, CP. 86287<br>
     TEL: (993) 337 9968", "correo" => "ventas01@mspetroleros.com", "formato" => "MSP-40-40-02", "revision" => "REV. Orig", 
     "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales", "nota" => "POR AUSENCIA DEL GERENTE GRAL. PUEDE FIRMAR JEFE DE PRODUCCION"],
    
     ["pkheader" => 15, "sucursal" => 3, "control" => 15, "texto1" => "",
     "texto2" => "","texto3" => "MSP-40-40-03", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
     "descripcion" => "CALLE OCHO, LT-1-C MZA-III, FRACCIONAMIENTO DEIT,<br>
     RIA. ANACLETO CANABAL 1RA. SECC.<br>
     CENTRO, TABASCO, CP. 86287<br>
     TEL: (993) 337 9968", "correo" => "ventas01@mspetroleros.com", "formato" => "MSP-40-40-03", "revision" => "REV. Orig", 
     "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales", "nota" => "POR AUSENCIA DEL GERENTE GRAL. PUEDE FIRMAR JEFE DE PRODUCCION"],
  
  

  // Revision preeliminar
  ["pkheader" => 16, "sucursal" => 1, "control" => 5, "texto1" => "Formulario - Sistema de Gestión de Calidad / Villahermosa / Tabasco",
    "texto2" => "Revisión Preliminar del Contrato","texto3" => "MSP-50-40-02 Rev. Orig.", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
    "descripcion" => "", "correo" => "", "formato" => "MSP-50-40-02", "revision" => "REV. Orig", "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],

    ["pkheader" => 17, "sucursal" => 2, "control" => 5, "texto1" => "Formulario - Sistema de Gestión de Calidad / Cd. del Carmen / Campeche",
    "texto2" => "Revisión Preliminar del Contrato","texto3" => "MSP-50-40-02 Rev. Orig.", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
    "descripcion" => "", "correo" => "", "formato" => "MSP-50-40-02", "revision" => "REV. Orig", "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],

    ["pkheader" => 18, "sucursal" => 3, "control" => 5, "texto1" => "Formulario - Sistema de Gestión de Calidad / Cd. del Carmen / Campeche",
    "texto2" => "Revisión Preliminar del Contrato","texto3" => "MSP-50-40-02 Rev. Orig.", "texto4" => "", "titulodesc" => "MSP - MAQUINADOS Y SERVICIOS PETROLEROS S.A. DE C.V.",
    "descripcion" => "", "correo" => "", "formato" => "MSP-50-40-02", "revision" => "REV. Orig", "rfc"=>"RFC: MMS240523DE6<br>Régimen fiscal: 601 - General de Ley de Personas Morales"],
];