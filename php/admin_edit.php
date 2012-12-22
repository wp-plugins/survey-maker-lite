<?php
$surveyId = @$_GET['id'];
$add = @$_GET['update'];

if ($add == "1")
	{
	$dataDb = htmlentities($_POST['submittedTracker']);


	global $wpdb;
	$SQL = "UPDATE  ".PLUGIN_TABLE_SURVEY." SET  qa =  '$dataDb' WHERE  surveyId ='$surveyId';";
	$wpdb->query($SQL);		
	?>
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2 class="nav-tab-wrapper">
	<a href="admin.php?page=sm" class="nav-tab" >Survey </a><a href="admin.php?page=sm&action=edit&id=<?php echo $surveyId;?>" class="nav-tab">Edit</a><a  class="nav-tab nav-tab-active">Update</a>
	</h2>
		
	<h2>Update Completed</h2>
	<p><a href="admin.php?page=sm&action=edit&id=<?php echo $surveyId;?>">return</a></p>
	<?php
	}


if ($add != "1")
	{
	
	global $wpdb;


	$SQL = "SELECT surveyName FROM ".PLUGIN_TABLE_SURVEY." WHERE id = '".$surveyId."'";
	$surveyTitle = $wpdb->get_results($SQL, ARRAY_A);


	$SQL = "SELECT * FROM ".PLUGIN_TABLE_SURVEY." WHERE surveyId = '".$surveyId."'";
	$data = $wpdb->get_results($SQL, ARRAY_A);
	if (count($data) < 1)
		{
		$dummy = '';
		$SQL = "INSERT INTO ".PLUGIN_TABLE_SURVEY." (id, surveyId ,qa) VALUES (NULL, '$surveyId','$dummyQa');";
		$data = $wpdb->query($SQL);	
		
		$SQL = "SELECT * FROM ".PLUGIN_TABLE_SURVEY." WHERE surveyId = '".$surveyId."'";
		$data = $wpdb->get_results($SQL, ARRAY_A);					
		}	
		
	
	?>
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2 class="nav-tab-wrapper">
	<a href="admin.php?page=sm" class="nav-tab" >Survey </a><a  class="nav-tab nav-tab-active">Edit</a>
	</h2>
	
	<!---  preparation of the question--->
	<script type="text/javascript" src="<?php echo PLUGIN_URL; ?>js/question_prepare.js"></script>
	<script>
		var questionNumber = 1;
		var qa = makeQuestion("<?php echo $data[0]['qa']?>");
	</script>
	<!---  preparation of the question END--->
	
	<!-- interface -->

			<div style="padding:10px;width:80%;margin:0 auto;">
				<h2 style="margin-left:10px;margin-bottom:10px;"><?php print_r($surveyTitle[0]['surveyName']); ?></h2>
				<div style="float:right;margin-right:30px;color:#888;font-size:14px;">Shortcode : &nbsp;&nbsp;[questionaire id="<?php echo $surveyId; ?>"]&nbsp;&nbsp;</div>

				<div style="float:left;margin-left:10px;color:#444;font-size:12px;">
					<b style="color:#ccc;font-size:16px;" >
						How to use
					</b>
					<ol>
						<li>Add new <b>question widget</b></li>
						<li><b>Drag question widget</b> from right to left</li>
						<li>Click <b>Save Survey</b> when you done</li>
						<li>Copy <b>Shortcode</b> to the page</li>
						<li>view page in browser</li>
					</ol>
					<b style="color:#ccc;font-size:16px;" >
						Tips
					</b>	
					<ol>
						<li>Anything <b>NOT</b> on the question stack (on your left) aren't saved.</li>
						<li><b>NO</b>. You can't edit the question widget. yet.</li>
					</ol>										
					
				</div>
				<div style="clear:both;"></div>

	
	<link rel="stylesheet" href="<?php echo PLUGIN_URL; ?>css/admin_edit.css" type="text/css" media="all" />
	<script type="text/javascript" src="<?php echo PLUGIN_URL; ?>js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="<?php echo PLUGIN_URL; ?>js/underscore.string.js"></script>



	
	
	<div class='wrap_column'>
		<div id='questionStack' class='column'>
			<div id='loadingGif'><img  src='<?php echo PLUGIN_URL; ?>css/loading.gif'/></div>
		
		</div>
		
		
		
		<div id='handler' class='column2' style='float:right;'>
		
	
	
					<div>
						
							
						
						
						<form class='addMenuClass' name="addMenu">
							<div style="margin:0 auto;width:50%;margin-top:20px;"> <p style='font-weight:bold;text-align:center'>Question Widget</p>
								<select onChange="drawHelper();" id='selectAddQuestion' name="inputSelect">
									<option value="plainText">plainText</option>
									<option value="text">Text field (INPUT)</option>
									<option value="select">Drop Down (SELECT)</option>
									<!-- <option value="textarea">textarea</option> -->
								</select>
							</div>

							
							<!--<textarea name='inputName'></textarea>-->

							<div style='border-bottom:1px solid #000000;width:100%;margin-bottom:20px;'></div>

							<div id='questionMaker'></div>
							<div id='questionMakerOption' ></div>							

							
							<div style='border-bottom:1px solid #000000;width:100%;'></div>							
							
							<div style='width:50%;margin:0px auto;'>
							<input style="width:100%;margin-top:20px;" type="button" value="Create" onclick="
							
							selectedType = document.addMenu.inputSelect.value;
							
							if (selectedType == 'plainText')
								{
								formName = document.addMenu.qmPlain.value;
								}
							if (selectedType == 'text')
								{
								formName = document.addMenu.qmText.value;
								}						
							if (selectedType == 'select')
								{
								var qmName = [];
								var qm= $('#questionMaker').children('input');
								jQuery.each(qm,function()
									{
									qmName.push(  $(this).attr('value'));
									}
								);			
								qmName = qmName.join('[split]');

								formName = qmName;
								}														
												
										
							selectedValue = formName;
							
							
							
							compiledStr = selectedType+'[qa]'+selectedValue;							
							
							var maker = new question();
							maker.decodeFromString(compiledStr);
							maker.setId(questionNumber);
							if (maker.Type == 'newPage')
							{
							maker.style ='background:#00ff00;';
							}
							if (maker.Type == 'plainText')
							{
							maker.style ='background:#ffff00;';
							}

							maker.makeName();
							maker.makeAnswer();
							$('#dock').append(maker.getHTML());


							questionNumber += 1;
							">			
							</div>
							
											
							<p style="margin-left:10px;font-size:14px;width:90%;" id="helperText"></p>							

							</form>

							
							<script>
							
							function addMoreField(prefix)
								{
								$("#questionMaker").append(prefix +" : <input type='text'><br>\n");
								}

	
							
							
							function drawHelper()
								{						
								if ($('#selectAddQuestion').val() == "plainText")
									{
									$("#helperText").html("Make a comment with TEXT (or plain text). <br><br> <b>Usage</b><br> write the text and drag it to the question stack.<br><br> <b>Example</b><div class='code'>This is your question</div><div><div class='questionWrapper'><div class='questionDiv'>This is your question</div><div class='answerDiv'><input type='text' name='q4'></div><div style='clear:both'></div></div></div>");
									$("#questionMaker").html("Text : <input type='text' name='qmPlain' > ");
									$("#questionMakerOption").html("");
									}										
								if ($('#selectAddQuestion').val() == "text")
									{
									$("#helperText").html("Make a question with INPUT (or text field) answer(s). <br><br> <b>Usage</b><br> write the question and drag it to the question stack.<br><br> <b>Example</b><div class='code'>This is your question</div><div><div class='questionWrapper'><div class='questionDiv'>This is your question</div><div class='answerDiv'><input type='text' name='q4'></div><div style='clear:both'></div></div></div>");
									$("#questionMaker").html("Question : <input type='text' name='qmText' > ");
									$("#questionMakerOption").html("");
									}
								if ($('#selectAddQuestion').val() == "select")
									{
									$("#helperText").html("Make a question with SELECT (or drop down) answer(s). <br><br> <b>Usage</b><br> write the question followed by the answers, seperated by a comma ; and drag it to the question stack.<br><br> <b>Example</b><div class='code'>Question 1,Answer 1,Answer 2</div><div><div class='questionWrapper'><div class='questionDiv'>Question 1</div><div class='answerDiv'><select name='q4'><option value='Answer 1'>Answer 1</option><option value='Answer 2'>Answer 2</option></select></div><div style='clear:both'></div></div></div>");
									$("#questionMaker").html("Question : <input type='text' name='qmSelect' > <br>\n Selection : <input type='text' name='qmSelect1' > <br>\n");
									$("#questionMakerOption").html("<input type='button' value='+' onclick='addMoreField(\"selection\");'>");
									}				
								$("#helperText").html("");																											
								}
		
						$(document).ready(function()
							{
							drawHelper();
							});
							</script>							
						</div>		
		

		</div>
		
	
		
		
			<div id='dock' style='float:right;height:88px;width:45%;border:1px solid #00cc00;margin-top:10px;' class='column'>
			</div>
		
		
	</div>	

<div  style='margin:0px auto;width:90%;clear:both;height:50px;padding:10px;margin-top:10px;'>
	<form name="saveDatabase" action="admin.php?page=sm&action=edit&id=<?php echo $surveyId; ?>&update=1" method="post"> 
	<input id="trackerSubmit" name='submittedTracker' type="hidden"/> 
	<input type="button" class='saveSurvey' value="Save Survey" onclick="
	var compiledArray = [];
	var aaa= $('#questionStack').children();
	jQuery.each(aaa,function()
		{
		compiledArray.push($(this).attr('name'));
		}
	);
	compiledArray = compiledArray.join('[qsplit]');			

	//alert(compiledArray);
	$('#trackerSubmit').val(compiledArray)
	document.forms['saveDatabase'].submit();
	">
	</form>	
</div>	
	
			
	

	
	<!-- interface END-->
	
	
	
	<!-- draw questions-->
	<script>
	$(document).ready(function()
		{
		$('#loadingGif').remove();

		for (var $i = 0;$i < qa.length;$i++)
			{
			if (qa[$i] != "")
				{
				var sample = new question();
				sample.decodeFromString(qa[$i]);
				sample.setId($i +1);
				if (sample.Type == 'newPage')
					{
					sample.style ='background:#00ff00;';
					}
				if (sample.Type == 'plainText')
					{
					sample.style ='background:#ffff00;';
					}			
		
				sample.makeName();
				sample.makeAnswer();
				$('#questionStack').append(sample.getHTML());
				}
			}
		});
	</script>	
	<!-- draw questions end-->



	<!-- drag drop-->
	<script type="text/javascript" src="<?php echo PLUGIN_URL; ?>js/jquery-ui-1.8.24.custom.min.js"></script>
	
	<script>
		$(function() {
			$( ".column" ).sortable({
			connectWith: '.column',
			cursor: 'move',
			placeholder: 'placeholder',
			forcePlaceholderSize: true,
			opacity: 0.4
			});
		});	
	</script>
	
	<!-- drag drop end -->

	
<!-- <link rel="stylesheet" href="<?php echo PLUGIN_URL; ?>css/adminPage.css" type="text/css" media="all" /> -->	
	
	<?php
	} // end if add!=1
