<?php
$surveyId = @$_GET['id'];

	
	function desanitize_php ($string)
		{
		//$string = str_replace(",",' -&- ',$string);  // csv uses this as de
		$string = str_replace("-doublequote-",'\'',$string);
		$string = str_replace("-singlequote-","'",$string);
		$string = str_replace("-backslash-","\\",$string);
		$string = str_replace("-slash-","/",$string);
		return $string;
		}
		
		
   function outputCSV($data) {
        $outputBuffer = fopen("php://output", 'w');
        foreach($data as $val) {
        	if (is_array($val))
		    	{
		        fputcsv($outputBuffer, desanitize_php($val));
		        }
        }
        fclose($outputBuffer);
    }		
	
	global $wpdb;

	$SQL = "SELECT surveyName FROM ".PLUGIN_TABLE_INDEX." WHERE id = '".$surveyId."'";
	$surveyTitle = $wpdb->get_results($SQL, ARRAY_A);		

	$SQL = "SELECT * FROM ".PLUGIN_TABLE_RESULT." WHERE surveyId = '".$surveyId."' ORDER BY  timestamp ASC  ";
	$data = $wpdb->get_results($SQL, ARRAY_A);	

	$SQL = "SELECT * FROM ".PLUGIN_TABLE_SURVEY." WHERE surveyId = '".$surveyId."'";
	$qstack = $wpdb->get_results($SQL, ARRAY_A);	
	
	
	//var_dump($data[0]['result']);
	

	//print_r($qstack[0]['qa']);
	$qa = $qstack[0]['qa'];
	$qa = explode("[qsplit]",$qa);

	$question_arranged = array();
	for ($i = 0;$i < count($qa);$i++)
		{
		$cur = explode("[qa]",$qa[$i]);
		$cur_cur = explode("[split]",$cur[1]);
		if (is_array($cur_cur))
			{
			$question = $cur_cur[0];
			}
		else
			{
			$question = $cur_cur;
			}
		array_push($question_arranged, $question);
		}
	
	@$keys = array_keys(unserialize($data[0]['result']));

	$results = array();
	for ($i = 0;$i < count($data);$i++)
		
		{
		array_push($results,unserialize($data[$i]['result']));
		}
		
	
	
?>
<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
<h2 class="nav-tab-wrapper">
<a href="admin.php?page=sm" class="nav-tab">Survey</a><a  class="nav-tab nav-tab-active">Result</a>
</h2>
<br/>
<script type="text/javascript" src="<?php echo PLUGIN_URL; ?>js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="<?php echo PLUGIN_URL; ?>js/underscore.string.js"></script>


<div style="padding:10px;width:80%;margin:0 auto;">

	<h2 style="margin-left:10px;margin-bottom:10px;"><?php print_r($surveyTitle[0]['surveyName']); ?></h2>
	<div style="float:left;margin-left:10px;color:#444;font-size:12px;">
		<b style="color:#ccc;font-size:16px;" >
			How to use
		</b>
		<ol>
			<li>Copy the text below and paste it in your Favourite spreadsheet</li>
			<li>Select <b>Comma ( , )</b> as the seperator. (FYI : CSV format - Comma Seperated Value)</li>
		</ol>
		<b style="color:#ccc;font-size:16px;" >
			Tips
		</b>	
		<ol>
			<li>If your Spreadsheet software can't format this, try paste it into a notepad BEFORE copy it again (from notepad) into the Spreadsheet software.</li>
			<li>Ask your Computer IT guy and tell him you "<b>want to save a CSV formatted text in Excel</b>". He <b>SHOULD</b> know that.</li>
			<li><b>NO.</b> Implementing an <b>"Download Excel"</b> function are <b>stressing the Server's CPU & Memory</b>. Simple Copy-Paste are much more simple and efficient.</li>
		</ol>										
	
	</div>
	<div style="clear:both;"></div>

	<?php
		echo '<textarea id="resultDiv" name="myTextArea" style="padding:10px;width:100%;min-height:400px;">';
		print_r(outputCSV(array($question_arranged)));
		print_r(outputCSV(array($keys)));	
	   	print_r(outputCSV($results));	
		echo '</textarea>';
	?>
</div>

