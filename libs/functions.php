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

function resize_crop_image($w, $h, $src, $dst, $type = IMAGETYPE_JPEG, $q = 80, $logo = false) {
    
    $img = new SimpleImage($src);

    if($logo) {
        $img -> maxareafill($w, $h, 255, 255, 255);
    }

    if($w > $h) {
        if($logo) {
            $img -> resizeToHeight($h);
        } else {
            $img -> resizeToWidth($w);
        }
    } else {
        if($logo) {
            $img -> resizeToWidth($w);
        } else {
            $img -> resizeToHeight($h);
        }
    }

    $img -> cutFromCenter($w, $h);
    $img -> save($dst, $type, $q);

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

// ZIP FILES

function ZIP($source, $destination)
{
    if (extension_loaded('zip') === true)
    {
        if (file_exists($source) === true)
        {
            $zip = new ZipArchive();

            if ($zip->open($destination, ZIPARCHIVE::CREATE) === true)
            {
                $source = realpath($source);

                if (is_dir($source) === true)
                {
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

                    foreach ($files as $file)
                    {
                        $file = realpath($file);

                        if (is_dir($file) === true)
                        {
                            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                        }

                        else if (is_file($file) === true)
                        {
                            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                        }
                    }
                }

                else if (is_file($source) === true)
                {
                    $zip->addFromString(basename($source), file_get_contents($source));
                }
            }

            return $zip->close();
        }
    }

    return false;
}

// SLUG

function slugify($text) {
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, '-');
  $text = preg_replace('~-+~', '-', $text);
  $text = strtolower($text);
  if (empty($text)) {
    return 'n-a';
  }
  return $text;
}

?>