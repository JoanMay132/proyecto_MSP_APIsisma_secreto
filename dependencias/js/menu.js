$(document).ready(function() {
    
    $('.menu-item').click(function() {
        $('.submenu').hide();
        $('.menu-item').css({
            "border-bottom" : "unset",
             "font-weight" : "unset"
        });
        let target = $(this).data('target');
        if (target === '#catalogos') {
            $('#catalogos').toggle();
        }else if(target === '#trazabilidad'){
            $('#trazabilidad').toggle();
        }else if(target === '#presupuesto'){
            $('#presupuesto').toggle();
        }else if(target === '#compras'){
            $('#compras').toggle();
        }
        else {
            $('.submenu').hide();
            $(target).show();
        }
        $(this).css({
            "border-bottom" : "2px solid blue",
            "font-weight" : "bold"
        });
    });

    // $('.submenu-item').click(function() {
    //     var target = $(this).data('target');
    //     $(target).show();
    // });
    let inicio = document.querySelector('[data-target="#catalogos"]');
    $(inicio).css({
        "border-bottom" : "2px solid blue",
        "font-weight" : "bold"
    });
    
});


