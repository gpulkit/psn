<?php

$orgs = array(
1=>'ADPi',
2=>'AEPhi',
3=>'AEPi',
4=>'ATO',
5=>'BETA',
6=>'Chi Phi',
7=>'DPhiE',
8=>'Gamma Phi',
9=>'KA',
10=>'Kappa Kappa Gamma',
11=>'Kappa Sigma',
12=>'Phi Delt',
13=>'PIKE',
14=>'SAE',
15=>'SDT',
16=>'Sig Chi',
17=>'Sig Nu',
18=>'Theta',
19=>'Tri Delt',
20=>'Emory Logo',
21=>'EPP Logo New',
22=>'Pink Ribbon',
23=>'To Life Logo',
24=>'CPP Logo',
25=>'YPC Logo',
26=>'SSC Logo',
27=>'SSSC Logo',
28=>'MPP Logo',
'epp'=>'EPP Logo'
);

function getOrgOpts($sel='', $logo=false) {
	global $orgs;
	$opt = '';
	foreach( $orgs as $i=>$name ) {
		$selected = '';
		if( $i == $sel ) { $selected = 'selected="selected"'; }
		if( $logo && $i == 'epp' && $sel == '' ) { $selected='selected="selected"';}
		$opt.= '<option value="'.$i . '" '.$selected.'>' . $name . '</option>';
	}
	return $opt;
}

function getOrg($num) {
	global $orgs;
	return $orgs[$num];
}

function getCrest($num) {
	global $orgs, $appdir;
	if( $num == 'epp' ) {
		return "$appdir/epp_logo.jpg";
	}
	$find = sprintf('%02d', $num);
	$dh = opendir($appdir);
	while( false !== ($file = readdir($dh)) ) {
		if( $file != '.' and $file != '..' ) { 
			if( substr($file, 0, 3) == $find . '_' ) {
				break;
			}
		}
	}
	closedir($dh);
	return "$appdir/$file";
}

function framePic($file, $tag, $dst) {
	global $appdir;
	$src= imagecreatefromjpeg($file);
	
	if( !$src ) return;

//horizontal pictures
	if( imagesy($src) > imagesx($src) ) {
		$newsrc = myrotate($src, 90);
		imagedestroy($src);
		$src = $newsrc;
		imagejpeg($newsrc,"preview4.jpg");

	}
	
	
	
	$x = 600; $y = 400;
//	$x = $srcx + 80 + 10 + 2; $y = $srcy + 20 + 2;
	$srcx = $x - 80 - 10 - 2;
	$srcy = $y - 20 -2;
	$im = imagecreatetruecolor($x, $y);
	$bg = imagecolorallocate ( $im, 255, 255, 255 );
    imagefill ($im, 0, 0, $bg );
    
	$src = myresizey($src, $srcy);
		
	imagecopy($im, $src, 12, 12, 0, 0, imagesx($src), imagesy($src));
	imagedestroy($src);
	$white = imagecolorallocate($im, 255, 255, 255);
	//imagerectangle($im, 11, 11, 600 - 80 - 1, 400 - 11, $white);

	//put tag on
	$rotated = myrotate($tag, 90);
	
	//imagejpeg($rotated, "$appdir/tag.jpg", 95);
	imagecopy($im, $rotated, 600 - 80, 0, 0, 0, imagesx($rotated), imagesy($rotated));
	imagedestroy($rotated);

	imagejpeg($im,$dst, 95);
		
	imagedestroy($im);
	
}

