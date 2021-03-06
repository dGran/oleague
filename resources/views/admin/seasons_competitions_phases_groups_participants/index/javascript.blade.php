<script>
    var competition_slug = {!! json_encode($group->phase->competition->slug) !!};
    var phase_slug = {!! json_encode($group->phase->slug) !!};
    var group_slug = {!! json_encode($group->slug) !!};

    $(function() {
        Mousetrap.bind(['command+a', 'ctrl+a'], function() {
            var url = $("#btnAdd").attr('href');
            $(location).attr('href', url);
            return false;
        });
    });

    $('#addon-new').click(function() {
        if (!$(this).hasClass('disabled')) {
            var url = '{{ route("admin.season_competitions_phases_groups_participants.add", [":competition_slug", ":phase_slug", ":group_slug"]) }}';
            url = url.replace(':competition_slug', competition_slug);
            url = url.replace(':phase_slug', phase_slug);
            url = url.replace(':group_slug', group_slug);
            window.location.href=url;
        }
    });

    $(".btn-delete").click(function(e) {
        window.event.preventDefault();
        var row = $(this).parents('tr');
        var id = row.attr("data-id");
        var name = row.attr("data-name");
        var allow_delete = row.attr("data-allow-delete");

        // if (allow_delete == 1) {
            swal({
                title: "¿Estás seguro?",
                text: 'Se va a eliminar el participante "' + name + '". No se podrán deshacer los cambios!',
                buttons: {
                    confirm: {
                        text: "Sí, estoy seguro",
                        value: true,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true
                    },
                    cancel: {
                        text: "No, cancelar",
                        value: null,
                        visible: true,
                        className: "btn btn-secondary",
                        closeModal: true,
                    }
                },
                closeOnClickOutside: false,
            })
            .then((value) => {
                if (value) {
                    var form = $('#form-delete');
                    var url = form.attr('action').replace(':PARTICIPANT_ID', id);
                    form.attr('action', url);
                    form.submit();
                }
            });
        // } else {
        //     swal("La categoría tiene equipos asociados por lo que no se puede eliminar.", {
        //         buttons: false,
        //         icon: "error",
        //         timer: 3000,
        //     });
        // }

    });

    function raffle() {
        window.event.preventDefault();
        disabledActionsButtons();
        swal({
            title: "¿Estás seguro?",
            text: 'Se van a completar los participantes del grupo por sorteo.',
            buttons: {
                confirm: {
                    text: "Sí, estoy seguro",
                    value: true,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true
                },
                cancel: {
                    text: "No, cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-secondary",
                    closeModal: true,
                }
            },
            closeOnClickOutside: false,
        })
        .then((value) => {
            if (value) {
                var url = '{{ route("admin.season_competitions_phases_groups_participants.raffle", [":competition_slug", ":phase_slug", ":group_slug"]) }}';
                url = url.replace(':competition_slug', competition_slug);
                url = url.replace(':phase_slug', phase_slug);
                url = url.replace(':group_slug', group_slug);
                window.location.href=url;
            } else {
                enabledActionsButtons();
            }
        });
    }

    function destroyMany() {
        window.event.preventDefault();
        disabledActionsButtons();
        swal({
            title: "¿Estás seguro?",
            text: 'Se van a eliminar los participantes seleccionados. No se podrán deshacer los cambios!.',
            buttons: {
                confirm: {
                    text: "Sí, estoy seguro",
                    value: true,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true
                },
                cancel: {
                    text: "No, cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-secondary",
                    closeModal: true,
                }
            },
            closeOnClickOutside: false,
        })
        .then((value) => {
            if (value) {
                var ids = [];
                $(".mark:checked").each(function() {
                    ids.push($(this).val());
                });
                var url = '{{ route("admin.season_competitions_phases_groups_participants.destroy.many", [":competition_slug", ":phase_slug", ":group_slug", ":ids"]) }}';
                url = url.replace(':ids', ids);
                url = url.replace(':competition_slug', competition_slug);
                url = url.replace(':phase_slug', phase_slug);
                url = url.replace(':group_slug', group_slug);
                window.location.href=url;
            } else {
                enabledActionsButtons();
            }
        });
    }


    function rowSelect(element) {
        $(element).siblings('.select').find('.mark').trigger('click');
    }

    function showHideRowOptions(element) {
        if ($(element).is(':checked')) {
            $(element).parents('tr').addClass('selected');
        } else {
            $(element).parents('tr').removeClass('selected');
        }

        if ($(".mark:checked").length > 0) {
            if (!$(".rowOptions").is(':visible')) {
                $(".rowOptions").removeClass('d-none');
                $(".tableOptions").addClass('d-none');
            }
            if ($(".mark:checked").length == 1) {
                // ...
            } else {
                // ...
            }
        } else {
            if ($(".rowOptions").is(':visible')) {
                $(".rowOptions").addClass('d-none');
                $(".tableOptions").removeClass('d-none');
            }
        }
    }

    function showHideAllRowOptions() {
        if ($("#allMark").is(':checked')) {
            $(".mark").prop('checked', true);
            $(".mark").parents('tr').addClass('selected');
        } else {
            $(".mark").prop('checked', false);
            $(".mark").parents('tr').removeClass('selected');
        }
        showHideRowOptions();
    }

    function disabledActionsButtons() {
        $('a').addClass('disabled');
        $('button').attr("disabled", "disabled");
    }

    function enabledActionsButtons() {
        $('a').removeClass('disabled');
        $('button').removeAttr("disabled");
    }

    function export_file(type) {
        window.event.preventDefault();

        swal({
            title: "Exportar todos los registros",
            text: 'Introduce nombre del archivo (opcional)',
            content: "input",
            buttons: {
                cancel: {
                    text: "Cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-secondary",
                    closeModal: true,
                },
                confirm: {
                    text: "Continuar",
                    value: true,
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true
                }
            },
        })
        .then((value) => {
            if (value) {
                var filename = `${value}`;
                if (!filename ) {
                    var time = Math.floor(new Date().getTime() / 1000);
                    var filename = 'season_competitions_phases_groups_participants_export' + time;
                }
                $(location).attr('href', 'participantes/exportar/' + filename + '/' + type);
            }
        });
    }

    function export_file_selected(type) {
        window.event.preventDefault();

        var ids = [];
        $(".mark:checked").each(function() {
            ids.push($(this).val());
        });

        swal({
            title: "Exportar los registros seleccionados",
            text: 'Introduce nombre del archivo (opcional)',
            content: "input",
            buttons: {
                cancel: {
                    text: "Cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-secondary",
                    closeModal: true,
                },
                confirm: {
                    text: "Continuar",
                    value: true,
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true
                }
            },
        })
        .then((value) => {
            if (value) {
                var filename = `${value}`;
                if (!filename ) {
                    var time = Math.floor(new Date().getTime() / 1000);
                    var filename = 'season_competitions_phases_groups_participants_export' + time;
                }
                $(location).attr('href', 'participantes/exportar/' + filename + '/' + type + '/' + ids);
            }
        });
    }

    function import_file() {
        window.event.preventDefault();
        swal({
            title: "Importar datos",
            text: 'Se van a importar los datos del archivo seleccionado, pulsa continuar y selecciona el archivo que contiene los datos (.xls, .xlsx, .csv).',
            buttons: {
                cancel: {
                    text: "Cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-secondary",
                    closeModal: true,
                },
                confirm: {
                    text: "Continuar",
                    value: true,
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true
                }
            },
            closeOnClickOutside: false,
        })
        .then((value) => {
            if (value) {
                $("#import_file").trigger('click');
            }
        });
    }

    $('#import_file').change(function(){
        $("#frmImport").submit();
    });

</script>