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
    public function get_data($date_info_start, $date_info_end)
    {
        if($date_info_start != "" && $date_info_end != ""){
            $sql = "SELECT subdistrict, COUNT(report_id) as total FROM reports WHERE (input_date BETWEEN '$date_info_start' AND '$date_info_end') AND validation = 'confirm' GROUP BY subdistrict ORDER BY total DESC;";
        }
        else{
            $sql = "SELECT subdistrict, COUNT(report_id) as total FROM reports WHERE validation = 'confirm'GROUP BY subdistrict ORDER BY total DESC;";
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_data_crime($date_info_start, $date_info_end)
    {
        if($date_info_start != "" && $date_info_end != ""){
            $sql = "SELECT crime_name, COUNT(report_id) as total FROM reports WHERE (input_date BETWEEN '$date_info_start' AND '$date_info_end') AND validation = 'confirm' GROUP BY crime_name ORDER BY total DESC;";
        }
        else{
            $sql = "SELECT crime_name, COUNT(report_id) as total FROM reports WHERE validation = 'confirm' GROUP BY crime_name ORDER BY total DESC;";
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_data_validation($date_info_start, $date_info_end){
        if($date_info_start != "" && $date_info_end != ""){
            $sql = "SELECT validation, COUNT(report_id) as total FROM reports WHERE (input_date BETWEEN '$date_info_start' AND '$date_info_end') GROUP BY validation ORDER BY total DESC;";
        }
        else{
            $sql = "SELECT validation, COUNT(report_id) as total FROM reports GROUP BY validation ORDER BY total DESC;";
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}