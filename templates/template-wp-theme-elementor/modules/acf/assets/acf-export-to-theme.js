(function($){
    var fn  = {};
    var isLoading = false;
    fn.exportAcfToTheme = function exportAcfToTheme(e) {
        e.preventDefault();
        var $this = $(this);
        var $form = $this.parents('form');
        var fieldGroups = [];

        if (isLoading) {
            return false;
        }

        isLoading = true;
        $this.addClass('button-disabled')
        $this.siblings('span').show();
        $('.acf-admin-notice').remove();

        $.each($form.find('input[name^="keys"]:checked'), function(index, el) {
            var $el = $(el);
            fieldGroups.push($el.val());
        });

        $.ajax({
            url: objBdAcf.ajaxURL,
            type: 'POST',
            dataType: 'json',
            data: {
                action : objBdAcf.action,
                nonce : objBdAcf.nonce,
                field_groups : fieldGroups,
            }
        })
        .done(function(r) {
            if (r.success && r.data) {
                $('#acf-admin-tools h1').after(r.data);
                $form.find('input[type="checkbox"]').prop('checked', false);
                window.onbeforeunload = function(){};
            }

            isLoading = false;
            $this.removeClass('button-disabled')
            $this.siblings('span').hide();
        })
        .fail(function() {
            isLoading = false;
            $this.removeClass('button-disabled')
            $this.siblings('span').hide();
        });
    }

    $(window).load(function() {
        var $checkboxWrapper = $('#acf-admin-tool-export .acf-field-checkbox');

        if ($checkboxWrapper.length) {
            var $exportBtn = $('<a class="button button-link-delete" id="acf-export-theme">Export as php file into theme</a><span class="loader"></span>');

            $exportBtn.on('click', fn.exportAcfToTheme);

            $exportBtn.appendTo($checkboxWrapper.parents('#acf-admin-tool-export').find('.acf-submit'));
        }
    });

    $(document).on('click', '.notice-dismiss', function(e) {
        var $this = $(this);
        $this.parents('.notice').remove();
    });

})(jQuery);
