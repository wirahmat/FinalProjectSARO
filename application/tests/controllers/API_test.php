<?php
class API_test extends TestCase
{
    public function setUp() {
        $_SESSION['loged_in'] = true;
        $_SESSION['username'] = 'admin';
        $_SESSION['level'] = 'admin';
        $_SESSION['photo']=base_url() . "asset/user.jpg";
        $_SESSION['filter']['tgl_awal'] = '2019/11/27';
        $_SESSION['filter']['tgl_akhir'] = '2019/11/2';
		$_SESSION['tgl_start'] = '2020/04/20';
		$_SESSION['tgl_end'] = '2020/04/20';
		$_SESSION['no_hp'] = '+628122358224';
	//	
    }  
// 	public function test_count_reguser()
//     {	
//         $this->request('GET', ['api', 'count_reguser']);
// 		$this->assertResponseCode(200);
//     }
	public function test_cont_datascan()
    {	
        $this->request('GET', ['api', 'cont_datascan']);
		$this->assertResponseCode(200);
    }
	public function test_activeuser_scan()
    {	
        $this->request('GET', ['api', 'activeuser_scan']);
		$this->assertResponseCode(200);
    }
	public function test_unique_scanned_dev()
    {	
        $this->request('GET', ['api', 'unique_scanned_dev']);
		$this->assertResponseCode(200);
    }
	public function test_total_user()
    {	
        $this->request('GET', ['api', 'total_user']);
		$this->assertResponseCode(200);
    }
	public function test_user_status()
    {	
        $this->request('GET', ['api', 'user_status']);
		$this->assertResponseCode(200);
    }
// 	public function test_table_statusUser()
//     {	
//         $this->request('GET', ['api', 'table_statusUser']);
// 		$this->assertResponseCode(200);
//     }
// 	public function test_status_PDP()
//     {	
//         $this->request('GET', ['api', 'status_PDP']);
// 		$this->assertResponseCode(200);
//     }
// 	public function test_status_ODP()
//     {	
//         $this->request('GET', ['api', 'status_ODP']);
// 		$this->assertResponseCode(200);
//     }
// 	public function test_status_POSITIVE()
//     {	
//         $this->request('GET', ['api', 'status_POSITIVE']);
// 		$this->assertResponseCode(200);
//     }
// 	public function test_status_NPOSITIVE()
//     {	
//         $this->request('GET', ['api', 'status_NPOSITIVE']);
// 		$this->assertResponseCode(200);
//     }
	//public function test_Api2()
    //{	
    //    $this->request('POST', ['api2', 'getLinkNode'],['tgl_start'=>'2020/04/20','tgl_end'=>'2020/04/20','no_hp'=> '+628122358224']);
	//	$this->assertResponseCode(200);
    //}
	
}