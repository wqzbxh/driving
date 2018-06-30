<?php
namespace app\admin\controller;

use think\Controller;

class Login extends Controller
{
	
	public function _initialize()
    {
        
    }
	
    public function index()
    {
		if(session('userRow')){
            $this->redirect('index/index');
        }else{
            return $this->fetch('login/login');
        }
        
    }
	
	
	public function loginAction()
    {
        $returnArray = array();
		if(!empty($_POST['username'])){
			$username = $_POST['username'];
		}else{
			$returnArray  = array(
				'code' => 0 ,
				'msg' => '用户名必填',
				'data' => array()
			);
		}
		if(!empty($_POST['password'])){
			$password = MD5($_POST['password']);
			//var_dump($password);exit;
		}else{
			$returnArray  = array(
				'code' => 0 ,
				'msg' => '密码必填',
				'data' => array()
			);
		}
		
		if(empty($returnArray)){
			$userModel = new \app\admin\model\Users();
			$result = $userModel->get(array('name'=>$username,'password'=>$password));
			if($result){
				$userRow =  $result->toArray();
				session('userRow',$userRow);
				$this->redirect('index/index');
			}else{
				echo "<script>alert('账号密码错误')</script>";
				$this->redirect('index');
			}
			
		}
		
    }
	/**
	**登出
	**/
	public function Logout()
	{
	    session('userRow',null);
		$this->redirect('index');
	
	}
}
