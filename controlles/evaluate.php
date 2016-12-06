<?php
$ctrl = $app['controllers_factory'];

$ctrl->get('/', function () use ($app) {
  return $app['twig']->render('index.twig');
});


$ctrl->post('/', function () use ($app) {
  $GLOBALS['response'] = new StdClass();

  function done($result) {
    $GLOBALS['response']->error = false;
    $GLOBALS['response']->result = $result;
    $GLOBALS['response']->contents = ob_get_contents();
  }

  function raise($error) {
    $GLOBALS['response']->error = is_string($error) ? $error : $error->getMessage();
    $GLOBALS['response']->result = null;
    $GLOBALS['response']->contents = ob_get_contents();
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

  echo json_encode($GLOBALS['response']);
  exit();
});

return $ctrl;