<?php
class PushadvAction extends BaseagentAction{
	private function doLoadID($id){
		$nav['m']=$this->getActionName();
		$nav['a']='pushadv';
		$this->assign('nav',$nav);
	}
	protected function _initialize(){
		parent::_initialize();
		$this->doLoadID();
	}
	
	
	public  function index(){
		
		import('@.ORG.AdminPage');
		$db=D('Pushadv');
		$where['aid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
  		
        //$result = $db->where($where)->field('id,shopname,add_time,linker,phone,account,maxcount,linkflag')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
       
		$result=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('sort desc,add_time desc') -> select();
		$list2 = array();
		foreach ( $result as $rs ) {
			$rs ['pic'] = $this->downloadUrl($rs['pic']);
			$list2 [] = $rs;
		}
		$this->assign('page',$page->show());
        $this->assign('lists',$list2);
		$this->display();
	}

	public function add(){
		if(isset($_POST) && !empty($_POST)){
//			 import('ORG.Net.UploadFile');       
//	        $upload             = new UploadFile();
//	        $upload->maxSize    = C('AD_SIZE') ;
//	        $upload->allowExts  = C('AD_IMGEXT');
//	        $upload->savePath   =  C('AD_PUSHSAVE');
	        if($_POST['startdate']==""||$_POST['enddate']==""){
	        	$this->error('请选择广告投放时间段');
	        }
	       list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'],$_FILES['img']['tmp_name'] );
				
				//		print_r($ret);exit;
				//7牛上传
				if ($err !== null) {
					$this->error ( '上传失败' );
	        }else{
//	            $info           =  $upload->getUploadFileInfo();
	            $ad             = D('Pushadv');
	           	$_POST['aid'] =$this->aid;
	     
	            $_POST['pic'] = $ret ['key'];
	            $_POST['sort'] = isset($_POST['sort']) ? $_POST['sort'] : 0;
	            
	            $_POST['startdate']=strtotime($_POST['startdate']);
	            $_POST['enddate']=strtotime($_POST['enddate']);
	            if($ad->create()){
		            if($ad->add())
		            {
		                $this->success('添加广告成功',U('pushadv/index','',true,true,true));
		            }else{
		                $this->error('添加失败，请重新添加');
		            }
	            }else{
	
	            	 $this->error($ad->getError());
	            }
	            
	        }
			
			
		}else{
			$this->display();
		}
	}
	
	public function edit(){
		if(isset($_POST) && !empty($_POST)){
		  	if($_POST['startdate']==""||$_POST['enddate']==""){
	        	$this->error('请选择广告投放时间段');
	        }
	        $id = I('post.id','0','int');
	        $where['id']=$id;
	      	
	        $db=D('Pushadv');
	        $result =$db
	                    ->where($where)
	                    ->field('id,pic')
	                    ->find();
	         if($result==false){
	         	$this->error('无此广告信息');
	         	exit;
	         }
	        
//	        import('ORG.Net.UploadFile');      
//	       
//	        $upload             = new UploadFile();
//	        $upload->maxSize    = C('AD_SIZE');
//	        $upload->allowExts  = C('AD_IMGEXT');
//	        $upload->savePath   =  C('AD_PUSHSAVE');
	    
	      	if (!is_null($_FILES['img']['name'])&& $_FILES['img']['name']!="") {
	      		list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'],$_FILES['img']['tmp_name'] );
				
				//		print_r($ret);exit;
				//7牛上传
				if ($err !== null) {
					$this->error ( '上传失败' );
		        }else{
//		            $info =  $upload->getUploadFileInfo();
		            $_POST['pic'] = $ret ['key'];;
		        }
	    	}else{
	    		$_POST['pic']=$result['pic'];
	    	}
       
         
            if($result)
            {
                $_POST['aid'] =$this->aid;
                $_POST['startdate']=strtotime($_POST['startdate']);
	            $_POST['enddate']=strtotime($_POST['enddate']);
                if($db->create()){
                	 if($db->where($where)->save()){
                	 
                	     $this->success('修改成功',U('pushadv/index','',true,true,true));
                	 }else{
                		 $this->error('操作出错');
                	 }
                }else{
                	 $this->error($db->getError());
                }
               
            }
		}else{
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	      
	        $where['id']=$id;
	        	        $where['aid']=$this->aid;
	            $result = D('Pushadv')
	                    ->where($where)
	                    ->find();
	                     if($id)
	        if($result){
	        	$result ['pic'] = $this->downloadUrl($result['pic']);
	            $this->assign('info',$result);
	            $this->display();
	        }else{
	        	$this->error('无此广告信息');
	        }
	
		}
	}
	
	public function  del(){
		$id = isset($_GET['id']) ? intval($_GET[id]) : 0;       
        
        if($id)
        { 
        	$where['id']=$id;
	        	        $where['aid']=$this->aid;
            $thumb = D('Pushadv')->where($where)->field("id,pic")->select();
            if(D('Pushadv')->delete($id))
            {
                if(file_exists( ".{$thumb[0]['pic']}"))
                {
                    unlink(".{$thumb[0]['pic']}");
                }
                
                $this->success('删除成功',U('index'));
            }else{
                $this->error('操作出错');
            }
        }
	}
	public function set(){
		if(isset($_POST) && !empty($_POST)){
			$_POST['aid']=$this->aid;
			$wt=$_POST['showtime'];
			if(!isNumber($wt)){
				$this->error("广告展示时间以秒为单位,请输入展示的时间");
			}
			if($wt<3){
				$this->error("最低展示时间不能小于3秒");
			}
			$db=D('Agentpushset');
			$where['aid']=$this->aid;
			$info=$db->where($where)->find();
			if($info){
				//update
				if($db->create()){
						$db->where($where)->save();
						$this->success("操作成功");
				}else{
					$this->error($db->getError());
				}

			}else{
				//add
//				dump('add');
				if($db->create()){
						$id=$db->add();
						$this->success("操作成功");
				}else{
					$this->error($db->getError());
				}
			}
			
		}else{
			$db=D('Agentpushset');
			$where['aid']=$this->aid;
			$info=$db->where($where)->find();
			$this->assign('info',$info);
			$this->display();
		}
			
		
	}

	public function rpt(){
		$way=I('get.mode');
			if(!empty($way)){
				$this->getadrpt();
				exit;
			}
			$this->display();
	}
	private  function getadrpt(){
    	
    	$way=I('get.mode');
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date='".date("Y-m-d")."' and mode=50 and agent='".$this->aid."'";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and mode=50 and agent='".$this->aid."'";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit ,COALESCE(hit/showup*100,0) as rt from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit from ".C('DB_PREFIX')."adcount";
				$sql.=" where   add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and mode=50 and agent='".$this->aid."' GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount  ";
				$sql.=" where   add_date >= '".date("Y-m-01")."' and mode=50 and agent='".$this->aid."' GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    			
    
				$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount ";
				$sql.=" where  add_date between '$sdate' and '$edate'  and mode=50 and agent=".$this->aid." GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";

			
    			break;
    	}
    
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
}