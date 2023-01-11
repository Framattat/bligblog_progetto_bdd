// funzione che viene caricata quando la pagina è 'pronta', attivo select2 sulle classi
$(document).ready(function() {
    $('.select2').select2();
    $('.select2_modale').each(function(){
        // funzione che abilita la select su modale bootstrap
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });
});

// funzione per abilitare le sottocategorie corrette in base alla categoria scelta
function aggiorna_sottocategorie(id_categoria,select2_sottocategorie){
    $(".sottocategoria").attr("disabled","disabled");
    $(".sottocategoria_"+id_categoria).removeAttr("disabled");
    $("#"+select2_sottocategorie).select2().val("").trigger("change");
}
