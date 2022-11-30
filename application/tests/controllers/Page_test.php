<?php
class Page_test extends TestCase
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
	public function test_page_login()
    {	
        $this->request('POST', ['Page', 'login'],['username'=>'admin','password'=>'admin']);
		$this->assertResponseCode(302);
    }
    public function test_dashboard()
    {
        $this->request('GET', ['page' ,'view', 'dashboard']);
		$this->assertResponseCode(200);
    }
	
	public function test_page_usermanagement()
    {	
		$data= ['admin'=>[1,2,3]];
        $this->request('POST', ['Page', 'user_level'],$data);
		$this->assertResponseCode(302);
    }
	//public function test_page_usermgt()
    //{	
	//	$data= ['dummy'=>[1,2,3]];
    //    $this->request('GET', ['Page', 'user_management']);
	//	$this->assertResponseCode(302);
    //}
	public function test_page_index()
    {	
        $this->request('GET', ['Page', 'index']);
		$this->assertResponseCode(302);
    }
	
}