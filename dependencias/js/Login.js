document.getElementById("form-login").addEventListener("submit", function (event) {
    event.preventDefault();

    let button = document.getElementById('ingresar');
        button.textContent = "Ingresando...";
        var formData = new FormData($('#form-login')[0]);
        setTimeout(() => {
            $.ajax({
                type: "POST",
                data: formData,
                url: "class/Login.php",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: async function (respuesta) {
                    button.textContent = "Ingresar";
                    let icono = '';
                    let mensaje = '';
                    
                    if(respuesta.ref){
                        location.href = respuesta.ref;
        
                        return true;
                    }
                    if(respuesta.Error){
                            icono = 'error';
                            mensaje = respuesta.Error;

                    }else if(respuesta.Pendiente){
                            icono = 'info';
                            mensaje = respuesta.Pendiente;
                           
                    }
                   
                    await Swal.fire({
                        position: 'top-end',
                        icon: icono,
                        text: mensaje ,
                        showConfirmButton: false,
                        width: 'auto',
                        position: 'center',
                        timer: 1900,
                    });
                   
                   
                }
            });
        }, 800);
    
   

    return false;
});