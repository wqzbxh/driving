<?php
namespace app\admin\controller;

use think\Controller;


class Place extends Controller
{
    /***
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */

    const TYPE = array(
        1 => '驾校',
        2 => '体验站'
    );

    public function index()
    {
        $returnArray = array();
		$info = array(); 
        $page = !empty($_GET['page']) ? $_GET['page'] : 0 ;
        $offset = $page * 100;
        $limit = 100 ;

        $order = array('id' => 'DESC');
        $driversModel = new \app\admin\model\Drivers();
        if(isset($_POST['search'])){
            $search = $_POST['search'];
            $driversRows = $driversModel->field('id,drive_name,link,lng,lat,site,phone,type')->where(array('is_del'=>0,'is_show'=>1))->order($order)->where($driversModel->fuzzy_query,'like','%'.$search.'%')->limit($offset,$limit)->select();
            $count = $driversModel->field('id,drive_name,link,lng,lat,site,phone,type')->where(array('is_del'=>0,'is_show'=>1))->order($order)->where($driversModel->fuzzy_query,'like','%'.$search.'%')->count();

        }else{
            $driversRows = $driversModel->field('id,drive_name,link,lng,lat,site,phone,type')->where(array('is_del'=>0,'is_show'=>1))->order($order)->limit($offset,$limit)->select();
            $count = $driversModel->field('id,drive_name,link,lng,lat,site,phone,type')->where(array('is_del'=>0,'is_show'=>1))->count();
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
        //分页处理
        $pages = ceil($count/100);

        if($page < 0 ){
            $page = 0;
        }
		
        $this->assign('page',$pages);
        $this->assign('page_current',$page);
		
        $this->assign('info',$info);

        return $this->fetch('index');
    }


    /**
     * 渲染修改驾校模板
     */

    public function edit()
    {
        if(empty($_GET['id']) == false)
        {
            $driversModel = new \app\admin\model\Drivers();
            $resultRow = $driversModel->get(array('id'=>$_GET['id']));
            if($resultRow)
            {
                $result = $resultRow->toArray();
            }
            $this->assign('row',$result);
            return $this->fetch('edit');
        }else
        {
            $this->error('新增成功', 'index');
        }

    }



    /**
     * 渲染增加驾校模板
     */
    public function add()
    {
        return view('add');
    }



    /**
     * 增加驾校或者体验站的地址
     */
    public function addAction()
    {
        $returnArray =array();
        $driversModel = new \app\admin\model\Drivers();
		$usersModel = new \app\admin\model\Users();
		 $redis = $usersModel->listRedis();

        if($_POST['name']){
            $data['drive_name'] = $_POST['name'];
        }else{
            $returnArray = array(
                 'code' => 100001,
                 'msg' => $driversModel::ERROR_CODE[100001],
                 'data' => array()
            );
        }

        if($_POST['address']){
            $data['site'] = $_POST['address'];
        }else{
            $returnArray = array(
                'code' => 100001,
                'msg' => $driversModel::ERROR_CODE[100001],
                'data' => array()
            );
        }

        if($_POST['telephone']){
            $data['phone'] = $_POST['telephone'];
        }else{
			$data['phone'] ='';
		}

        if($_POST['lng']){
            $data['lng'] = $_POST['lng'];
        }else{
            $returnArray = array(
                'code' => 100001,
                'msg' => $driversModel::ERROR_CODE[100001],
                'data' => array()
            );
        }

        if($_POST['lat']){
            $data['lat'] = $_POST['lat'];
        }else{
            $returnArray = array(
                'code' => 100001,
                'msg' => $driversModel::ERROR_CODE[100001],
                'data' => array()
            );
        }
        if($_POST['link']){
            $data['link'] = $_POST['link'];
        }else{
			$data['link'] = '';
		}

        if($_POST['status']){
            $data['is_show'] = $_POST['status'];
        }else{
			 $data['is_show'] = 1;
		}

        if($_POST['adr_type']){
            $data['type'] = $_POST['adr_type'];
        }else{
			 $data['type'] = 1;
		}


        $data['create_at'] =  date('Y-m-d H:i:s');

        if(empty($returnArray)){
            $result = $driversModel->create($data);
            if($result)
            {
				$info = '';
                $returnArray = array(
                    'code' => 1,
                    'msg' => $driversModel::ERROR_CODE[1],
                    'data' => $result
                );
		
			 $info = $data['drive_name'].'!@#$%'. $data['link'].'!@#$%'. $data['lng'].'!@#$%'. $data['lat'].'!@#$%'. $data['site'].'!@#$%'. $data['phone'].'!@#$%'. $data['type'].'!@#$%'. $result['id'];
			 $redis->LPUSH('seatList',$info); 		
                $this->success('新增成功', 'index');
            }else
                {
                    $this->error('新增失败');
                }
        }else{
            $this->error('新增失败');
        }

    }
/**
	**
	**修改
	*/
 public function editAction()
    {
        $returnArray =array();
        $driversModel = new \app\admin\model\Drivers();
		//echo phpinfo();
		
		if(!empty($_POST['id'])){
			$id = $_POST['id'];
		}else{
			 $returnArray = array(
                 'code' => 100001,
                 'msg' => $driversModel::ERROR_CODE[100001],
                 'data' => array()
            );
		}
        if($_POST['name']){
            $data['drive_name'] = $_POST['name'];
        }else{
            $returnArray = array(
                 'code' => 100001,
                 'msg' => $driversModel::ERROR_CODE[100001],
                 'data' => array()
            );
        }

        if($_POST['address']){
            $data['site'] = $_POST['address'];
        }else{
            $returnArray = array(
                'code' => 100001,
                'msg' => $driversModel::ERROR_CODE[100001],
                'data' => array()
            );
        }

        if($_POST['telephone']){
            $data['phone'] = $_POST['telephone'];
        }else{
			$data['phone'] = '';
		}



        if($_POST['lng']){
            $data['lng'] = $_POST['lng'];
        }else{
            $returnArray = array(
                'code' => 100001,
                'msg' => $driversModel::ERROR_CODE[100001],
                'data' => array()
            );
        }

        if($_POST['lat']){
            $data['lat'] = $_POST['lat'];
        }else{
            $returnArray = array(
                'code' => 100001,
                'msg' => $driversModel::ERROR_CODE[100001],
                'data' => array()
            );
        }
        if($_POST['link']){
            $data['link'] = $_POST['link'];
        }

        if($_POST['status']){
            $data['is_show'] = $_POST['status'];
        }

        if($_POST['adr_type']){
            $data['type'] = $_POST['adr_type'];
        }


        $data['create_at'] =  date('Y-m-d H:i:s');

        if(empty($returnArray)){
            $result = $driversModel->where(array('id'=>$id))->update($data);
            if($result)
            {
                $returnArray = array(
                    'code' => 1,
                    'msg' => $driversModel::ERROR_CODE[1],
                    'data' => $result
                );
				self::updateSeat();
                $this->success('修改成功', 'index');
            }else
                {
                    $this->error('修改失败');
                }
        }else{
            $this->error('修改失败');
        }

    }
	
    /**
     * @return booly验证手机号
     * @ telephone POST  手机号
     */
    public function verifyPhone(){
        if(isset($_POST['telephone']) && !empty($_POST['telephone']))
        {
            $verifyModel = new \app\admin\model\Verify();
            $result = $verifyModel->verifyTelephone($_POST['telephone']);
            if($result)
            {
                return true;
            }else{
                return false;
            }
        }


    }


    /**
     * 验证经纬度
     *  lat float  纬度
     * lng float 经度
     */

    public function verifyLatlng()
    {
        if(isset($_POST['lat']) && !empty($_POST['lat']))
        {

            if(0< $_POST['lat'] && $_POST['lat']<90 )
            {
                return true;
            }else{
                return false;
            }
        }

        if(isset($_POST['lng']) && !empty($_POST['lng']))
        {
            if(-180 < $_POST['lng'] && $_POST['lng'] < 180 )
            {
                return true;
            }else{
                return false;
            }
        }

    }

    /**
     * 删除位置操作：
     *
     */
    public function delAction()
        {
            if(empty($_POST['id']) == false ){
                $driversModel = new \app\admin\model\Drivers();
                $result = $driversModel->where(array("id"=> $_POST['id']))->update(['is_show' =>2]);
                if($result)
                {
					self::updateSeat();
                    return true;
                }
            }else{
                return false;
            }
        }
		
		/**
		*批量导入记录
		*
		*/
	public function lead()
	{
		return 1;
	}
	
	/**
	*更新全部位置
	*/
	public function updateSeat()
	{
        $usersModel = new \app\admin\model\Users();
	    $driversModel = new \app\admin\model\Drivers();
		$redis = $usersModel->listRedis();
		$redis->flushDB();  
		$result = $driversModel->where(array("is_show"=>1,"is_del"=>0))->select();
		foreach($result as $rows){
			$info = '';
			$data = $rows->toArray();
			if($data){
				 $info = $data['drive_name'].'!@#$%'. $data['link'].'!@#$%'. $data['lng'].'!@#$%'. $data['lat'].'!@#$%'. $data['site'].'!@#$%'. $data['phone'].'!@#$%'. $data['type'].'!@#$%'. $data['id'];
				 $redis->LPUSH('seatList',$info); 
			}
		}
		return 1; 
	}
	
}