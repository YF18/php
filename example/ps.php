<?php
header('Content-Type:text/html;charset=GB2312');
function get_rand($proArr) {     
    $result = '';     
   $proSum = array_sum($proArr);    
    foreach ($proArr as $key =>$proCur) {        
    $randNum = mt_rand(1, $proSum);         
    if ($randNum <= $proCur) {            
    $result = $key;             
    break;         
    } else { 
       
    $proSum -= $proCur;         
    }     
    }     
    unset ($proArr);     
    return $result; 
    } 
       
       
    $prize_arr = array(
    '0' => array('id'=>1,'prize'=>'ƽ�����','v'=>1),
    '1' => array('id'=>2,'prize'=>'�������','v'=>5),
    '2' => array('id'=>3,'prize'=>'�����豸','v'=>10),
    '3' => array('id'=>4,'prize'=>'4G����','v'=>12),
    '4' => array('id'=>5,'prize'=>'10Q��','v'=>22),
    '5' => array('id'=>6,'prize'=>'�´�û׼������Ŷ','v'=>5000), ); 
       
       
    foreach ($prize_arr as $key => $val) {
        $arr[$val['id']] = $val['v']; 
    } 
    $rid = get_rand($arr); 
    //���ݸ��ʻ�ȡ����id
    $res['yes'] = $prize_arr[$rid-1]['prize']; //�н��� 
    unset($prize_arr[$rid-1]); //���н�����������޳���ʣ��δ�н��� 
    shuffle($prize_arr); //��������˳�� 
    for($i=0;$i<count($prize_arr);$i++){     
        $pr[] = $prize_arr[$i]['prize']; 
        } 
    $res['no'] = $pr;   
    // �����н������������
    print_r($res);