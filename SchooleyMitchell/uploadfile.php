<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Schooley Mitchell Test - Arielle LePage</title>
<link href="styles/styles.css" rel="stylesheet" type="text/css" media="all" />
<script src="jquery-1.7.2.js"></script>
<script src="js/ui/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/ui/jquery.ui.widget.js" type="text/javascript"></script>
<script src="js/ui/jquery.ui.mouse.js" type="text/javascript"></script>
<script src="js/ui/jquery.ui.sortable.js" type="text/javascript"></script>

</head>

<body>
<?php
if ($_FILES["browse"]["size"]/1024 < 20000){
if ($_FILES["browse"]["error"] > 0){
    echo "Return Code: " . $_FILES["browse"]["error"] . "<br>";
}
	}else{
		echo "Sorry, this image is too large. <br />";
		echo "Size: ".($_FILES["browse"]["size"] / 1024)." kB<br>";
	}

if( ! is_uploaded_file($_FILES["browse"]["tmp_name"]) || $_FILES["browse"]["error"] !== UPLOAD_ERR_OK)
{
    exit('File not uploaded. Possibly too large.');
}

switch(strtolower($_FILES["browse"]["type"]))
{
    case 'image/jpeg':
        $image = imagecreatefromjpeg($_FILES["browse"]["tmp_name"]);
        break;
    case 'image/png':
        $image = imagecreatefrompng($_FILES["browse"]["tmp_name"]);
        break;
    case 'image/gif':
        $image = imagecreatefromgif($_FILES["browse"]["tmp_name"]);
        break;
    default:
        exit('Unsupported type: '.$_FILES["browse"]["type"]);
}

$imagename = $_FILES["browse"]["name"];

$max_width = 640;
$max_height = 640;

$old_width = imagesx($image);
$old_height = imagesy($image);

$scale = min($max_width/$old_width, $max_height/$old_height);

$new_width  = ceil($scale*$old_width);
$new_height = ceil($scale*$old_height);

$new = imagecreatetruecolor($new_width, $new_height);

imagecopyresampled($new, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

$image_type = $_FILES["browse"]["type"];

if ($image_type == "image/jpeg"){
	imagejpeg($new, "images/$imagename");
} else if ($image_type == "image/png"){
	imagepng($new, "images/$imagename");
} else if ($image_type == "image/gif"){
	imagegif($new, "images/$imagename");
} 

include_once('split.php');

$images = explode(',', $image_names);
shuffle($images);

echo "<p class=\"msg_text\">Organize the boxes to form the image</p>";

echo "<ul id='sortable' style='width:".$new_width."px; height:".$new_height."px;'>";
            
foreach ($images as $key => $image_name) {
	if ($image_type == "image/jpeg"){
		echo "<li id='recordArr_$image_name' style='border:none; width:".$split_sizew."px; height:".$split_sizeh."px;'>
		<img src='slices/$image_name.jpg' /></li>";
	}else if ($image_type == "image/png"){
		echo "<li id='recordArr_$image_name' style='border:none; width:".$split_sizew."px; height:".$split_sizeh."px;'>
		<img src='slices/$image_name.png' /></li>";
	}else if ($image_type == "image/gif"){
		echo "<li id='recordArr_$image_name' style='border:none; width:".$split_sizew."px; height:".$split_sizeh."px;'>
		<img src='slices/$image_name.gif' /></li>";
	}
}
            
echo "</ul>";
?>
<script type="text/javascript">
$(function Move(){
	$("#sortable").sortable({
		opacity: 0.8,
		cursor: 'move',
		update: function(){
			
			var winningString = "<?php echo $image_names; ?>";
			var currentString = '';
			
			$('#sortable li').each(function(){
				var imageId = $(this).attr("id");
				currentString += imageId.replace("recordArr_", "")+",";
			});
			
			currentString = currentString.substr(0,(currentString.length) -1);
			
			if(currentString == winningString){
				alert("You Won!!!");
			}
			console.log(currentString);
		}
	});
});
</script>

</body>
</html>
