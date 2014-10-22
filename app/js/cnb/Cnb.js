/**
 * Clase para controlar el CNB
 * @param {string} nivel_dir ruta para cargar en remoto
 */
var Cnb = function (nivel_dir) {
    this.nivel_dir = nivel_entrada || nivel_dir;
    this.cargar_datos();
}

/**
 * Abre los datos
 * @method cargar_datos
 * @see localStorage
 * @uses cargar_remoto
 * @uses set_data
 */
Cnb.prototype.cargar_datos = function() {
    var cnb_local = localStorage.getItem('__CNB__');
    if(cnb_local!=undefined){
        console.log('CNB desde local');
        this.set_data(JSON.parse(localStorage.getItem('__CNB__')));
    }
    else{
        this.cargar_remoto();
    }
};

/**
 * Cargar el Cnb desde el servidor
 * @return {Array}
 */
Cnb.prototype.cargar_remoto = function(callback) {
    var respuesta = $.getJSON(this.nivel_dir+'app/src/libs_plan/cnb.php',
    {
        fn_nombre: 'abrir_cnb'
    })
    .done(function (cnb_recibido) {
        localStorage.setItem('__CNB__', JSON.stringify(cnb_recibido));
        set_data(cnb_recibido);
        return cnb_recibido;
    });
    return respuesta;
};

function set_data(datos_obtenidos) {
    this.arr_competencia = datos_obtenidos.arr_competencia;
    this.arr_indicador = datos_obtenidos.arr_indicador;
    this.arr_contenido = datos_obtenidos.arr_contenido;
    this.arr_rel_contenido = datos_obtenidos.arr_rel_contenido;
    this.arr_funsepa = datos_obtenidos.arr_funsepa;
    this.arr_metodo = datos_obtenidos.arr_metodo;
};

/**
 * Guarda el CNB como atributo de la instancia actual
 * @param {Array} datos_obtenidos
 */
Cnb.prototype.set_data = function(datos_obtenidos) {
    this.arr_competencia = datos_obtenidos.arr_competencia;
    this.arr_indicador = datos_obtenidos.arr_indicador;
    this.arr_contenido = datos_obtenidos.arr_contenido;
    this.arr_rel_contenido = datos_obtenidos.arr_rel_contenido;
    this.arr_funsepa = datos_obtenidos.arr_funsepa;
    this.arr_metodo = datos_obtenidos.arr_metodo;
};