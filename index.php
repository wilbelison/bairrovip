<!DOCTYPE html>
<html>
  <head>
    <title>Bairro VIP Generator</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="libs/style.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,900" rel="stylesheet">
  </head>
  <body>

    <h1>Bairro VIP Generator <small>beta</small></h1>

<?php

require 'libs/functions.php';
require 'libs/simpleimage.php';


delete_files('temp/');

if(isset($_POST['submit'])) {

  mkdir('temp/728x90/');
  mkdir('temp/300x250/');
  mkdir('temp/320x100/');

  mkdir('temp/src/');

  // FOTOS RESIZE

  $nome = $_POST['nome'];
  $slug = slugify($nome);

  $countfiles = count($_FILES['fotos']['name']);

  $fotos = '';

  for($i = 0; $i < $countfiles; $i++) {

    $filename = $_FILES['fotos']['name'][$i];
    $filesize = $_FILES['fotos']['size'][$i];
    move_uploaded_file($_FILES['fotos']['tmp_name'][$i],'temp/src/' . $filename);

    resize_crop_image(224, 90, 'temp/src/' . $filename, 'temp/728x90/foto_' . $i . '.jpg', IMAGETYPE_JPEG, 100);
    resize_crop_image(300, 110, 'temp/src/' . $filename, 'temp/300x250/foto_' . $i . '.jpg', IMAGETYPE_JPEG, 100);
    resize_crop_image(150, 150, 'temp/src/' . $filename, 'temp/320x100/foto_' . $i . '.jpg', IMAGETYPE_JPEG, 100);

    $fotos .= "'foto_" . $i . ".jpg'";

    if($i < $countfiles){
      $fotos .= ',';
    } 

  }

  // LOGO RESIZE

  $filename = $_FILES['logo']['name'];
  $filesize = $_FILES['logo']['size'];
  move_uploaded_file($_FILES['logo']['tmp_name'],'temp/src/' . $filename);

  resize_crop_image(172, 90, 'temp/src/' . $filename, 'temp/728x90/logo.png', IMAGETYPE_PNG, 100, true);
  resize_crop_image(290, 45, 'temp/src/' . $filename, 'temp/300x250/logo.png', IMAGETYPE_PNG, 100, true);
  resize_crop_image(124, 80, 'temp/src/' . $filename, 'temp/320x100/logo.png', IMAGETYPE_PNG, 100, true);

  $nome = $_POST['nome'];

  $hash = array(
    'nome' => $nome,
    'fotos' => $fotos
  );

  parse_template('templates/728x90/index.html', $hash, 'temp/728x90/index.html');
  parse_template('templates/300x250/index.html', $hash, 'temp/300x250/index.html');
  parse_template('templates/320x100/index.html', $hash, 'temp/320x100/index.html');

  copy('templates/728x90/vivareal.gif', 'temp/728x90/vivareal.gif');
  copy('templates/300x250/vivareal.gif', 'temp/300x250/vivareal.gif');
  copy('templates/320x100/vivareal.png', 'temp/320x100/vivareal.png');
  copy('templates/320x100/seta.gif', 'temp/320x100/seta.gif');

  mkdir('temp/zip/');

  ZIP('temp/728x90/', 'temp/zip/' . $slug . '-728x90.zip');
  ZIP('temp/300x250/', 'temp/zip/' . $slug . '-300x250.zip');
  ZIP('temp/320x100/', 'temp/zip/' . $slug . '-320x100.zip');

  $download = './temp/' . $slug . '.zip';

  ZIP('temp/zip/', $download);

?>

<div id="preview">
  <iframe class="w728h90" src="temp/728x90/index.html"></iframe><br />
  <iframe class="w300h250" src="temp/300x250/index.html"></iframe><br />
  <iframe class="w320h100" src="temp/320x100/index.html"></iframe>
</div>

<ul id="acoes">
  
  <li class="new"><a href="index.php">← Criar novo banner</a> ou</li>
  <li class="download"><a href="<?php echo $download ?>">⤓ Download</a></li>
</ul>

<?php } else { ?>

    <form method="post" action="" enctype="multipart/form-data" id="formulario">

      <p>
        <label>Nome: <input type="text" name="nome" id="nome" required="required"></label>
      </p>

      <p>
        <label>Fotos: <input type="file" name="fotos[]" id="fotos" multiple required="required"></label>
      </p>

      <p>
        <label>Logo:  <input type="file" name="logo" id="logo" required="required"></label>
      </p>

      <p><input type='submit' name='submit' value='Enviar →' id="enviar"></p>

    </form>

    <script>document.getElementById('nome').focus();</script>

<?php } ?>

   <a class="documentacao" href="https://github.com/wilbelison/bairrovip" target="_blank"></svg>Documentação</a>

  <p id="copyright">Desenvolvido com <i>❤</i> por <a href="http://bit.ly/wilbelison" target="_blank">Wilbelison Junior</a></p>

  </body>
</html>