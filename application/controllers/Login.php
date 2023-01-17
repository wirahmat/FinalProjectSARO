<?php
class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
    public function index()
	{
		$this->load->view('pages/login');
	}
	public function login_action(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$where = array(
			'username' => $username,
			'password' => md5($password)
			);
		$this->load->model('Login_model');
		$check_login = $this->Login_model->login_check("admin",$where)->num_rows();
		if($check_login > 0){
 
			$data_session = array(
				'nama' => $username,
				'status' => "login"
				);
			$this->session->set_userdata($data_session);
 
			redirect(base_url("admin"));
 
		}else{
			echo "Username and/or password is wrong!";
		}
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}
}