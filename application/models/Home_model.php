<?php
class Home_model extends CI_Model
{
    public function get_data_report()
    {
        $sql = "SELECT subdistrict, COUNT(report_id) as total FROM reports GROUP BY subdistrict ORDER BY total DESC;";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_detail_data_report($subdistrict)
    {
        $sql = "SELECT subdistrict, COUNT(report_id) as total, crime_name FROM reports WHERE subdistrict = '$subdistrict' GROUP BY crime_name ORDER BY total DESC;";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_subdistrict($village, $postal_code){
        $this->db->select("kecamatan");
        $this->db->from("sub_district_detail");
        if($village != "" && $postal_code != ""){
            $this->db->like("desa", $village);
            $this->db->like("kodePos", $postal_code);
        }
        else if($village != ""){
            $this->db->like("desa", $village);
        }
        else if($postal_code != ""){
            $this->db->like("kodePos", $postal_code);
        }
        $data = $this->db->get();
        return $data->result_array();
    }
    public function add_report($data){
        // $query = $this->db->insert('logistik',$data);
        $hasil = $this
            ->db
            ->query("INSERT into reports(crime_name, description_crime, latitude_pos, longitude_pos, subdistrict, file_name, input_date) 
            values (?,?,?,?,?,?,?);", array(
            $data['name'],
            $data['description'],
            $data['latitude_pos'],
            $data['longitude_pos'],
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
    public function get_report_points($subdistrict, $crime_name){
        $sql = "SELECT crime_name, description_crime, latitude_pos, longitude_pos, subdistrict, file_name, input_date FROM reports WHERE subdistrict = '$subdistrict' AND crime_name = '$crime_name';";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_all_report_points($action){
        $sql = "SELECT crime_name, description_crime, latitude_pos, longitude_pos, subdistrict, file_name, input_date FROM reports WHERE subdistrict = '$action';";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_crime(){
        $sql = "SELECT * FROM crime_type";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_latlong_json(){
        $sql = "SELECT accuracy, latitude_pos AS latitude, longitude_pos AS longitude FROM reports";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}