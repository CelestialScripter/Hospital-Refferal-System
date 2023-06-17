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

    if (isset($_GET['accept_id'])) {
        $ref_id = $_GET['accept_id'];

        if($user->accept($ref_id))
        {
        ?>
            <script>
                window.location.href="referred_single.php?success";
            </script>
        <?php
        }
    }
?>