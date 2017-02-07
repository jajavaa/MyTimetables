<!DOCTYPE html>
<html>
  <?php
    include 'partials/head.php';
    $script = basename(isset($_GET['p']) ? $_GET['p'] : "home");
    $dir = 'requests/';
    $file = $dir . $script . '.php';
    if (!file_exists($file)) {
      $file = $dir . 'home.php';
    }
    include($file);
  ?>
</html>
