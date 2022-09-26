<style>
    * {
        margin: 1% 3%;
    }
</style>
<h1>Generator random data!</h1>
<?php

//Gera paragráfos aleatórios
function geraIpsum()
{
    $file = file_get_contents(rtrim("datas/ipsum.txt"));
    $fileExp = explode("|", $file);
    return $fileExp;
}
//How to use
//Ver como embaralhar os paragráfos
// for ($i=0; $i < 3; $i++) { 
//     echo '<p>'.geraIpsum()[$i].'</p>';
// }

//Gerando senha
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*-_+(/|),.;/~][{}<>?';
$password = substr(str_shuffle($chars), 0, 12);

// for ($i=0; $i < 10; $i++) {   
//   echo trim(substr(str_shuffle($chars), 0, 12 ))."<br>\n";
// }


// for ($i=0; $i < 100; $i++) {   
//     echo '{"id":'.$i.',';
//     //trim retira espaços do final do parágrafo
//     echo '"paragraphs":"<p>'.trim(geraIpsum()[$i], " \n\t\r").'</p>",';
//     echo '"password":"'.substr(str_shuffle($chars), 0, 12 ).'"},
//     ';
//   }

$names =  file_get_contents("datas/names.txt");
$names = explode("\r", $names);

$surnames =  file_get_contents("datas/surnames.txt");
$surnames = explode("\r", $surnames);

$email =  file_get_contents("datas/emails.txt");
$email = explode("\r", $email);

//Embaralhando os dados
$randNumberforEmails = rand(0, count($email) - 1);
$randNumberforNames = rand(0, count($names) - 1);
$randNumberforSurnames = rand(0, count($surnames) - 1);

// $numberRegistries = 1000000;
// for ($i = 0; $i <=$numberRegistries; $i++) {
//     

//     echo "<li>".$i.' - '
//     .$names[$randNumberforNames].' '
//     . $surnames[$randNumberforSurnames].' - '
//     .strtolower($names[$randNumberforNames]).'@'.trim($email[$randNumberforEmails]) . "</li>";
// }

//echo '<h1>Mais de um parágrafo</h1>';
$numberRandom = 15;
$rand = rand(1, $numberRandom);
for ($x=1; $x <= $rand; $x++) { 
  $paragraphs[]= '<p>' . trim(geraIpsum()[$x], " \n\t\r") . '</p>';
}
//$par2 = implode("",$paragraphs);
//echo $par2;

for ($i = 0; $i < 300; $i++) {
    echo '{"id":' . $i . ',';
    echo '"name":"'. trim($names[$i]).'",';
    echo '"surname":"' . trim($surnames[$i]) . '",';
    echo '"email":'.strtolower($names[$i]).'@'.trim($email[$i]).'",';
    //trim retira espaços do final do parágrafo
    echo '"paragraphs":"<p>' . trim(geraIpsum()[$i], " \n\t\r") . '</p>",';
    echo '"password":"' . substr(str_shuffle($chars), 0, 12) . '"},
    ';
}