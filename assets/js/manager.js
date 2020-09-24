$(document).ready(function () {

    function postFormRedirect(data, uri, message) {
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "dispatcher.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (result) {
                toastr.options.progressBar = true;
                toastr.options.onHidden = function () {
                    window.location.href = uri;
                };
                toastr.info(result, message);
            }
        });
    }

    function postFormReload(data, message) {
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "dispatcher.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (result) {
                toastr.options.progressBar = true;
                toastr.options.onHidden = function () {
                    window.location.reload(true);
                };
                toastr.info(result, message);
            }
        });
    }

    function postRemove(data, message, id) {
        $.ajax({
            type: "GET",
            url: "dispatcher.php",
            data: data +'&id=' + id,
            processData: false,
            contentType: false,
            cache: false,
            success: function (result) {
                toastr.options.progressBar = true;
                toastr.options.onHidden = function () {
                    window.location.reload(true);
                };
                toastr.info(result, message);
            }
        });
    }

    $("#btnsalvar-cargo").on('click', function (e) {
        var form = $('#persiste')[0];
        var data = new FormData(form);
        postFormRedirect(data, '../cargo', 'sucesso');
        e.preventDefault();
    });

    $("#btnsalvar-setor").on('click', function (e) {
        var form = $('#persiste')[0];
        var data = new FormData(form);
        postFormRedirect(data, '../setor', 'sucesso');
        e.preventDefault();
    });

    $("#btnsalvar-funcionario").on('click', function (e) {
        var form = $('#persiste')[0];
        var data = new FormData(form);
        postFormRedirect(data, '../funcionario', 'sucesso');
        e.preventDefault();
    });

    $("#btnsalvar-productowner").on('click', function (e) {
        var form = $('#persiste')[0];
        var data = new FormData(form);
        postFormRedirect(data, '../productowner/ativos', 'sucesso');
        e.preventDefault();
    });

    $("#btnsalvar-profile").on('click', function (e) {
        var form = $('#persiste')[0];
        var data = new FormData(form);
        postFormRedirect(data, '../profile', 'sucesso');
        e.preventDefault();
    });

    $(".btn-remove-cargo").on('click', function (e) {
        var id = $(this).attr("data-id");
        postRemove("view=cargo&rota=desativar", id);
        e.preventDefault();
    });

    $(".btn-remove-setor").on('click', function (e) {
        var id = $(this).attr("data-id");
        postRemove("view=setor&rota=desativar", id);
        e.preventDefault();
    });

    $(".btn-remove-funcionario").on('click', function (e) {
        var id = $(this).attr("data-id");
        postRemove("view=funcionario&rota=desativar", id);
        e.preventDefault();
    });

    $(".btn-remove-productowner").on('click', function (e) {
        var id = $(this).attr("data-id");
        postRemove("view=funcionario&rota=desativar", id);
        e.preventDefault();
    });

    $(".ativar-productowner").on('click', function (e) {
        var id = $(this).attr('data-id');
        var form = $("#persiste" + id)[0];
        var data = new FormData(form);
        postFormRedirect(data, '../productowner/ativos', 'sucesso');
        e.preventDefault();
    });

    $(".desativar-productowner").on('click', function (e) {
        var id = $(this).attr('data-id');
        var form = $("#persiste" + id)[0];
        var data = new FormData(form);
        postFormReload(data, 'sucesso');
        e.preventDefault();
    });

    $("#btnsalvar-inventario").on('click', function (e) {
        var form = $('#persiste')[0];
        var data = new FormData(form);
        postFormRedirect(data, '../inventario', 'sucesso');
        e.preventDefault();
    });

    $(".btn-remove-inventario").on('click', function (e) {
        var id = $(this).attr("data-id");
        postRemove("view=inventario&rota=desativar", id);
        e.preventDefault();
    });

});





