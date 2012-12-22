<?php
$surveyId = @$_GET['id'];
$add = @$_GET['add'];

	global $wpdb;
	$SQL = "SELECT surveyName FROM ".PLUGIN_TABLE_INDEX." WHERE id = '".$surveyId."'";
	$surveyTitle = $wpdb->get_results($SQL, ARRAY_A);	


?>
<div class="wrap">
<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
<h2 class="nav-tab-wrapper">
<a href="admin.php?page=sm" class="nav-tab">Survey</a><a  class="nav-tab nav-tab-active">Delete</a>
</h2>
<br/>
<?php

if ($add == NULL)
	{
?>

<div style="padding:10px;width:80%;margin:0 auto;">

	<?php
	if ($comment != "")
		{
		echo "<div style='background:#FFFBCC;padding:10px;margin:10px;border:1px solid #000000;'>".$comment."</div>";
		}
	?>

	<h2 style="margin-left:10px;margin-bottom:10px;"><?php print_r($surveyTitle[0]['surveyName']); ?></h2>

	
	<div style="float:left;margin-left:10px;color:#444;font-size:12px;">
		<b style="color:#ccc;font-size:16px;" >
			How to use
		</b>
		<ol>
			<li>Read carefully the options below.</li>
			<li>AFTER you COMPLETELY understands, click the related link.</li>
		</ol>
		<b style="color:#ccc;font-size:16px;" >
			Tips
		</b>	
		<ol>
			<li><b>NO.</b> There were no <b>Delete Confirmation</b>. We wants you to practice Safe computing.</li>
			<li><b>NO.</b> There were no <b>Un delete</b>. Your accident is <b>YOUR</b> accident by choice.</li>
		</ol>										
	
	</div>
	<div style="clear:both;"></div>
<br/>







<div style="clear:both;"></div>




<div style="width:70%;margin:0px auto;">
	<a style="text-decoration:none" href="admin.php?page=sm&action=delete&id=<?php echo $surveyId;?>&add=2">
	<div style="float:left;width:200px;height:120px;padding:10px;border:1px solid #CCC;	background: #006600;color:#ffffff;text-align:center;	-webkit-border-radius: 5px;	-moz-border-radius: 5px;	border-radius: 5px;		">
	Yes, i want to delete all the <br/><br/><b>Result</b><br/><br/> from the survey <br/>
	<b>" <?php echo $surveyTitle[0]['surveyName']; ?> "</b>
	</div>
	</a>

	<a style="text-decoration:none" href="admin.php?page=sm&action=delete&id=<?php echo $surveyId;?>&add=1">
	<div style="float:right;width:200px;height:120px;padding:10px;border:1px solid #CCC;	background: #660000;color:#ffffff;text-align:center;	-webkit-border-radius: 5px;	-moz-border-radius: 5px;	border-radius: 5px;		">
	Yes, i want to delete the <br/><br/><b>Survey,
	Question(s) and the Result(s)</b><br/><br/> from the survey <br/>
	<b>" <?php echo $surveyTitle[0]['surveyName']; ?> "</b>
	</div>
	</a>
</div>

</div>









<?php
	}
if ($add == "1") //delete all
	{
	global $wpdb;

	$SQL = "DELETE FROM ".PLUGIN_TABLE_INDEX." WHERE id = $surveyId ";		
	$wpdb->query($SQL);		

	$SQL = "DELETE FROM ".PLUGIN_TABLE_SURVEY." WHERE surveyId = $surveyId ";		
	$wpdb->query($SQL);		

	$SQL = "DELETE FROM ".PLUGIN_TABLE_RESULT." WHERE surveyId = $surveyId ";		
	$wpdb->query($SQL);				
	echo "<h2>Delete Completed</h2>";
	echo "<p><a href='admin.php?page=sm'>return</a></p>";
	exit;			
	}
if ($add == "2") // only delete result
	{
	global $wpdb;
	$SQL = "DELETE FROM ".PLUGIN_TABLE_RESULT." WHERE surveyId = $surveyId ";		
	$wpdb->query($SQL);		
	
	echo "<h2>Delete Completed</h2>";
	echo "<p><a href='admin.php?page=sm'>return</a></p>";
	exit;			
	}	
	
?></div>
