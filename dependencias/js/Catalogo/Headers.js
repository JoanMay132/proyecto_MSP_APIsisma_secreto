function Preview(){

    //Input de formulario
    let tititleDesc = document.getElementById('titulodesc');
    let descripcion = document.getElementById('fdescripcion');
    let texto1 = document.getElementById('ftexto1');
    let texto2 = document.getElementById('ftexto2');
    let texto3 = document.getElementById('ftexto3');
    let texto4 = document.getElementById('ftexto4');


    //Display
    let displayTituolodesc = document.getElementById('display-titulodesc');
    let displayDescripcion = document.getElementById('descripcion');
    let displayTexto1 = document.getElementById('texto1');
    let displayTexto2 = document.getElementById('texto2');
    let displayTexto3 = document.getElementById('texto3');
    let displayTexto4 = document.getElementById('texto4');

    //descripcion = descripcion.replace(/\n/g, '<br>');

    

    displayTituolodesc.textContent = tititleDesc.value == '' ? 'Titulo de la descripción (vacio)' : tititleDesc.value ;
    displayDescripcion.innerHTML = descripcion.value === '' ? 'Descripción (vacio)' : descripcion.value.replace(/\n/g, '<br>') ;
    displayTexto1.textContent = texto1.value == '' ? 'texto 1 (vacio)' : texto1.value ;
    displayTexto2.textContent = texto2.value == '' ? 'texto 2 (vacio)' : texto2.value ;
    displayTexto3.textContent = texto3.value == '' ? 'texto 3 (vacio)' : texto3.value ;
    displayTexto4.textContent = texto4.value == '' ? 'texto 4 (vacio)' : texto4.value ;
}