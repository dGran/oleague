<script>
    $(function() {
        Mousetrap.bind(['command+s', 'ctrl+s'], function() {
            $("#frmEdit").submit();
            return false;
        });
        Mousetrap.bind(['command+c', 'ctrl+c'], function() {
            window.location.href = '{{ route("admin.season_competitions") }}';
        });

        $('#url_img').change(function(){
            if ($('#url_img').is(':checked')) {
                $('#img_local').parent().removeClass('d-inline-block');
                $('#img_local').parent().addClass('d-none');
                $('#img_link').removeClass('d-none');
                $('#img_link').addClass('d-inline-block');
                $('#img_link').focus();
            } else {
                $('#img_local').parent().removeClass('d-none');
                $('#img_local').parent().addClass('d-inline-block');
                $('#img_link').removeClass('d-inline-block');
                $('#img_link').addClass('d-none');
            }
        });

        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        // img preview
        $('#img_field').change(function(){
            var reader = new FileReader();
            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                $('.preview').removeClass('d-none').addClass('d-block animated bounceIn').one(animationEnd, function() {
                    $(this).removeClass('animated bounceIn');
                });
                $('#img_preview').attr('src', e.target.result);
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
            $('#img_remove').removeClass('d-none').addClass('d-inline-block');
        });
        // img preview remove
        $('#img_remove').click(function(){
            $('.preview').addClass('animated bounceOut').one(animationEnd, function() {
                $(this).removeClass('d-block animated bounceOut').addClass('d-none');
                $("#img_field").val("");
                $("#old_img").val("");
                $('#img_preview').attr('src', '');
                $('#img_remove').removeClass('d-inline-block').addClass('d-none');
            });
        });


        $("#frmAdd").submit(function(event) {
            $("#btnSave").attr("disabled", "disabled");
        });
    });

</script>