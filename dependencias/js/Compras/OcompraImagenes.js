//Imágenes de la orden de compra que se insertan en el PDF (debajo de observaciones)
(function () {
    const contenedor = document.getElementById('imagenes-oc');
    if (!contenedor) return;

    const ocompra = contenedor.dataset.ocompra;
    const ruta = "Controller/OcompraImagen.php";
    const lista = document.getElementById('listaImagenesOc');
    const nota = document.getElementById('notaImagenesOc');
    const input = document.getElementById('inputImagenesOc');

    function mensaje(icon, title, text = '') {
        return Swal.fire({ position: 'center', icon: icon, title: title, text: text, showConfirmButton: true, width: '500' });
    }

    function cargar() {
        $.ajax({
            type: "GET",
            url: ruta,
            data: { "ocompra": ocompra },
            dataType: "json",
            success: function (respuesta) {
                if (respuesta.error) {
                    nota.textContent = respuesta.error;
                    return;
                }
                pintar(respuesta);
            }
        });
    }

    function pintar(respuesta) {
        lista.innerHTML = '';

        respuesta.imagenes.forEach(function (img) {
            const item = document.createElement('div');
            item.style.cssText = 'position:relative;width:70px;height:70px;border:1px solid #BFBFBF;';
            item.title = img.nombre + (img.origen === 'dropbox' ? ' (Dropbox)' : '');

            const foto = document.createElement('img');
            foto.src = img.url;
            foto.style.cssText = 'width:100%;height:100%;object-fit:cover;cursor:pointer;';
            foto.addEventListener('click', () => window.open(img.url, '_blank'));
            item.appendChild(foto);

            if (img.origen === 'dropbox') {
                const badge = document.createElement('span');
                badge.innerHTML = '<i class="fa fa-dropbox"></i>';
                badge.style.cssText = 'position:absolute;left:2px;bottom:2px;background:#0061FF;color:white;font-size:10px;padding:0 3px;';
                item.appendChild(badge);
            } else {
                const borrar = document.createElement('button');
                borrar.type = 'button';
                borrar.innerHTML = '<i class="fa fa-trash-o"></i>';
                borrar.title = 'Eliminar imagen';
                borrar.style.cssText = 'position:absolute;right:0;top:0;border:none;background:rgba(255,255,255,.85);color:red;font-size:11px;padding:0 4px;cursor:pointer;';
                borrar.addEventListener('click', () => eliminar(img.id));
                item.appendChild(borrar);
            }

            lista.appendChild(item);
        });

        const total = respuesta.imagenes.length;
        let texto = total === 0 ? 'Sin imágenes.' : total + ' imagen(es).';
        if (total > respuesta.maxPdf) texto += ' En el PDF solo se incluyen las primeras ' + respuesta.maxPdf + '.';
        if (respuesta.dropbox) texto += ' Incluye las fotos de Dropbox con el folio de esta OC.';
        nota.textContent = texto;
    }

    input.addEventListener('change', function () {
        if (input.files.length === 0) return;

        const formData = new FormData();
        formData.append('accion', 'subir');
        formData.append('ocompra', ocompra);
        Array.from(input.files).forEach(file => formData.append('imagenes[]', file));

        nota.textContent = 'Subiendo imágenes...';
        $.ajax({
            type: "POST",
            url: ruta,
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (respuesta) {
                if (respuesta.error) {
                    mensaje('info', respuesta.error);
                } else if (respuesta.errores && respuesta.errores.length > 0) {
                    mensaje('warning', 'Algunas imágenes no se cargaron', respuesta.errores.join('\n'));
                }
                cargar();
            },
            error: function () {
                mensaje('error', 'Error al subir las imágenes');
                cargar();
            },
            complete: function () {
                input.value = '';
            }
        });
    });

    async function eliminar(id) {
        const confirmacion = await Swal.fire({
            icon: 'warning',
            title: '¿Eliminar imagen?',
            text: 'Ya no aparecerá en el PDF de la orden de compra',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33'
        });
        if (!confirmacion.isConfirmed) return;

        $.ajax({
            type: "POST",
            url: ruta,
            data: { "accion": "eliminar", "ocompra": ocompra, "id": id },
            dataType: "json",
            success: function (respuesta) {
                if (respuesta.error) mensaje('info', respuesta.error);
                cargar();
            }
        });
    }

    cargar();
})();
