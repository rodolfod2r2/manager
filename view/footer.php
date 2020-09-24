<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 05/03/2018
 * Time: 10:51
 */

//$urifull = "//sysmanfab.com.br/";
$urifull = "//localhost/desenvolvimento/php/manager/";

?>

<script src="<?php echo $urifull ?>assets/js/jquery-3.3.0.js"></script>
<script src="<?php echo $urifull ?>assets/js/jquery-migrate-3.0.1.js"></script>
<script src="<?php echo $urifull ?>assets/js/bootstrap.js"></script>
<script src="<?php echo $urifull ?>assets/js/maskedinput.js"></script>
<script src="<?php echo $urifull ?>libs/modernizr/modernizr.js"></script>
<script src="<?php echo $urifull ?>libs/jquery-ui/jquery-ui.js"></script>
<script src="<?php echo $urifull ?>libs/jquery-toggles/toggles.js"></script>
<script src="<?php echo $urifull ?>libs/jquery-knob/jquery.knob.js"></script>
<script src="<?php echo $urifull ?>assets/js/quirk.js"></script>
<script src="<?php echo $urifull ?>assets/js/autosize.js"></script>
<script src="<?php echo $urifull ?>assets/js/nanoscroller.js"></script>
<script src="<?php echo $urifull ?>assets/js/select2.js"></script>
<script src="<?php echo $urifull ?>assets/js/toastr.js"></script>
<script src="<?php echo $urifull ?>assets/js/manager.js"></script>

<script>

    // select and Mask
    $(document).ready(function () {
        $(window).keydown(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                return false;
            }
        });

        $('select').select2();
        $("#nasc").mask("99/99/9999");
        $(".dt-all").mask("99/99/9999");
        $("#telefone").mask("(99) 99999-9999");
        $("#cpf").mask("999.999.999-99");


        $("#cep").blur(function () {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep != "") {
                var validacep = /^[0-9]{8}$/;
                if (validacep.test(cep)) {
                    $("#rua").val("...");
                    $("#bairro").val("...");
                    $("#cidade").val("...");
                    $("#estado").val("...");
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
                        if (!("erro" in dados)) {
                            $("#rua").val(dados.logradouro);
                            $("#bairro").val(dados.bairro);
                            $("#cidade").val(dados.localidade);
                            $("#estado").val(dados.uf);
                        }
                        else {
                            // $("#rua").val("");
                            // $("#bairro").val("");
                            // $("#cidade").val("");
                            // $("#estado").val("");
                            toastr.options.progressBar = true;
                            toastr.info('CEP Não Encontrado.');
                        }
                    });
                }
                else {
                    // $("#rua").val("");
                    // $("#bairro").val("");
                    // $("#cidade").val("");
                    // $("#estado").val("");
                    toastr.options.progressBar = true;
                    toastr.info('CEP Não Encontrado.');
                }
            }
            else {
                // $("#rua").val("");
                // $("#bairro").val("");
                // $("#cidade").val("");
                // $("#estado").val("");
                toastr.options.progressBar = true;
                toastr.info('CEP Não Encontrado.');
            }
        });
    });

    //filter Jquery
    $(function () {
        jQuery.expr[':'].Contains = function (a, i, m) {
            return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };

        function listFilter(list) {
            $('#pesquisar')
                .change(function () {
                    var filter = $(this).val();
                    if (filter) {
                        $(list).find(".view-holder:not(:Contains(" + filter + "))").parent().slideUp();
                        $(list).find(".view-holder:Contains(" + filter + ")").parent().slideDown();
                    } else {
                        $(list).find(".recycle-adapter").slideDown();
                    }
                    return false;
                })
                .keyup(function () {
                    $(this).change();
                });
        }

        $(function () {
            listFilter($("#list"));
        });
    });


    $(function () {
        $('.arquivo').change(function () {
            const file = $(this)[0].files[0];
            const fileReader = new FileReader();
            fileReader.onloadend = function () {
                // $('#img').attr('src', fileReader.result);
                $('#thumb').css('background-image', 'url(' + fileReader.result + ')');
            }
            fileReader.readAsDataURL(file);
        });
    });

    $('#btn-select').on('click', function () {
        $('.arquivo').trigger('click');
    });

    $('.arquivo').on('change', function () {
        var fileName = $(this)[0].files[0].name;
        $('#textfile').val(fileName);
    });


</script>