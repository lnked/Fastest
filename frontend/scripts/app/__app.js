;(function (w) {
    "use strict";

	w.app = {

		init: function()
		{
            // new Tablesort(document.querySelectorAll('table.table')[1], {
            //     descending: true
            // });

            var table = document.querySelectorAll('table.table')[1];
            var sort = new Tablesort(table, {
                descending: true
            });

            // sort.refresh();
            
            table.addEventListener('beforeSort', function() {
                // alert('Table is about to be sorted!');
            });

            table.addEventListener('afterSort', function() {
                // alert('Table sorted!');
            });

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