<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
	public function _initialize()
    {
		parent::_initialize();
        $this->userId = session('userRow')['id'];
    }
    public function index()
    {
		$userModel = new \app\admin\model\Users();
		$userRow  =  $userModel->get($this->userId);
		if($userRow){
			$userRow = $userRow->toArray();
		}
		$this->assign('row',$userRow);
		return $this->fetch('index');
    }
}
