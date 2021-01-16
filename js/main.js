jQuery(function(t){
    "use strict"

});

$(document).ready(function () {
    
    /***************** boton hacia arriba ****************/
    $('.ir-arriba').click(function(){
        $('body, html').animate({
            scrollTop: '0px'
        }, 1000);
    });

    $(window).scroll(function(){
        if( $(this).scrollTop() > 0 ){
            $('.ir-arriba').slideDown(600);
        } else {
            $('.ir-arriba').slideUp(600);
        }
    });

    /***************** hacia abajo ****************/
    $('.ir-abajo').click(function(){
        $('body, html').animate({
            scrollTop: '1000px'
        }, 1000);
    });
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
        e.preventDefault();
        var parametro_id = $(this).attr("href").replace("#",""); //id de categoria en este caso
        var concepto = $(this).text();
        $('.search-panel span#search_concept').text(concepto);
        $('.input-group #search_param').val(parametro_id);
		$('#txt_id_categoria').val(parametro_id);
		buscar();
    });

});