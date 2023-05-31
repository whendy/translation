<script type="text/javascript">
    $(document).ready(function (){
        $(document).find('.modal').on('hidden.bs.modal',function (e) {
            e.preventDefault();
            $(document).find('.modal-title').html('');
            $(document).find('.modal-body-load').html('');
        });
        $(document).on('click','.show_modal_sm',function(e) {
            e.stopPropagation();
            modal_show(this,$(this).attr('data-value'));
        });
        $(document).on('click','.show_modal_xl',function(e) {
            e.stopPropagation();
            modal_show(this,$(this).attr('data-value'),'xl');
        });

        $(document).on('submit', '#formLanguage', function (e) {
            e.preventDefault();
            let self = $(this),
                url = self.attr('action'),
                params = self.serialize();
            $.ajax({
                url:url, type:'POST', typeData:'json',  cache:false, data:params,
                success: function(response){
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        // Display the error messages to the user
                        $.each(errors, function (idx, val){
                            $(`[name="${idx}"]`,document).addClass('is-invalid');
                            $(`.invalid-feedback-${idx}`,document).html(val);
                        });
                    }else{
                        alert('Internal Server Error');
                    }
                }
            });
        });

        $(document).on('click', '.makeItDefault', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let self = $(this),
                url = self.attr('data-action'),
                params = {};
            $.ajax({
                url:url, type:'POST', typeData:'json',  cache:false, data:params,
                success: function(response){
                    window.location.reload();
                }
            });
        });

        /*=======================================TRANSLATION===========================================*/
        let selector_input_translation_namespace_select = $('#input_translation_namespace_select', document),
            selector_input_translation_namespace        = $('#input_translation_namespace', document),
            selector_input_translation_namespace_check  = $('#input_translation_namespace_check', document),

            selector_input_translation_group_select     = $('#input_translation_group_select', document),
            selector_input_translation_group            = $('#input_translation_group', document),
            selector_input_translation_group_check      = $('#input_translation_group_check', document);

        $(document).on('change', '#input_translation_namespace_check', function(e){
            if ($(this).is(':checked')){
                $('#input_translation_namespace_select', document).prop('disabled', true).addClass('d-none');
                $('#input_translation_namespace', document).prop('disabled', false).removeClass('d-none');
                return true;
            }else{
                $('#input_translation_namespace', document).prop('disabled', true).addClass('d-none');
                $('#input_translation_namespace_select', document).prop('disabled', false).removeClass('d-none');
                return true;
            }
        });

        $(document).on('change', '#input_translation_group_check', function(e){
            if ($(this).is(':checked')){
                $('#input_translation_group_select', document).prop('disabled', true).addClass('d-none');
                $('#input_translation_group', document).prop('disabled', false).removeClass('d-none');
                return true;
            }else{
                $('#input_translation_group', document).prop('disabled', true).addClass('d-none');
                $('#input_translation_group_select', document).prop('disabled', false).removeClass('d-none');
                return true;
            }
        });

        $(document).on('submit', '#formTranslationSaveUpdate', function (e) {
            e.preventDefault();
            let self = $(this),
                url = self.attr('data-action'),
                params = self.serialize();
            $.ajax({
                url:url, type:'POST', typeData:'json',  cache:false, data:params,
                success: function(response){
                    window.location.reload();
                }
            });
        });

        $(document).on('click', '.deleteTranslation', function (e) {
            let self = $(this),
                url = self.attr('data-action'),
                title = self.attr('title'),
                params = self.attr('data-value');
            params = (typeof params === "string" ? JSON.parse(params) : params);
            if(confirm(title)) {
                $.ajax({
                    url: url, type: 'POST', typeData: 'json', cache: false, data: params,
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }else {
                e.preventDefault();
                e.stopPropagation();
            }
        });

        if($('ul.pagination', document).length){
            $.each($('ul.pagination li', document), function(){
                $(this).addClass('page-item');
                $(this).find('a, span').addClass('page-link');
            });
        }

    });

    $.fn.hasAttr = function(attribute){
        return (typeof this.attr(attribute) !== 'undefined' && this.attr(attribute) !== false);
    };

    function hideModal()
    {
        $(document).find('.modal-title').html('');
        $(document).find('.modal-body-load').html('');
        $(document).find('.modal').modal('hide');
    }

    function modal_show(selector,dataValue,size='sm') {
        $(`#modal-${size}`).modal('show');
        let modalTitle = $(selector).attr('title');

        $(`#modal-${size}-label`).html(modalTitle);

        let url = $(selector).attr('href'),
            dataAjax = dataValue,
            method='POST';

        if ($(selector).hasAttr('data-action')){
            url = $(selector).attr('data-action');
        }
        if(typeof dataAjax !== 'undefined' && dataAjax !=='') {
            if (typeof dataAjax === 'string') {
                dataAjax = JSON.parse(dataAjax);
            }
        }else {
            dataAjax = {};
        }
        if ( typeof $(selector).attr('data-method') !== 'undefined'){
            method = $(selector).attr('data-method');
        }
        $(`#modal-${size}-body-load`).html('<div class="overlay d-flex justify-content-center align-items-center text-center">Request...</div>');
        $.ajax({
            url:url, type:method, typeData:'json',  cache:false, data:dataAjax,
            success: function(data){
                $(`#modal-${size}-body-load`).show().html(data);
            }
        });
    }
</script>
