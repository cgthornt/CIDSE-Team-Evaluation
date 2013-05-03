<?php 
echo $this->pageTitle('Import Students'); 

if (isset($invalide_user_name)) {
	echo '<h3>There are invalide user name in the file please check</h3>';
	echo '<ul>';
	foreach ($invalide_user_name as $user_name)
	{
		echo '<li>'.$user_name.'</li>'; 
	}
	echo '</ul>';
}elseif (isset($invalide_moevefile_error)){
	echo '<h3>Failed move the uploaded file</h3>';
}elseif (isset($invalide_upload_error) ){
	echo '<h3>Failed upload excel file</h3>';
}

?>

