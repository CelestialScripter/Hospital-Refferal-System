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

  $stat_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM referred WHERE hospitalReferredTo=:hosp AND status=:stat");
  $stat_stmt->execute(array(":hosp"=>$contact_row['hospital_id'], ":stat"=>'OPEN'));
  $statRow = $stat_stmt->fetch(PDO::FETCH_ASSOC);
  $statNo = $statRow['num'];

  if(isset($_POST['submit']))
  {
    $doc_stmt = $user->runQuery("SELECT * FROM contact_person WHERE contact_id=:id");
    $doc_stmt->execute(array(":id"=>$_SESSION['userSession']));
    $doc_row = $doc_stmt->fetch(PDO::FETCH_ASSOC);

    if (is_array($doc_row)) {

      $referring_stmt = $user->runQuery("SELECT * FROM hospitals WHERE hospital_id=:hid");
      $referring_stmt->execute(array(":hid"=>$doc_row['hospital_id']));
      $referral_row = $referring_stmt->fetch(PDO::FETCH_ASSOC);
      
      $referring_lat = $referral_row['latitude'];
      $referring_long = $referral_row['longitude'];


      $referred_by = $doc_row['hospital_id'];
      $firstname = $_POST['firstname'];
      $middlename = $_POST['middlename'];
      $lastname = $_POST['lastname'];
      $gender = $_POST['gender'];
      $telephone = $_POST['telephone'];
      $dob = $_POST['dob'];
      $sickness = $_POST['sickness'];
      $priority = $_POST['priority'];
      $diagnosis = $_POST['editor1'];
      $docs = $_FILES['docs']['name'];
      $docs_size = $_FILES['docs']['size'];
      $docs_tmp = $_FILES['docs']['tmp_name'];
      $size=1024*1024;
      $specialty = $_POST['specialty'];
      
      if(!empty($docs) && $docs_size <= $size && preg_match("/\.(pdf)$/i", $docs))
      {
          $stmt = $user->runQuery("SELECT * FROM referrals WHERE telephone=:phone AND status='OPEN'");
          $stmt->execute(array(":phone"=>$telephone));
          $referral_row = $stmt->fetch(PDO::FETCH_ASSOC);
          
          if($stmt->rowCount() > 0)
          {
          ?>
              <script>
                  window.location.href="refer.php?duplicate";
              </script>
          <?php
              
          }
          else{
              $random_name = md5(rand()*time());
              $cover_link='documents/'.$random_name.'.pdf';
              $move=move_uploaded_file($docs_tmp, $cover_link);
              if($move)
              {
                  if($user->refer($referred_by,$firstname,$middlename,$lastname,$gender,$telephone,$dob,$sickness,$priority,$diagnosis,$specialty,$cover_link))
                  {
                      //code for auto referral

                      //select all facilities with specialty and find distance diff
                      $specialty_stmt = $user->runQuery("SELECT * FROM contact_person WHERE specialization=:special");
                      $specialty_stmt->execute(array(":special"=>$specialty));
                      while($specialty_row = $specialty_stmt->fetch(PDO::FETCH_ASSOC)){
                        $search_stmt = $user->runQuery("SELECT * FROM hospitals WHERE hospital_id=:hsid");
                        $search_stmt->execute(array(":hsid"=>$specialty_row['hospital_id']));
                        $search_row = $search_stmt->fetch(PDO::FETCH_ASSOC);
                        if (is_array($search_row)) {
                          $destination = $search_row['hospital_id'];

                          $search_lat = $search_row['latitude'];
                          $search_long = $search_row['longitude'];

                          //Converting to radians
                          $longi1 = deg2rad($referring_long); 
                          $longi2 = deg2rad($search_long); 
                          $lati1 = deg2rad($referring_lat); 
                          $lati2 = deg2rad($search_lat); 

                          //Haversine Formula 
                          $difflong = $longi2 - $longi1; 
                          $difflat = $lati2 - $lati1; 
                                  
                          $val = pow(sin($difflat/2),2)+cos($lati1)*cos($lati2)*pow(sin($difflong/2),2); 
                                  
                          $distance = 6378.8 * (2 * asin(sqrt($val))); //for kilometers

                          $hospital_id = $doc_row['hospital_id'];

                          //post calculated distance to temp db
                          if ($user->temp($hospital_id,$destination,$telephone,$distance)) {
                              //

                          }
                        }
                      }
                      $postRefer_stmt = $user->runQuery("SELECT MIN(distance) AS shortest_distance FROM temp_dist WHERE batch=:bat");
                      $postRefer_stmt->execute(array(":bat"=>date('Y-m-d')));
                      $postRefer_row = $postRefer_stmt->fetch(PDO::FETCH_ASSOC);
                      $shortest_distance = $postRefer_row['shortest_distance'];

                      $searchTemp_stmt = $user->runQuery("SELECT * FROM temp_dist WHERE distance=:dist AND batch=:bat");
                      $searchTemp_stmt->execute(array(":dist"=>$shortest_distance, ":bat"=>date('Y-m-d')));
                      $searchTemp_row = $searchTemp_stmt->fetch(PDO::FETCH_ASSOC);
                      if ($postRefer_stmt) {
                        $searchReferral_stmt = $user->runQuery("SELECT * FROM referrals WHERE telephone=:pphone");
                        $searchReferral_stmt->execute(array(":pphone"=>$telephone));
                        $searchReferral_row = $searchReferral_stmt->fetch(PDO::FETCH_ASSOC);
                        $referral_id = $searchReferral_row['referral_id'];
                        $destination_hospital = $searchTemp_row['destination_hospital'];
                        if ($user->post_refer($referral_id,$destination_hospital)) {
                          $dropTemp_stmt = $user->runQuery("DELETE FROM temp_dist");
                          $dropTemp_stmt->execute();
                          if ($dropTemp_stmt) {
                            ?>
                              <script>
                                  window.location.href="refer.php?success";
                              </script>
                            <?php
                          }
                        }
                      }
                  }else {
                    ?>

                      <script>
                          window.location.href="refer.php?error";
                      </script>
                      
                    <?php 
                  }

                  
              }
      
          }

      }else {
      ?>

          <script>
              window.location.href="refer.php?filetype";
          </script>
          
      <?php
      }
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HRS | Refer Patient</title>
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
            <li>
              <a href="home.php">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
            <li class="active">
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
            Refer Patient
          </h1>
          <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Refer Patient</li>
          </ol>
        </section>

        <form method="post" enctype="multipart/form-data">
            <?php
                if(isset($_GET['success']))
                {
                ?>
                <div class="container">
                    <div class="alert alert-success">
                    <strong>SUCCESS!</strong> Patient has been successfully Referred !
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
                if(isset($_GET['filetype']))
                {
                ?>
                <div class="container">
                    <div class="alert alert-warning">
                    <strong>ERROR!</strong> The Document must not be more than 1MB and filetype must be PDF !
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
                    <strong>SORRY!</strong> A similar record is still Opened or Processsing !
                    </div>
                </div>
                <?php
                } else {}
            ?>
            <!-- Main content -->
            <section class="content" style="padding-bottom: 0;">

            <!-- SELECT2 EXAMPLE -->
            <div class="box box-default">
                <div class="box-header with-border">
                <h3 class="box-title">Patient Information</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" id="exampleInputEmail1" placeholder="First Name">
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="text" name="middlename" class="form-control" id="exampleInputEmail1" placeholder="Other Name">
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control" id="exampleInputEmail1" placeholder="Surname">
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control select2" name="gender" style="width: 100%;">
                            <option selected="selected">Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Telephone</label>
                        <input type="text" name="telephone" class="form-control" id="exampleInputEmail1" placeholder="Telephone">
                    </div>
                    <!-- /.form-group -->
                    <!-- Date dd/mm/yyyy -->
                    <div class="form-group">
                        <label>Date of Birth:</label>

                        <div class="form-group">
                        <input type="text" name="dob" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                        </div>
                        <!-- /.input group -->
                    </div>
                    <!-- /.form group -->
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

            <section class="content" style="padding-top: 0;">

            <!-- SELECT2 EXAMPLE -->
            <div class="box box-default">
              <div class="form-group" style="margin: 0 10px;">
                <label>Ailment</label>
                <input type="text" name="sickness" class="form-control" id="exampleInputEmail1" placeholder="Ailment">
              </div>
              <!-- /.form-group -->
                <div class="box-header with-border">
                <h3 class="box-title">Reason for Referral</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group">
                        <label>Priority: </label><br>
                        <label style="padding-right: 15px;">
                        <input type="radio" name="priority" value="Routine" class="minimal">
                        Routine
                        </label>
                        <label>
                        <input type="radio" name="priority" value="Urgent" class="minimal-red">
                        Medically Urgent
                        </label>
                    </div>
                    <div class="box box-info">
                        <!-- /.box-header -->
                        <div class="box-body pad">
                            <label>Diagnosis: </label>
                            <textarea id="editor1" name="editor1" rows="10" cols="80">
                            </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">File input</label>
                        <input type="file" name="docs" id="exampleInputFile">

                        <p class="help-block">Attach Additional Document in PDF only</p>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Specialty</label>
                        <input type="text" name="specialty" class="form-control" id="exampleInputEmail1" placeholder="Specialty Requested">
                    </div>
                    <!-- /.form-group -->
                    <!-- /.box -->
                    <div class="box-footer">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
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
        </form>
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
