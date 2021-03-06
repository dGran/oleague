<script>
    var competition_slug = {!! json_encode($group->phase->competition->slug) !!};
    var phase_slug = {!! json_encode($group->phase->slug) !!};
    var group_slug = {!! json_encode($group->slug) !!};

    $(function() {

        $('#assingLocalParticipantModal').on('show.bs.modal', function(e) {
            var row = $(e.relatedTarget).parents('tr');
            var id = row.attr("data-id");
            var url = '{{ route("admin.season_competitions_phases_groups_playoffs.clashes.assing_local_participant", ":clash_id") }}';
            url = url.replace(':clash_id', id);
            $.ajax({
                url         : url,
                type        : 'GET',
                datatype    : 'html',
            }).done(function(data){
                $('#modal-dialog-assing-local-participant').html(data);
            });
        });

        $("#assingLocalParticipantModal").on("hidden.bs.modal", function(){
            $('#modal-dialog-assing-local-participant').html("");
        });

        $('#assingVisitorParticipantModal').on('show.bs.modal', function(e) {
            var row = $(e.relatedTarget).parents('tr');
            var id = row.attr("data-id");
            var url = '{{ route("admin.season_competitions_phases_groups_playoffs.clashes.assing_visitor_participant", ":clash_id") }}';
            url = url.replace(':clash_id', id);
            $.ajax({
                url         : url,
                type        : 'GET',
                datatype    : 'html',
            }).done(function(data){
                $('#modal-dialog-assing-visitor-participant').html(data);
            });
        });

        $("#assingVisitorParticipantModal").on("hidden.bs.modal", function(){
            $('#modal-dialog-assing-visitor-participant').html("");
        });

        $(".btnLiberate").click(function() {
            window.event.preventDefault();
            swal({
                title: "¿Estás seguro?",
                text: 'Se va a eliminar el participante del emparejamiento.',
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
                    var url = $(this).attr('href');
                    $(location).attr('href', url);
                }
            });
        });

        $('#updateModal').on('show.bs.modal', function(e) {
            var row = $(e.relatedTarget).parents('div');
            var id = row.attr("data-id");
            var url = '{{ route("admin.season_competitions_phases_groups_playoffs.clashes.match.edit", ":match_id") }}';
            url = url.replace(':match_id', id);
            $.ajax({
                url         : url,
                type        : 'GET',
                datatype    : 'html',
            }).done(function(data){
                $('#modal-dialog-update').html(data);
                // $('#stats_mvp').selectpicker('refresh');
            });
        });

        $("#updateModal").on("hidden.bs.modal", function(){
            $('#modal-dialog-update').html("");
        });

    	$("#second_round").click(function(){
    		// $("#inverse_order").attr('disabled', !$("#inverse_order").attr('disabled'));
	    	if ($("#second_round").prop('checked')) {
	    		$("#inverse_order").attr('disabled', false);
	    		$("#lbinverse_order").removeClass('text-muted');
	    	} else {
	    		$("#inverse_order").attr('disabled', true);
	    		$("#inverse_order").prop('checked', false);
	    		$("#lbinverse_order").addClass('text-muted');
	    	}
    	});

        $("#btnGenerate").click(function(){
            swal({
                text: "Generando calendario, por favor espera...",
                button: false,
                closeOnClickOutside: false,
                closeOnEsc: false,
            });
        });

        $(".btnReset").click(function(e){
	        window.event.preventDefault();
	        var row = $(this).parents('tr');
	        var id = row.attr("data-id");
	        var name = row.attr("data-name");

	        swal({
	            title: "¿Estás seguro?",
	            text: 'Se va a resetear el partido "' + name + '". No se podrán deshacer los cambios!',
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
	                var url = $(this).attr('href');
	            	$(location).attr('href', url);
	            }
	        });
        });
    });


    function enable_disable_penalties(e) {
        var row = $(e).parents('tr');
        var round_trip = row.attr("data-round-round_trip");
        var double_value = row.attr("data-round-double_value");
        var prev_local_score = row.attr("data-prev-match-local_score");
        var prev_visitor_score = row.attr("data-prev-match-visitor_score");
        var match_order = row.attr("data-match_order");
        var local_score = parseInt($("#local_score").val());
        var visitor_score = parseInt($("#visitor_score").val());

        if (round_trip == 1) {
            if (match_order == 2) {
                var local_score_total = parseInt($("#local_score").val()) + parseInt(prev_visitor_score);
                var visitor_score_total = parseInt($("#visitor_score").val()) + parseInt(prev_local_score);

                if (double_value == 1) {
                    if ( (local_score == prev_local_score) && (visitor_score == prev_visitor_score) ) {
                        $("#penalties_local_score").prop('disabled', false);
                        $("#penalties_visitor_score").prop('disabled', false);

                        $("#send_result").prop('disabled', true);
                    } else {
                        $("#penalties_local_score").prop('disabled', true);
                        $("#penalties_local_score").val('0');
                        $("#penalties_visitor_score").prop('disabled', true);
                        $("#penalties_visitor_score").val('0');

                        $("#send_result").prop('disabled', false);
                    }
                } else {
                    if (local_score_total == visitor_score_total) {
                        $("#penalties_local_score").prop('disabled', false);
                        $("#penalties_visitor_score").prop('disabled', false);

                        $("#send_result").prop('disabled', true);
                    } else {
                        $("#penalties_local_score").prop('disabled', true);
                        $("#penalties_local_score").val('0');
                        $("#penalties_visitor_score").prop('disabled', true);
                        $("#penalties_visitor_score").val('0');

                        $("#send_result").prop('disabled', false);
                    }
                }
            } else {
                $("#send_result").prop('disabled', false);
            }
        } else {
            if (local_score == visitor_score) {
                $("#penalties_local_score").prop('disabled', false);
                $("#penalties_visitor_score").prop('disabled', false);

                $("#send_result").prop('disabled', true);
            } else {
                $("#penalties_local_score").prop('disabled', true);
                $("#penalties_local_score").val('0');
                $("#penalties_visitor_score").prop('disabled', true);
                $("#penalties_visitor_score").val('0');

                $("#send_result").prop('disabled', false);
            }
        }
    }

    function validate_penalties(e) {
        if ($("#penalties_local_score").val() == $("#penalties_visitor_score").val()) {
            $("#send_result").prop('disabled', true);
        } else {
            $("#send_result").prop('disabled', false);
        }
    }

    function sanction_local(id) {
    	if ($('#chk_local_sanctioned').prop('checked')) {
    		$("#local_score").val('0');
    		$('#local_score').prop('readonly', true);
    		$("#visitor_score").val('3');
    		$('#visitor_score').prop('readonly', true);
			$("#sanctioned_id").val(id);
	    	$('#chk_visitor_sanctioned').prop('disabled', true);
	    	$('#lb_visitor_sanctioned').addClass('text-muted');
    	} else {
    		$("#local_score").val('0');
    		$('#local_score').prop('readonly', false);
    		$("#visitor_score").val('0');
    		$('#visitor_score').prop('readonly', false);
			$("#sanctioned_id").val(null);
	    	$('#chk_visitor_sanctioned').prop('disabled', false);
	    	$('#lb_visitor_sanctioned').removeClass('text-muted');
    	}
    }

    function sanction_visitor(id) {
    	if ($('#chk_visitor_sanctioned').prop('checked')) {
    		$("#local_score").val('3');
    		$('#local_score').prop('readonly', true);
    		$("#visitor_score").val('0');
    		$('#visitor_score').prop('readonly', true);
			$("#sanctioned_id").val(id);
	    	$('#chk_local_sanctioned').prop('disabled', true);
	    	$('#lb_local_sanctioned').addClass('text-muted');
    	} else {
    		$("#local_score").val('0');
    		$('#local_score').prop('readonly', false);
    		$("#visitor_score").val('0');
    		$('#visitor_score').prop('readonly', false);
			$("#sanctioned_id").val(null);
	    	$('#chk_local_sanctioned').prop('disabled', false);
	    	$('#lb_local_sanctioned').removeClass('text-muted');
    	}
    }


    function generate() {
		window.event.preventDefault();
		$('#frmGenerate').submit();
    }

    function sanctioned(local, id) {
    	window.event.preventDefault();
    	if (local) {
    		$("#local_score").val('0');
    		$("#visitor_score").val('3');
    	} else {
    		$("#local_score").val('3');
    		$("#visitor_score").val('0');
    	}
		$("#sanctioned_id").val(id);
    	console.log('Sancionamos a ' + id);
    }

</script>