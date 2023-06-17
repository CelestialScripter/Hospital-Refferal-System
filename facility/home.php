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

  $outward_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM referrals WHERE reffered_by=:hosp");
  $outward_stmt->execute(array(":hosp"=>$contact_row['hospital_id']));
  $outwardRow = $outward_stmt->fetch(PDO::FETCH_ASSOC);
  $outwardNo = $outwardRow['num'];

  $inward_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM referred WHERE hospitalReferredTo=:hosp");
  $inward_stmt->execute(array(":hosp"=>$contact_row['hospital_id']));
  $inwardRow = $inward_stmt->fetch(PDO::FETCH_ASSOC);
  $inwardNo = $inwardRow['num'];

  $hospital_stmt = $user->runQuery("SELECT * FROM hospitals WHERE hospital_id=:hid");
  $hospital_stmt->execute(array(":hid"=>$contact_row['hospital_id']));
  $hospital_row = $hospital_stmt->fetch(PDO::FETCH_ASSOC);
  
  $stat_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM referred WHERE hospitalReferredTo=:hosp AND status=:stat");
  $stat_stmt->execute(array(":hosp"=>$contact_row['hospital_id'], ":stat"=>'OPEN'));
  $statRow = $stat_stmt->fetch(PDO::FETCH_ASSOC);
  $statNo = $statRow['num'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HRS | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

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
        <li class="active">
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
              <small class="label pull-right bg-red"><?php print($statNo); ?></small>
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
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Referral Outwards</span>
              <span class="info-box-number"><?php echo $outwardNo; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Referral Inwards</span>
              <span class="info-box-number"><?php echo $inwardNo; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-user-md"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Doctors</span>
              <span class="info-box-number"><?php echo $hospital_row['physicians']; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Nurses</span>
              <span class="info-box-number"><?php echo $hospital_row['nurses']; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->


      <!-- Main row -->
      <div class="row">
        <div class="col-md-1"></div>
        <!-- Left col -->
        <div class="col-md-10">
          <!-- MAP & BOX PANE -->
          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Recent Referrals</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Health Status</th>
                    <th>Facility</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $referred_stmt = $user->runQuery("SELECT * FROM referred WHERE hospitalReferredTo=:hid");
                      $referred_stmt->execute(array(":hid"=>$contact_row['hospital_id']));

                      if ($referred_stmt->rowCount() > 0) {
                        while ($referred_row = $referred_stmt->fetch(PDO::FETCH_ASSOC)) {
                          $referral_stmt = $user->runQuery("SELECT * FROM referrals WHERE referral_id=:id ORDER BY date_referred DESC");
                          $referral_stmt->execute(array(":id"=>$referred_row['referral_id']));
                          $referral_row = $referral_stmt->fetch(PDO::FETCH_ASSOC);

                          if (is_array($referral_row)) {
                          ?>
                            <tr>
                              <td><a href="referred_single.php?referred_id=<?php print($referral_row['referral_id']); ?>"><?php print("000".$referral_row['referral_id']); ?></a></td>
                              <td><?php print($referral_row['firstname']." ".$referral_row['lastname']); ?></td>
                              <td>
                                <?php
                                  if ($referral_row['priority'] == 'urgent') {
                                  ?>
                                    <span class="label label-danger">Medically Urgent</span>
                                  <?php
                                  }else {
                                  ?>
                                    <span class="label label-warning">Routine</span>
                                  <?php
                                  }
                                ?>
                              </td>
                              <td>
                                <?php
                                  $facility_stmt = $user->runQuery("SELECT * FROM hospitals WHERE hospital_id=:id");
                                  $facility_stmt->execute(array(":id"=>$referral_row['reffered_by']));
                                  $facility_row = $facility_stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <div class="sparkbar" data-color="#00a65a" data-height="20"><?php print($facility_row['hospital_name']); ?></div>
                              </td>
                            </tr>
                          <?php
                          }
                        }
                      }else {
                        ?>
                          <tr>
                            <td style="text-align:center;">No Recent Referral...</td>
                          </tr>
                        <?php
                      }
                      
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="refer.php" class="btn btn-sm btn-info btn-flat pull-left">Refer Patient</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-1"></div>
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
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="plugins/chartjs/Chart.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