function genTag($pic1, $pic2, $txt, $ret=false) {
	global $appdir;
	$y = 80;
	$x = 400;
	
	//$im = imagecreatetruecolor($x, $y);
	$im = imagecreate($x, $y);

	//$black = imagecolorallocate($im, 0, 0,0);
	$white = imagecolorallocate($im, 255,255,255);
			
	if( $pic1 ) {
		//$tmp = imagecreatefromjpeg(getCrest($pic1));
		
		$tmp = imagecreatefromjpeg($pic1);
	
		if( $tmp ) {
		
			$pic1 = myresize($tmp, 50);
			$picx = imagesx($pic1); $picy = imagesy($pic1);
			imagecopymerge($im, $pic1, 40, ($y/2 ) - ($picy /2), 0, 0, $picx, $picy,100);
			imagedestroy($pic1);
			imagedestroy($tmp);
		}
	}

	
	if( $pic2 ) {
		//$tmp = imagecreatefromjpeg(getCrest($pic2));
		$tmp = imagecreatefromjpeg($pic2);
		if( $tmp ) {
			$pic2 = myresize($tmp, 50);
			$picx = imagesx($pic2); $picy = imagesy($pic2);
			imagecopymerge($im, $pic2, $x - $picx - 40, ($y/2 ) - ($picy /2), 0, 0, $picx, $picy,100);
			imagedestroy($pic2);
			imagedestroy($tmp);
		}
	}

	$font = $appdir. "/times_new_roman_bold.ttf";
/*
	$bbox = imagettfbbox(11, 0, $font, $txt);
	$txth = $bbox[1] - $bbox[7];
	$txtw = $bbox[2] - $bbox[0];
//	print_r($bbox);
//	echo "Width: $txtw  Hieght: $txth";
	$ptxtx = ($x / 2) - ($txtw / 2 );
	$ptxty = ($y / 2) - ($txth / 2 ) + 10;
//	echo "<br>Place X: $ptxtx Place Y: $ptxty";

	ImageTTFText($im, 11, 0, $ptxtx, $ptxty , $white, $font, $txt);
*/

	$ctxt = imagettfJustifytext($txt, $font);
	
	imagecopy($im, $ctxt, ($x/2)-(imagesx($ctxt)/2), ($y/2)-(imagesy($ctxt)/2),0,0,imagesx($ctxt), imagesy($ctxt));
	if( $ret ) return $im;
	//imagejpeg($im,"preview.jpg",90) or die("Error");
	

	imagedestroy($im);
}

