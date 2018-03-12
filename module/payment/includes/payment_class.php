<?php
class payment
{
	var $db;
	var $tpl;
	var $page;
	var $pay_uid;
	
	function payment()
	{
		global $db;
		global $config;
		global $tpl;
		global $buid;
				
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
		
		if(empty($from_admin))
		{
			$sql = "select pay_id from ".PUSER." where userid='$buid'";
			$this->db->query($sql);
			$this->pay_uid=$this->db->fetchField('pay_id');
			//if(empty($this->pay_uid)&&$_GET['s']!='admin_info')
			//	msg("main.php?m=payment&s=admin_info");
		}
	}
	
	function update_account()
	{
		if(!empty($_POST['pay_pass']))
		{
			$pay_pass=md5(trim($_POST['pay_pass']));
			$pay_pass2=md5(trim($_POST['pay_pass2']));
			if($pay_pass!=$pay_pass2)
			{
				return "f";
			}
			else
			{
				$sql="update ".PUSER." set pay_pass='$pay_pass' where pay_uid='".$this->pay_uid."'";
				$this->db->query($sql);
			}
		}
		else
		{
			$sql="update ".PUSER." set name='$_POST[name]',tell='$_POST[tell]',mobile='$_POST[mobile]' where pay_uid='".$this->pay_uid."'";
			$this->db->query($sql);
		}
	}
	
	function get_payment_statu()
	{	
		//是否开通支付账户
		$pay_uid=$this->pay_uid;
		$sql = "select * from ".PUSER." where pay_uid='$pay_uid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re;
	}
	
	function payment_base()
	{
		global $config;
		$sql = "select * from ".PUSER." where pay_id='".$this->pay_uid."'";
		$this->db->query($sql);
		$list = $this->db->fetchRow();
		return $list;
	}
	
	function get_bind_bank($statu=NULL)
	{	
		if($statu==1)
			$sql=" and active=1";
		$sql = "select * from ".ACCOUNTS." where pay_uid='".$this->pay_uid."' $sql ";
		$this->db->query($sql);
		$list = $this->db->getRows();
		return $list;
	}
	function payment_bind()
	{
		$add_time = time();
		$sql = "insert into ".ACCOUNTS." (pay_uid,master,bank,bank_addr,accounts,add_time) values('".$this->pay_uid."','$_POST[master]','$_POST[bank]','$_POST[bank_addr]','$_POST[accounts]','$add_time')";
		$re=$this->db->query($sql);
		return $re;
	}
	
	function add_pickup()
	{
		global $config;
		//-----------验证金额及密码
		$sql = "select cash, pay_pass from ".PUSER." where pay_uid='".$this->pay_uid."'";
		$this->db->query($sql);
		$re = $this->db->fetchRow();
		if($re['pay_pass'] != md5($_POST['pword']))
		{
			return "2";
		}
		if(!is_numeric($_POST['pickup']) || $_POST['pickup']<=0 || $re['cash']<$_POST['pickup'])
		{
			return "1";
		}
		//------------减去费用
		$sql = "update ".PUSER." set cash=cash-".$_POST['pickup'].", unreachable=unreachable+".$_POST['pickup']." where pay_uid='".$this->pay_uid."'";
		$o=$this->db->query($sql);
		
		//------------提现记录表
		$flow_id=date("Ymdhis").rand(0,9);
		$add_time=time();
		$cashflowid=$this->db->lastid();
		$sql = "insert into ".CASHPICKUP." 
			(amount,pay_uid,cashflowid,add_time,bank_id)
			 	values
			('$_POST[pickup]','".$this->pay_uid."','$flow_id','$add_time',$_POST[bank_id])";
		$this->db->query($sql);
		$order_id=$this->db->lastid();
		
		//------------写进流水账
		include("$config[webroot]/module/payment/lang/$config[language].php");
		$sql="insert into ".CASHFLOW." 
		 (`pay_uid`,`order_id`,`price`,`flow_id`,`note`,`time`,statu,type)
		 values 
		 ('".$this->pay_uid."','$order_id','-".$_POST['pickup']."','$flow_id','$note[2]','$add_time',1,1)";
		$this->db->query($sql);
		//--------------
		return $re;
	}
	function pickup_list()
	{
		global $config;
		$sql = "select * from ".CASHPICKUP." where pay_uid='".$this->pay_uid."' order by id desc";
		//==================================
		include_once("$config[webroot]/includes/page_utf_class.php");
	  	$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows'))
		{
			$this->db->query($sql);
			$num=$this->db->fetchRow();
			$page->totalRows = $num["num"];
		}
        $sql .= "  limit ".$page->firstRow.",20";
		//==================================
		$this->db->query($sql);
		$re["list"]=$this->db->getRows();
		$re["page"]=$page->prompt();
		return $re;
	}
	function del_cashflow($deid)
	{
		$sql="update ".CASHFLOW." set display=0 where id='$deid'";
		$this->db->query($sql);
	}
	function payment_cashflow()
	{
		global $config;
		if(isset($_POST['tstart']) && !empty($_POST['tstart']))
			$subsql .= " and time>='".strtotime($_POST['tstart'])."'";
		if(isset($_POST['tend']) && !empty($_POST['tend']))
			$subsql .= " and time<='".(strtotime($_POST['tend'])+60*60*24)."'";
			
		$sql = "select * from ".CASHFLOW." where display=1 and pay_uid='".$this->pay_uid."' $subsql order by id desc";
		//==================================
		include_once("$config[webroot]/includes/page_utf_class.php");
	  	$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows'))
		{
			$this->db->query($sql);
			$num=$this->db->fetchRow();
			$page->totalRows = $num["num"];
		}
        $sql .= "  limit ".$page->firstRow.",20";
		//==================================
		$this->db->query($sql);
		$re["list"]=$this->db->getRows();
		$re["page"]=$page->prompt();
		return $re;
	}
	function payment_pay()
	{
		$sql = "select * from ".PAYMENT." where active=1 order by payment_id";
		$this->db->query($sql);
		$list = $this->db->getRows();
		return $list;
	}
	//=================================
	
