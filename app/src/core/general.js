var nivel_entrada=js_general.getAttribute("nivel_entrada");

function datos_formulario($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(index, i){
        indexed_array[index['name']] = index['value'];
    });

    return indexed_array;
}

//return an array of objects according to key, value, or key and value matching
function getObjects(obj, key, val) {
    var objects = [];
    for (var i in obj) {
        if (!obj.hasOwnProperty(i)) continue;
        if (typeof obj[i] == 'object') {
            objects = objects.concat(getObjects(obj[i], key, val));    
        }
        else{
            //if key matches and value matches or if key matches and value is not passed (eliminating the case where key matches but passed value does not)
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
        message: "<img src='"+nivel_entrada+"js/framework/select2/select2-spinner.gif'>"+
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