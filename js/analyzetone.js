/**/

	function analyzeTone(textArea, respDiv) {
		$.ajax({
			type: "GET",  
			url: './includes/ToneAnalyzer/tone.inc.php', 
			data: { feedback : textArea},

			success:function(html) {
				var toneanalyze = $(respDiv);
				toneanalyze.html(html);
			}
		});
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
