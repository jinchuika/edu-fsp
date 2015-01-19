var Auth = function () {
	
}

Auth.prototype.login = function(username, password) {
	$.ajax({
        url: nivel_entrada+'includes/libs/login.php',
        type: 'post',
        data: {
            user: username,
            pass: this.encriptar_string(password)
        },
        success: function (respuesta) {
            var respuesta = $.parseJSON(respuesta);
            if(respuesta.msj=='si'){
                window.location = (respuesta.url ? respuesta.url : 'app');
            }
        }
    });
};

Auth.prototype.form_pass_recovery = function(first_argument) {
    bootbox.prompt('Ingrese el correo electrónico registrado', function(result) {
        if (result !== null) {
            $.ajax({
                url: nivel_entrada+'includes/libs/pass_recovery.php',
                data:{
                    mail: result
                },
                dataType: 'json',
                success: function (respuesta) {
                    //Enviado
                    if(respuesta.msj=='si'){
                        var mensaje = 'Por favor revise su correo electrónico para recuperar su contraseña.';
                        mensaje += '<br>Revise su carpeta de correo no deseado en caso de no haberlo recibido.';
                        bootbox.alert(mensaje);
                    }
                    //No existe el correo
                    else if (respuesta.msj=='404'){
                        bootbox.alert('No se encontró la dirección de correo.');
                    }
                    else{
                        bootbox.alert('Ocurrió un error al enviar. Por favor vuelva a intentarlo');
                    }
                }
            });
        }
    });
};

Auth.prototype.encriptar_string = function(string_real) {
    var resultado = '';
    for (var i = 0; i < string_real.length; i++) {
        var enc_key = Math.floor((Math.random() * 50) +1);
        resultado += '-'+(string_real.charCodeAt(i) * enc_key)+'.'+(enc_key-1);
    };
    return resultado;
};

Auth.prototype.enviar_pass_recovery = function(username, password, salt) {
    $.ajax({
        url: nivel_entrada+'app/src/libs_usr/user.php',
        data: {
            fn_nombre: 'nuevo_password',
            username: username,
            password: this.encriptar_string(password),
            salt: salt
        },
        type: 'post',
        dataType: 'json',
        success: function (respuesta) {
            if(respuesta.msj=='si'){
                bootbox.alert('Se modificó correctamente la contraseña para el usuario '+respuesta.username, function () {
                    window.location.href = '../../';
                });
            }
        }
    })
};