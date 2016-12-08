<?php
require('../vendor/autoload.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require('../controlles/evaluate.php');
} else {
  $loader = new Twig_Loader_Filesystem(__DIR__ . '/../views');
  $twig = new Twig_Environment($loader);

  echo $twig->render('index.twig');
}
