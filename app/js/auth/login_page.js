var auth = new Auth();

$(document).ready(function () {
    $('#form_login').submit(function (e) {
        e.preventDefault();
        auth.login($('#username').val(), $('#password').val());
    });
    $('#btn-recovery').click(function () {
    	auth.form_pass_recovery();
    });
});