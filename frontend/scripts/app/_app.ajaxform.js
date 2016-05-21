;( function( $ ) {
	"use strict";

	$.app = $.app || {};

	var $body = $('body'), _this = null;

	$.app.ajaxForm = {

		config: {
			form_class: '.form-ajax',
			form_reset: 'form-reset',
			form_button: '.j-form-button',
			link_class: '.js-request-link',
			error_class: 'error',
			error_message: 'form__error-message',
			form_label: '.form__wrapper',
			checkbox_label: 'checkbox__label'
		},

		callback_stack: {},

		extend: function(config)
		{
			_this = this;

			if (typeof config !== 'undefined')
			{
				var x;
				for (x in config)
				{
					if (typeof _this.config[x] !== 'undefined')
						_this.config[x] = config[x];
				}
			}
		},

		default_handler: function($form, response)
		{
			_this = this;

			if (response.status)
			{
				if (response.hasOwnProperty('redirect_url'))
				{
					window.location.href = response.redirect_url;
				}
			}
			else if (response.errors)
			{
				_this.validation($form, response.errors);
			}
			
			if (response.hasOwnProperty('message'))
			{
				$.popup.message(response.title, response.message);
			}

		},

		upload: function()
		{
			_this = this;

			$body.on('submit', '.form-file-upload', function(e) {
				return AIM.submit(this, {
					onStart: function()
					{

					},
					onComplete: function(result)
					{
						if (typeof result === 'object' && result.status === true && result.hasOwnProperty('photo_url'))
						{

						}
					}
				});
			});
			
			$(document).on('change', '.upload_button_onchange', function(){
				if ($(this).closest('.upload_button').find('.upload_button_field').length > 0)
				{
					$(this).closest('.upload_button').find('.upload_button_field').html($(this).val());
				}
			});
		},

		validation: function($form, errors)
		{
			_this = this;

			$form.find('.' + _this.config.error_class).removeClass(_this.config.error_class);
			$form.find('.' + _this.config.error_message).remove();
			
			var fieldName, field;

			setTimeout(function() {
				if (errors)
				{
					for(fieldName in errors)
					{
						if ($form.find('input[name="'+fieldName+'"]').length > 0)
						{
							field = $form.find('input[name="'+fieldName+'"]');
						}

						if ($form.find('select[name="'+fieldName+'"]').length > 0)
						{
							field = $form.find('select[name="'+fieldName+'"]');
						}

						if ($form.find('textarea[name="'+fieldName+'"]').length > 0)
						{
							field = $form.find('textarea[name="'+fieldName+'"]');
						}

						if (field.closest('.' + _this.config.checkbox_label).length > 0)
						{
							field = field.closest('.' + _this.config.checkbox_label);
						}

						if (typeof field !== 'undefined')
						{
							field.addClass(_this.config.error_class);
							field.closest(_this.config.form_label).append('<div class="' + _this.config.error_message + '">' + errors[fieldName] + '</div>');
						}
					}
				}
			}, 10);
		},

		send: function(action, method, data, cb, err)
		{
			if (typeof cb !== 'function')
			{
				cb = function() {};
			}

			if (typeof err !== 'function')
			{
				err = function() {};
			}

			try {
			
				$.ajax({
					url: action,
					type: method,
					data: data,
					contentType: false,
					processData: method.toLowerCase() == 'get',
					success: cb,
					error: err,
					dataType: 'JSON'
				});

			} catch(e){}
		},

		initLink: function()
		{
			_this = this;

			$body.on('click', _this.config.link_class, function(e) {
				e.preventDefault ? e.preventDefault() : e.returnValue = false;
				
				var $link = $(this);

				if ($link.data('is-busy')) {
					return;
				}

				$link.data('is-busy', true);

				var action = ($(this).attr('href')) || $(this).data('action'),
					method = ($(this).data('method')) || 'get',
					data = {};

				_this.send(
					action,
					method,
					data,
					function(response)
					{
						if ($link.data('callback') && _this.callback_stack.hasOwnProperty($link.data('callback')))
						{
							_this.callback_stack[$link.data('callback')]($link, response);
						}
						else
						{
							_this.default_handler($link, response);
						}

						if (response.status === true)
						{

						}

						$link.data('is-busy', false);
					},
					function(response)
					{
						_this.default_handler($link, response);
						$link.data('is-busy', false);
					}
				);
			});
		},

		initForm: function()
		{
			_this = this;

			$body.on('submit', _this.config.form_class, function(e) {
				e.preventDefault ? e.preventDefault() : e.returnValue = false;

				var $form 	= $(this),
					action	= $form.attr('action'),
					method	= ($form.attr('method') || 'post'),
					data 	= !!window.FormData ? new FormData($form[0]) : $form.serialize();

				if ($form.data('is-busy')) {
					return;
				}

				$form.data('is-busy', true);

				if ($form.find(_this.config.form_button).length)
				{
					var $button = $form.find(_this.config.form_button);
				}

				if (typeof $button !== 'undefined')
				{
					if ($button.data('loading'))
					{
						$button.data('original', $button.text());
						$button.text($button.data('loading'));
					}

					$button.addClass('preload');
				}

				if ($form.data('precallback') && _this.callback_stack.hasOwnProperty($form.data('precallback'))) {
					if(!_this.callback_stack[$form.data('precallback')]($form))
					{
						return false;
					}
				}

				_this.send(
					action,
					method,
					data,
					function(response)
					{
						if ($form.data('callback') && _this.callback_stack.hasOwnProperty($form.data('callback')))
						{
							_this.callback_stack[$form.data('callback')]($form, response);
						}
						else
						{
							_this.default_handler($form, response);
						}

						if (response.status === true)
						{
							if (form.hasClass(_this.config.form_reset))
							{
								form.find('.' + _this.config.error_class).removeClass(_this.config.error_class);
								form.get(0).reset();
							}
						}

						if (typeof $button !== 'undefined')
						{
							if ($button.data('original'))
							{
								$button.text($button.data('original'));
							}

							$button.removeClass('preload');
						}
						
						$form.data('is-busy', false);
					},
					function(response)
					{
						_this.default_handler($form, response);
						$form.data('is-busy', false);
					}
				);
			});
		},

		initValidate: function()
		{
			$body.on('focus', '.form__input.error, .form__textarea.error', function(){
				$(this).removeClass('error');
			});
		},

		init: function(config)
		{
			if (typeof config !== 'undefined')
			{
				this.extend(config);
			}
			
			this.initForm();
			this.initLink();
			this.initValidate();
		}

	};

})( jQuery );