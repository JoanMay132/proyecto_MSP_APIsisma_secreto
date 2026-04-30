<?php

	
	class Imagen  {


		//Validar imagen
		public function validar_img($tipo): bool
		{
		
			$permitidos = array("image/jpg", "image/jpeg", "image/png", "image/JPG", "image/JPEG", "image/PNG", "IMAGE/jpg", "IMAGE/jpeg", "IMAGE/png", "IMAGE/JPG", "IMAGE/JPEG", "IMAGE/PNG");

			if (in_array($tipo, $permitidos)){	//@copy ($ruta,$destino);	
				return true; 
			}
			return false;
		
		}

		//Validar PDF
		public function validar_pdf($tipo,$ruta,$destino)
		{
		
			$permitidos = array("application/pdf","" );

			if (in_array($tipo, $permitidos)){	@copy ($ruta,$destino);	return true; }
		
		}



		}

?>