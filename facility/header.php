<?php
  $doc_stmt = $user->runQuery("SELECT * FROM contact_person WHERE contact_id=:id");
  $doc_stmt->execute(array(":id"=>$_SESSION['userSession']));
  $doc_row = $doc_stmt->fetch(PDO::FETCH_ASSOC);

  $stat_stmt = $user->runQuery("SELECT COUNT(*) AS num FROM referred WHERE hospitalReferredTo=:hosp AND status=:stat");
  $stat_stmt->execute(array(":hosp"=>$doc_row['hospital_id'], ":stat"=>'OPEN'));
  $statRow = $stat_stmt->fetch(PDO::FETCH_ASSOC);
  $statNo = $statRow['num'];
?>

  <header class="main-header">

        <!-- Logo -->
        <a href="home.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>H</b>RS</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Hospital</b>RS</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning"><?php print($statNo); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have <?php print($statNo); ?> notifications</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li>
                        <?php
                          if ($statNo < 1) {
                            print("You have no notification");
                          }else {
                          ?>
                            <a href="referred.php">
                              <i class="fa fa-users text-aqua"></i> <?php print($statNo); ?> patients was referred to your facility
                            </a>
                          <?php
                          }
                        ?>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="referred.php">View all</a></li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../admin/<?php print($doc_row['photograph']); ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php print($doc_row['contact_firstname']." ".$doc_row['contact_lastname']); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../admin/<?php print($doc_row['photograph']); ?>" class="img-circle" alt="User Image">

                    <p>
                    <?php print($doc_row['contact_firstname']." ".$doc_row['contact_lastname']); ?> - <?php print($doc_row['specialization']); ?>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <!-- <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div> -->
                    <div class="pull-right">
                      <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>

        </nav>
      </header>