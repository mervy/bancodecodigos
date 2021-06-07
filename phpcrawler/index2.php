<style>
h2{text-align: center; margin: 20px}
table {padding:10px}
</style>

<h2>Listando dados de um site</h2>
<form action="">
<table border="1">
    <tr>    
        <td><label for="url">URL do site</label>
            <input type="text" id="url" name="url" style="width: 400px; heid" placehold="https://www.google.com/"></td>
            <td><p><input type="submit" value="Submit"></p></td>
    </tr>  
</table>
</form>

<?php
$urlGeted = isset($_GET['url']) && !empty($_GET['url']) ? $_GET['url'] : "";

if($urlGeted)

$site =  file_get_contents($urlGeted);

libxml_use_internal_errors(true);

$domDocument = new DOMDocument();
$domDocument->loadHTML($site);

$linkGetTitles = $domDocument->getElementsByTagName("h1");
$linkGetImgs = $domDocument->getElementsByTagName("img");
$linkGetParagraphs = $domDocument->getElementsByTagName("p");

$linkTitle = '';
$linkImg = '';
$linkParagrafos = '';

foreach($linkGetTitles as $linkT){       
    $linkTitle.= $linkT->textContent;   
 }

 foreach($linkGetImgs as $linkI){       
    $linkImg.= "<img src=".$linkI->getAttribute('src').">";   
 }

foreach($linkGetParagraphs as $linkP){       
    $linkParagrafos.= '<p>'.$linkP->textContent.'</p>';   
 }

?>
<h1><?=$linkTitle?></h1>
<?=$linkImg?>
<?=$linkParagrafos?>