	//生成在线支付连接，并做好记录
	function online_pay()
	{
		//第一步写入流水记录，状态为处理中。
		global $config;
		$amount=trim($_POST['amount'])*1; 
		$time=time();
		$flow_id=date("Ymdhis").rand(0,9);
		include("$config[webroot]/module/payment/lang/$config[language].php");
		include("$config[webroot]/module/payment/lang/payment_$config[language].php");
		if($_POST['payment_type']=='cards')
		{
			if($_POST['card_num'] and $_POST['password'])
			{
				$sql="select id,statu,stime,etime,total_price,give_price from ".PAYCARD." where card_num='$_POST[card_num]' and password='$_POST[password]'";
				$this->db->query($sql);
				$re=$this->db->fetchRow();
				if($re)
				{
					if($re['statu']==1)
					{
						msg("main.php?m=payment&s=admin_accounts_pay","充值卡已被使用过");
					}
					else
					{
						if($re['stime']<=$time&&$re['etime']>=$time)
						{
							$give_price=trim($re['give_price'])*1;
							$amount=trim($re['total_price'])*1+($give_price?$give_price:"0");
						}
						else
						{
							msg("main.php?m=payment&s=admin_accounts_pay","充值卡已过期");
						}
					}
				}
				else
				{
					msg("main.php?m=payment&s=admin_accounts_pay","充值卡不存在");	
				}
			}
		}
		if($amount)
		{
			$payment_name=$lang[$_POST['payment_type']].($_POST['card_num']?$_POST['card_num']:"");
			if($give_price>0)
			{
				$sql="insert into ".CASHFLOW." (`pay_uid`,flow_id,`price`,`time`,`note`,`mold`,`type`,`statu`) values ('0','$flow_id','-$give_price','$time','$payment_name $note[1] 加送点','7','1','4')";
				$this->db->query($sql);
			}
			$sql="insert into ".CASHFLOW." (`pay_uid`,flow_id,`price`,`time`,`note`,`type`,`mold`,`statu`) values ('".$this->pay_uid."','$flow_id','$amount','$time','$payment_name $note[1]','1','2','1')";
			$this->db->query($sql);
			$id=$this->db->lastid();
		}
		
		if(is_numeric($_POST['id'])) $extra_common_param=$_POST['id'];
		
		if($_POST['payment_type']=='cards'&&$id)
		{
			$sql="update ".PAYCARD." set statu=1,use_name='$_COOKIE[USER]' where id='$re[id]' and card_num='$_POST[card_num]'";
			$this->db->query($sql);
			$sql="update ".CASHFLOW." set price='$amount',statu='4' where id='$id'";
			$this->db->query($sql);
			$sql="update ".PUSER." set cash=cash+$amount where pay_uid='".$this->pay_uid."'";
			$this->db->query($sql);
			msg("main.php?m=payment&s=admin_accounts_cashflow","充值成功");	
		}
		if($_POST['payment_type']=='tenpay'&&$id)
		{
			$url=$config['weburl']."/main.php?m=payment&s=admin_accounts_base&onlinepaytype=tenpay";
			require_once($config['webroot']."/module/payment/lib/tenpay/classes/PayRequestHandler.class.php");
			$configs=$this->get_pay_config('tenpay');
			
			$strDate = date("Ymd");
			$strTime = date("His");
			$randNum = rand(1000, 9999);//4位随机数。
			
			$transaction_id = $configs['tenpay_account'] . $strDate . $strTime.$randNum;/* 财付通交易单号*/
			$reqHandler = new PayRequestHandler();
			$reqHandler->init();
			$reqHandler->setKey($configs['tenpay_key']);
			$reqHandler->setParameter("bargainor_id", $configs['tenpay_account']);	//商户号
			$reqHandler->setParameter("sp_billno", $id);						//商户订单号
			$reqHandler->setParameter("transaction_id", $transaction_id);		//财付通交易单号
			$reqHandler->setParameter("total_fee", $amount*100);				//商品总金额,以分为单位
			$reqHandler->setParameter("return_url", $url);						//返回处理地址
			$reqHandler->setParameter("attach",  $extra_common_param); //自定义参数
			$reqHandler->setParameter("desc", iconv('utf-8','gb2312',$config['company']));				//商品名称
			//$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//用户ip
			$link = $reqHandler->getRequestURL();
			msg($link);
		}
		if($_POST['payment_type']=='cbp'&&$id)
		{
			$sum=$amount*1;
			msg($config['weburl']."/module/payment/lib/cbp/Send.php?id=$id&sum=$sum".$u);
		}
		if($_POST['payment_type']=='alipay'&&$id)
		{
			require_once($config['webroot']."/module/payment/lib/alipay/lib/alipay_service.class.php");
			$configs=$this->get_pay_config('alipay');

			$parameter = array(
				"service"         => "create_direct_pay_by_user",   //交易类型
				"payment_type"    => "1",               			//默认为1,不需要修改
				"partner"         => $configs['partner'], 			//合作商户号
				"_input_charset"  => 'UTF-8',   					//字符集，默认为GBK
				"seller_email"    => $configs['seller_email'],    	//卖家邮箱，必填
				"return_url"      => $configs['return_url'],       	//同步返回
				"notify_url"      => $configs['notify_url'],       	//异步返回
				"out_trade_no"    => $id,     						//商品外部交易号，必填（保证唯一性）
				"subject"         => $config['company'],  			//商品名称，必填
				"body"            => $config['company'],     		//商品描述，必填
				"total_fee"       => $amount,       				//商品单价，必填（价格不能为0）
				"show_url"        => $config['weburl'], 			//商品相关网站
				"paymethod"			=> $paymethod,//默认支付方式，取值见“即时到帐接口”技术文档中的请求参数列表
				"defaultbank"		=> $defaultbank,//默认网银代号，代号列表见“即时到帐接口”技术文档“附录”→“银行列表”
				"anti_phishing_key"	=> $anti_phishing_key,//防钓鱼时间戳
				"exter_invoke_ip"	=> $exter_invoke_ip,//获取客户端的IP地址，建议：编写获取客户端IP地址的程序
				"extra_common_param"=> $extra_common_param,//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
				"royalty_type"		=> $royalty_type,//提成类型，该值为固定值：10，不需要修改
				"royalty_parameters"=> $royalty_parameters//提成类型，该值为固定值：10，不需要修改
			);
			$alipayService = new AlipayService($configs);
			echo $alipayService->create_direct_pay_by_user($parameter);
		}
		
		if($_POST['payment_type']=='bill99'&&$id)
		{
			$sum=$amount*100;
			if($extra_common_param)
			{
				$u="&order=$extra_common_param";	
			}
			msg($config['weburl']."/?m=payment&s=bill99&id=$id&sum=$sum".$u);
		}
	}
	function get_pay_config($type)
	{
		global $config;
		$sql="select * from ".PAYMENT." where payment_type='$type'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		$re=unserialize($re['payment_config']);
		foreach($re as $v)
		{
			$name=$v['name'];
			$cn[$name]=$v['value'];
		}
		if($type=='alipay')
		{
			$url=$config['weburl']."/main.php?m=payment&s=admin_accounts_base&onlinepaytype=alipay";
			$cn['sign_type']    = 'MD5';
			$cn['input_charset']= 'utf-8';
			$cn['transport']    = 'http';
			$cn['return_url']   = $url;
			$cn['notify_url']   = $url;
		}
		return $cn;
	}
}
?>
