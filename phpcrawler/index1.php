<h2>Listando todos os links do site G1</h2>
<?php
$site =  file_get_contents('https://g1.globo.com/');

libxml_use_internal_errors(true);

$domDocument = new DOMDocument();
$domDocument->loadHTML($site);

$linkTags = $domDocument->getElementsByTagName("a");

$linkList='';

foreach($linkTags as $link){
    $href = $link->getAttribute('href');
    
    if(!empty($href)){
        $linkList.= $href ."<br>";
    }
}

echo $linkList;
?>
<h2>Pegando os titulos</h2>

<?php

$linkTitulos = '';

foreach($linkTags as $link){
    
    if(strpos($link->getAttribute('class'), 'feed-post-link') === 0){
        $linkTitulos.= $link->textContent ."<br>";
    }
}
echo $linkTitulos;