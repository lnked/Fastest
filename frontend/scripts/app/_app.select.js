;(function (window) {
	"use strict";

	var app = window.app || {};

	app.plugin = {
        
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