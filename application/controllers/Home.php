<?php
class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
    public function index()
	{
			$this->load->view('pages/home');
	}
	public function add_report()
    {
        $this->load->helper(array('form', 'url'));
        $config['upload_path']="./asset/upload";
        $config['allowed_types']='gif|jpg|png|jpeg';
        $this->load->library('upload',$config);
        $this->upload->initialize($config);

        $this->upload->do_upload("file1");
        $file1 = array('upload_data1' => $this->upload->data());

        $name = $this->input->post('name');
        $location = $this->input->post('location');
		$location_fix = explode(",",$location);
        $description = $this->input->post('description');
        $village = $this->input->post('village');
        $postal_code = $this->input->post('postal_code');

		$date = date("Y-m-d h:i:s", time());

        // // read file
        // $data = file_get_contents('./asset/geopackage/oriWithVal/gadm36_IDN_3.geojson');
        // // decode json to array
        // $json_arr = json_decode($data, true);

        $this->load->model('home_model');
        $subdistrict = $this->home_model->get_subdistrict($village, $postal_code);
        $data2 = $this->home_model->get_data_report();

        $data = [          
            'name' => $name,
            'latitude_pos' => $location_fix[0],
            'longitude_pos' => $location_fix[1],
            'description' => $description,
            'subdistrict' => $subdistrict[0],
            'photo' => $file1['upload_data1']['file_name'],
			'date' => $date
        ];
        $res = $this->home_model->add_report($data);
        // if (count($data2) > 0){
        //     // read file
        //     $data = file_get_contents('./asset/geopackage/oriWithVal/gadm36_IDN_3.geojson');
        //     // decode json to array
        //     $json_arr = json_decode($data, true);

        //     $output .= "<h1>".var_export($json_arr["features"][0]["properties"]["NAME_3"])."</h1>";
        //     $output .= "<h1>".var_export($json_arr["features"][0]["properties"]["CRIME_VAL"])."</h1>";

        //     foreach ($json_arr as $key => $value) {
        //         if ($value['Code'] == '2') {
        //             $json_arr[$key]['Sports'] = "Foot Ball";
        //         }
        //     }
            
        //     // encode array to json and save to file
        //     file_put_contents('./asset/geopackage/gadm36_IDN_3.geojson', json_encode($json_arr));
        // }  
        echo json_encode($res);
    }
	public function get_data_report()
    {
        // read file
        $data = file_get_contents('./asset/geopackage/oriWithVal/gadm36_IDN_3.geojson');
        // decode json to array
        $json_arr = json_decode($data, true);

		$output = "";
        $this->load->model('home_model');
        $data = $this->home_model->get_data_report();
		// foreach ($data as $key => $value) {
		// 	foreach(array_count_values($value) as $k => $v){
		// 	  $data2[$key][$k] = $v;
		// 	}
		// }
		// echo "<script>console.log('Debug Objects: " . $data . "' );</script>";
        if (count($data) > 0)
        {
            // $output .= "<h1>".var_export($json_arr["features"][0]["properties"]["NAME_3"])."</h1>";
            // $output .= "<h1>".var_export($json_arr["features"][0]["properties"]["CRIME_VAL"])."</h1>";
            // foreach ($json_arr as $key => $value) {
            //     $output .= "<h1>".var_export($value[0])."</h1>";
            //     // if ($key["features"][0]["properties"]["NAME_3"] == 'Banyusari') {
            //     //     $json_arr["features"][0]["properties"]["CRIME_VAL"] = 0;
            //     // }
            // }
            // $output .= "<h1>".var_export($json_arr["features"][0]["properties"]["CRIME_VAL"])."</h1>";
            foreach($data as $row)
            {
                for ($x = 0; $x <= 29; $x++) {
                    if ($json_arr["features"][$x]["properties"]["NAME_3"] == $row["subdistrict"]){
                        $json_arr["features"][$x]["properties"]["CRIME_VAL"] = $row["total"] ;
                    }
                    // $output .= "<h1>".var_export($json_arr["features"][$x]["properties"]["NAME_3"])."</h1>";
                    // $output .= "<h1>".var_export($json_arr["features"][$x]["properties"]["CRIME_VAL"])."</h1>";
                }
                // foreach ($json_arr as $key => $value) {
                //     if ($value[features][1].properties.NAME_3 $value['properties']['NAME_3'] == $row["subdistrict"] ) {
                //         $json_arr[$key]['properties']['CRIME_VAL'] = $row["total"];
                //     }
                // }
                $output .= "
                        <tr>
                            <td><a data-toggle='modal' data-target='#statistic_detail' onclick='getDetailReport(\"".$row["subdistrict"]."\")'>".$row["subdistrict"]."</a></th>
                            <td><a data-toggle='modal' data-target='#statistic_detail'>".$row["total"]."</a></th>";
            }
            // encode array to json and save to file
            file_put_contents('./asset/geopackage/gadm36_IDN_3.geojson', json_encode($json_arr));
        }
        else
        {
            $output .= '<tr>
                <td style="text-align: center" colspan="2">No Data Found</td>
                </tr>';
        }
        $output .= '</tbody>';

        echo $output;        
    }

    public function get_detail_data_report()
    {
		$output = "";
        $subdistrict = $this->input->post('subdistrict');
        $this->load->model('home_model');
        $data = $this->home_model->get_detail_data_report($subdistrict);
		// foreach ($data as $key => $value) {
		// 	foreach(array_count_values($value) as $k => $v){
		// 	  $data2[$key][$k] = $v;
		// 	}
		// }
		// echo "<script>console.log('Debug Objects: " . $data . "' );</script>";
        $output .= "<a data-toggle='modal' data-target='#statistic' onclick='load_data()'>Back to Statistic</a>
                <h2>".$subdistrict."</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Crime Type</th>
                            <th>Total Cases</th>
                        </tr>
                    </thead> 
                    <tbody>";
        if (count($data) > 0)
        {
            foreach($data as $row)
            {
                // $id = $row->id_sales;
                $output .= "
                    <tr>
                        <td><a data-toggle='modal' data-target='#statistic_detail' onclick='getPointReport(\"".$subdistrict."\",\"".$row['crime_name']."\")'>".$row['crime_name']."</td>
                        <td>".$row['total']."</td>
                    </tr>";
            }
        }
        else
        {
            $output .= '<tr>
                <td style="text-align: center" colspan="2">No Data Found</td>
                </tr>';
        }
        $output .= '</tbody></table>';
        echo $output;
    }
    public function get_report_points()
    {
        $subdistrict = $this->input->post('subdistrict');
        $crime_name = $this->input->post('crime_name');
        $this->load->model('home_model');
        $data = $this->home_model->get_report_points($subdistrict, $crime_name);
        echo json_encode($data);
    }
    public function get_crime()
    {
        $output = "<select class='input-data' name='type_of_crime' id='type_of_crime'>
        <option value='' disabled selected>Select Crime Type</option>";
        $this->load->model('home_model');
        $data = $this->home_model->get_crime();
        if (count($data) > 0)
        {
            foreach($data as $row)
            {
                $output .= "<option value='".$row['crime_name']."'>".$row['crime_name']."</option>";
            }
            $output .= "</select>";
        }
        echo $output;
    }
    public function get_latlong_json()
    {
        $jsonArray = [];
        $this->load->model('home_model');
        $data = $this->home_model->get_latlong_json();
        if (count($data) > 0)
        {
            foreach($data as $row)
            {
                $row['accuracy'] = intval($row['accuracy']);
                $row['latitude'] = floatval($row['latitude']);
                $row['longitude'] = floatval($row['longitude']);
                // array_push($jsonArray, $row);
                array_push($jsonArray, array("location"=>($row)));
            }
        }
        // echo $data;
        echo json_encode($jsonArray, JSON_PRETTY_PRINT);
    }
}