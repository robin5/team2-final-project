/*****************************************************************
 *       File: register.js
 *     Author: Robin Murray
 *      Class: CTEC 227
 * Assignment: Final Project 
 *        Due: June 14, 2017 
 *****************************************************************/

// Wait for document to be ready
$( document ).ready(function() {
	// Process the submit button click event
	$('#register').on('click', validateForm);
});
/***********************************************
 * Function: validateForm
 * Description: validates the form.
 ***********************************************/
 
function validateForm(evt) {

	var isValid = false;
	const msgRequired = "<strong>&nbsp;&nbsp;required!&nbsp;&nbsp;</strong>";
	
	try {
		isValid = true;
		isValid &= checkValid('#username',   '#req-username'  );
		isValid &= checkValid('#password',   '#req-password'  );
		isValid &= checkValid('#first-name', '#req-first-name');
		isValid &= checkValid('#last-name',  '#req-last-name' );
		isValid &= checkValid('#email',      '#req-email'     );
	}
	finally {
		if (!isValid) {
			evt.preventDefault();
		}
	}
}

/*******************************************************
 * Function: checkValid
 * Description: Displays required message if 
 *     field is not blank
 * Parameters:
 *     field - field to check for being blank
 *     reqElement - element to place required message in
 *******************************************************/

 function checkValid(field, reqElement) {

	var text = $(field).val().trim();
	$(field).val(text);

	if (text == '') {
		$(reqElement).html('<strong>&nbsp;&nbsp;required!&nbsp;&nbsp;</strong>').show();
		return false;
	} else {
		$(reqElement).html('&nbsp;').hide();
		return true;
	}
}
