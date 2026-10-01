<?php
require_once __DIR__ . "/Database.php";

class Service
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all(): array
    {
        $res = $this->db->query("SELECT * FROM services ORDER BY service_id DESC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function allActive(): array
    {
        $res = $this->db->query("SELECT * FROM services WHERE is_active=1 ORDER BY service_name ASC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE service_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function update(int $id, string $name, string $desc, float $rate, int $active): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE services SET service_name=?, description=?, hourly_rate=?, is_active=? WHERE service_id=?"
        );
        $stmt->bind_param("ssdii", $name, $desc, $rate, $active, $id);
        return $stmt->execute();
    }
}
