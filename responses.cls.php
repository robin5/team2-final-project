<?php
require_once('includes/Database/TeamInstanceFactory.php');
require_once('includes/Database/QuestionResponseFactory.php');

class Responses {

	public static function xxxinjectQuestionAnswers($reviewer, $questions, $users) {

	$html = <<<EOT

		<!-- <QUESTIONS> -->
		<div>

		<!------------------------------------------------------>
		<div class="question">
		<p class="resp-grade">Grade: 
		<span style="background: white;">&nbsp;A&nbsp;</span> Work cooperatively as part of a team and contribute in both leadership and supportive roles.</p>
		
		<textarea id="txt-q1" class="ta-response" rows="5" placeholder="Enter text here...">Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.</textarea>
		<br>
		<button id="btn-q1" class="resp-button" onclick="getAreaTxt('btn-q1','txt-q1','tone-q1')">Review</button>
		<div id="tone-q1"></div>
		<div class="clear-question"></div>
		</div>

		<!------------------------------------------------------>
		<div class="question">
		<p class="resp-grade">Grade: 
		<span style="background: white;">&nbsp;A&nbsp;</span> Build relationships of trust, mutual respect and productive interactions.</p>
		<textarea id="txt-q2" class="ta-response" rows="5" placeholder="Enter text here...">Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.</textarea>
		<br><button id="btn-q2" class="resp-button" onclick="getAreaTxt('btn-q2','txt-q2','tone-q2')">Review</button><br>
		<div id="tone-q2"><p></p></div>
		</div>

		<!------------------------------------------------------>
		<div class="question">
		<p class="resp-grade">Grade: 
		<span style="background: white;">&nbsp;A&nbsp;</span> Be flexible, adapt to unanticipated situations and resolve conflicts</p>
		<textarea id="txt-q3" class="ta-response" rows="5" placeholder="Enter text here...">Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.</textarea>
		<br><button id="btn-q3" class="resp-button" onclick="getAreaTxt('btn-q3','txt-q3','tone-q3')">Review</button><br>
		<div id="tone-q3"><p></p></div>
		</div>
		
		<!------------------------------------------------------>
		<div class="question">
		<p class="resp-grade">Grade: 
		<span style="background: white;">&nbsp;A&nbsp;</span> Communicate and clarify ideas through well-written business correspondence, proposals, instructions, design summaries and client briefs. (Note: This includes all correspondence through email, Slack, and other communication methodologies adopted by your team.)</p>
		<textarea id="txt-q4" class="ta-response" rows="5" placeholder="Enter text here...">Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.</textarea>
		<br><button id="btn-q4" class="resp-button" onclick="getAreaTxt('btn-q4','txt-q4','tone-q4')">Review</button><br>
		<div id="tone-q4"><p></p></div>
		</div>

		<!------------------------------------------------------>
		</div>
EOT;

	echo $html;

	}

	public static function injectQuestionAnswers($reviewer, $questions, $users) {
	
		//echo "<div>";

		$userIndex = 0;
		$buttonIndex = 0;
		foreach($users as $user) {
			echo "<div id=\"user-{$userIndex}\" class=\"question\">";
			echo "<div class=\"resp-grade\">";
			echo "<p>{$user['first_name']} {$user['last_name']} ({$user['user_name']})</p>";
			echo "</div>";
			foreach($questions as $question) {
				
				$text = "";
				$grade = "---";
				if (false !== ($response = QuestionResponsefactory::getResponse($question['question_id'], $user['user_id'], $reviewer))) {
					$text = $response['text'];
					$grade = $response['grade'];
					if (empty($grade)) {
						$grade = "---";
					}
				}
				//echo "<br>{$user['first_name']} {$user['last_name']}&nbsp;&nbsp;-&nbsp;&nbsp;";
				echo "<div class=\"resp-grade\">";
				echo "Grade: <span style=\"background: white;\">&nbsp;{$grade}&nbsp;</span><br>";
				echo "</div>";
				echo "<textarea id=\"txt-q{$buttonIndex}\" class=\"ta-response\" cols=\"80\" rows=\"5\">{$text}</textarea>";
				
				echo "<button id=\"btn-q{$buttonIndex}\" class=\"resp-button\" onclick=\"getAreaTxt('btn-q{$buttonIndex}','txt-q{$buttonIndex}','tone-q{$buttonIndex}')\">Review</button>";
				echo "<div id=\"tone-q{$buttonIndex}\" class=\"resp-tone\"></div>";
				$buttonIndex++;
			} // for each questions
			echo "<div class=\"clear-question\"></div></div>";
			$userIndex++;
		} // for each users
		//echo "</div>";
	}

	public static function injectUserTabs($users) {
		
		echo "<div id=\"div-resp-student-select\">";
		echo "<strong>";
		$index = 0;
		foreach($users as $user) {
			if ($index) {
				echo "&nbsp;|&nbsp;";
			}
			echo "<span id=\"sp-tab-{$index}\" data-index=\"{$index}\" class=\"sp-tab\" onclick=\"tabClick('{$index}');\">{$user['first_name']} {$user['last_name']}</span>";
			$index++;
		}
		echo "</strong>";
		echo "</div>";
		
	}
	
}