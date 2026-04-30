function menu(data){
    let id = data.id;
    $("#"+id).on("contextmenu", function (e) {
        e.preventDefault();
        var menu = $("#menu");
        
        menu.css({
            top: e.pageY + "px",
            left: e.pageX + "px"
        });

        menu.show();

        $(document).on("click", function () {
            menu.hide();
            $(document).off("click");
        });

        menu.on("click", function (e) {
            e.stopPropagation();
        });     

        /*
        $("#menu ul li").on("click", function () {
            var opcion = $(this).text();
            alert("Has seleccionado: " + opcion);
            menu.hide();
        });*/
    });
}
