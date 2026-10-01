<?php
// booking_system.php - front controller (OOP version)
// Place in: C:\xampp\htdocs\assessment_beginner\
require_once "classes/Service.php";
require_once "classes/Client.php";
require_once "classes/Booking.php";

$serviceModel = new Service();
$clientModel  = new Client();
$bookingModel = new Booking();

$page = $_GET['page'] ?? 'services';

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/* ---------- ACTIONS ---------- */
if ($page === 'services_edit' && isset($_POST['update'])) {
    $serviceModel->update(
        (int)$_GET['id'],
        trim($_POST['service_name']),
        trim($_POST['description']),
        (float)$_POST['hourly_rate'],
        (int)$_POST['is_active']
    );
    header("Location: booking_system.php?page=services");
    exit;
}

if ($page === 'bookings_create' && isset($_POST['create'])) {
    $bookingModel->create(
        (int)$_POST['client_id'],
        (int)$_POST['service_id'],
        $_POST['booking_date'],
        (int)$_POST['hours']
    );
    header("Location: booking_system.php?page=bookings");
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Booking System</title></head>
<body>

<?php include __DIR__ . "/nav.php"; ?>

<?php if ($page === 'services'): ?>

  <h2>Services</h2>
  <table border="1" cellpadding="8">
    <tr><th>ID</th><th>Name</th><th>Rate</th><th>Active</th><th>Action</th></tr>
    <?php foreach ($serviceModel->all() as $row): ?>
      <tr>
        <td><?= e($row['service_id']) ?></td>
        <td><?= e($row['service_name']) ?></td>
        <td>₱<?= number_format($row['hourly_rate'], 2) ?></td>
        <td><?= $row['is_active'] ? "Yes" : "No" ?></td>
        <td><a href="booking_system.php?page=services_edit&id=<?= e($row['service_id']) ?>">Edit</a></td>
      </tr>
    <?php endforeach; ?>
  </table>

<?php elseif ($page === 'services_edit'):
  $service = $serviceModel->find((int)($_GET['id'] ?? 0));
  if (!$service): ?>
  <p>Service not found.</p>
<?php else: ?>

  <h2>Edit Service</h2>
  <form method="post">
    <label>Service Name</label><br>
    <input type="text" name="service_name" value="<?= e($service['service_name']) ?>"><br><br>

    <label>Description</label><br>
    <textarea name="description" rows="4" cols="40"><?= e($service['description']) ?></textarea><br><br>

    <label>Hourly Rate</label><br>
    <input type="text" name="hourly_rate" value="<?= e($service['hourly_rate']) ?>"><br><br>

    <label>Active</label><br>
    <select name="is_active">
      <option value="1" <?= $service['is_active'] == 1 ? "selected" : "" ?>>Yes</option>
      <option value="0" <?= $service['is_active'] == 0 ? "selected" : "" ?>>No</option>
    </select><br><br>

    <button type="submit" name="update">Update</button>
  </form>

<?php endif; elseif ($page === 'bookings_create'): ?>

  <h2>Create Booking</h2>
  <form method="post">
    <label>Client</label><br>
    <select name="client_id">
      <?php foreach ($clientModel->all() as $c): ?>
        <option value="<?= e($c['client_id']) ?>"><?= e($c['full_name']) ?></option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Service</label><br>
    <select name="service_id">
      <?php foreach ($serviceModel->allActive() as $s): ?>
        <option value="<?= e($s['service_id']) ?>">
          <?= e($s['service_name']) ?> (₱<?= number_format($s['hourly_rate'], 2) ?>/hr)
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Date</label><br>
    <input type="date" name="booking_date" required><br><br>

    <label>Hours</label><br>
    <input type="number" name="hours" min="1" value="1"><br><br>

    <button type="submit" name="create">Create Booking</button>
  </form>

<?php elseif ($page === 'bookings'): ?>

  <h2>Bookings</h2>
  <p><a href="booking_system.php?page=bookings_create">+ Create Booking</a></p>
  <table border="1" cellpadding="8">
    <tr><th>ID</th><th>Client</th><th>Service</th><th>Date</th><th>Hours</th><th>Total</th><th>Status</th><th>Action</th></tr>
    <?php foreach ($bookingModel->all() as $b): ?>
      <tr>
        <td><?= e($b['booking_id']) ?></td>
        <td><?= e($b['client_name']) ?></td>
        <td><?= e($b['service_name']) ?></td>
        <td><?= e($b['booking_date']) ?></td>
        <td><?= e($b['hours']) ?></td>
        <td>₱<?= number_format($b['total_cost'], 2) ?></td>
        <td><?= e($b['status']) ?></td>
        <td><a href="pages/payment_process.php?booking_id=<?= e($b['booking_id']) ?>">Process Payment</a></td>
      </tr>
    <?php endforeach; ?>
  </table>

<?php else: ?>
  <p>Page not found.</p>
<?php endif; ?>

</body>
</html>
