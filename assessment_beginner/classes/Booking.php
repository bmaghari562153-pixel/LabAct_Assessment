<?php
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/Service.php";

class Booking
{
    private mysqli $db;
    private Service $services;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->services = new Service();
    }

    public function all(): array
    {
        $sql = "SELECT b.*, c.full_name AS client_name, s.service_name
                FROM bookings b
                JOIN clients c ON b.client_id = c.client_id
                JOIN services s ON b.service_id = s.service_id
                ORDER BY b.booking_id DESC";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function create(int $clientId, int $serviceId, string $date, int $hours): bool
    {
        $service = $this->services->find($serviceId);
        if (!$service) {
            return false;
        }

        $rate  = (float)$service['hourly_rate'];
        $total = $rate * $hours;
        $status = "PENDING";

        $stmt = $this->db->prepare(
            "INSERT INTO bookings (client_id, service_id, booking_date, hours, hourly_rate_snapshot, total_cost, status)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("iisidds", $clientId, $serviceId, $date, $hours, $rate, $total, $status);
        return $stmt->execute();
    }
}
