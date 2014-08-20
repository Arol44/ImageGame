<?php
$image_file = "images/".$imagename;

if ($image_type == "image/jpeg"){
	$src = imagecreatefromjpeg($image_file);
} else if ($image_type == "image/png"){
	$src = imagecreatefrompng($image_file);
} else if ($image_type == "image/gif"){
	$src = imagecreatefromgif($image_file);
}

list($width, $height, $type, $attr) = getimagesize($image_file);

$split_sizew = round($width / 3);
$split_sizeh = round($height / 3);

$cal_width  = $width % $split_sizew;
$cal_height = $height % $split_sizeh;

if ($cal_width > 0) {
    $new_width = intval($width / $split_sizew) * $split_sizew + 10; 
}else{
    $new_width = $width;
}
	
if ($cal_height > 0) {
    $new_height = intval($height / $split_sizeh) * $split_sizeh + 10; 
}else{
    $new_height = $height;
}

$image_p = imagecreatetruecolor($new_width, $new_height);

if ($image_type == "image/jpeg"){
	$image = imagecreatefromjpeg($image_file);
} else if ($image_type == "image/png"){
	$image = imagecreatefrompng($image_file);
} else if ($image_type == "image/gif"){
	$image = imagecreatefromgif($image_file);
}

imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

if ($image_type == "image/jpeg"){
	imagejpeg($image_p, $image_file, 100);
} else if ($image_type == "image/png"){
	imagepng($image_p, $image_file, 9);
} else if ($image_type == "image/gif"){
	imagegif($image_p, $image_file, 100);
}

$x_comp = intval($new_width / $split_sizew);
$y_comp = intval($new_height / $split_sizeh);

$winning_string = '';
$image_names = '';

if ($image_type == "image/jpeg"){
	$src = imagecreatefromjpeg($image_file);
} else if ($image_type == "image/png"){
	$src = imagecreatefrompng($image_file);
} else if ($image_type == "image/gif"){
	$src = imagecreatefromgif($image_file);
}
$dest = imagecreatetruecolor($split_sizew, $split_sizeh);
 
for ($y = 0; $y < $y_comp; $y++) {
	for ($i = 0; $i < $x_comp; $i++) {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $ran_string = '';
        for ($p = 0; $p < 4; $p++) {
            $ran_string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        imagecopy($dest, $src, 0, 0, $i * $split_sizew, $y * $split_sizeh, $split_sizew, $split_sizeh);

		if ($image_type == "image/jpeg"){
			imagejpeg($dest, "slices/$ran_string.jpg");
		} else if ($image_type == "image/png"){
			imagepng($dest, "slices/$ran_string.png");
		} else if ($image_type == "image/gif"){
			imagegif($dest, "slices/$ran_string.gif");
		}
        $winning_string .= $ran_string;
        $image_names .= $ran_string . ",";
    }
}
$image_names = substr($image_names, 0, -1);
$images = explode(',', $image_names);
shuffle($images);
$images = implode(",",$images);

?>