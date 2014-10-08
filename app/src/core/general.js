function datos_formulario($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(index, i){
        indexed_array[index['name']] = index['value'];
    });

    return indexed_array;
}