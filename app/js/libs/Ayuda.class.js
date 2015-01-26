var Ayuda = function (nivel_dir) {
	this.nivel_dir = nivel_entrada || nivel_dir;
}

Ayuda.prototype.crearFormulario = function() {
	bootbox.dialog({
        title: "Envía tus comentarios.",
        message:
        '<form class="form-horizontal" id="help-form">'+
        '<fieldset>'+
        '<div class="form-group">'+
        '<label class="col-md-4 control-label" for="help-nombre">Nombre</label>'+
        '<div class="col-md-5">'+
        '<input id="help-nombre" name="help-nombre" type="text" placeholder="" class="form-control input-md" required="">'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
        '<label class="col-md-4 control-label" for="help-mail">Correo electrónico</label>'+
        '<div class="col-md-5">'+
        '<input id="help-mail" name="help-mail" type="email" class="form-control input-md" required="">'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
        '<label class="col-md-4 control-label" for="help-msj">Mensaje</label>'+
        '<div class="col-md-4">'+
        '<textarea class="form-control" id="help-msj" name="help-msj"></textarea>'+
        '</div>'+
        '</div>'+
        '</fieldset>'+
        '</form>',
        buttons: {
            success: {
                label: "Enviar",
                className: "btn-success",
                callback: function () {
                    $.ajax({
                        url: nivel_entrada+'app/src/libs_gn/ayuda.php',
                        data: {
                            fn_nombre: 'enviar_ayuda',
                            nombre: $('#help-nombre').val(),
                            mail: $('#help-mail').val(),
                            mensaje: $('#help-msj').val()
                        },
                        dataType: 'json',
                        success: function (data) {
                            alert(data.msj=='si' ? 'Gracias por enviar su comentario.' : 'Error al enviar')
                        }
                    });
                }
            }
        }
    });
};