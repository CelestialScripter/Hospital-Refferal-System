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
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $telephone = $_POST['telephone'];
    $position = $_POST['position'];
    $passport = $_FILES['passport']['name'];
    $passport_size = $_FILES['passport']['size'];
    $passport_tmp = $_FILES['passport']['tmp_name'];
    $size=1024*1024;
    $username = $_POST['username'];
    $specialization = $_POST['specialization'];
    $hospital = $_POST['hospital'];
    $password = strtoupper($lastname);
    
    if(!empty($passport) && $passport_size <= $size && preg_match("/\.(gif|png|jpeg|jpg)$/i", $passport))
    {
        $stmt = $user->runQuery("SELECT * FROM contact_person WHERE telephone=:phone");
        $stmt->execute(array(":phone"=>$telephone));
        $physician_row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($stmt->rowCount() > 0)
        {
        ?>
            <script>
                window.location.href="add_physician.php?duplicate";
            </script>
        <?php
            
        }
        else{
            $random_name = md5(rand()*time());
            $cover_link='passports/'.$random_name.'.jpg';
            $move=move_uploaded_file($passport_tmp, $cover_link);
            if($move)
            {
                if($user->add_contact($hospital,$firstname,$lastname,$telephone,$position,$username,$password,$specialization,$cover_link))
                {
                ?>
                    <script>
                        window.location.href="add_physician.php?success";
                    </script>
                <?php
                }
                else 
                { 
                ?>

                    <script>
                        window.location.href="add_physician.php?error";
                    </script>
                    
                <?php 
                }
            }
    
        }

    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HRS | Add Contact</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/colorpicker/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
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

  <script>
      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            $('#blah')
              .attr('src', e.target.result)
              .width(110)
              .height(110);
          };

          reader.readAsDataURL(input.files[0]);
        }
      }
  </script>
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
            <li class="active"><a href="add_physician.php"><i class="fa fa-plus-square"></i> Add Physician</a></li>
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
        Add Contact
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Add Hospital</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" style="padding-bottom: 0;">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Contact Person</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form method="post" enctype="multipart/form-data">
                <?php
                  if(isset($_GET['success']))
                  {
                  ?>
                    <div class="container">
                      <div class="alert alert-success">
                        <strong>SUCCESS!</strong> Record Successfully inserted !
                      </div>
                    </div>
                  <?php
                  } else {}
                ?>

                <?php
                  if(isset($_GET['failure']))
                  {
                  ?>
                    <div class="container">
                      <div class="alert alert-danger">
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

                <div class="form-group">
                  <label>First Name</label>
                  <input type="text" class="form-control" name="firstname" id="exampleInputEmail1" placeholder="First Name">
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                  <label>Last Name</label>
                  <input type="text" class="form-control" name="lastname" id="exampleInputEmail1" placeholder="Last Name">
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                  <label>Telephone</label>
                  <input type="text" class="form-control" name="telephone" id="exampleInputEmail1" placeholder="Telephone">
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                    <label>Position</label>
                    <select class="form-control select2" name="position">
                    <option value="" selected="selected" disabled="disabled">Select Post</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Nurse">Nurse</option>
                    </select>
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                    <label>Passport</label>
                    <input id="file" type='file' name="passport" onchange="readURL(this);"/><br>
                    <img id="blah" src="#" alt="" />
                </div>
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" name="username" id="exampleInputEmail1" placeholder="Username">
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                  <label>Specialization</label>
                  <input type="text" name="specialization" class="form-control" id="exampleInputEmail1" placeholder="Specialization">
                </div>
                <!-- /.form group -->
                <div class="form-group">
                    <label>Hospital</label>
                    <select class="form-control select2" name="hospital">
                        <option value="" disabled="disabled" selected>Choose Hospital</option>
                        <?php
                            $hospital_stmt = $user->runQuery("SELECT * FROM hospitals ORDER BY hospital_name ASC");
                            $hospital_stmt->execute();
                            while($hospital_row = $hospital_stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                            <option value="<?php echo $hospital_row['hospital_id']; ?>"> <?php echo $hospital_row['hospital_name'];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <!-- /.box -->
                <div class="box-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
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
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- CK Editor -->
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1');
    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
  });
</script>
</body>
</html>
