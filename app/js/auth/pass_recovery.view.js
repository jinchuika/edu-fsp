var auth = new Auth();
$(document).ready(function () {
    $('#form-recovery').bootstrapValidator({
        fields: {
            password1: {
                validators: {
                    notEmpty: {
                        message: 'Este campo es obligatorio'
                    },
                    identical: {
                        field: 'password2',
                        message: 'Las contraseñas no coinciden'
                    }
                }
            },
            password2: {
                validators: {
                    notEmpty: {
                        message: 'Este campo es obligatorio'
                    },
                    identical: {
                        field: 'password1',
                        message: 'Las contraseñas no coinciden'
                    }
                }
            }
        }
    })
    .on('success.form.bv' ,function (e) {
        e.preventDefault();
        auth.enviar_pass_recovery($('#username').val(), $('#password1').val(), $('#salt').val());
    });
});