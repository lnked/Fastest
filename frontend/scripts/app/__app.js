;(function (w) {
    "use strict";

	w.app = {

        tablesort: function()
        {
            var sort = [], table = document.querySelectorAll('table.table');

            if (table.length) {

                table.forEach(function(element, index, array){
                    
                    sort[index] = new Tablesort(element, {
                        descending: true
                    });

                    // sort[index].refresh();

                    element.addEventListener('beforeSort', function() {
                        // alert('Table is about to be sorted!');
                    });

                    element.addEventListener('afterSort', function() {
                        // alert('Table sorted!');
                    });
                });

            }

        },

		init: function()
		{
            $('select').chosen({
                width: "100%",
                allow_single_deselect: true,
                no_results_text: 'Не найдено!',
                disable_search_threshold: 10
            });

            this.tablesort();

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
            
            // this.pagination.init();
            
            // this.placeholder.init();

            // this.select.init();
		}

	};

    w.app.init();

})(window);