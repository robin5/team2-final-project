/**/

	function analyzeTone(textArea, respDiv) {

		if (textArea !='') {
			$.ajax({
				type: "GET",  
				url: './includes/ToneAnalyzer/tone.inc.php', 
				data: { feedback : textArea},
			
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
	}
	  
	/***************************************************
	 *
	 ***************************************************/

     function getAreaTxt(button, textArea, respDiv) {
		// Change button text to Refresh
		$('#' + button).html('Refresh');
	
		//is it summary? 
		if (button === 'btn-summary') {
			//process text for summary
			var textArea='';
			$('textarea').each(function(){
				textArea += " "+ $(this).val();});

			$('#' + button).html('Hide');
			$("#btn-summary").attr("onclick","toggleAnalyze()");


		  } else {
		  //process text for a question
		  	textArea = $('#' + textArea).val();}
			$('#tone-summary').css('display', 'block');
		  //Get Tone
		  analyzeTone(textArea, '#' + respDiv);
    }
