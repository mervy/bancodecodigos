<?php
ini_set("display_errors", 1);
/**
 * How to use: http://ungeeksi.mervy.net/web_files/imagens/artigos/thumbs.php?i=Name-image.jpg&w=1200&h=400 or 
 * http://ungeeksi.mervy.net/web_files/imagens/artigos/thumbs.php?i=Name-image.jpg for original sizes
 */
// Variables
$filename = filter_input(INPUT_GET, 'i', FILTER_DEFAULT);
$newwidth = !empty(filter_input(INPUT_GET, 'w', FILTER_DEFAULT)) ? filter_input(INPUT_GET, 'w', FILTER_DEFAULT) : "";
$newheight = !empty(filter_input(INPUT_GET, 'h', FILTER_DEFAULT)) ? filter_input(INPUT_GET, 'h', FILTER_DEFAULT) : "";

//Verificando o formato da imagem com base na extensão
$image_type = strstr($filename, '.');
switch ($image_type) {
    case '.jpg':
        $source = imagecreatefromjpeg($filename);
        break;
    case '.jpeg':
        $source = imagecreatefromjpeg($filename);
        break;
    case '.png':
        $source = imagecreatefrompng($filename);
        break;
    case '.gif':
        $source = imagecreatefromgif($filename);
        break;
    default:
        echo("Error Invalid Image Type");
        die;
        break;
}
$percent = 1.0;

// Cabeçalho que ira definir a saida da pagina
header('Content-type: image/jpeg');
if ($source) {
// pegando as dimensoes reais da imagem, largura e altura
    list($width, $height) = getimagesize($filename);

//setando a largura da miniatura - se vazia usa a largura original
    $new_width = !empty($newwidth) ? $newwidth : $width;
//setando a altura da miniatura - se vazia usa a largura original
    $new_height = !empty($newheight) ? $newheight : $height;

//gerando a a miniatura da imagem
    $image_p = imagecreatetruecolor($new_width, $new_height);

//$source foi gerado lá em cima

    imagecopyresampled($image_p, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

//o 3º argumento é a qualidade da imagem de 0 a 100
    imagejpeg($image_p, null, 100);
    imagedestroy($image_p);
}
