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

  $referral_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM referrals");
  $referral_stmt->execute();
  $referralRow = $referral_stmt->fetch(PDO::FETCH_ASSOC);
  $referralNo = $referralRow['num'];

  $hospital_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM hospitals");
  $hospital_stmt->execute();
  $hospitalRow = $hospital_stmt->fetch(PDO::FETCH_ASSOC);
  $hospitalNo = $hospitalRow['num'];

  $doctor_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM contact_person WHERE post=:post");
  $doctor_stmt->execute(array(":post"=>"Doctor"));
  $doctorRow = $doctor_stmt->fetch(PDO::FETCH_ASSOC);
  $doctorNo = $doctorRow['num'];

  $nurse_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM contact_person WHERE post=:post");
  $nurse_stmt->execute(array(":post"=>"Nurse"));
  $nurseRow = $nurse_stmt->fetch(PDO::FETCH_ASSOC);
  $nurseNo = $nurseRow['num'];
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
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Administrator</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active">
          <a href="home.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-hospital-o"></i>
            <span>Hospitals</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="add_hospital.php"><i class="fa fa-plus-square"></i> Add Hospital</a></li>
            <li><a href="manage_hospitals.php"><i class="fa fa-edit"></i> Manage Hospitals</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-stethoscope"></i>
            <span>Physicians</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="add_physician.php"><i class="fa fa-plus-square"></i> Add Physician</a></li>
            <li><a href="manage_physician.php"><i class="fa fa-edit"></i> Manage Physicians</a></li>
          </ul>
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
              <span class="info-box-text">Total Referrals</span>
              <span class="info-box-number"><?php echo $referralNo; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-hospital-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Facilities</span>
              <span class="info-box-number"><?php echo $hospitalNo; ?></span>
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
              <span class="info-box-number"><?php echo $doctorNo; ?></span>
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
              <span class="info-box-number"><?php echo $nurseNo; ?></span>
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
        <!-- Left col -->
        <div class="col-md-12">
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
                      $referral_stmt = $user->runQuery("SELECT * FROM referrals ORDER BY date_referred DESC");
                      $referral_stmt->execute();

                      if ($referral_stmt->rowCount() > 0) {
                        while ($referral_row = $referral_stmt->fetch(PDO::FETCH_ASSOC)) {
                          if (is_array($referral_row)) {
                          ?>
                            <tr>
                              <td><a href="#"><?php print("000".$referral_row['referral_id']); ?></a></td>
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
          </div>
          <!-- /.box -->
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
