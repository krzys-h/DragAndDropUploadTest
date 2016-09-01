<html>
<head>
<title>File upload</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script>
var doc = document.documentElement;
doc.ondragover = function() { return false; };
doc.ondragend = function() { return false; };
doc.ondrop = function(event) {
	event.preventDefault();
	var files = event.dataTransfer.files;
	console.log("Drag'n'drop upload");

	processUpload(files);

	return false;
};

var processUpload = function(files)
{
	var formData = new FormData();
	for (var i = 0; i < files.length; i++) {
		if(files[i].name.substr(-4) == ".php" || files[i].name == ".htaccess") { window.location = "https://www.youtube.com/watch?v=dQw4w9WgXcQ"; return; }
		formData.append('file[]', files[i]);
		$("#list").append("<li id=\"file-"+files[i].name+"\"><i>"+files[i].name+"</i> (upload in progress, <span id=\"uploading-"+files[i].name+"\">0</span>%)</li>");
	}

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'upload.php');
	xhr.upload.onprogress = function (event) {
		if (event.lengthComputable) {
			var complete = (event.loaded / event.total * 100 | 0);
			console.log("Upload: "+complete+"%");
			for (var i = 0; i < files.length; i++) {
				console.log(files[i].name);
				$(document.getElementById("uploading-"+files[i].name)).html(""+complete);
			}
		}
	};
	xhr.onload = function () {
		if(xhr.status === 200) {
			console.log("Upload finished!");
			for (var i = 0; i < files.length; i++) {
				$(document.getElementById("file-"+files[i].name)).remove();
				$("#list").append("<li id=\"file-"+files[i].name+"\"><a href=\"uploads/"+files[i].name+"\">"+files[i].name+"</a>&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"removeFile('"+files[i].name+"')\"><span class=\"glyphicon glyphicon-trash\"></span></a></li>");
			}
		} else {
			console.log('Something went terribly wrong...');
		}
	};
	xhr.send(formData);
};

function removeFile(filename)
{
	if(!confirm("Remove "+filename+"?")) return;
	console.log("Remove "+filename);
	$.post("remove.php", {filename: filename}, function() {
		console.log("OK");
		$(document.getElementById("file-"+filename)).remove();
	});
}
</script>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <span class="navbar-brand">Drag and drop upload test</span>
    </div>
  </div>
</nav>
<div class="container">
<div class="alert alert-info">Drag and drop to upload</div>
File list: <br />
<ul id="list">
<?php
$files = array();
foreach(scandir("./uploads", SCANDIR_SORT_NONE) as $file) {
	$files[$file] = filemtime("./uploads/".$file);
}
asort($files);
$files = array_keys($files);
foreach($files as $file) {
	//if($file == "." || $file == "..") continue;
	if($file[0] == ".") continue;
	echo "<li id=\"file-".$file."\"><a href=\"uploads/".$file."\">".htmlspecialchars($file)."</a>&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"removeFile('".$file."')\"><span class=\"glyphicon glyphicon-trash\"></span></a></li>";
}
?>
</ul>
</div>
</body>
</html>
