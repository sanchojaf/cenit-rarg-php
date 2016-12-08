<?php
function done($result) {
  header('Content-Type: application/json');
  ob_clean();
  echo json_decode($result);
  ob_flush();
  exit();
}

function raise($error) {
  if ($error instanceof Error) {
    $msg = $error->getMessage();
  } else if (is_object($error) && method_exists($error, 'toString')) {
    $msg = $error->toString();
  } else {
    $msg = $error;
  }

  http_response_code(500);
  ob_clean();
  echo (string)$msg;
  ob_flush();
  exit();
}

try {
  ob_start();
  {
    // Save parameters in $params.
    $params = is_string($_POST['parameters']) ? json_decode($_POST['parameters']) : $_POST['parameters'];
    // Legacy: Declare local var for each parameter.
    extract(is_string($_POST['parameters']) ? json_decode($_POST['parameters'], true) : $_POST['parameters'], false);
    // Run code.
    eval($_POST['code']);
  }
//  ob_end_clean();
} catch (Error $e) {
  raise($e);
}
