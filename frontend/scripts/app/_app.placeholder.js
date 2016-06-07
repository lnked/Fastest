;(function (window) {
 "use strict";

    var app = window.app || {};

    app.placeholder = {
        
        use: function (element)
        {
            var $label = $(element), $input = $label.find('.input-placeholder__text');

            if (!$.trim($input.val()) == '')
            {
                $label.attr('placeholder', $.trim($input.attr('placeholder')));
                $label.addClass('focus');
            }
            else
            {
                $label.removeClass('focus');
                $label.removeAttr('placeholder');
            }
        },

        init: function()
        {
            if ($('.input-placeholder').length)
            {
                $('.input-placeholder').each(function(){
                    app.placeholder.use($(this));
                });

                $('body').on('keydown keyup', '.input-placeholder input', function(){
                    app.placeholder.use($(this).closest('.input-placeholder'));
                });

                $('body').on('blur', '.input-placeholder input', function(){
                    app.placeholder.use($(this).closest('.input-placeholder'));
                });
            }
        }

    };

})(window);