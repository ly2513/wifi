<?php
class LoginAction extends BaseApiAction{
	private $gw_address = null;
	private $gw_port = null;
	private $gw_id = null;
	private $mac = null;
	private $url = null;
	private $logout = null;

	
	public function index(){
//		print_r('eee');exit;
		
		if (isset($_REQUEST["gw_address"])) {
		    $this->gw_address = $_REQUEST['gw_address'];
		    cookie('gw_address',$_REQUEST['gw_address']);
		}

		if (isset($_REQUEST["gw_port"])) {
		    $this->gw_port = $_REQUEST['gw_port'];
		    cookie('gw_port', $_REQUEST['gw_port']);
		}
		
		if (isset($_REQUEST["gw_id"])) {
		    $this->gw_id = $_REQUEST['gw_id'];
		    cookie('gw_id', $_REQUEST['gw_id']);
		}
		
		$mac = '';//海蜘蛛的没有mac,其实可以用网关的来代替
		if (isset($_REQUEST["mac"])) {
		    cookie('mac', $_REQUEST['mac']);
		    $mac = $_REQUEST['mac'];
		}
		
		if (isset($_REQUEST["url"])) {
		    $this->url = $_REQUEST['url'];
		    cookie('gw_url', $_REQUEST['url']);
		    $this->wLog($this->url,$this->gw_id,$mac);//日志
		}
		$nowdate=getNowDate();//当前日期
		if(!empty($this->gw_id)){
			//检测是否为iphone
			$agent = $this->agent;
			$pos2 = strstr($agent, "iPhone OS");
			if($pos2){
				$this->assign('is_iphone',true);
			}else{
				$this->assign('is_iphone',false);
			}
			//检测是否为iphone
			
			//寻找网关ID
			$sql="select a.*,b.wx,b.shopname,b.notice,b.logo,b.ad_show_time,b.address,b.phone,b.authmode,b.maxcount,b.linkflag,b.sh,b.eh,b.pid,b.countflag,b.countmax,b.tpl_path,a.hotspotname,a.hotspotpass,b.wxurl,b.codeimg from ".C('DB_PREFIX')."routemap a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id ";
			$sql.=" where a.gw_id='".$this->gw_id."' limit 1";
			$db=D('Routemap');
			$addb=D('Ad');
			$info=$db->query($sql);
			$tmpurl=$_REQUEST['url'];
			$arr = parse_url($tmpurl);
            $arr_query =$this->convertUrlQuery($arr['query']);
            $token=$arr_query['token'];
            $num=strlen($arr_query['token']);
			if($info!=false){
				if($num>=0){
					$db = D ( 'Member' );
					$sql = "SELECT * FROM wifi_member WHERE `token`='$token'";
					$minfo = $db->query ( $sql );
					// 认证成功后，跳转页面
            		if((time ()-$minfo[0]['add_time'])/60<5){
            			// wifidog认证地址
						$jump="http://".$_REQUEST["gw_address"].":".$_REQUEST["gw_port"]."/wifidog/auth?token=".$token;
				  		if(cookie('gw_port')==null||cookie('gw_port')==""){
							$jump=cookie('gw_address')."?username=".$info[0]['hotspotname']."&password=".$info[0]['hotspotpass'];
						}

						cookie('token',$token);
						cookie('mid',$newid);
						unset($where);
						$where['state']=1;
						$where['startdate']= array('elt',time());
						$where['enddate']= array('egt',time());
						$where['aid']=0;
						$ads=D('Pushadv')->where($where)->field(array('id','pic'=>'ad_thumb'))->limit(10)->select();
						$tplkey=$info[0]['tpl_path'];
						$show=1;
						$max=$info[0]['maxcount'];
						$limit=$info[0]['linkflag'];
						if($limit==0){
							$where['shopid']=$info[0]['shopid'];
							$count=D('member')->where($where)->count();
							if($count>$max){
								$show=0;
							}
						}
						cookie('shopid', $info[0]['shopid']);
						unset($where);
						$authmode=$info[0]['authmode'];
						$where['uid']=$info[0]['shopid'];
						$where['ad_pos']=1 ;
						
						$ad=$addb->where($where)->order('ad_sort desc')->field('id,ad_thumb,mode')->limit(5)->select();
						$ids="";
						$this->assign('ad',$ad);
						$this->assign("shopinfo",$info);
                        $this->assign('jump',$jump);
                        // 认证成功页面
                       	$this->display('ok');
						exit;
					}
				}
				if(!empty($info[0]['logo'])){
					$info[0]['logo'] = $this->downloadUrl($info[0]['logo']);
				}
				$tplkey=$info[0]['tpl_path'];
				$show=1;
				$max=$info[0]['maxcount'];
				// P($info);
				$limit=$info[0]['linkflag'];
				if($limit==0){
					$where['shopid']=$info[0]['shopid'];
					$count=D('member')->where($where)->count();
					if($count>$max){
						$show=0;
					}
				}
				cookie('shopid', $info[0]['shopid']);
				
				$authmode=$info[0]['authmode'];
				$where['uid']=$info[0]['shopid'];
				$where['ad_pos']=0;
				// P($info[0]['shopid']);
				$ad=$addb->where($where)->order('ad_sort desc')->field('id,ad_thumb,mode')->limit(5)->select();
				$ids="";
				$tmpad= array();
				foreach ($ad as $k=>$v){
					$v['ad_thumb']=$this->downloadUrl($v['ad_thumb']);
					$ids.=$v['id'].",";
					$tmpad[] = $v;
				}
				$ad = $tmpad;
				
				$this->assign('ad',$ad);
				$this->assign('adid',$ids);
				// P($show);
				$this->assign('show',$show);
				$hour=(int)date("H");
				/*判断是否在允许上网时段*/
				if(!empty($info[0]['sh'])&&!empty($info[0]['eh'])&&$info[0]['sh']!=""&&$info[0]['eh']!=""){
					$sh=(int)$info[0]['sh'];
					$eh=(int)$info[0]['eh'];
					$auth['opensh']=$sh;
					$auth['openeh']=$eh;
					$auth['openflag']=true;//设置时段
					if($hour>=$sh&&$hour<=$eh){
						$auth['open']=true;
						
					}else{
						$auth['open']=false;	
					}
				}else{
					$auth['open']=true;
					$auth['openflag']=false;//未设置
				}
				if($authmode==null||$authmode==""){
					$auth['reg']=1;
					
				}else{
					$tmp=explode('#', $authmode);
					foreach($tmp as $v){
						if($v!='#'&&$v!=''){
							$arr[]=$v;
						}
					}
					foreach($arr as $v){
						$temp=explode('=', $v);
						if(count($temp)>1&&$temp[0]=='3'){
							$auth['wx']=1;
						}else if(count($temp)>1&&$temp[0]=='4'){
							$auth['wx_f']=1;
						}else{
							if($v=='0'){
								$auth['reg']=1;
							}
							if($v=='4'){
								$auth['wx_f']=1;
							}
							if($v=='1'){
								$auth['phone']=1;
							}
							if($v=='2'){
								$auth['allow']=1;
							}
						}
					}	
				}
				
				/*判断是否启用认证限制*/
				if($info[0]['countflag']>0){
					$maxcount=$info[0]['countmax'];
					$authdb=D('Authlist');
					$countwhere['mac']=$mac;
					$countwhere['shopid']=$info[0]['shopid'];
					$countwhere['add_date']=$nowdate;
					$auth_count=$authdb->where($countwhere)->count();
					//dump($authdb->getLastSql());
					if(($maxcount-$auth_count)<=0){
						//echo "超过啦";
						$auth['overmax']=1;
					}else{
						$auth['overmax']=0;
					}
				}else{
					$auth['overmax']=0;
				}
				$this->assign("authmode",$auth);
				$this->assign("shopinfo",$info);
				// P($tplkey);
				if(empty($tplkey)||$tplkey==""||strtolower($tplkey)=="default"){

					$this->display();
				}else{

					$this->display('index$'.$tplkey);
				}
			}else{
				echo '参数不正确';
			}
		}else{
			//没有网关ID
			// echo 111;
			echo '参数不正确';
		}
	}
	
