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

if(isset($_POST['delete']))
{
	$id = $_GET['delete_id'];
	$user->delete_physician($id);
	header("Location: delete_physician.php?deleted");	
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HRS | Delete Physician</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
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
        <li>
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
        <li class="treeview active">
          <a href="#">
            <i class="fa fa-stethoscope"></i>
            <span>Physicians</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="add_physician.php"><i class="fa fa-plus-square"></i> Add Physician</a></li>
            <li class="active"><a href="manage_physician.php"><i class="fa fa-edit"></i> Manage Physicians</a></li>
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
        Delete Physician
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Physicians</a></li>
        <li class="active">Delete Physician</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
            <?php
                if(isset($_GET['deleted']))
                {
                    ?>
                    <div class="alert alert-success">
                    <strong>Success!</strong> record was deleted... 
                    </div>
                    <?php
                }
                else
                {
                    ?>
                    <div class="alert alert-danger">
                    <strong>Are You Sure </strong>you want to remove this Record ? 
                    </div>
                    <?php
                }
                ?>
            <div class="box">
                <div class="box-body">
                    <?php
                        if(isset($_GET['delete_id']))
                        {
                        ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Photograph</th>
                                        <th>Name</th>
                                        <th>Telephone</th>
                                        <th>Position</th>
                                        <th>Hospital</th>
                                    </tr>
                                </thead>
                                <?php

                                    $stmt = $user->runQuery("SELECT * FROM contact_person WHERE contact_id=:id");
                                    $stmt->execute(array(":id"=>$_GET['delete_id']));
                                    while($row=$stmt->fetch(PDO::FETCH_BOTH))
                                    {
                                      $hosp_stmt = $user->runQuery("SELECT * FROM hospitals WHERE hospital_id=:id");
                                      $hosp_stmt->execute(array(":id"=>$row['hospital_id']));
                                      $hosp_row=$hosp_stmt->fetch(PDO::FETCH_BOTH);
                                    ?>
                                        <tbody>
                                            <tr>
                                              <td><img src="<?php print($row['photograph']); ?>" width="50px" height="50px"/></td>
                                              <td><?php print($row['contact_firstname']." ".$row['contact_lastname']); ?></td>
                                              <td><?php print($row['telephone']); ?></td>
                                              <td><?php print($row['post']); ?></td>
                                              <td><?php print($hosp_row['hospital_name']); ?></td>
                                            </tr>
                                        </tbody>
                                    <?php
                                    }
                                ?>
                            </table>
                        <?php
                        }
                    ?>
                </div>
                <!-- /.box-body -->
                <div>
                  <p>
                    <?php
                        if(isset($_GET['delete_id']))
                        {
                            ?>
                            <form method="post">
                            <input type="hidden" name="id" value="<?php echo $row['contact_id']; ?>" />
                            <button class="btn btn-large btn-primary" type="submit" name="delete"><i class="fa fa-trash"></i> &nbsp; YES</button>
                            <a href="manage_hospitals.php" class="btn btn-large btn-success"><i class="fa fa-backward"></i> &nbsp; NO</a>
                            </form>  
                            <?php
                        }
                        else
                        {
                            ?>
                            <a href="manage_physician.php" class="btn btn-large btn-success"><i class="fa fa-backward"></i> &nbsp; Back to Views</a>
                            <?php
                        }
                    ?>
                  </p>
                </div>
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
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
</body>
</html>
