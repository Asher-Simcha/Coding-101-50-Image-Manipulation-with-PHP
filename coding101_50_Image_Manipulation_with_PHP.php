<?php
// Authors: Patrick Delahanty
// Additional Authors: Asher Simcha
// Date: 01-16-2015
// Description: Coding 101 50: Image Manipulation with PHP
// last Modified: 05-23-2019
// Meta_data_for_Youtube: <iframe width="560" height="315" src="https://www.youtube.com/embed/CEcHBeGns6I" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
// Source: https://www.youtube.com/watch?v=CEcHBeGns6I&list=PLTmR6HsT7006WXLVezBEm6Me_EdXTDES8&index=49&t=0s
// 
// Additional Notes: The original code from Coding 101 is the same
// Additional Notes: Asher Simcha added so that it will work with both Windows and Linux Servers.
// also changed the GET to include errors
// if GET is NOT set display a form

// if the GET is NOT set just show a quick form page to start out with then you get no errors
if (!isset($_GET['name'])) {
	echo "<form action='' method='get'>";
	echo "<p>Enter Your name here:";
	echo "<input type='text' name='name'></input>";
	echo "<br>";
	echo "<button name='Submit' value='submit' type='submit'>Submit</button>";
	echo "</p>";
	echo "</form>";
exit;
}



function windows_os() {
    $DS = '\\'; // Directory seperator is \
    //echo "You are running a Windows OS<br>";
    $font = 'C:\Windows\Fonts\arial.ttf'; // this is a Windows location for me
    return $font;
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    //echo "This is a server using Windows!<br>";
    $check_os_1 = 0;
} else {
    //echo "This is a server using a POSTIX like OS!<br>";
    $check_os_1 = 1;
}

// double check to make sure it is not Darwin (aka Mac) last function check to see if win was in the name
// and Darwin has the name win... some mac users said the last function did not work for them. So the need for double checking.
if (DIRECTORY_SEPARATOR == '\\') {
    //echo "This is a windows machine<br>";
    $check_os_2 = 0;
}
if (DIRECTORY_SEPARATOR == '/') {
   //echo "This is a Linux or some other POSTIX OS<br>"; // in production remove this line
    $check_os_2 = 1;
}
$check_os_final = $check_os_1 + $check_os_2;

// check to see what OS you are running. Are you running Windows $check_os_final will equal 0
// If you are running Linux, FreeBSD, etc, etc $check_os_final will equal 2
// If you are running Mac $check_os_final will equal 1 or 2 depending on the version
if ($check_os_final >= 1) {
    //echo "You are running a POSTIX OS<br>"; // in production remove this line
    $DS = '/'; // Directory seperator is /
    //$font = '/usr/share/kodi/media/Fonts/arial.ttf';
    // with Directory Seperator the code would look like
    $font = "$DS"."usr$DS"."share$DS"."kodi$DS"."media$DS"."Fonts$DS"."arial.ttf";
} else {
    //echo "You are running on a Windows Machine<br>"; // in production remove this line
    $DS = '\\'; // Directory seperator is \
    $font=windows_os();
    //$font = 'C:\Windows\Fonts\arial.ttf'; // this is a Windows location for me
    // with Directory Seperator the code would look like
    $font = "C:$DS"."Windows$DS"."Fonts$DS"."arial.ttf";
} 
if (!file_exists($font)) {
echo "The font file does not exist";
exit;
}

// Path to our font file 
// in linux type
// locate arial.ttf
//$font = '/usr/share/kodi/media/Fonts/arial.ttf'; // this is a linux location for me 
// 
// in Windows type:
// c:
// cd \
// dir /s arial.ttf
// these are the locations that your are going to want to replace $font with
// 
//$font = 'C:\Windows\Fonts\arial.ttf'; // this is a Windows location for me
// $font = './arial.ttf';
///$font = '/usr/share/kodi/media/Fonts/arial.ttf';

$fontsize = 40;

$text = $_GET["name"];

// get dimensions of the text
$fit = 0;
while ($fit < 1) {
	$dims = imagettfbbox($fontsize, 0, $font, $text);
	// dims[0] = lower left x, dims[1] = lower left Y
	// dims[2] = lower right x, dims[3] = lowwer right Y
	// dims[4] = upper right x, dims[5] = upper right Y
	// dims[6] = upper left x, dims[7] = upper left Y
	
	// 400 pixels is out allocated max width for text
	if ($dims[4] > 400) {
		$fontsize--;
	} else {
		$fit = 1;
	}
}

// Yank base image off the Interwebz...
$image = imageCreateFromJPEG("http://twit.cachefly.net/coverart/code/code600.jpg");

// Pick color for the background (RGB 0, 0, 0 is black)
$bgcolor = imagecolorallocate($image, 0, 0, 0);

// Pick color for the name text (RGB 255, 255, 255 is white)
$textcolor = imagecolorallocate($image, 255, 255, 255);

// Fill in the background with the background color
// 150 is upper-left x, 525 is upper-left Y
// 600 is lower-left x, 600 is lower-left Y
imagefilledrectangle($image, 150, 525, 600, 600, $bgcolor);

// x,y coords for out text
$x = 160;
$y = 573;

// Put it all together
imagettftext($image, $fontsize, 0, $x, $y, $textcolor, $font, $text);

// tell the browser that the content is an image
header('Content-type: image/jpeg');

// output image to the browser
imagejpeg($image);

// delete the image resource
imagedestroy($image);

?>
