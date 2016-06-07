;(function (window) {
    "use strict";

    var app = window.app || {};

    app.pagination = {

        use: function ()
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
            this.use();
        }

    };

})(window);