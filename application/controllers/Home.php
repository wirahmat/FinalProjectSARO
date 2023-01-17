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
        // read file
        $data2 = file_get_contents('./asset/geopackage/oriWithVal/gadm36_IDN_3.geojson');
        // decode json to array
        $json_arr = json_decode($data2, true);
        
        $start_date = '';
        $end_date = '';

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
        $accuracy = $this->input->post('accuracy');
        $description = $this->input->post('description');
        $village = $this->input->post('village');
        $postal_code = $this->input->post('postal_code');
          
        date_default_timezone_set('Asia/Jakarta');
		$date = date("Y-m-d h:i:s", time());

        // // read file
        // $data = file_get_contents('./asset/geopackage/oriWithVal/gadm36_IDN_3.geojson');
        // // decode json to array
        // $json_arr = json_decode($data, true);

        $this->load->model('home_model');
        $subdistrict = $this->home_model->get_subdistrict($village, $postal_code);
        // $data2 = $this->home_model->get_data_report();

        $data = [          
            'name' => $name,
            'latitude_pos' => $location_fix[0],
            'longitude_pos' => $location_fix[1],
            'accuracy' => $accuracy,
            'description' => $description,
            'subdistrict' => $subdistrict[0],
            'photo' => $file1['upload_data1']['file_name'],
			'date' => $date
        ];
        $res = $this->home_model->add_report($data);
        $data3 = $this->home_model->get_data_report($start_date, $end_date);
        if (count($data3) > 0)
        {
            foreach($data3 as $row)
            {
                for ($x = 0; $x <= 29; $x++) {
                    if ($json_arr["features"][$x]["properties"]["NAME_3"] == $row["subdistrict"]){
                        $json_arr["features"][$x]["properties"]["CRIME_VAL"] = $row["total"] ;
                    }
                }
            }
            // encode array to json and save to file
            file_put_contents('./asset/geopackage/gadm36_IDN_3.geojson', json_encode($json_arr));
        }
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
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        }     
        // // read file
        // $data2 = file_get_contents('./asset/geopackage/oriWithVal/gadm36_IDN_3.geojson');
        // // decode json to array
        // $json_arr = json_decode($data2, true);

		$output = "<canvas id='myChart'></canvas>
        <h2>Statistic</h1>
        <div class='btn-group dropright'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Filter by Date</a>
                    <div class='dropdown-menu'>
                        <a onclick='showInputDate(\"0\", \"SubdistrictReport\")'>Reset</a><br>
                        <a onclick='showInputDate(\"1\", \"SubdistrictReport\")'>1 Month</a><br>
                        <a onclick='showInputDate(\"6\", \"SubdistrictReport\")'>6 Months</a><br>
                        <a onclick='showInputDate(\"12\", \"SubdistrictReport\")'>12 Months</a><br>
                        <a onclick='showInputDate(\"custom\", \"SubdistrictReport\")'>Custom Input</a>
                    </div>
                </div>
                <input type='date' id='starting_date' name='starting_date'>
                <input type='date' id='end_date' name='end_date'>
            <table>
                <thead>
                    <tr>
                        <th>Kecamatan</th>
                        <th>Total Cases</th>
                    </tr>
                </thead>
                <tbody>";
        $this->load->model('home_model');
        $data = $this->home_model->get_data_report($start_date, $end_date);
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
                // for ($x = 0; $x <= 29; $x++) {
                //     if ($json_arr["features"][$x]["properties"]["NAME_3"] == $row["subdistrict"]){
                //         $json_arr["features"][$x]["properties"]["CRIME_VAL"] = $row["total"] ;
                //     }
                //     // $output .= "<h1>".var_export($json_arr["features"][$x]["properties"]["NAME_3"])."</h1>";
                //     // $output .= "<h1>".var_export($json_arr["features"][$x]["properties"]["CRIME_VAL"])."</h1>";
                // }
                // foreach ($json_arr as $key => $value) {
                //     if ($value[features][1].properties.NAME_3 $value['properties']['NAME_3'] == $row["subdistrict"] ) {
                //         $json_arr[$key]['properties']['CRIME_VAL'] = $row["total"];
                //     }
                // }
                $output .= "
                        <tr>
                            <td><a data-toggle='modal' data-target='#statistic_detail' onclick='getDetailReport(\"".$row["subdistrict"]."\",\"".$start_date."\", \"".$end_date."\")'>".$row["subdistrict"]."</a></th>
                            <td><a data-toggle='modal' data-target='#statistic_detail'>".$row["total"]."</a></th>";
            }
            // encode array to json and save to file
            // file_put_contents('./asset/geopackage/gadm36_IDN_3.geojson', json_encode($json_arr));
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
    public function get_data_report_persub(){
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        }     
        // $output = "<canvas id='myChart'></canvas>
        $output = "<canvas id='myChart'></canvas>
        <h2>Statistic</h1>
        <div class='btn-group dropright'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Filter by Date</a>
                    <div class='dropdown-menu'>
                        <a onclick='showInputDate(\"0\", \"CrimeNameReport\")'>Reset</a><br>
                        <a onclick='showInputDate(\"1\", \"CrimeNameReport\")'>1 Month</a><br>
                        <a onclick='showInputDate(\"6\", \"CrimeNameReport\")'>6 Months</a><br>
                        <a onclick='showInputDate(\"12\", \"CrimeNameReport\")'>12 Months</a><br>
                        <a onclick='showInputDate(\"custom\", \"CrimeNameReport\")'>Custom Input</a>
                    </div>
                </div>
                <input type='date' id='starting_date' class='date_filter' name='starting_date'>
                <input type='date' id='end_date' class='date_filter' name='end_date'>
            <table>
                <thead>
                    <tr>
                        <th>Crime Name</th>
                        <th>Total Cases</th>
                    </tr>
                </thead>
                <tbody>";
        $this->load->model('home_model');
        $data = $this->home_model->get_data_report_sub($start_date, $end_date);
        if (count($data) > 0)
        {
            foreach($data as $row)
            {
                // for ($x = 0; $x <= 29; $x++) {
                //     if ($json_arr["features"][$x]["properties"]["NAME_3"] == $row["subdistrict"]){
                //         $json_arr["features"][$x]["properties"]["CRIME_VAL"] = $row["total"] ;
                //     }
                // }
                $output .= "
                        <tr>
                            <td><a data-toggle='modal' data-target='#statistic_detail' onclick='getDetailReportCrimeName(\"".$row["crime_name"]."\",\"".$start_date."\", \"".$end_date."\")'>".$row["crime_name"]."</a></th>
                            <td><a data-toggle='modal' data-target='#statistic_detail'>".$row["total"]."</a></th>";
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

    public function get_data_chart_crime_name(){
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        }   
        $this->load->model('home_model');
        $data = $this->home_model->get_data_report_sub($start_date, $end_date);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function get_data_chart(){
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        }     
        $this->load->model('home_model');
        $data = $this->home_model->get_data_report($start_date, $end_date);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function get_detail_data_report()
    {
		$output = "";
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        }   
        $subdistrict = $this->input->post('subdistrict');
        $this->load->model('home_model');
        $data = $this->home_model->get_detail_data_report($subdistrict, $start_date, $end_date);

        $output .= "<a data-toggle='modal' data-target='#statistic' onclick='load_data(\"".$start_date."\", \"".$end_date."\")'> < Back to Statistic</a>
				<canvas id='detailedChart'></canvas>
                <a onclick='getPointReportAll(\"".$subdistrict."\",\"".$start_date."\", \"".$end_date."\")'>View All Data</a>
                <input type='date' id='starting_date' class='date_filter' name='starting_date'>
                <input type='date' id='end_date' class='date_filter' name='end_date'>
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
                $output .= "
                    <tr>
                        <td><a data-toggle='modal' data-target='#statistic_detail' onclick='getPointReport(\"".$subdistrict."\",\"".$row['crime_name']."\",\"".$start_date."\", \"".$end_date."\")'>".$row['crime_name']."</td>
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

    public function get_detail_data_report_crime_name(){
        $output = "";
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        } 
        $crimeName = $this->input->post('crimeName');
        $this->load->model('home_model');
        $data = $this->home_model->get_detail_data_report_crime_name($crimeName, $start_date, $end_date);
		// foreach ($data as $key => $value) {
		// 	foreach(array_count_values($value) as $k => $v){
		// 	  $data2[$key][$k] = $v;
		// 	}
		// }
		// echo "<script>console.log('Debug Objects: " . $data . "' );</script>";
        $output .= "<a data-toggle='modal' data-target='#statistic' onclick='load_data_crime_name(\"".$start_date."\", \"".$end_date."\")'> < Back to Statistic</a>
                <canvas id='detailedChart'></canvas>
                <a onclick='getPointReportAll(\"".$crimeName."\",\"".$start_date."\", \"".$end_date."\")'>View All Data</a>
                <input type='date' id='starting_date' class='date_filter' name='starting_date'>
                <input type='date' id='end_date' class='date_filter' name='end_date'>
                <h2>".$crimeName."</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Subdistrict</th>
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
                        <td><a data-toggle='modal' data-target='#statistic_detail' onclick='getPointReport(\"".$crimeName."\",\"".$row['subdistrict']."\",\"".$start_date."\", \"".$end_date."\")'>".$row['subdistrict']."</td>
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

    public function get_all_report_points(){
        $action = $this->input->post('action');
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        }     
        $this->load->model('home_model');
        $data = $this->home_model->get_all_report_points($action, $start_date, $end_date);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function get_detail_data_chart(){
        $subdistrict = $this->input->post('subdistrict');
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        }     
        $this->load->model('home_model');
        $data = $this->home_model->get_detail_data_report($subdistrict, $start_date, $end_date);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function get_detail_data_chart_crime_name(){
        $crimeName = $this->input->post('crimeName');
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        } 
        $this->load->model('home_model');
        $data = $this->home_model->get_detail_data_report_crime_name($crimeName, $start_date, $end_date);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function get_report_points()
    {
        $varA = $this->input->post('varA');
        $varB = $this->input->post('varB');
        $start_date = '';
        $end_date = '';
        if($this->input->post('startDate'))
        {
            $start_date = $this->input->post('startDate');
        }
        if($this->input->post('endDate'))
        {
            $end_date = $this->input->post('endDate');
        }     
        $this->load->model('home_model');
        $data = $this->home_model->get_report_points($varA, $varB, $start_date, $end_date);
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
    public function get_user_subdistrict()
    {
        $village = $this->input->post('village');
        $postal_code = $this->input->post('postal_code');
        $this->load->model('home_model');
        $data = $this->home_model->get_alert_subdistrict($village, $postal_code);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function get_user_subdistrict_route()
    {
        $village = $this->input->post('village');
        $postal_code = $this->input->post('postal_code');
        $arrdata = [];
        $this->load->model('home_model');
        for ($i = 0; $i < count($village); $i++){
            $data = $this->home_model->get_alert_subdistrict($village[$i], $postal_code[$i]);
            array_push($arrdata, $data);
        }
        echo json_encode($arrdata, JSON_PRETTY_PRINT);
    }
    public function get_report_route(){
        $data_route_sub = $this->input->post('data_route_sub');
        $start_date = '';
        $end_date = '';
        $arrdata = [];
        $this->load->model('home_model');
        for ($i = 0; $i < count($data_route_sub); $i++){
            $data = $this->home_model->get_detail_data_report($data_route_sub[$i], $start_date, $end_date);
            array_push($arrdata, $data);
        }
        echo json_encode($arrdata, JSON_PRETTY_PRINT);

    }
}