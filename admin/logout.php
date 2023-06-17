<?php
session_start();
require_once 'class.user.php';
$user = new USER();

if(!$user->is_logged_in())
{
	?>
		<script>
			window.location.href="index.php";
		</script>
	<?php
}

if($user->is_logged_in()!="")
{
	$user->logout();	
	?>
		<script>
			window.location.href="index.php";
		</script>
	<?php
}
?>