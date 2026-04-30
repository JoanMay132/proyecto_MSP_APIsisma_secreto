
//Convierte los input tipo text en valores porcentuales
function Porcentaje()
{
    var inputs = document.getElementsByClassName('porcentaje');

     // Recorre todos los elementos y agrega el evento 'input'
     for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener('change', function() {
          // Obtén el valor del input actual
          let inputValue = this.value;
  
          // Elimina caracteres no numéricos
          inputValue = inputValue.replace(/[^0-9]/g, '');
  
          // Añade el símbolo de porcentaje al final
          inputValue = inputValue + '%';
            
          inputValue = inputValue === "%"  ? "0%": inputValue;
          // Asigna el valor formateado de nuevo al input actual
          this.value = inputValue;
        });
      }
    
}

Porcentaje();