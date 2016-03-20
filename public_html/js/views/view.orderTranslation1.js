/*
Name: 			View - Contact
Written by: 	Okler Themes - (http://www.okler.net)
Version: 		4.4.0
*/

(function($) {

	'use strict';

	/*
	Contact Form: Basic
	*/
	$('#orderTranslationForm:not([data-type=advanced])').validate({		
		submitHandler: function(form) {

			var $form = $(form),
				$messageSuccess = $('#orderSuccess'),
				$messageError = $('#orderError'),
				$submitButton = $(this.submitButton);

			$submitButton.button('loading');

			// Ajax Submit
			$.ajax({
				type: 'POST',
				url: $form.attr('action'),
				data: {
					clientNumber: $form.find('#clientNumber').val(),
					name: $form.find('#name').val(),
					phone: $form.find('#phone').val(),
					email: $form.find('#email').val(),
					by: $form.find('#by').val(),
					ddate: $form.find('#ddate').val(),
					fromLang: $form.find('#fromLang').val(),
					toLang: $form.find('#toLang').val(),
					attach: $form.find('#attachment').val()
				},
				dataType: 'json',
				complete: function(data) {
				
					if (typeof data.responseJSON === 'object') {
						if (data.responseJSON.response == 'success') {

							$messageSuccess.removeClass('hidden');
							$messageError.addClass('hidden');

							// Reset Form
							$form.find('.form-control')
								.val('')
								.blur()
								.parent()
								.removeClass('has-success')
								.removeClass('has-error')
								.find('label.error')
								.remove();

							if (($messageSuccess.offset().top - 80) < $(window).scrollTop()) {
								$('html, body').animate({
									scrollTop: $messageSuccess.offset().top - 80
								}, 300);
							}

							$submitButton.button('reset');
							
							return;

						}
					}

					$messageError.removeClass('hidden');
					$messageSuccess.addClass('hidden');

					if (($messageError.offset().top - 80) < $(window).scrollTop()) {
						$('html, body').animate({
							scrollTop: $messageError.offset().top - 80
						}, 300);
					}

					$form.find('.has-success')
						.removeClass('has-success');
						
					$submitButton.button('reset');

				}
			});
		}
	});

	/*
	Contact Form: Advanced
	*/
	$('#contactFormAdvanced, #contactForm[data-type=advanced]').validate({
		onkeyup: false,
		onclick: false,
		onfocusout: false,
		rules: {
			'captcha': {
				captcha: true
			},
			'checkboxes[]': {
				required: true
			},
			'radios': {
				required: true
			}
		},
		errorPlacement: function(error, element) {
			if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox') {
				error.appendTo(element.parent().parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

}).apply(this, [jQuery]);


$('#clearform').on('click', function () {
    $("#orderTranslationForm").validate().resetForm();  // clear out the validation errors	
});