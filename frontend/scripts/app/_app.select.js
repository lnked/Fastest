;(function (w) {
	"use strict";

	w.app = w.app || {};

	w.app.plugin = {
        
        use: function ()
        {
            // function selectize( selector )
            // {
            //     selector = selector || 'select';
                
            //     $(selector).chosen({
            //         width: "100%",
            //         allow_single_deselect: true,
            //         no_results_text: 'Не найдено!',
            //         disable_search_threshold: 10
            //     });
            // }
		},

        init: function()
        {
            this.use();
        }

	};

})(window);