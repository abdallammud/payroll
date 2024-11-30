<?php 
class Settings extends Model {
    public function __construct() {
        parent::__construct('sys_settings', 'type');
    }

    /*public function update($type, $data) {
        $set = "";
        $values = [];

        foreach ($data as $key => $value) {
            $set .= "$key = ?, ";
            $values[] = $value;
        }

        $set = rtrim($set, ', ');
        $values[] = $type; // Add the ID as the last parameter
        $types = $this->determineTypes($data) . 'i'; // Assume ID is an integer

        $stmt = $this->db->prepare("UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = ?");
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }*/
}

$GLOBALS['settingsClass']     = $settingsClass = new Settings();