	public  function countad(){
		$gid=cookie('gw_id');
		$sid=cookie('shopid');
		if(empty($gid)||empty($sid)){
			
			exit;
		}
		
		$ids=I('post.ids');
		
		$idarr=explode(',', $ids);
		/*统计展示*/
				///////////////////////////
				$tr=new Model();
				$time=time();
				$tr->startTrans();
				$arrdata['showup']=1;
				$arrdata['hit']=0;
				$arrdata['shopid']=$sid;
				$arrdata['add_time']=$time;
				$arrdata['add_date']=getNowDate();
				$arrdata['mode']=1;
				foreach($idarr as $v){
					if($v!=""){
						$arrdata['aid']=$v;
						$tr->table(C('DB_PREFIX')."adcount")->add($arrdata);
					}
				}
				$tr->commit();
				///////////////////////////
	}
	


public function getUrlQuery($array_query)
{
    $tmp = array();
    foreach($array_query as $k=>$param)
    {
        $tmp[] = $k.'='.$param;
    }
    $params = implode('&',$tmp);
    return $params;
}

public  function convertUrlQuery($query)
{ 
    $queryParts = explode('&', $query); 
     
    $params = array(); 
    foreach ($queryParts as $param) 
    { 
        $item = explode('=', $param); 
        $params[$item[0]] = $item[1]; 
    } 
     
    return $params; 
}
 
public function is_weixin(){


if ( strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
return true;
}else{
return false;
}
}
	public function apple(){
		//log::write("签到");
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN"><HTML><HEAD><TITLE>Success</TITLE></HEAD><BODY>Success</BODY></HTML>';
	}
}
