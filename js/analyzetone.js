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

		//is it summary? 
		//if button clicked is summary bttton 
		if (button.search( "btn-summary") != -1) {
			//console.log("YOU ARE in summary button");
			//process text for summary
			var txtcollect="";

			//SELECT EACH text area and collect all all the text shown() text from textarea
			$( "textarea" ).each(function(  ) {
				//text only from visible elements //
				if ($(this).is(':visible') ) {
					txtcollect += " "+ $(this).val();
				} //end if it is not hide()
			}); //END each text area
			//console.log(txtcollect);

			//IF it is question button //
		  } else {
		  	console.log("question  button");
		  //process text for a question

		  	txtcollect = $('#' + txtA).val();
		  	//console.log(txtcollect);	
		  }//END ELSE

		 //Get Tone
		 analyzeTone(txtcollect, '#' + respDiv);
    } //END getAreaTxt
