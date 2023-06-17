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
  <title>HRS | Referral Note</title>
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
        Read Mail
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Referral Note</li>
      </ol>
    </section>
    <?php
      if(isset($_GET['success']))
      {
      ?>
      <div class="container">
          <div class="alert alert-success">
            <strong>SUCCESS!</strong> Referral Accepted successfully ! <br>
            <a href="referred.php">Click here to view referred patients</a>
          </div>
      </div>
      <?php
      } else {}
    ?>

    <!-- Main content -->
    <?php
      if (isset($_GET['referred_id'])) {
        $ref_id = $_GET['referred_id'];

        $refer_stmt = $user->runQuery("SELECT * FROM referrals WHERE referral_id=:id");
        $refer_stmt->execute(array(":id"=>$ref_id));
        $refer_row = $refer_stmt->fetch(PDO::FETCH_ASSOC);

        $hosp_stmt = $user->runQuery("SELECT * FROM hospitals WHERE hospital_id=:id");
        $hosp_stmt->execute(array(":id"=>$refer_row['reffered_by']));
        $hosp_row = $hosp_stmt->fetch(PDO::FETCH_ASSOC);
      ?>
        <section class="content">
          <div class="row">
            <div class="col-md-3">
              <a href="refer.php" class="btn btn-primary btn-block margin-bottom">Refer Patient</a>

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
                    <li><a href="referred.php"><i class="fa fa-inbox"></i> Referred Patients
                      <span class="label label-primary pull-right"><?php print($inwardNo); ?></span></a></li>
                    <!-- <li><a href="#"><i class="fa fa-envelope-o"></i> Referrals Outwards</a></li> -->
                  </ul>
                </div>
                <!-- /.box-body -->
              </div>
            </div>
            <!-- /.col -->
            <?php
              if (is_array($refer_row)) {
            ?>
                <div class="col-md-9">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Read Note</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                      <div class="mailbox-read-info">
                        <h3><?php print($refer_row['sickness']); ?></h3>
                        <h5>From: <?php print($hosp_row['hospital_name']); ?>
                          <span class="mailbox-read-time pull-right">
                            <?php print($refer_row['date_referred']);?>
                          </span>
                        </h5>
                      </div>
                      <!-- /.mailbox-read-info -->
                      <div class="mailbox-read-message">
                        <p><?php print($refer_row['diagnosis']); ?></p>
                      </div>
                      <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                      <ul class="mailbox-attachments clearfix">
                        <li>
                          <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

                          <div class="mailbox-attachment-info">
                            <a href="<?php print($refer_row['file']) ?>" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> Additional Document</a>
                                <span class="mailbox-attachment-size">
                                  <a href="<?php print($refer_row['file']) ?>" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                </span>
                          </div>
                        </li>
                      </ul>
                    </div>
                    <!-- /.box-footer -->
                    <div class="box-footer">
                      <div class="pull-right">
                        <?php
                          if ($refer_row['status'] == "ACCEPTED") {
                            # code...
                          }else {
                          ?>
                            <a class="btn btn-success" href="accept_patient.php?accept_id=<?php print($refer_row['referral_id']); ?>"><i class="fa fa-check">Accept</i></a>
                          <?php
                          }
                        ?>
                      </div>
                      <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                    </div>
                    <!-- /.box-footer -->
                  </div>
                  <!-- /. box -->
                </div>
            <?php
              }
            ?>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->
      <?php
      }
    ?>
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
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>