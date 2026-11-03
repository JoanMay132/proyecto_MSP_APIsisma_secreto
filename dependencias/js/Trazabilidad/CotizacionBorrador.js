/*
 * Borrador automático de cotizaciones (alta y edición).
 * - Respalda lo capturado en localStorage de este equipo mientras se escribe.
 * - Al volver a entrar ofrece recuperar el borrador.
 * - Avisa antes de cerrar/salir si hay cambios sin guardar.
 * - Mantiene viva la sesión mientras la pantalla está abierta.
 * Requiere Cotizacion.js (Sucursal, usercustomer, totales, etc.).
 */
(function () {
    const form = document.getElementById('form-cotizacion');
    if (!form) return;

    const PREFIJO = 'sisma_cot_borrador_';
    //En algunas versiones el id viene como pkcotizacion y en otras como cotizacion (oculto)
    const pkInput = form.querySelector("input[name='pkcotizacion']") || form.querySelector("input[type='hidden'][name='cotizacion']");
    const esEdicion = !!(pkInput && pkInput.value);
    const PROYECTO = location.pathname.split('/')[1];
    const BASE = PREFIJO + PROYECTO + '_' + (esEdicion ? 'edit_' + pkInput.value : 'nueva') + '__';
    const VIVO = 'sisma_cot_vivo_';
    const DIAS_MAX = 7;

    //Cada pestaña tiene su propio borrador (sessionStorage sobrevive a recargar la misma pestaña)
    let tabId = '';
    try {
        tabId = sessionStorage.getItem('sisma_cot_tab') || '';
        if (!tabId) {
            tabId = Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
            sessionStorage.setItem('sisma_cot_tab', tabId);
        }
    } catch (e) { tabId = Date.now().toString(36); }
    let CLAVE = BASE + tabId;

    let restaurando = false;
    let listo = false;          //No se guarda nada hasta terminar la carga inicial
    let timerGuardado = null;
    window.cotDirty = false;

    //#region utilidades
    function storage() {
        try { return window.localStorage; } catch (e) { return null; }
    }

    function esperarAjax(maxMs = 15000) {
        return new Promise(resolve => {
            const inicio = Date.now();
            (function revisar() {
                if (!window.jQuery || jQuery.active === 0 || Date.now() - inicio > maxMs) {
                    setTimeout(resolve, 150);
                } else {
                    setTimeout(revisar, 150);
                }
            })();
        });
    }

    function camposDe(raiz) {
        return Array.from(raiz.querySelectorAll('input[name], select[name], textarea[name]'))
            .filter(el => el.type !== 'hidden' && el.type !== 'file' && el.type !== 'button' && el.type !== 'submit');
    }

    function valorDe(el) {
        if (el.type === 'checkbox' || el.type === 'radio') return el.checked;
        return el.value;
    }

    function asignar(el, valor) {
        if (el.type === 'checkbox' || el.type === 'radio') { el.checked = !!valor; return; }
        if (el.tagName === 'SELECT' && valor !== '' && !Array.from(el.options).some(o => o.value === valor)) {
            return; //La opción ya no existe
        }
        el.value = valor;
    }

    function hora(ts) {
        const d = new Date(ts);
        return d.toLocaleDateString('es-MX') + ' ' + d.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
    }

    function status(texto) {
        const tag = document.getElementById('draftStatus');
        if (tag) tag.textContent = texto;
    }
    //#endregion

    //#region serializar
    function serializar() {
        const tbody = document.getElementById('serv-cotizacion');
        const generales = {};
        camposDe(form).forEach(el => {
            if (tbody && tbody.contains(el)) return;
            (generales[el.name] = generales[el.name] || []).push(valorDe(el));
        });

        const filas = [];
        if (tbody) {
            Array.from(tbody.querySelectorAll('tr')).forEach(tr => {
                const pk = tr.querySelector("[name='pkservcotizacion[]']");
                const campos = camposDe(tr).map(el => [el.name, valorDe(el)]);
                if (!campos.length) return;
                filas.push({ pk: pk ? pk.value : '', campos: campos });
            });
        }

        //En alta: id de la cotización si ya se le asignó folio (campo oculto agregado por setFolio o por un guardado fallido)
        const idOculto = !esEdicion ? form.querySelector("input[type='hidden'][name='cotizacion']") : null;

        return {
            v: 1,
            ts: Date.now(),
            cotizacion: idOculto ? idOculto.value : '',
            folioInput: (document.getElementById('folio') || {}).value || '',
            folio: (document.getElementById('displayFolio') || {}).textContent || '',
            generales: generales,
            filas: filas
        };
    }

    function tieneContenido(b) {
        if (!b) return false;
        const g = b.generales || {};
        const llenos = ['sucursal', 'cliente', 'titulo'].some(n => (g[n] || []).some(v => v));
        const filas = (b.filas || []).some(f => f.campos.some(([n, v]) => /descripcion/.test(n) && String(v).trim() !== ''));
        return llenos || filas;
    }

    window.guardarBorradorCot = function (forzar = false) {
        if (!listo || restaurando) return;
        if (!forzar && !window.cotDirty) return;
        const st = storage();
        if (!st) return;
        try {
            const b = serializar();
            if (!tieneContenido(b)) return;
            st.setItem(CLAVE, JSON.stringify(b));
            status('Borrador respaldado ' + hora(b.ts).split(' ')[1]);
        } catch (e) { }
    };

    window.limpiarBorradorCot = function () {
        window.cotDirty = false;
        const st = storage();
        if (!st) return;
        try {
            st.removeItem(CLAVE);
            //Al guardar una cotización nueva también se limpia el borrador de alta de esta pestaña
            st.removeItem(PREFIJO + PROYECTO + '_nueva__' + tabId);
        } catch (e) { }
        status('');
    };
    //#endregion

    //#region restaurar
    async function asignarGenerales(generales, nombres) {
        nombres.forEach(n => {
            const els = Array.from(form.querySelectorAll('[name="' + n + '"]'))
                .filter(el => el.type !== 'hidden' && !el.closest('#serv-cotizacion'));
            (generales[n] || []).forEach((v, i) => { if (els[i]) asignar(els[i], v); });
        });
    }

    function filaVacia(tr) {
        return !tr.querySelector("[name='pkservcotizacion[]']") && camposDe(tr).every(el => !String(el.value).trim());
    }

    function agregarFilaNueva() {
        const tbody = document.getElementById('serv-cotizacion');
        const origen = Array.from(tbody.querySelectorAll('tr')).find(tr => (tr.getAttribute('ondblclick') || '').includes('addServcot'));
        if (origen && typeof origen.ondblclick === 'function') origen.ondblclick.call(origen);
    }

    async function restaurar(b) {
        restaurando = true;
        const g = b.generales || {};
        const v = n => (g[n] || [''])[0];

        //1. Sucursal y listas dependientes (solo en alta; en edición ya vienen cargadas)
        if (!esEdicion && v('sucursal')) {
            asignarGenerales(g, ['sucursal']);
            if (typeof Sucursal === 'function') { Sucursal(v('sucursal')); await esperarAjax(); }
        }
        //2. Cliente, usuarios y áreas
        if (v('cliente') && document.getElementById('listClientes')) {
            const cambioCliente = document.getElementById('listClientes').value !== v('cliente');
            asignarGenerales(g, ['cliente']);
            if ((cambioCliente || !esEdicion) && typeof usercustomer === 'function') {
                await usercustomer(v('cliente'));
                await esperarAjax();
            }
        }

        //3. Partidas
        const tbody = document.getElementById('serv-cotizacion');
        if (tbody) {
            const nuevas = [];
            (b.filas || []).forEach(f => {
                if (f.pk) {
                    const pk = Array.from(tbody.querySelectorAll("[name='pkservcotizacion[]']")).find(i => i.value === f.pk);
                    if (!pk) return; //La partida ya no existe
                    llenarFila(pk.closest('tr'), f.campos);
                } else {
                    nuevas.push(f);
                }
            });

            const nuevasConDatos = nuevas.filter(f => f.campos.some(([, val]) => val !== '' && val !== false));
            let disponibles = () => Array.from(tbody.querySelectorAll('tr')).filter(filaVacia);
            let intentos = 0;
            while (disponibles().length < nuevasConDatos.length && intentos < 200) {
                agregarFilaNueva();
                intentos++;
            }
            const vacias = disponibles();
            nuevasConDatos.forEach((f, i) => { if (vacias[i]) llenarFila(vacias[i], f.campos); });
        }

        //4. Folio ya asignado en alta: se conserva para no generar otro
        if (!esEdicion && b.cotizacion && !form.querySelector("input[type='hidden'][name='cotizacion']")) {
            const h = document.createElement('input');
            h.type = 'hidden';
            h.name = 'cotizacion';
            h.value = b.cotizacion;
            form.appendChild(h);
            if (b.folio && document.getElementById('displayFolio')) document.getElementById('displayFolio').textContent = b.folio;
            if (b.folioInput && document.getElementById('folio')) document.getElementById('folio').value = b.folioInput;
            const btnFolio = document.getElementById('btnfolio');
            if (btnFolio) btnFolio.style.pointerEvents = 'none';
        }

        //5. Resto de campos generales
        const resto = Object.keys(g).filter(n => n !== 'sucursal' && n !== 'cliente');
        asignarGenerales(g, resto);

        //6. Recalcular
        try { if (typeof viewCambio === 'function' && v('cliente')) await viewCambio(v('cliente')); } catch (e) { }
        try { if (typeof Cambio === 'function') await Cambio(); } catch (e) { }
        try { if (typeof totales === 'function') totales(); } catch (e) { }
        try { if (typeof CalculaContenido === 'function') CalculaContenido(); } catch (e) { }
        form.querySelectorAll('textarea').forEach(t => { if (typeof autoResize === 'function' && t.closest('#serv-cotizacion')) autoResize(t); });

        restaurando = false;
        window.cotDirty = true; //Lo recuperado aún no está guardado en el servidor
    }

    function llenarFila(tr, campos) {
        const usados = {};
        campos.forEach(([n, val]) => {
            const els = Array.from(tr.querySelectorAll('[name="' + n + '"]')).filter(el => el.type !== 'hidden');
            const i = usados[n] || 0;
            if (els[i]) asignar(els[i], val);
            usados[n] = i + 1;
        });
    }
    //#endregion

    //#region eventos
    function marcarCambio(e) {
        if (!e.isTrusted || restaurando) return;
        window.cotDirty = true;
        clearTimeout(timerGuardado);
        timerGuardado = setTimeout(() => window.guardarBorradorCot(), 800);
    }
    form.addEventListener('input', marcarCambio, true);
    form.addEventListener('change', marcarCambio, true);
    setInterval(() => window.guardarBorradorCot(), 15000);

    //Respaldo inmediato y aviso del navegador al cerrar/salir con cambios sin guardar
    window.addEventListener('beforeunload', function (event) {
        if (window.skipUnloadPrompt || !window.cotDirty) return;
        window.guardarBorradorCot(true);
        event.preventDefault();
        event.returnValue = '';
    });
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') window.guardarBorradorCot();
    });

    //Mantener viva la sesión (expira a los 24 min sin actividad en el servidor)
    let avisoSesion = false;
    setInterval(() => {
        if (!window.jQuery) return;
        jQuery.ajax({ url: 'Cargas/keepalive.php', dataType: 'json', cache: false })
            .done(r => {
                if (r && r.active === false && !avisoSesion && window.cotDirty) {
                    avisoSesion = true;
                    window.guardarBorradorCot(true);
                    if (typeof sesionExpiradaCot === 'function') sesionExpiradaCot();
                }
                if (r && r.active) avisoSesion = false;
            });
    }, 4 * 60 * 1000);
    //#endregion

    //#region inicio
    //Latido: indica que esta pestaña sigue abierta, para no ofrecer su borrador en otra
    function latido() {
        const st = storage();
        try { if (st) st.setItem(VIVO + tabId, String(Date.now())); } catch (e) { }
    }
    function pestanaViva(id) {
        const st = storage();
        try { return Date.now() - parseInt(st.getItem(VIVO + id) || '0', 10) < 20000; } catch (e) { return false; }
    }

    //Si la pestaña se duplicó (copia el sessionStorage) se genera un id nuevo
    if (pestanaViva(tabId)) {
        tabId = Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
        try { sessionStorage.setItem('sisma_cot_tab', tabId); } catch (e) { }
    }
    CLAVE = BASE + tabId;
    latido();
    setInterval(latido, 5000);
    window.addEventListener('pagehide', () => {
        try { localStorage.removeItem(VIVO + tabId); } catch (e) { }
    });

    //Borradores de esta cotización que quedaron de pestañas cerradas (o de esta misma al recargar)
    function buscarBorrador() {
        const st = storage();
        if (!st) return null;
        let mejor = null;
        try {
            for (let i = st.length - 1; i >= 0; i--) {
                const k = st.key(i);
                if (!k) continue;
                if (k.startsWith(PREFIJO)) {
                    let d = null;
                    try { d = JSON.parse(st.getItem(k)); } catch (e) { }
                    //Limpieza de borradores viejos o dañados
                    if (!d || !d.ts || Date.now() - d.ts > DIAS_MAX * 86400000) { st.removeItem(k); continue; }
                    if (!k.startsWith(BASE)) continue;
                    const id = k.slice(BASE.length);
                    if (id !== tabId && pestanaViva(id)) continue; //Otra pestaña abierta la está usando
                    if (tieneContenido(d) && (!mejor || d.ts > mejor.data.ts)) mejor = { clave: k, data: d };
                } else if (k.startsWith(VIVO) && Date.now() - parseInt(st.getItem(k) || '0', 10) > 86400000) {
                    st.removeItem(k);
                }
            }
        } catch (e) { }
        return mejor;
    }

    window.addEventListener('load', async function () {
        await new Promise(r => setTimeout(r, 300));
        await esperarAjax();

        const st = storage();
        const encontrado = buscarBorrador();

        if (encontrado && window.Swal) {
            const b = encontrado.data;
            const r = await Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Cambios sin guardar',
                html: '<div style="font-size:14px">Se encontró un borrador de esta cotización' +
                    (b.folio && b.folio !== 'VACIO' ? ' (folio <b>' + b.folio + '</b>)' : '') +
                    ' del <b>' + hora(b.ts) + '</b> que no se guardó.<br>¿Deseas recuperarlo?</div>',
                showCancelButton: true,
                confirmButtonText: 'Recuperar',
                cancelButtonText: 'Descartar',
                allowOutsideClick: false,
                width: 'auto',
            });
            //En ambos casos el borrador encontrado se quita; si se recupera, se respalda con la clave de esta pestaña
            try { st.removeItem(encontrado.clave); } catch (e) { }
            if (r.isConfirmed) {
                try {
                    await restaurar(b);
                    listo = true;
                    window.guardarBorradorCot(true);
                    status('Borrador recuperado: da GUARDAR para conservarlo');
                } catch (e) {
                    restaurando = false;
                    console.error(e);
                }
            }
        }
        listo = true;
    });
    //#endregion
})();
