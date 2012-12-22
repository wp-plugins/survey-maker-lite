<?php
add_shortcode("questionaire","render_questionaire");

function render_questionaire($atts)
	{
	extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );
	
	$surveyId = $id;
	global $wpdb;
	
	$SQL = "SELECT surveyName FROM ".PLUGIN_TABLE_SURVEY." WHERE surveyId = '".$id."'";
	$data = $wpdb->get_results($SQL, ARRAY_A);	
	$surveyName = $data[0]["surveyName"];
	
	$SQL = "SELECT * FROM ".PLUGIN_TABLE_SURVEY." WHERE surveyId = '".$id."' ORDER BY   LENGTH(surveyId) ASC";
	$data = $wpdb->get_results($SQL, ARRAY_A);	
	
	$qa = $data[0]['qa'];
	
	$url = PLUGIN_URL;
	#wp_deregister_script('jquery');
	wp_enqueue_script('jquery1.8',$url.'js/jquery-1.8.2.min.js',array(),false,false);
	wp_enqueue_script('jqueryui',$url.'js/jquery-ui-1.8.24.custom.min.js',array(),false,false);
	wp_enqueue_script('paginate',$url.'js/jPaginate/jquery.paginate.js',array(),false,false);
	wp_enqueue_script('questions_prepare',$url.'js/question_prepare.js',array(),false,false);	

	
	
	
	
	#wp_register_script('jquery', $url.'js/jquery-1.8.2.min.js', array(''), '1.8.2');
	#wp_register_script('jquery', $url.'js/jquery-ui-1.8.24.custom.min.js', array('jquery'), '1.8.24');
	
	
	$returnStr = "<div id='shortcodeDiv' class='shortcodePage'><div class='spacer'>";

	$thankYouNotes = '';
	if ($_POST != null)
		{
		$returnStr = "Thanks for participating in our survey.";
		return $returnStr;
		}
	
	$returnStr .= '
	<link rel="stylesheet" href="'.$url.'css/generated-shortcode.css" type="text/css" media="all" />
	<link rel="stylesheet" href="'.$url.'js/jPaginate/css/style.css" type="text/css" media="all" />
	

	
	<h2 class="surveyTitle">'.$surveyName.'</h2>
	<form name="submitSurvey" id="questionDiv" method="post">
		<div id="loadingGif"><img  src="'.PLUGIN_URL.'css/loading.gif"/></div>
	</form>
	<div>'.$thankYouNotes.'</div>

	<div style="width:90%; margin:0px auto;padding-left:20px;">
		<div style="margin:0 auto; width:70%;">
		<div id="paging"></div>
		</div>
	</div>
	';



	$returnStr .= "<script>";
	$returnStr .= "window.onload=function(){";
	$returnStr .= "$(document).ready(function() {";
	$returnStr .= "$('#loadingGif').remove();";
	$returnStr .= "$('#questionDiv').append(\"<input type='hidden' name='sm[surveyId]' value='".$surveyId."'>\");\n";
	$returnStr .= "pageNumSelfTracker = 1;";
	$outputStr = "";
	unset($ia);

	$returnStr .="var qaStack = makeQuestion('".$qa."');\n";
	$returnStr .="pageCounter = 1\n";
	$returnStr .="var pageId = 'p'+pageCounter;\n";
	$returnStr .="for (qi = 0;qi < qaStack.length;qi++)\n";
	$returnStr .="{\n";

	$returnStr .="if (qi == 0){\n";
	$returnStr .="$('#questionDiv').append('<div id=\"'+pageId+'\" class=\"pagedemo _current\"></div>' );\n";
	$returnStr .="}\n";

	$returnStr .="\n";

	$returnStr .="var gshort = new question();\n
	gshort.decodeFromString(qaStack[qi]);\n
	gshort.setId(qi +1);\n
	gshort.makeName();\n
	gshort.makeAnswer();\n
	if (gshort.Type == 'newPage')\n
		{\n
		gshort.style='display:none;';\n
		pageCounter += 1;\n
		var pageId = 'p'+pageCounter;\n
		$('#questionDiv').append('<div id=\"'+pageId+'\" class=\"pagedemo _current\" style=\"display:none\"></div>' );\n
		}\n
	if (gshort.Type == 'plainText')\n
		{\n
		gshort.style='font-weight:bold;';\n
		}\n
	";
	
	$returnStr .="var pageId = 'p'+pageCounter;\n";
	$returnStr .="$('#'+pageId).append(gshort.getHTML());\n";
	$returnStr .="}\n";
	$returnStr .="\n";

	$returnStr .="$('#'+pageId).append(\"<input type='Button' onclick='submitMe()' value='Submit Survey'>\");\n";
	$returnStr .="InputTrackerCounter = " .count($data) .";\n";
	
	$returnStr .="
			$('#paging').paginate({
				count 		: pageCounter,
				start 		: 1,
				display     : 4,
				border					: true,
				border_color			: '#fff',
				text_color  			: '#fff',
				background_color    	: 'black',	
				border_hover_color		: '#ccc',
				text_hover_color  		: '#000',
				background_hover_color	: '#fff', 
				images					: false,
				mouse					: 'press',
				onChange     			: function(page){
											$('._current','#questionDiv').removeClass('_current').hide();
											$('#p'+page).addClass('_current').show();
										  }
				}); ";
	$returnStr .= "}); };";
	
	$returnStr .= "function submitMe() { $('form#questionDiv').submit(); }";
	
	$returnStr .= "</script>";
	return $returnStr;
	}
?>
