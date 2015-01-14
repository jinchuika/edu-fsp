/**
 * Clase para controlar el CNB
 * @param {string} nivel_dir ruta para cargar en remoto
 */
var Cnb = function (nivel_dir, callback) {
    this.nivel_dir = nivel_entrada || nivel_dir;
    this.cargar_datos(callback);
    this.en_uso = [];
}

/**
 * Abre los datos
 * @method cargar_datos
 * @see localStorage
 * @uses cargar_remoto
 * @uses set_data
 */
Cnb.prototype.cargar_datos = function(callback) {
    var cnb_local = localStorage.getItem('__CNB__');
    //Si existe
    if(cnb_local!=undefined){
        console.log('CNB desde local');
        this.set_data(JSON.parse(localStorage.getItem('__CNB__')), this, callback);
        return true;
    }
    //si no existe
    else{
        this.set_data(this.cargar_remoto(this, callback), this, callback);
        return true;
    }
};

/**
 * Cargar el Cnb desde el servidor
 * @return {Array}
 */
Cnb.prototype.cargar_remoto = function(instancia_actual, callback) {
    if($.gritter){
        var cnb_notif = $.gritter.add({
            title: 'Descargando CNB',
            text: 'Por favor espere mientras se realiza la descarga. Este proceso sólo se raliza una vez.',
            sticky: true
        });
    }
    var respuesta = $.getJSON(this.nivel_dir+'app/src/libs_plan/cnb.php',
    {
        fn_nombre: 'abrir_cnb'
    })
    .done(function (cnb_recibido) {
        localStorage.setItem('__CNB__', JSON.stringify(cnb_recibido));
        //this.set_data(cnb_recibido, instancia_actual, callback);
        (cnb_notif ? $.gritter.remove(cnb_notif) : '');
        return cnb_recibido;
    });
    return respuesta;
};

/**
 * Carga el CNB a la instancia actual
 * @param {Array}   datos_obtenidos  El CNB
 * @param {Cnb}   instancia_actual La instancia actual
 * @param {Function} callback         Para realizarse cuando se termina de cargar
 */

Cnb.prototype.set_data = function (datos_obtenidos, instancia_actual, callback) {
    instancia_actual.arr_competencia = datos_obtenidos.arr_competencia;
    instancia_actual.arr_indicador = datos_obtenidos.arr_indicador;
    instancia_actual.arr_contenido = datos_obtenidos.arr_contenido;
    instancia_actual.arr_rel_contenido = datos_obtenidos.arr_rel_contenido;
    instancia_actual.arr_funsepa = datos_obtenidos.arr_funsepa;
    instancia_actual.arr_metodo = datos_obtenidos.arr_metodo;

    this.en_uso = [];

    if (callback && typeof(callback) === "function") {
        callback();
    }
};

/**
 * Guarda el CNB como atributo de la instancia actual
 * @param {Array} datos_obtenidos
 
Cnb.prototype.set_data = function(datos_obtenidos) {
    this.arr_competencia = datos_obtenidos.arr_competencia;
    this.arr_indicador = datos_obtenidos.arr_indicador;
    this.arr_contenido = datos_obtenidos.arr_contenido;
    this.arr_rel_contenido = datos_obtenidos.arr_rel_contenido;
    this.arr_funsepa = datos_obtenidos.arr_funsepa;
    this.arr_metodo = datos_obtenidos.arr_metodo;
    this.en_uso = [];
};*/