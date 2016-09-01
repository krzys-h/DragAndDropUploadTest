<?php
if(strpos($_POST["filename"], "..") !== FALSE || !file_exists("uploads/".$_POST["filename"]))
{
	header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
	die();
}
unlink("uploads/".$_POST["filename"]);
?>
