<?php
class Admin_model extends CI_Model
{
    public function get_all_data_admin()
    {
        $sql = "SELECT * FROM reports ORDER BY report_id;";
        $query = $this->db->query($sql);
        // $this->db->select("*");
        // $this->db->from("reports");
        return $query->result_array();
    }
    public function send_validate_data($id, $action)
    {
        $sql = "UPDATE reports SET validation = '$action' WHERE report_id = $id";
        $query = $this->db->query($sql);
        // $this->db->select("*");
        // $this->db->from("reports");
        // return $query->result_array();
    }
}