<body>
  <div class="container">
    <?php include 'partials/nav.php'; ?>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <h2 class="center">Connect to people behind this.</h2>
      </div>
    </div>
    <div class="row">
      <div id="motif" class="col-lg-6 col-md-6 col-sm-12">
        <h4 class="break">How to contact</h4>
        <p>You can contact anyone of us. By filling out this form. It's most direct connection to us, and we reply to all emails.</p>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12">
        <h3 class="center">Direct connection</h3>
        <form id="contact" class="form" action="mailer.php" method="post">
          <div class="form-group">
            <input type="text" class="form-control" name="name" placeholder="Name" required="true">
            <input type="text" class="form-control" name="email" placeholder="Email" required="true">
            <input type="text" class="form-control" name="subject" placeholder="Subject">
            <select class="form-control" name="who">
              <?php
                $who = isset($_GET['f']) ? $_GET['f'] : "any";
                switch ($who) {
                  case 'admin':
                    echo '<option value="any">Anyone</option>
                    <option value="admin" selected="selected">Administrator</option>
                    <option value="moderator">Moderator</option>
                    <option value="relations">Public Relations</option>';
                    break;
                  case 'moderator':
                    echo '<option value="any">Anyone</option>
                    <option value="admin">Administrator</option>
                    <option value="moderator" selected="selected">Moderator</option>
                    <option value="relations">Public Relations</option>';
                    break;
                  case 'pr':
                    echo '<option value="any">Anyone</option>
                    <option value="admin">Administrator</option>
                    <option value="moderator">Moderator</option>
                    <option value="relations" selected="selected">Public Relations</option>';
                    break;
                  default:
                    echo '<option value="any">Anyone</option>
                    <option value="admin">Administrator</option>
                    <option value="moderator">Moderator</option>
                    <option value="relations">Public Relations</option>';
                    break;
                }
               ?>
              <
            </select>
            <textarea class="form-control custom-control" name="message" rows="3" style="resize:none" required="true" placeholder="Message"></textarea>
            <input class="btn btn-primary" type="submit" value="Send">
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
