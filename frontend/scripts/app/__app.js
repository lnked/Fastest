;(function($){
	"use strict";

	$.app = {
        
        _placeholder: function(element)
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

        initPlaceholder: function()
        {
            var _this = this;

            if ($('.input-placeholder').length)
            {
                $('.input-placeholder').each(function(){
                    _this._placeholder($(this));
                });

                $('body').on('keydown keyup', '.input-placeholder input', function(){
                    _this._placeholder($(this).closest('.input-placeholder'));
                });

                $('body').on('blur', '.input-placeholder input', function(){
                    _this._placeholder($(this).closest('.input-placeholder'));
                });
            }
        },

        initPagination: function()
        {
            if ($('.pagination').length)
            {
                $('.pagination').each(function(){
                    if (!is_undefined($(this).data('pagination')))
                    {
                        var config = $(this).data('pagination'), p, options = {};

                        options['cssStyle'] = 'light-theme';

                        for (p in config)
                        {
                            options[p] = config[p];
                        }

                        $(this).pagination(options);
                    }
                });
            }

            $(function() {
                // $(selector).pagination('getPagesCount');
                // $(selector).pagination('getCurrentPage');
                // $(selector).pagination('disable');
                // $(selector).pagination('enable');
                // $(selector).pagination('destroy');
                // $(selector).pagination('redraw');
                // $(selector).pagination('updateItems', 100);
                // $(selector).pagination('updateItemsOnPage', 20);
                // $(selector).pagination('drawPage', 5);
                // $(selector).pagination('nextPage');
                // $(selector).pagination('prevPage');
                // $(selector).pagination('selectPage', pageNumber);
            });
        },

		init: function()
		{
            this.initPlaceholder();
            this.initPagination();

            $('select').selectize();
		}
	};

})(jQuery);