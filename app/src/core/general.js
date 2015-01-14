var nivel_entrada=js_general.getAttribute("nivel_entrada");

function datos_formulario($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(index, i){
        if(!indexed_array[index['name']]){
            indexed_array[index['name']] = index['value'];
        }
        else{
            if(typeof indexed_array[index['name']] === Array){
                indexed_array[index['name']].push(index['value']);
            }
            else{
                var index_temp = indexed_array[index['name']];
                indexed_array[index['name']] = new Array();
                indexed_array[index['name']].push(index_temp);
                indexed_array[index['name']].push(index['value']);
            }
        }
    });

    return indexed_array;
}

//return an array of objects according to key, value, or key and value matching
/**
 * Devuelve un array de objetos que coinciden
 * @param  {Object|Array} obj [description]
 * @param  {string} key El identificador a buscar
 * @param  {string|integer} val el valor a buscar
 * @return {Object}     El objeto con array de respuestas
 */
function getObjects(obj, key, val) {
    var objects = [];
    for (var i in obj) {
        if (!obj.hasOwnProperty(i)) continue;
        if (typeof obj[i] == 'object') {
            objects = objects.concat(getObjects(obj[i], key, val));    
        }
        else{
            if (i == key && obj[i] == val || i == key && val == '') { //
                objects.push(obj);
            } else if (obj[i] == val && key == ''){
                //only add if the object is not already in the array
                if (objects.lastIndexOf(obj) == -1){
                    objects.push(obj);
                }
            }
        } 
    }
    return objects;
}

//return an array of values that match on a certain key
function getValues(obj, key) {
    var objects = [];
    for (var i in obj) {
        if (!obj.hasOwnProperty(i)) continue;
        if (typeof obj[i] == 'object') {
            objects = objects.concat(getValues(obj[i], key));
        } else if (i == key) {
            objects.push(obj[i]);
        }
    }
    return objects;
}

//return an array of keys that match on a certain value
function getKeys(obj, val) {
    var objects = [];
    for (var i in obj) {
        if (!obj.hasOwnProperty(i)) continue;
        if (typeof obj[i] == 'object') {
            objects = objects.concat(getKeys(obj[i], val));
        } else if (obj[i] == val) {
            objects.push(i);
        }
    }
    return objects;
}

function modal_carga_gn (nombre) {
    if(!nombre){
        nombre = "area_modal_carga";
    }
    var modal_carga = bootbox.dialog({
        title: "Procesando",
        message: "<img src='"+nivel_entrada+"fw/theme/plugins/select2/select2-spinner.gif'>"+
        "<p>Por favor espere...</p>"
    });
    this.crear = function () {
        modal_carga.modal('hide');
        return this;
    }
    this.mostrar = function() {
        modal_carga.modal('show');
    }
    this.ocultar = function () {
        modal_carga.modal('hide');
    }
    return this;
}

function formato_fecha(fecha_str)
{
    nueva_fecha = fecha_str.split("-");
    return nueva_fecha[2]+ "/" +nueva_fecha[1]+ "/" +nueva_fecha[0];
}

function encriptar_string (enc_string) {
    var resultado = '';
    for (var i = 0; i < enc_string.length; i++) {
        var enc_key = Math.floor((Math.random() * 50) +1);
        resultado += '-'+(enc_string.charCodeAt(i) * enc_key)+'.'+(enc_key-1);
    };
    return resultado;
}

/**
 * Exporta una tabla a excel
 * @param  {string} id_tabla el id de la tabla a exportar
 */
function exportar_excel (id_tabla) {
    $("#"+id_tabla).btechco_excelexport({
        containerid: id_tabla
        , datatype: $datatype.Table
    });
}

$.fn.goTo = function() {
    $('html, body').animate({
        scrollTop: $(this).offset().top + 'px'
    }, 'fast');
    return this; // for chaining...
}

/* Clase para mostrar barra de carga en la parte inferior de la pantalla */
var BarraCargaInf = function (id_html, objetivo){
  this.id_html = (id_html ? id_html : 'barra_carga_inf');
  this.activo = true;
  this.contenido_div =
  '<div id="'+this.id_html+'" class="progress progress-striped active navbar-fixed-bottom" data-offset-top="150">'+
  '<div class="progress-bar progress-bar-info" style="width: 100%">Cargando</div>'+
  '</div>';
  if(!objetivo){
    $(document.body).append(this.contenido_div);
  }
  else{
    $('#'+objetivo).append(this.contenido_div);
  }
  this.ocultar();
};

BarraCargaInf.prototype.mostrar = function() {
    if(this.activo==false){
        $("#"+this.id_html+"").toggle('show');
        this.activo = true;
    }
};

BarraCargaInf.prototype.ocultar = function() {
    if(this.activo==true){
        $("#"+this.id_html+"").toggle('hide');
        this.activo = false;
    }
};