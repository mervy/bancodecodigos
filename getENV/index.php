<?php

require __DIR__.'/vendor/autoload.php';

use App\Common\Environment;

Environment::load(__DIR__);

$env = getenv();

//Testando
echo "
<h1>Mostrando as variáveis usadas</h1>
<pre>";
  var_dump($env);
echo "</pre>";