function myresize($im, $sizex) {
	$width_orig = imagesx($im);
 	$height_orig = imagesy($im);
  $ratio_orig = $width_orig/$height_orig;
  $width = $sizex;
  $height = $sizex / $ratio_orig;
  $newim = imagecreatetruecolor($width, $height);
 	imagecopyresampled($newim, $im, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	return $newim;
}
function myresizey($im, $sizey) {
	$width_orig = imagesx($im);
 	$height_orig = imagesy($im);
 	
  $ratio_orig = $height_orig/$width_orig;
  $width = $sizey / $ratio_orig;
	$height = $sizey;
  $newim = imagecreatetruecolor($width, $height);
  $bg = imagecolorallocate ( $newim, 255, 255, 255 );
  imagefill ($newim, 0, 0, $bg );

 // 	$newim = imagecreate($width, $height);
	//imagecolorallocate($newim, 255,255,255);

 	imagecopyresampled($newim, $im, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
 //imagecopyresampled($newim, $im, 0, 0, 0, 0, $width, $height, $width, $height);

	return $newim;
}
function myrotate($im, $degrees) {
        if(function_exists("imagerotate"))
            return imagerotate($im, $degrees, 0);
        else
        {
            function imagerotate($src_img, $angle) 
            {
                $src_x = imagesx($src_img);
                $src_y = imagesy($src_img);
                if ($angle == 180)
                {
                    $dest_x = $src_x;
                    $dest_y = $src_y;
                } 
                elseif ($src_x <= $src_y) 
                {
                    $dest_x = $src_y;
                    $dest_y = $src_x;
                } 
                elseif ($src_x >= $src_y)  
                {
                    $dest_x = $src_y;
                    $dest_y = $src_x;
                }
                
                $rotate=imagecreatetruecolor($dest_x,$dest_y);
                $bg = imagecolorallocate ( $rotate, 255, 255, 255 );
			    imagefill ($rotate, 0, 0, $bg );
                imagealphablending($rotate, false);
                
                switch ($angle) 
                {
                    case 270:
                        for ($y = 0; $y < ($src_y); $y++) 
                        {
                            for ($x = 0; $x < ($src_x); $x++) 
                            {
                                $color = imagecolorat($src_img, $x, $y);
                                imagesetpixel($rotate, $dest_x - $y - 1, $x, $color);
                            }
                        }
                        break;
                    case 90:
                        for ($y = 0; $y < ($src_y); $y++) 
                        {
                            for ($x = 0; $x < ($src_x); $x++) 
                            {
                                $color = imagecolorat($src_img, $x, $y);
                                imagesetpixel($rotate, $y, $dest_y - $x - 1, $color);
                            }
                        }
                        break;
                    case 180:
                        for ($y = 0; $y < ($src_y); $y++) 
                        {
                            for ($x = 0; $x < ($src_x); $x++) 
                            {
                                $color = imagecolorat($src_img, $x, $y);
                                imagesetpixel($rotate, $dest_x - $x - 1, $dest_y - $y - 1, $color);
                            }
                        }
                        break;
                    default: $rotate = $src_img;
                };
                return $rotate;
            }
            return imagerotate($im, $degrees);
        }
}
    /**
     * @name                    : makeImageF
     * 
     * Function for create image from text with selected font. Justify text in image (0-Left, 1-Right, 2-Center).
     *
     * @param String $text     : String to convert into the Image.
     * @param String $font     : Font name of the text. Kip font file in same folder.
     * @param int    $Justify  : Justify text in image (0-Left, 1-Right, 2-Center).
     * @param int    $Leading  : Space between lines.
     * @param int    $W        : Width of the Image.
     * @param int    $H        : Hight of the Image.
     * @param int    $X        : x-coordinate of the text into the image.
     * @param int    $Y        : y-coordinate of the text into the image.
     * @param int    $fsize    : Font size of text.
     * @param array  $color    : RGB color array for text color.
     * @param array  $bgcolor  : RGB color array for background.
     * 
     */
 function imagettfJustifytext($text, $font="CENTURY.TTF", $Justify=2, $Leading=3, $W=0, $H=0, $X=0, $Y=0, $fsize=11, $color=array(0x0,0x0,0x0), $bgcolor=array(0xff,0xff,0xff)) {
        
        $angle = 0;
        $_bx = imageTTFBbox($fsize,0,$font,$text);
        $s = explode("[\n]+", $text);  // Array of lines
        $nL = count($s);  // Number of lines
        $W = ($W==0)?abs($_bx[2]-$_bx[0]):$W;    // If Width not initialized by programmer then it will detect and assign perfect width. 
        $H = ($H==0)?abs($_bx[5]-$_bx[3])+($nL>1?($nL*$Leading):0):$H;    // If Height not initialized by programmer then it will detect and assign perfect height. 
        
        $im = @imagecreate($W, $H)
            or die("Cannot Initialize new GD image stream");
        
        $background_color = imagecolorallocate($im, $bgcolor[0], $bgcolor[1], $bgcolor[2]);  // RGB color background.
        $text_color = imagecolorallocate($im, $color[0], $color[1], $color[2]); // RGB color text.
        
        if ($Justify == 0){ //Justify Left
            imagettftext($im, $fsize, $angle, $X, $fsize, $text_color, $font, $text);
        } else {
            // Create alpha-nummeric string with all international characters - both upper- and lowercase
            $alpha_arr = range("a", "z");
            $alpha_str = implode(",", $alpha_arr);

            $alpha = '';
            $alpha = strtoupper($alpha_str).implode(',',range(0, 9));
           

            // Use the string to determine the height of a line
            $_b = imageTTFBbox($fsize,0,$font,$alpha);
            $_H = abs($_b[5]-$_b[3]);
            $__H=0;
            for ($i=0; $i<$nL; $i++) {
                $_b = imageTTFBbox($fsize,0,$font,$s[$i]);
                $_W = abs($_b[2]-$_b[0]);
                //Defining the X coordinate.
                if ($Justify == 1) $_X = $W-$_W;  // Justify Right
                else $_X = abs($W/2)-abs($_W/2);  // Justify Center
                
                //Defining the Y coordinate.
                $__H += $_H;
                imagettftext($im, $fsize, $angle, $_X, $__H, $text_color, $font, $s[$i]);
                $__H += $Leading;
            }
        }
        
        return $im;
}

?>
