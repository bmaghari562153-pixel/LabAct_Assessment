<?php
require_once __DIR__ . "/Database.php";

class Client
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all(): array
    {
        $res = $this->db->query("SELECT * FROM clients ORDER BY full_name ASC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
