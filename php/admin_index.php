<style>
.actions{
display:none;
}

</style>
<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
<h2 class="nav-tab-wrapper">
<a href="admin.php?page=sm" class="nav-tab nav-tab-active" >Survey </a> <a href="admin.php?page=sm&action=new" class=" tablenav top add-new-h2">Add New Survey</a>
</h2>


<?php
$testListTable = new sortableTable2();

global $wpdb;
$table_name = $wpdb->prefix . PLUGIN_SLUG; 
$SQL = "SELECT id,surveyName FROM $table_name";
$data = $wpdb->get_results($SQL, ARRAY_A);	



for ($i = 0;$i < count($data);$i++)
	{
	$name = $data[$i]['surveyName'];
	$id = $data[$i]['id'];
	$data[$i]['title'] = $name;
	$data[$i]['ID'] = $id;
	unset($name,$id);
	}


$testListTable->setData($data);



$columns = array(
        'title' => 'Name'
    );
    
$testListTable->setColumns($columns);

//print_r($testListTable->get_columns($columns));

$testListTable->prepare_items();
	?>
    <form id="movies-filter" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <!-- Now we can render the completed list table -->
        <?php $testListTable->display() ?>
    </form>
