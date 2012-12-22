<?php
$surveyId = @$_GET['id'];
$add = @$_GET['add'];

if ($add != "1")
	{
?>

<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
<h2 class="nav-tab-wrapper">
<a href="admin.php?page=sm" class="nav-tab" >Survey </a><a  class="nav-tab nav-tab-active">Add new Survey</a>
</h2>

<form name="saveDatabase" action="admin.php?page=sm&action=new&add=1" method="post"> 
<br/>

Survey Name : <input style="min-width:300px;" name='questionaireName' />
<br/>
<br/>
<input type="Submit" value="Add new Questionaire">
</form>
</div>
<?php
	}
if ($add == "1")
	{
	if ($_POST['questionaireName'] != null)
		{
		$qName = htmlentities($_POST['questionaireName']);

		global $wpdb;
		$SQL = "INSERT INTO ".PLUGIN_TABLE_INDEX." (id, surveyName) VALUES (NULL, '$qName');";		
		$wpdb->query($SQL);	

		?>
		<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
		<h2 class="nav-tab-wrapper">
		<a href="admin.php?page=sm" class="nav-tab" >Survey </a><a  class="nav-tab nav-tab-active">Add new Survey</a>
		</h2>		
		<h2>Success</h2>
		<a href="admin.php?page=sm">Return</a>
		<?php

		}	
	}
