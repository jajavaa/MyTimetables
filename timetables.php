<!DOCTYPE html>
<html>
  <?php include 'partials/head.php';?>
  <body>
    <div class="container">
      <?php include 'requests/partials/nav.php'; ?>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <?php
              include 'functions.php';
              courses();
            ?>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12">
          <?php isset($_GET['q']) ? getTimetable($_GET['q']) : splash();?>
        </div>
      </div>
    </div>
  </body>
</html>
