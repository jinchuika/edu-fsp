/**
 * Clase para manipular datos de escuelas
 * @param {string} nivel_dir ruta para cargar en remoto
 */
var GnEscuela = function (nivel_dir) {
    this.nivel_dir = nivel_entrada || nivel_dir;
    this.remoteUrl = this.nivel_dir+'app/src/libs_gn/GnEscuela.php'
}

GnEscuela.prototype.responder = function(value) {
	return value;
};

GnEscuela.prototype.listarEscuela = function(data, done) {
	return $.ajax({
		url: this.remoteUrl,
		data: {fn_nombre: 'listar_escuela'},
		dataType: 'json',
		success: function (json) {
			return json;
		}
	});
};

