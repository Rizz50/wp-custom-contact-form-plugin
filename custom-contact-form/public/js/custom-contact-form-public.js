(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( window ).load(function() {
		$( '#firstName, #lastName' ).keydown( function(event) {
			var eventInstance = eventInstance || window.event;
	        var key = eventInstance.keyCode || eventInstance.which;
	        // console.log(key);
	        if ( ( 65 <= key ) && ( key <= 90 ) || ( 97 <= key ) && ( key <= 122 ) || key == 8 || key == 9 || key == 189 || key == 109 || key == 35 || key == 36 || key == 45 || key == 46 || key == 13 ) {
	            return true;
	        } else {
	            if ( eventInstance.preventDefault )
	                eventInstance.preventDefault();
	            eventInstance.returnValue = false;
	            return false;
	        }
		} );

		$( '#contact' ).keydown( function(event) {
			var eventInstance = eventInstance || window.event;
	        var key = eventInstance.keyCode || eventInstance.which;
	        // console.log(key);
	        if ( ( 48 <= key ) && ( key <= 57 ) || key == 8 || key == 9 || key == 109 || key == 35 || key == 36 || key == 45 || key == 46 || key == 13 ) {
	            return true;
	        } else {
	            if ( eventInstance.preventDefault )
	                eventInstance.preventDefault();
	            eventInstance.returnValue = false;
	            return false;
	        }
		} );

		var error = 0;
		$( "#custom-form" ).submit( function( event ) {
			error = 0;
			event.preventDefault();
			

			var contactValidate = new RegExp(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/);
			var emailValidate = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);

			$( '.custom-submit-btn' ).prop( 'disabled', true );
			$( '.custom-error-msg' ).html( '' );
			$( '.custom-success-msg' ).html( '' );

			var data = {};
			var firstName = $( '#firstName' ).val();
			var lastName = $( '#lastName' ).val();
			var email = $( '#email' ).val();
			var contact = $( '#contact' ).val();
			var message = $( '#message' ).val();

			if( firstName == '' ) {
				data.firstName = 'First Name';
			}
			if( firstName != '' ) {
				if( firstName.length < 2 ) {
					data.firstNameLength = 'First Name can not be less then 2 characters.';
				}
			}

			if( lastName == '' ) {
				data.lastName = 'Last Name';
			}
			if( lastName != '' ) {
				if(lastName.length < 2) {
					data.lastNameLength = 'LastName can not be less then 2 characters.';
				}
			}

			if( email == '' ) {
				data.email = 'Email';
			}
			if( email != '' ) {
				data.emailValidation = emailValidate.test(email);
			}

			if( contact == '' ) {
				data.contact = 'Contact';
			}
			if( contact != '' ) {
				data.contactValidation = contactValidate.test(contact);
			}

			if( message == '' ) {
				data.message = 'Message';
			}
			if( message != '' ) {
				if(message.length < 10) {
					data.messageLength = 'Message can not be less then 10 characters.';
				}
			}

			if( !$.isEmptyObject( data ) ) {
				$.each( data, function( key, value ) {
					if( key == 'contactValidation' && value == false ) {
						$( '.custom-error-msg' ).append( 'Contact number invalid.' + '<br/>' );
						error = 1;
						return;
					}
					if( key == 'emailValidation' && value == false ) {
						$( '.custom-error-msg' ).append( 'Email invalid.' + '<br/>' );
						error = 1;
						return;
					}
					if( key == 'messageLength' ) {
						$( '.custom-error-msg' ).append( value + '<br/>' );
						error = 1;
						return;
					}
					if( key == 'firstNameLength' ) {
						$( '.custom-error-msg' ).append( value + '<br/>' );
						error = 1;
						return;
					} 
					if( key == 'lastNameLength' ) {
						$( '.custom-error-msg' ).append( value + '<br/>' );
						error = 1;
						return;
					}
					if( value != true ) {
						$( '.custom-error-msg' ).append( value +' is empty.' + '<br/>' );
						error = 1;
					}
				});
				$( '.custom-submit-btn' ).prop( 'disabled', false );
			}
			if( error == 0 ) {
				$.ajax({
				    type : "post",
				    dataType : "json",
				    url : ajax_var.url,
				    data : {
				    	action: "save_custom_form_data",
				    	custom_nonce: ajax_var.nonce,
				    	form: 'Custom Form',
				    	first_name: firstName,
				    	last_name: lastName,
				    	email: email,
				    	contact: contact,
				    	message: message
				    },
				    success: function( data ) {
				        // $( '.custom-success-msg' ).append( JSON.stringify(data) );
				        if( data.success ) {
				        	$( '#custom-form' )[0].reset();
				        	$( '.custom-success-msg' ).append( data.data.data );
				        } else {
				        	$( '.custom-error-msg' ).append( data.data.data );
				        }
				    }
				});
			}
		
			
		} );
	});

})( jQuery );
