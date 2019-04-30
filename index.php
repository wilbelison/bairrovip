<!DOCTYPE html>
<html>
  <head>
    <title>Bairro VIP Generator</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="libs/style.css" />
  </head>
  <body>

    <h1>Bairro VIP Generator</h1>

<?php

require 'libs/functions.php'; 

delete_files('temp/');

if(isset($_POST['submit'])) {

?>

<ul id="arquivos">

<?php

  mkdir('temp/728x90/');
  mkdir('temp/300x250/');
  mkdir('temp/320x100/');

  // FOTOS RESIZE

  $nome = $_POST['nome'];

  $countfiles = count($_FILES['fotos']['name']);

  $fotos = '';

  for($i = 0; $i < $countfiles; $i++) {

    $filename = $_FILES['fotos']['name'][$i];
    $filesize = $_FILES['fotos']['size'][$i];
    move_uploaded_file($_FILES['fotos']['tmp_name'][$i],'temp/' . $filename);

    echo '<li>' . $filename . ' <span class="size">' . filesize_formatted($filesize) . '</span> <span class="ok">ok</span> </li>';

    resize_crop_image(224, 90, 'temp/' . $filename, 'temp/728x90/foto_' . $i . '.jpg');
    resize_crop_image(300, 110, 'temp/' . $filename, 'temp/300x250/foto_' . $i . '.jpg');
    resize_crop_image(150, 100, 'temp/' . $filename, 'temp/320x100/foto_' . $i . '.jpg');

    $fotos .= "'foto_" . $i . ".jpg'";

    if($i < $countfiles){
      $fotos .= ',';
    } 

  }

  // LOGO RESIZE

  $filename = $_FILES['logo']['name'];
  $filesize = $_FILES['logo']['size'];
  move_uploaded_file($_FILES['logo']['tmp_name'],'temp/' . $filename);

  echo '<li>' . $filename . ' <span class="size">' . filesize_formatted($filesize) . '</span> <span class="ok">ok</span> </li>';

  resize_crop_image(162, 90, 'temp/' . $filename, 'temp/728x90/logo.png');
  resize_crop_image(280, 45, 'temp/' . $filename, 'temp/300x250/logo.png');
  resize_crop_image(124, 80, 'temp/' . $filename, 'temp/320x100/logo.png');

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

?>

</ul>

<div id="preview">
  <iframe class="w728h90" src="temp/728x90/index.html"></iframe>
  <iframe class="w300h250" src="temp/300x250/index.html"></iframe>
  <iframe class="w320h100" src="temp/320x100/index.html"></iframe>
</div>

<ul id="acoes">
  <li class="download"><a href="download.zip">Download</a> ou</li>
  <li class="new"><a href="index.php">Criar um novo banner</a></li>
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

      <p><input type='submit' name='submit' value='Enviar'></p>

    </form>

<?php } ?>

  <p id="copyright">Desenvolvido com <i>❤</i> por <a href="mailto:meustudio@gmail.com">Wilbelison Junior</a></p>

  </body>
</html>