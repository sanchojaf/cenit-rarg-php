<?php
use Symfony\Component\HttpFoundation\Response;

$ctrl = $app['controllers_factory'];

$ctrl->get('/', function () use ($app) {
  return $app['twig']->render('index.twig');
});


$ctrl->post('/', function () use ($app) {
  $GLOBALS['response'] = new StdClass();

  function done($result) {
    global $app;

    $GLOBALS['response'] = $app->json($result);
  }

  function raise($error) {
    if ($error instanceof Error) {
      $msg = $error->getMessage();
    } else if (is_object($error) && method_exists($error, 'toString')) {
      $msg = $error->toString();
    } else {
      $msg =  $error;
    }

    $GLOBALS['response'] = new Response((string)$msg, 500);
  }

  try {
    ob_start();
    {
      $params = is_string($_POST['parameters']) ? json_decode($_POST['parameters']) : $_POST['parameters'];
      eval($_POST['code']);
    }
    ob_end_clean();
  } catch (Error $e) {
    raise($e);
  }

  return $GLOBALS['response'];
  exit();
});

return $ctrl;