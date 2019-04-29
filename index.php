<!DOCTYPE html>
<html>
  <head>
    <title>Bairro VIP Generator</title>
    <meta charset="UTF-8" />
  </head>
  <body>

    <h1>Bairro VIP Generator</h1>

    <style type="text/css">
      
      html, body {

        font-family: verdana;
        font-size: 16px;
        
      }

    </style>

<?php

delete_files('temp/');

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

if(isset($_POST['submit'])) {

  include 'libs/ImageResize.php';

  // FOTOS RESIZE

  $countfiles = count($_FILES['fotos']['name']);

  for($i=0;$i<$countfiles;$i++) {

     $filename = $_FILES['fotos']['name'][$i];
     move_uploaded_file($_FILES['fotos']['tmp_name'][$i],'temp/'.$filename);
  
  }

  // LOGO RESIZE

  $filename = $_FILES['logo']['name'];
  move_uploaded_file($_FILES['logo']['tmp_name'],'temp/'.$filename);

?>

<p>
  <a href="download.zip">DOWNLOAD</a>
</p>

<p>
  <a href="index.php">VOLTAR</a>
</p>

<?php } else { ?>

    <form method='post' action='' enctype='multipart/form-data'>

      <p>
        <label>Nome:<br />
          <input type="text" name="nome" id="nome" required="required">
        </label>
      </p>

      <p>
        <label>Fotos:<br />
         <input type="file" name="fotos[]" id="fotos" multiple required="required">
        </label>
      </p>

      <p>
        <label>Logo:<br />
          <input type="file" name="logo" id="logo" required="required">
        </label>
      </p>

      <p>
        <input type='submit' name='submit' value='Enviar'>
      </p>

    </form>

<?php } ?>

  </body>
</html>