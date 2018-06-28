<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('max_execution_time', 0);
ini_set('memory_limit','1024M');
//
$host='192.168.11.29';
$username='admin';
$password='admin101';
$database='mph_member_v1.2';
$con=mysql_connect($host,$username,$password) or die('connect db error'. mysql_error());;
mysql_select_db($database,$con) or die('select db error');

//
mysql_query("CREATE TABLE mph_supplier_member_purview2 SELECT * FROM mph_supplier_member_purview;");
mysql_query("ALTER TABLE mph_supplier_member_purview2 MODIFY COLUMN `smp_id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增编号' FIRST , ADD PRIMARY KEY (`smp_id`);");
mysql_query("ALTER TABLE mph_supplier_member_purview2 COMMENT='采购权限表';");
mysql_query("DELETE FROM mph_supplier_member_purview2;");
mysql_query("ALTER TABLE mph_supplier_member_purview2 AUTO_INCREMENT=1;");

//Sql
$sql="SELECT * FROM mph_supplier_member";
$res=mysql_query($sql);
while($item=mysql_fetch_array($res)){
	//
	$data['m_id']=$item['m_id'];
	$data['sm_id']=$item['sm_id'];
	$data['su_id']=$item['su_id'];
	
	$data['smp_start_time']=$item['sm_add_time'];
	$data['smp_end_time']=$item['sm_add_time'];
	$data['smp_add_time']=$item['sm_add_time'];
	$data['smp_action_time']=$item['sm_action_time'];
	$data['smp_status']=$item['sm_status'];
	$data['smp_is_erp']=$item['sm_is_erp'];
	
	if(stripos($item['sm_purview'],',')){
		$sm_purview=explode(',',$item['sm_purview']);
		//$sm_purview=array_unique($sm_purview);
		foreach ($sm_purview as $item){
			$data['sp_id']=$item;
			insert($data);
		}
		continue;
	}else{
		$data['sp_id']=$item['sm_purview'];
	}
	insert($data);
}

mysql_query("RENAME TABLE mph_supplier_member_purview TO mph_supplier_member_purview_old;");
mysql_query("RENAME TABLE mph_supplier_member_purview2 TO mph_supplier_member_purview;");


die('ok');

function insert($data){
	//
	$sql='INSERT INTO mph_supplier_member_purview2 values ';
	$sql.="(NULL,".$data['m_id'].",".$data['sm_id'].",".$data['su_id'].", ".$data['sp_id'].", NULL, NULL, '".$data['smp_add_time']."', '".$data['smp_action_time']."', ".$data['smp_status'].", ".$data['smp_is_erp'].");";
	//echo $sql ."<br/>";
	mysql_query($sql);
}