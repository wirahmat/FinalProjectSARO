<?php
class Forminputkhusus_test extends TestCase
{
    public function setUp() {
        $_SESSION['loged_in'] = true;
        $_SESSION['username'] = 'admin';
        $_SESSION['level'] = 'admin';
        $_SESSION['photo']=base_url() . "asset/user.jpg";
        $_SESSION['filter']['tgl_awal'] = '2019/11/27';
        $_SESSION['filter']['tgl_akhir'] = '2019/11/2';
	//	
    }  
	public function test_get_nohp()
    {	
        $this->request('GET', ['forminputkhusus', 'get_nohp'],['mobile_number'=>'+6281515888872']);
		$this->assertResponseCode(200);
    }
	public function test_cek_tabelUpdate()
    {	
        $this->request('GET', ['forminputkhusus', 'cek_tabelUpdate'],['mobile_number'=>'+6281278166435']);
		$this->assertResponseCode(200);
    }
	//public function test_update()
    //{	
    //    $this->request('GET', ['forminputkhusus', 'update']);
	//	$this->assertResponseCode(200);
    //}
     public function test_get_dataTable()
    {	
        $this->request('GET', ['forminputkhusus', 'get_dataTable']);
		$this->assertResponseCode(200);
    }
    
}
