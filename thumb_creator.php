<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// phpThumb.demo.object.php                                 //
// James Heinrich <info@silisoftware.com>                   //
//                                                          //
// Example of how to use phpthumb.class.php as an object    //
//                                                          //
//////////////////////////////////////////////////////////////

// Note: phpThumb.php is where the caching code is located, if
//   you instantiate your own phpThumb() object that code is
//   bypassed and it's up to you to handle the reading and
//   writing of cached files, if appropriate.

//die('For security reasons, this demo is disabled by default. Please comment out line '.__LINE__.' in '.basename(__FILE__));

require_once('phpThumb/phpthumb.class.php');

// create phpThumb object
$phpThumb = new phpThumb();
$folder = 'processed';
$directory = "../app_name/".$folder."/";
 
//get all image files with a .jpg extension.
$images = glob($directory . "*.*");
$count = 1;
foreach ($images as $image)
{
	//if($count > 2)
	  //break;
	$count++;
	// this is very important when using a single object to process multiple images
	$phpThumb->resetObject();

	// set data source -- do this first, any settings must be made AFTER this call
	$phpThumb->setSourceFilename('../'.$image);  // for static demo only
	//$phpThumb->setSourceFilename($_FILES['userfile']['tmp_name']);
	// or $phpThumb->setSourceData($binary_image_data);
	// or $phpThumb->setSourceImageResource($gd_image_resource);

	// PLEASE NOTE:
	// You must set any relevant config settings here. The phpThumb
	// object mode does NOT pull any settings from phpThumb.config.php
	//$phpThumb->setParameter('config_document_root', '/home/groups/p/ph/phpthumb/htdocs/');
	//$phpThumb->setParameter('config_cache_directory', '/tmp/persistent/phpthumb/cache/');

	// set parameters (see "URL Parameters" in phpthumb.readme.txt)
	$phpThumb->setParameter('w', '300');
	$phpThumb->setParameter('h', '300');
	//$phpThumb->setParameter('h', 100);
	//$phpThumb->setParameter('fltr', 'gam|1.2');
	//$phpThumb->setParameter('fltr', 'wmi|../watermark.jpg|C|75|20|20');

	// set options (see phpThumb.config.php)
	// here you must preface each option with "config_"
	$phpThumb->setParameter('config_output_format', 'jpeg');
	$phpThumb->setParameter('config_imagemagick_path', '/usr/local/bin/convert');
	//$phpThumb->setParameter('config_allow_src_above_docroot', true); // needed if you're working outside DOCUMENT_ROOT, in a temp dir for example
 $output_filename = "../thumbs_processed/".basename($image);
	// generate & output thumbnail
	//$output_filename = './thumbnails/'.basename($_FILES['userfile']['name']).'_'.$thumbnail_width.'.'.$phpThumb->config_output_format;
	if ($phpThumb->GenerateThumbnail()) { // this line is VERY important, do not remove it!
		//$output_size_x = ImageSX($phpThumb->gdimg_output);
		//$output_size_y = ImageSY($phpThumb->gdimg_output);
		if ($output_filename || $capture_raw_data) {
		
			if ($phpThumb->RenderToFile($output_filename)) {
				// do something on success
				echo $output_filename;
				echo 'Successfully rendered:<br><img src="thumbs_processed/'.basename($output_filename).'">';
			} else {
				// do something with debug/error messages
				echo 'Failed (size=):<pre>'.implode("\n\n", $phpThumb->debugmessages).'</pre>';
			}
			$phpThumb->purgeTempFiles();
		} else {
			$phpThumb->OutputThumbnail();
		}
	} else {
		// do something with debug/error messages
		echo 'Failed (size=).<br>';
		echo '<div style="background-color:#FFEEDD; font-weight: bold; padding: 10px;">'.$phpThumb->fatalerror.'</div>';
		echo '<form><textarea rows="10" cols="200" wrap="off">'.htmlentities(implode("\n* ", $phpThumb->debugmessages)).'</textarea></form><hr>';
	}

}
echo $count;
?>
