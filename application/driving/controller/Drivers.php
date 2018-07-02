<?php
namespace app\driving\controller;


use think\Controller;

class Drivers extends  Controller
{

	public function _initialize()
    {
         
    }
	
    const TYPE = array(
        1 => '驾校',
        2 => '体验站'
    );

    public function index()
    {
		//echo '<h1 style="text-align:center;font-size:88px;">网站维护中……</h1>';exit;
		//var_dump(session('userRow'));exit;
        $info = array();
        $driversMOdel = new \app\admin\model\Drivers();
		if(!empty($_GET['id'])){
			$id = $_GET['id'];
			$postion = $driversMOdel->field('lng,lat')->where(array('is_del'=>0,'is_show'=>1,'id'=>$id))->select();
			if($postion){
				$postion = $postion[0]->toArray();
				$lat = $postion['lat'];
				$lng = $postion['lng'];
				$zoom =  18;
			}
			
		}else{
			$lat = 22.855567;
			$lng = 108.4388888;
			$zoom =  13;
		}
        $driversRows = $driversMOdel->field('id,drive_name,link,lng,lat,site,phone,type')->where(array('is_del'=>0,'is_show'=>1))->select();
        if($driversRows)
        {
            foreach ($driversRows as $driversRow)
            {
                $driverSchool =  $driversRow->toArray();
                $driverSchool['type_name'] = self::TYPE[$driverSchool['type']];
                $info[] = $driverSchool;
            }
        }


        $this->assign('lat',$lat);
        $this->assign('lng',$lng);
        $this->assign('zoom',$zoom);
        $this->assign('list',$info);
		
        return view('index');
    }


    public function  test(){

        $driversModel = new \app\admin\model\Drivers();
        $order = array('id' => 'desc');
        $info = array();
        $driversRows = array();
        if(!empty($_POST['sear_name'])){
            $search = $_POST['sear_name'];
            $search = trim($search);
			if($search !=''){
			  $driversRows = $driversModel->field('id,drive_name,link,lng,lat,site,phone,type')->where(array('is_del'=>0,'is_show'=>1))->order($order)->where($driversModel->fuzzy_query,'like','%'.$search.'%')->select();	
			}
            // $count = $driversModel->field('id,drive_name,link,lng,lat,site,phone,type')->where(array('is_del'=>0,'is_show'=>1))->order($order)->where($driversModel->fuzzy_query,'like','%'.$search.'%')->count();
        }

        if($driversRows)
        {
            foreach ($driversRows as $driversRow)
            {
                $driverSchool =  $driversRow->toArray();
                $driverSchool['type_name'] = self::TYPE[$driverSchool['type']];
                $info[] = $driverSchool;
            }
        }

      return $info;

    }
	
	public function getInfo()
	{
	   $info = array();
        $driversMOdel = new \app\admin\model\Drivers();
		$driversRows = $driversMOdel->field('id,drive_name,link,lng,lat,site,phone,type,price')->where(array('is_del'=>0,'is_show'=>1))->select();
        if($driversRows)
        {
            foreach ($driversRows as $driversRow)
            {
                $driverSchool =  $driversRow->toArray();
                $driverSchool['type_name'] = self::TYPE[$driverSchool['type']];
				if(empty($driverSchool['link'])){
					$driverSchool['link'] =  "http://seat.gyouth.cn/driving/drivers/linkXq?id=".$driverSchool['id'];
				}
                $info[] = $driverSchool;
               // $info['username'] = session('userRow');
            }
        }
		return array($info, session('userRow')['type']);
	}
	
	
	/**
	*加载第三方地图
	*/
	
	public function getOtherMap()
	{
		if(!empty($_GET['site'])){
		 $this->assign('site',$_GET['site']);
		 return view('getOtherMap');
		}else{
			echo "<script>alert('地点不能为空')</script>";
			$this->redirect('index');
		}
		
		
	}
	
	/**
	*加载第三方地图
	*/
	
	public function linkXq()
	{
		if(empty($_GET['id'])==false){
		 $driversModel = new \app\admin\model\Drivers();
		 $driversRows = $driversModel->get($_GET['id']);
		 $rows = $driversRows->toArray();
		
		 echo '<h1 style="margin-top:20%;text-align:center">南宁驾校统一平台温馨提醒</h1>';
		 echo '<h1 style="margin-top:20%;text-align:center">'.$rows['drive_name'].'暂无详情</h1>';
		 if(empty($rows['phone']) == false){
			echo "<h1 style='text-align:center'>请拨打: <a href='tel:".$rows['phone']."'>".$rows['phone']."</a>,联系工作人员</h1>" ;
		 }
		 
		 //return view('getOtherMap');
		}else{
			echo "<script>alert('地点不能为空')</script>";
			$this->redirect('index');
		}
		
		
	}
}
