<?php
// nav.php - shared navigation. $base is detected automatically from the current folder.
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), "/\\");
if (basename($base) === "pages") { $base = dirname($base); }
$base = rtrim($base, "/\\") . "/";
?>
<nav>
  <a href="<?= $base ?>index.php">Home</a> |
  <a href="<?= $base ?>pages/clients_list.php">Clients</a> |
  <a href="<?= $base ?>pages/services_list.php">Services</a> |
  <a href="<?= $base ?>booking_system.php?page=bookings">Bookings</a> |
  <a href="<?= $base ?>booking_system.php?page=bookings_create">+ Create Booking</a>
</nav>
<hr>