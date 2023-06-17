<?php
  session_start();
  require_once 'class.user.php';

  $user = new USER();

  if($user->is_logged_in()!="")
  {
      $AdminID = $_SESSION['adminSession'];
  }
  else
  {
    ?>
		<script>
			window.location.href="index.php";
		</script>
	<?php
  }

  if(isset($_POST['submit']))
  {
  $Lname = $_POST['lastname'];
  $Fname = $_POST['firstname'];
  $Uname = $_POST['username'];
  $Uemail = $_POST['email'];
  $Upword = $_POST['password'];
  $Uphone = $_POST['phone'];
  $Ustatus = 'Y';
  
  $stmt = $user->runQuery("SELECT * FROM users");
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_BOTH);

  if($row['username'] == $_POST['username'] OR $row['user_email'] == $_POST['email'] OR $row['password'] == $_POST['password'] OR $row['user_phone'] == $_POST['phone'])
  {
	?>
		<script>
			window.location.href="new_user.php?duplicate";
		</script>
	<?php
  }else
    {
      if($user->new_user($Lname,$Fname,$Uname,$Uemail,$Upword,$Uphone,$Ustatus))
      {
		$user_stmt = $user->runQuery("SELECT * FROM users WHERE user_email=:email_id");
		$user_stmt->execute(array(":email_id"=>$Uemail));
		$user_row = $user_stmt->fetch(PDO::FETCH_ASSOC);
		
		$_id = $user_row['user_id'];
		$lastname = $user_row['lastname'];
		$firstname = $user_row['firstname'];
		$email = $user_row['user_email'];
		$stel = $user_row['user_phone'];
		if ($user->payment($_id,$lastname,$firstname,$email,$stel)) {
		?>
			<script>
				window.location.href="view_users.php";
			</script>
	<?php
		}
      }
      else
      {
		?>
			<script>
				window.location.href="new_user.php?failure";
			</script>
		<?php
      }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>AONET Consult ::: Admin</title>

  <!-- Bootstrap core CSS -->

  <link href="css/bootstrap.min.css" rel="stylesheet">

  <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="css/custom.css" rel="stylesheet">
  <link href="css/icheck/flat/green.css" rel="stylesheet">


  <script src="js/jquery.min.js"></script>

  <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>


<body class="nav-md">

  <div class="container body">


    <div class="main_container">

      <?php
        include  'sidebar.php';
      ?>

      <!-- top navigation -->
      <?php
        include 'topNav.php';
      ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">

        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>
                    Settings
                </h3>
            </div>

          </div>
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">

                  <form class="form-horizontal form-label-left" method="post" novalidate>

                    <span class="section">Global</span>

                    <?php
                    if(isset($_GET['failure']))
                    {
                      ?>
                        <div class="container">
                      <div class="alert alert-warning">
                        <strong>SORRY!</strong> ERROR while inserting record !
                      </div>
                      </div>
                        <?php
                    } else {}
                    ?>
                    
                    <?php
                    if(isset($_GET['duplicate']))
                    {
                      ?>
                        <div class="container">
                      <div class="alert alert-warning">
                        <strong>SORRY!</strong> Record already exist !
                      </div>
                      </div>
                        <?php
                    } else {}
                    ?>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Lastname <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="lastname" placeholder="Lastname" required="required" type="text">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Firstname <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="firstname" placeholder="Firstname" required="required" type="text">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Username <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" name="username" placeholder="Username" required="required" type="text">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email" name="email" placeholder="Email Address" required="required" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" id="password" name="password" placeholder="Password" required="required" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="telephone">Telephone <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="tel" id="telephone" name="phone" placeholder="Telephone" required="required" data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-md-offset-3">
                        <button type="reset" class="btn btn-warning">Cancel</button>
                        <button id="send" type="submit" name="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- footer content -->
        <?php
          include 'footer.php';
        ?>
        <!-- /footer content -->

      </div>
      <!-- /page content -->
    </div>

  </div>

  <div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
  </div>

  <script src="js/bootstrap.min.js"></script>

  <!-- bootstrap progress js -->
  <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="js/nicescroll/jquery.nicescroll.min.js"></script>
  <!-- icheck -->
  <script src="js/icheck/icheck.min.js"></script>
  <!-- pace -->
  <script src="js/pace/pace.min.js"></script>
  <script src="js/custom.js"></script>
  <!-- input mask -->
  <script src="js/input_mask/jquery.inputmask.js"></script>
  <!-- input_mask -->
  <script>
    $(document).ready(function() {
      $(":input").inputmask();
    });
  </script>
  <!-- form validation -->
  <script src="js/validator/validator.js"></script>
  <script>
    // initialize the validator function
    validator.message['date'] = 'not a real date';

    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
      .on('blur', 'input[required], input.optional, select.required', validator.checkField)
      .on('change', 'select.required', validator.checkField)
      .on('keypress', 'input[required][pattern]', validator.keypress);

    $('.multi.required')
      .on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

    // bind the validation to the form submit event
    //$('#send').click('submit');//.prop('disabled', true);

    $('form').submit(function(e) {
      var submit = true;
      // evaluate the form using generic validaing
      if (!validator.checkAll($(this))) {
        submit = false;
      }

      if (submit)
        this.submit();
      return false;
    });

    /* FOR DEMO ONLY */
    $('#vfields').change(function() {
      $('form').toggleClass('mode2');
    }).prop('checked', false);

    $('#alerts').change(function() {
      validator.defaults.alerts = (this.checked) ? false : true;
      if (this.checked)
        $('form .alert').remove();
    }).prop('checked', false);
  </script>

</body>

</html>
