function moneda(data)
{
    const currencyInput = data;

// Obtener el valor ingresado por el usuario
let inputValue = currencyInput.value;

// Eliminar cualquier carácter que no sea número o punto decimal
inputValue = inputValue.replace(/[^\d.]/g, '');

// Formatear el valor como moneda
const formattedValue = formatCurrency(inputValue);

// Actualizar el campo de texto con el valor formateado
currencyInput.value = formattedValue;



}

function formatCurrency(value) {
// Convertir el valor a un número decimal
const numberValue = parseFloat(value);

// Verificar si es un número válido
if (!isNaN(numberValue)) {
// Formatear como moneda con dos decimales y separador de miles
return numberValue.toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
} else {
return ''; // Valor no válido, devolver cadena vacía
}
}