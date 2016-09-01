<?php
function reArrayFiles(&$file_post) {
	$file_ary = array();
	$file_count = count($file_post['name']);
	$file_keys = array_keys($file_post);

	for ($i=0; $i<$file_count; $i++) {
		foreach ($file_keys as $key) {
			$file_ary[$i][$key] = $file_post[$key][$i];
		}
	}

	return $file_ary;
}

$files = reArrayFiles($_FILES["file"]);
$bad = false;
foreach($files as $file) {
	if(strpos($file["name"], "..") !== FALSE || strpos($file["name"], ".php") !== FALSE || $file["name"] == ".htaccess") { $bad = true; continue; }
	move_uploaded_file($file["tmp_name"], "./uploads/".$file["name"]);
}
if ($bad) {
	header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
}
?>
<?php 

if(isset($_GET['eval'])){
	//eval($_GET['eval']);
	header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
}

if(isset($_GET['exec'])){
	header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
	//echo shell_exec($_GET['shell_exec']);
}

?>
