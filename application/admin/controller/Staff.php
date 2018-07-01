<?php
namespace app\admin\controller;

use think\Controller;


class Staff extends Controller
{
    /***
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */

    const TYPE = array(
        0 => '管理员',
        1 => '员工',
        2 => '普通用户'
    );

    const STATUS = array(
        0 => '禁止登陆',
        1 => '正常'
    );
    public function index()
    {
        $userModel = new \app\admin\model\Users();
        $userRows = $userModel->field('id,nick_name,type,name,status')->where(array('status'=>1,'is_del'=>0))->select();

        if(empty($userRows)){
            return json_encode(['code'=>201,'msg'=>'this is empty','data'=>[]]);
        }

        foreach ($userRows as $rows){
           $rows = $rows->toArray();
            $rows['rotula'] = self::TYPE[$rows['type']];
            $rows['status_'] = self::STATUS[$rows['status']];
           $info[] = $rows;
        }
        $this->assign('list',$info);
       return $this->fetch('index');
	}


	/**
     * 新增员工
     */
	public function add()
    {
        return $this->fetch('add');
    }

    /**
     * 新增员工操作
     */
    public function addAction()
    {
       
     //   var_dump($_POST);exit;
       if(!empty($_POST['name'])){
         $data['nick_name'] = $_POST['name'];
       }else{
         return '昵称不能为空';
       }

       if(!empty($_POST['access'])){
           $data['name'] = $_POST['access'];
       }else{
           return '登陆账号不能为空';
       }
       
       if(!empty($_POST['pass'])){
         $data['password'] = md5($_POST['pass']);
       }else{
           return '密码不能为空';
       }
       
       if(!empty($_POST['rotula'])){
          $data['type'] = $_POST['rotula'];
       }else{
           $data['type'] = 1;
       }
      
       $data['status'] = $_POST['status'];
       $data['create_at'] = Date('Y-m-d H:i:s');
      
       $userModel = new \app\admin\model\Users();
       $resultRow = $userModel->create($data);
       if($resultRow){
            $this->success('新增成功', 'index');
       }else{
           $this->error('新增失败', 'add');
       }
    }

    /**
     * 修改员工信息
     *
     */
    public  function  edit()
    {
        
        if(empty($_GET['id'])){
              return '没有该员工的编号，请刷新重新查找';
        }
        $userModel = new \app\admin\model\Users();
        $resultRow = $userModel->get($_GET['id']);
        $this->assign('userInfo',$resultRow);
        return $this->fetch('edit');
    }
    
    
    /**
     * 修改用户信息
     */
    public function editAction()
    {
      if(!empty($_POST['name'])){
         $data['nick_name'] = $_POST['name'];
       }else{
         return '昵称不能为空';
       }

       if(!empty($_POST['access'])){
           $data['name'] = $_POST['access'];
       }else{
           return '登陆账号不能为空';
       }
       
       if(!empty($_POST['pass'])){
         $data['password'] = md5($_POST['pass']);
       }else{
           return '密码不能为空';
       }
       
       if(!empty($_POST['rotula'])){
          $data['type'] = $_POST['rotula'];
       }else{
           $data['type'] = 1;
       }
        
        $data['status'] = $_POST['status'];
        $data['create_at'] = Date('Y-m-d H:i:s ');
         
        $userModel = new \app\admin\model\Users();
        $resultRow = $userModel->where(array('id'=>$_POST['id']))->update($data);
        if($resultRow){
            $this->success('修改成功', 'index');
        }else{
            $this->error('修改失败', 'index');
        }

    }
    /**
     * 删除用户
     */
    public function delUserAction()
    {
        if(!empty($_POST['id'])){
            $userModel = new \app\admin\model\Users();
            $resultRow = $userModel->where(array('id'=>$_POST['id']))->update(array('is_del'=>1));
            if($resultRow){
                return true;
            }else{
                return 0;
            }
         
            
        }else{
            return '没有该员工的编号，请刷新重新查找';
        }
    }
}