	/***************************************************
	 * SEND TEXT TO WATSON API in TONE.INC.PHP
	 * PUT TONE RESULTS BACK ON PAGE
	 ***************************************************/
	function analyzeTone(txtReview, respDiv) {
		//txtReview = feedback to be reviewed
		//respDiv = ID for div where tone results will go

		if (txtReview !='') {
			$.ajax({
				type: "GET",  
				url: './includes/ToneAnalyzer/tone.inc.php', 
				data: { feedback : txtReview},
			
				success:function(html) {
					var toneanalyze = $(respDiv);
					toneanalyze.html(html);
				},
				error: function(){
					$(respDiv).html('<div class="tone-error">An error occurred</div>');
				}
			});
		} else {var toneanalyze = $(respDiv);
				toneanalyze.html('<div class="tone-error">ERROR: Nothing to Review...Please type your peer review in the text box above.</div>')};
	}//END analyzeTone


	/***************************************************
	 * COLLECT TEXT FOR TONE ANALYZE
	 ***************************************************/

     function getAreaTxt(button, txtA, respDiv) {
     	//button = button id, txtA=textarea id, respDiv = output div ID for tone results

		//is it summary or single question 
		//if summary button clicked 
		if (button.search( "btn-summary") != -1) {
			//console.log("YOU ARE in summary button");
			//process text for summary
			var txtcollect="";

			//Select all visible textareas objects and collect text in txtcollect to pass to ajax
			$( "textarea" ).each(function(  ) {
				//text only from visible elements //
				if ($(this).is(':visible') ) {
					txtcollect += " "+ $(this).val();
				} //end if it is not hide()
			}); //End txt collection for Summary
			//console.log(txtcollect);
		//// End Summary Text Tone data collection

			//Single Question button clicked//
		  } else {
		  	//console.log("question button");
		  //process text for tone of single question
		  	txtcollect = $('#' + txtA).val();
		  	//console.log(txtcollect);	
		  }//end txt collection for a single question

		 //Get Tone ->pass text to be reviewed for tone and the ouput div for results
		 analyzeTone(txtcollect, '#' + respDiv);
    } //END getAreaTxt
