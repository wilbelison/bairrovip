<?php

// DELETE FILES

function delete_files($target) {

    if(is_dir($target)){

        $files = glob( $target . '*', GLOB_MARK );

        foreach( $files as $file ){
            delete_files( $file );      
        }

        rmdir( $target );

    } elseif(is_file($target)) {

        unlink( $target );  

    }
}

// RESIZE CROP IMAGE

function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 85){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];
 
    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;
 
        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;
 
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;
 
        default:
            return false;
            break;
    }
     
    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);
     
    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;

    if($width_new > $width){
        $h_point = (($height - $height_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
     
    $image($dst_img, $dst_dir, $quality);
 
    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}

// TEMPLATE PARSER

function template($string, $hash) {
    foreach ( $hash as $ind=>$val ) {
        $string = str_replace('{{'.$ind.'}}', $val, $string);
    }   
    $string = preg_replace('/\{\{(.*?)\}\}/is','',$string);
    return $string;
}

function parse_template($template, $hash, $newfile) {
    $string = file_get_contents($template);
    if ($string) {
        $string = template($string, $hash);
    }
    if (!file_exists($newfile)) {
        $handle = fopen($newfile, 'w+');
        fwrite($handle, $string);
        fclose($handle);
    }
}

// FILESIZE

function filesize_formatted($size) {
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

?>