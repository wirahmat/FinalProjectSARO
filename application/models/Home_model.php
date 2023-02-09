<?php
class Home_model extends CI_Model
{
    public function get_data_report($start_date, $end_date)
    {
        if($start_date != "" && $end_date != ""){
            $sql = "SELECT subdistrict, COUNT(report_id) as total FROM reports WHERE (input_date BETWEEN '$start_date' AND '$end_date') AND validation = 'confirm' GROUP BY subdistrict ORDER BY total DESC;";
        }
        else{
            $sql = "SELECT subdistrict, COUNT(report_id) as total FROM reports WHERE validation = 'confirm'GROUP BY subdistrict ORDER BY total DESC;";
        }
        $query = $this->db->query($sql);
        // $this->db->select("*");
        // $this->db->from("reports");
        return $query->result_array();
    }
    public function get_data_report_sub($start_date, $end_date){
        if($start_date != "" && $end_date != ""){
            $sql = "SELECT crime_name, COUNT(report_id) as total FROM reports WHERE (input_date BETWEEN '$start_date' AND '$end_date') AND validation = 'confirm' GROUP BY crime_name ORDER BY total DESC;";
        }
        else{
            $sql = "SELECT crime_name, COUNT(report_id) as total FROM reports WHERE validation = 'confirm' GROUP BY crime_name ORDER BY total DESC;";
        }
        $query = $this->db->query($sql);
        // $this->db->select("*");
        // $this->db->from("reports");
        return $query->result_array();
    }
    public function get_detail_data_report($subdistrict, $start_date, $end_date)
    {
        if($start_date != "" && $end_date != ""){
            $sql = "SELECT subdistrict, COUNT(report_id) as total, crime_name FROM reports WHERE subdistrict = '$subdistrict' AND (input_date BETWEEN '$start_date' AND '$end_date') AND validation = 'confirm' GROUP BY crime_name ORDER BY total DESC;";
        }
        else{
            $sql = "SELECT subdistrict, COUNT(report_id) as total, crime_name FROM reports WHERE subdistrict = '$subdistrict' AND validation = 'confirm' GROUP BY crime_name ORDER BY total DESC;";
        }
        $query = $this->db->query($sql);
        // $this->db->select("*");
        // $this->db->from("reports");
        return $query->result_array();
    }
    public function get_detail_data_report_crime_name($crimeName, $start_date, $end_date)
    {
        if($start_date != "" && $end_date != ""){
            $sql = "SELECT subdistrict, COUNT(report_id) as total, crime_name FROM reports WHERE crime_name = '$crimeName' AND (input_date BETWEEN '$start_date' AND '$end_date') AND validation = 'confirm' GROUP BY subdistrict ORDER BY total DESC;";
        }
        else{
            $sql = "SELECT subdistrict, COUNT(report_id) as total, crime_name FROM reports WHERE crime_name = '$crimeName' AND validation = 'confirm' GROUP BY subdistrict ORDER BY total DESC;";
        }
        $query = $this->db->query($sql);
        // $this->db->select("*");
        // $this->db->from("reports");
        return $query->result_array();
    }
    public function get_subdistrict($village, $postal_code){
        $this->db->select("kecamatan");
        $this->db->from("sub_district_detail");
        $this->db->like("desa", $village);
        $this->db->or_like("kodePos", $postal_code);
        $data = $this->db->get();
        return $data->result_array();
    }
    public function get_alert_subdistrict($village, $postal_code){
        $this->db->select("kecamatan");
        $this->db->from("sub_district_detail");
        if($village != ""){
            $this->db->like("desa", $village);
        }
        else if($postal_code != ""){
            $this->db->like("kodePos", $postal_code);
        }
        else if($village != "" && $postal_code != ""){
            $this->db->like("desa", $village);
            $this->db->like("kodePos", $postal_code);
        }
        $data = $this->db->get();
        return $data->result_array();
    }
    public function add_report($data){
        // $query = $this->db->insert('logistik',$data);
        $hasil = $this
            ->db
            ->query("INSERT into reports(crime_name, description_crime, latitude_pos, longitude_pos, accuracy, subdistrict, file_name, input_date) 
            values (?,?,?,?,?,?,?,?);", array(
            $data['name'],
            $data['description'],
            $data['latitude_pos'],
            $data['longitude_pos'],
            $data['accuracy'],
            $data['subdistrict'],
            $data['photo'],
            $data['date'],
        ));
        if (!$hasil)
        {
            return $error = $this
                ->db
                ->error();
        }
        else
        {
            return $hasil;
        }
    }
    public function get_report_points($varA, $varB, $start_date, $end_date){
        if($start_date != "" && $end_date != ""){
            $sql = "SELECT crime_name, description_crime, latitude_pos, longitude_pos, subdistrict, file_name, input_date FROM reports WHERE ((subdistrict = '$varA' AND crime_name = '$varB') OR (subdistrict = '$varB' AND crime_name = '$varA')) AND (input_date BETWEEN '$start_date' AND '$end_date') AND validation = 'confirm';";
        }
        else{
            $sql = "SELECT crime_name, description_crime, latitude_pos, longitude_pos, subdistrict, file_name, input_date FROM reports WHERE ((subdistrict = '$varA' AND crime_name = '$varB') OR (subdistrict = '$varB' AND crime_name = '$varA')) AND validation = 'confirm';";
        }
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_all_report_points($action, $start_date, $end_date){
        if($start_date != "" && $end_date != ""){
            $sql = "SELECT crime_name, description_crime, latitude_pos, longitude_pos, subdistrict, file_name, input_date FROM reports WHERE ((subdistrict = '$action' AND crime_name <> '$action') OR (subdistrict <> '$action' AND crime_name = '$action')) AND (input_date BETWEEN '$start_date' AND '$end_date') AND validation = 'confirm';";
        }
        else{
            $sql = "SELECT crime_name, description_crime, latitude_pos, longitude_pos, subdistrict, file_name, input_date FROM reports WHERE ((subdistrict = '$action' AND crime_name <> '$action') OR (subdistrict <> '$action' AND crime_name = '$action')) AND validation = 'confirm';";
        }
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_crime(){
        $sql = "SELECT * FROM crime_type";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_latlong_json(){
        $sql = "SELECT accuracy, latitude_pos AS latitude, longitude_pos AS longitude FROM reports WHERE validation = 'confirm'";
        $query = $this->db->query($sql);
        // return json_encode($query->result(), JSON_PRETTY_PRINT);
        return $query->result_array();
    }
}