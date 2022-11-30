<?php
class Trackinguser_test extends TestCase
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
	public function test_get_profile()
    {	
        $this->request('GET', ['trackinguser', 'get_profile']);
		$this->assertResponseCode(200);
    }
	public function test_get_data_table()
    {	
        $this->request('GET', ['trackinguser', 'get_data_table']);
		$this->assertResponseCode(200);
    }
	public function test_get_kelurahan()
    {	
        $this->request('GET', ['trackinguser', 'get_kelurahan']);
		$this->assertResponseCode(200);
    }
    public function test_get_zona()
    {	
        $this->request('GET', ['trackinguser', 'get_zona']);
		$this->assertResponseCode(200);
    }
    public function test_get_latlong()
    {	
        $this->request('GET', ['trackinguser', 'get_latlong']);
		$this->assertResponseCode(200);
    }
}
