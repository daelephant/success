<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dataright extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview(82);
		$this->action = $this->input->get('action',TRUE);
    }
	
	public function index() {
		switch ($this->action) {
			case 'ar':
			    $this->ar();break; 
			case 'dt':
			    $this->dt();break;
			case 'update':
			    $this->update();break;
			case 'query':
			    $this->query();break;
			default:  
			    str_alert(-1,'非法请求'); 		 
		}
	}
	
	private function ar() {
		$data = $this->input->post(NULL,TRUE);
		if (count($data)>0) {
		    $userName = str_enhtml($this->input->get('userName',TRUE));
			$rightid  = (array)json_decode($data['rightid'],true);
			$info['rightids'] = join(',',$rightid['rightids']);
			$sql  = $this->mysql_model->update('admin',$info,'(username="'.$userName.'")');
			if ($sql) {
				str_alert(200,'success');
			}
		}
		str_alert(-1,'更新失败');
	}
     
	private function dt() {
	    $userName = str_enhtml($this->input->get_post('userName',TRUE));
		$user = $this->mysql_model->get_rows('admin',array('username'=>$userName));
		$json['status']             = 200;
		$json['msg']                = 'success'; 
		$fright1 = in_array(1,explode(',',$user['rightids'])) ? 1 :0;
		$fright2 = in_array(2,explode(',',$user['rightids'])) ? 1 :0;
		$fright4 = in_array(4,explode(',',$user['rightids'])) ? 1 :0;
		$fright8 = in_array(8,explode(',',$user['rightids'])) ? 1 :0;
		$json['data']['items'][0]   = array('FNAME'=>'仓库','FRIGHT'=>$fright1,'FRIGHTID'=>'1','FNUMBER'=>'location');
		$json['data']['items'][1]   = array('FNAME'=>'客户','FRIGHT'=>$fright2,'FRIGHTID'=>'2','FNUMBER'=>'customer');
		$json['data']['items'][2]   = array('FNAME'=>'供应商','FRIGHT'=>$fright4,'FRIGHTID'=>'4','FNUMBER'=>'supplier');
		$json['data']['items'][3]   = array('FNAME'=>'制单人','FRIGHT'=>$fright8,'FRIGHTID'=>'8','FNUMBER'=>'user');
		$json['data']['total']      = 4;
		die(json_encode($json));	
	}
	
	private function update() {
	    $this->common_model->checkpurview();
		$type     = max(intval($this->input->get('type',TRUE)),1);
		$rights   = $this->input->post('rights',TRUE);
		$userName = str_enhtml($this->input->get('userName',TRUE));
		$data = $this->mysql_model->get_rows('admin',array('username'=>$userName));
		if (count($data>0)) {
		    $array  = explode(',',$data['righttype'.$type]);
			foreach((array)json_decode($rights,true) as $arr=>$row){
				if ($row['FRIGHT']==1) {
					$s1[] = $row['FITEMID'];   //新增
				} else {
					$s2[] = $row['FITEMID'];   //除去
				}
			}
			if (isset($s1)) {
				$info['righttype'.$type] = join(',',array_filter(array_merge($array,$s1)));
				$this->mysql_model->update('admin',$info,array('username'=>$userName));
			}
			if (isset($s2)) {
				$info['righttype'.$type] = join(',',array_filter(array_diff($array,$s2)));
				$this->mysql_model->update('admin',$info,array('username'=>$userName));
			}
			str_alert(200,'success');
		}
		str_alert(-1,'更新失败');
	} 
	
	 
	private function query() {
	    $v = array();
	    $type      = max(intval($this->input->get_post('type',TRUE)),0);
		$skey      = str_enhtml($this->input->get_post('skey',TRUE));
		$userName  = str_enhtml($this->input->get_post('userName',TRUE));
		$data = $this->mysql_model->get_rows('admin',array('username'=>$userName)); 
		if (count($data)>0) {
			switch ($type) {
				case 1:
				    $righttype = explode(',',$data['righttype'.$type]);
					$where = $skey ? ' and (locationNo like "%'.$skey.'%" or name like "%'.$skey.'%")' : '';
					$list = $this->mysql_model->get_results('storage','(isDelete=0) '.$where,'id desc'); 
					foreach ($list as $arr=>$row) {
						$v[$arr]['FITEMID']     = intval($row['id']);
						$v[$arr]['FNAME']       = $row['name'];
						$v[$arr]['FITEMNO']     = $row['locationNo'];
						$v[$arr]['FRIGHT']      = in_array($row['id'],$righttype)==1 ? 1 : 0;
					}
					break;  
				case 2:
				    $righttype = explode(',',$data['righttype'.$type]);
					$where = $skey ? ' and (number like "%'.$skey.'%" or name like "%'.$skey.'%")' : '';
					$list = $this->mysql_model->get_results('contact','(isDelete=0) and type=-10 '.$where,'id desc'); 
					foreach ($list as $arr=>$row) {
						$v[$arr]['FITEMID']     = intval($row['id']);
						$v[$arr]['FNAME']       = $row['name'];
						$v[$arr]['FITEMNO']     = $row['number'];
						$v[$arr]['FRIGHT']      = in_array($row['id'],$righttype)==1 ? 1 : 0;
					}
					break;  	
				case 4:
				    $righttype = explode(',',$data['righttype'.$type]);
					$where = $skey ? ' and (number like "%'.$skey.'%" or name like "%'.$skey.'%")' : '';
					$list = $this->mysql_model->get_results('contact','(isDelete=0) and type=10 '.$where,'id desc');  
					foreach ($list as $arr=>$row) {
						$v[$arr]['FITEMID']     = intval($row['id']);
						$v[$arr]['FNAME']       = $row['name'];
						$v[$arr]['FITEMNO']     = $row['number'];
						$v[$arr]['FRIGHT']      = in_array($row['id'],$righttype)==1 ? 1 : 0;
					}
					break; 
				case 8:
				    $righttype = explode(',',$data['righttype'.$type]);
					$where = $skey ? ' and (username like "%'.$skey.'%" or name like "%'.$skey.'%")' : '';
					$list = $this->mysql_model->get_results('admin','(1=1) '.$where,'uid desc');  
					foreach ($list as $arr=>$row) {
						$v[$arr]['FITEMID']     = intval($row['uid']);
						$v[$arr]['FNAME']       = $row['username'];
						$v[$arr]['FITEMNO']     = intval($row['uid']);
						$v[$arr]['FRIGHT']      = in_array($row['uid'],$righttype)==1 ? 1 : 0;
					}
					break; 	
				default:     	
			}
		}
		$data['status']             = 200;
		$data['msg']                = 'success'; 
		$data['data']['rows']       = $v;
		$data['data']['total']      = 1;
		$data['data']['records']    = count($v);
		$data['data']['page']       = 1;
		die(json_encode($data));
	}
 
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */