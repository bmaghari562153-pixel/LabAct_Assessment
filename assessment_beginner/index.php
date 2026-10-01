<?php
require_once __DIR__ . "/classes/Database.php";
$db = Database::connect();

function countRows(mysqli $db, string $table): int
{
    $row = $db->query("SELECT COUNT(*) AS c FROM $table")->fetch_assoc();
    return (int)$row['c'];
}

$stats = [
    'Clients'  => countRows($db, 'clients'),
    'Services' => countRows($db, 'services'),
    'Bookings' => countRows($db, 'bookings'),
    'Tools'    => countRows($db, 'tools'),
];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Service Booking System</title></head>
<body>
<?php include __DIR__ . "/nav.php"; ?>

<h1>Service Booking System</h1>

<table border="1" cellpadding="8">
  <tr>
    <?php foreach ($stats as $label => $count): ?>
      <th><?= $label ?></th>
    <?php endforeach; ?>
  </tr>
  <tr>
    <?php foreach ($stats as $count): ?>
      <td align="center"><?= $count ?></td>
    <?php endforeach; ?>
  </tr>
</table>

<h3>Quick Links</h3>
<ul>
  <li><a href="booking_system.php?page=services">Manage Services</a></li>
  <li><a href="booking_system.php?page=bookings">View Bookings</a></li>
  <li><a href="booking_system.php?page=bookings_create">Create a Booking</a></li>
</ul>

</body>
</html>
