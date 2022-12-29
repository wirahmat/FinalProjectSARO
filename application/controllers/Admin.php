<?php
class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
    public function index()
	{
			$this->load->view('pages/admin');
	}
	public function get_all_data_admin(){
		 $output = "<thead class='thead-dark'>
		 <tr>
			 <th>#</th>
			 <th>Crime Name</th>
			 <th>Description</th>
			 <th>Latitude</th>
			 <th>Longitude</th>
			 <th>Subdistrict</th>
			 <th>Image (Click to See Full Image)</th>
			 <th>Input Date</th>
			 <th>Validation</th>
			 <th>Action</th>
		 </tr>
		 </thead>";
		 $outputvalid = "";
		 $this->load->model('admin_model');
		 $data = $this->admin_model->get_all_data_admin();
		 if (count($data) > 0)
		 {
			foreach($data as $row)
			{
				if($row["validation"] != ""){
					$outputvalid .= "
					<tr>
						<td>".$row["report_id"]."</td>
						<td>".$row["crime_name"]."</td>
						<td>".$row["description_crime"]."</td>
						<td>".$row["latitude_pos"]."</td>
						<td>".$row["longitude_pos"]."</td>
						<td>".$row["subdistrict"]."</td>
						<td><a href='".base_url()."/asset/upload/".$row["file_name"]."' target='_blank'> <img src='".base_url()."/asset/upload/".$row["file_name"]."' alt='".$row["file_name"]."' width='300' height='auto'> </a> </td>
						<td>".$row["input_date"]."</td>
						<td>".$row["validation"]."</td>
						<td>Validated</td>
					</tr>";
				}
				else{
					$output .= "
					<tr>
						<td>".$row["report_id"]."</td>
						<td>".$row["crime_name"]."</td>
						<td>".$row["description_crime"]."</td>
						<td>".$row["latitude_pos"]."</td>
						<td>".$row["longitude_pos"]."</td>
						<td>".$row["subdistrict"]."</td>
						<td><a href='".base_url()."/asset/upload/".$row["file_name"]."' target='_blank'> <img src='".base_url()."/asset/upload/".$row["file_name"]."' alt='".$row["file_name"]."' width='300' height='auto'> </a> </td>
						<td>".$row["input_date"]."</td>
						<td>".$row["validation"]."</td>
						<td>
							<button type='button' class='btn btn-success' onclick='validateData(".$row["report_id"].", \"confirm\")'>Confirm</button>
							<button type='button' class='btn btn-danger' onclick='validateData(".$row["report_id"].", \"reject\")'>Reject</button>
						</td>
					</tr>";
				}
				 
			}
		 }
		 else
        {
            $output .= '<tr>
                <td style="text-align: center" colspan="2">No Data Found</td>
                </tr>';
        }

        $output .= $outputvalid .'</tbody>';
		echo $output;        
	}
	public function send_validate_data(){
		$report_id = $this->input->post('report_id');
		$action = $this->input->post('action');
		$this->load->model('admin_model');
		$data = $this->admin_model->send_validate_data($report_id,$action);
        // echo json_encode($data, JSON_PRETTY_PRINT);
	}
}