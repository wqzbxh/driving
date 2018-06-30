<?php
namespace app\admin\controller;
use think\Controller;
use IOFactory;
class Assist extends Controller
{

	/**
	*登录
	*
	*/ 
	
	public function index()
    {		
		return $this->fetch('index');
	}
	
	public function leadExcel()
    {		
        $resultArray = array();
		$driversModel = new \app\admin\model\Drivers();
        date_default_timezone_set("Asia/Shanghai");
		//Vendor("PHPExcel.PHPExcel");
		Vendor("PHPExcel");
		Vendor("PHPExcel.IOFactory");
        $filename = $_FILES['file']['tmp_name'];
		$reader = \PHPExcel_IOFactory::createReader('Excel2007'); // 读取 excel 文档
	    $objPHPExcel = $reader->load($filename);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); 
		for ($i = 2 ;$i<=$highestRow;$i++)
        {
            $info = array();
            $info['drive_name'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            $info['site'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            $info['link'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            $info['lng'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            $info['lat'] = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            $info['type'] = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
            $info['is_show'] = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
            $info['phone'] = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
			
            if($info){
				$result = $driversModel ->create($info);
				if($result){
				}else{
					echo '第'.$i.'及其行以下没有插入成功！请整理表格重新上传';return;
				}
			}
        }
		
		echo '<script>alert("导入成功")</script>';
		$this->redirect('index');
	}	
    
    public function test()
    {
        		return $this->fetch('test/test');
    }
	public function kuayu()
    {		
        
        	 // Create a blank image.
            $image = imagecreatetruecolor(400, 300);
            
            // Select the background color.
            $bg = imagecolorallocate($image, 255, 102, 0);
            
            // Fill the background with the color selected above.
            imagefill($image, 0, 0, $bg);
            
            // Choose a color for the ellipse.
            $col_ellipse = imagecolorallocate($image, 255, 255, 255);

            $col_ellipse1 = imagecolorallocate($image, 0, 0, 255);
            // Draw the ellipse.
            imageellipse($image, 200, 150, 300, 200, $col_ellipse);
            
            // Output the image.
            header("Content-type: image/png");
            imagepng($image);
    }		
	public function index1()
    {		
            $a = imagecreatetruecolor(200, 200);
            $b = imagecolorallocate($a,102 , 255, 102);
            $col_ellipse1 = imagecolorallocate($a, 0, 0,255);
            $col_ellipse  = imagecolorallocate($a, 255, 255,255);
	        imagearc($a,100, 100, 100, 100, 0, 360, $b);
	        imagejpeg($a,'index.jpeg');
// 	        header("Content-type: image/png");
// 	        imagepng($a,'index.jpeg');
// 	        imagedestroy($a);
    }	
	
} 