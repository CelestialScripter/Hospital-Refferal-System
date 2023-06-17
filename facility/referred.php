<?php
  session_start();
  require_once 'class.user.php';

  $user = new USER();

  if($user->is_logged_in()!="")
  {
    $UserID = $_SESSION['userSession'];
  }
  else
  {
    ?>
		<script>
			window.location.href="index.php";
		</script>
	<?php
  }

  $contact_stmt = $user->runQuery("SELECT * FROM contact_person WHERE contact_id=:id");
  $contact_stmt->execute(array(":id"=>$_SESSION['userSession']));
  $contact_row = $contact_stmt->fetch(PDO::FETCH_ASSOC);

  $inward_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM referred WHERE hospitalReferredTo=:hosp AND status=:stat");
  $inward_stmt->execute(array(":hosp"=>$contact_row['hospital_id'], ":stat"=>'OPEN'));
  $inwardRow = $inward_stmt->fetch(PDO::FETCH_ASSOC);
  $inwardNo = $inwardRow['num'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HRS | Reffered Patients</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.print.css" media="print">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php
        include 'header.php';
      ?>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="">
          <a href="home.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="">
          <a href="refer.php">
            <i class="fa fa-share"></i>
            <span>Refer Patient</span>
          </a>
        </li>
        <li>
          <a href="referred.php">
            <i class="fa fa-envelope"></i> <span>Referred Patients</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red"><?php print($inwardNo); ?></small>
            </span>
          </a>
        </li>
        <!-- <li class="">
          <a href="#">
            <i class="fa fa-cog"></i>
            <span>Settings</span>
          </a>
        </li> -->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Referred Patients
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Referred Patients</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="#" class="btn btn-primary btn-block margin-bottom">Refer Patient</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><i class="fa fa-inbox"></i> Referred Patients
                  <span class="label label-primary pull-right"><?php echo $inwardNo; ?></span></a></li>
                <!-- <li><a href="#"><i class="fa fa-envelope-o"></i> Referral Outwards<span class="label label-danger pull-right">Coming</span></a></li> -->
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Referred Patients</h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                    <?php
                        $referred_stmt = $user->runQuery("SELECT * FROM referred WHERE hospitalReferredTo=:hid");
                        $referred_stmt->execute(array(":hid"=>$contact_row['hospital_id']));

                        if ($referred_stmt->rowCount() > 0) {
                          while ($referred_row = $referred_stmt->fetch(PDO::FETCH_ASSOC)) {
                            $referral_stmt = $user->runQuery("SELECT * FROM referrals WHERE referral_id=:id");
                            $referral_stmt->execute(array(":id"=>$referred_row['referral_id']));
                            $referral_row = $referral_stmt->fetch(PDO::FETCH_ASSOC);

                            if (is_array($referral_row)) {
                            ?>
                              <tr>
                                <td class="mailbox-name">
                                  <?php
                                    if ($referral_row['status'] == 'ACCEPTED') {
                                    ?>
                                      <a style="color:grey;" href="referred_single.php?referred_id=<?php print($referral_row['referral_id']); ?>"><?php print($referral_row['firstname']." ".$referral_row['lastname']); ?></a>
                                    <?PHP
                                    }else {
                                    ?>
                                      <a href="referred_single.php?referred_id=<?php print($referral_row['referral_id']); ?>"><?php print($referral_row['firstname']." ".$referral_row['lastname']); ?></a>
                                    <?php
                                    }
                                  ?>
                                </td>
                                <td class="mailbox-subject"><b><?php print($referral_row['priority']) ?></b> - <?php print(substr($referral_row['diagnosis'], 0, 15)); ?>
                                </td>
                                <td class="mailbox-attachment"></td>
                                <td class="mailbox-date"><?php print($referral_row['date_referred']); ?></td>
                              </tr>
                            <?php
                            }
                          }
                        }else {
                          ?>
                            <tr>
                              <td style="text-align:center;">No Referral...</td>
                            </tr>
                          <?php
                        }
                        
                    ?>
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2023 <a href="#">Hospital Referral System</a>.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- Page Script -->
<script>
  $(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");

      //Switch states
      if (glyph) {
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }

      if (fa) {
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });
  });
</script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
