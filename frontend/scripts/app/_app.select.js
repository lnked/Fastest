;(function (w) {
	"use strict";

	w.app = w.app || {};

	w.app.plugin = {
        
        use: function ()
        {
			$('select').selectize();
		},

        init: function()
        {
            this.use();
        }

	};

})(window);