<?php
if(isset($_GET['pwd'])){
	$passwd=$_GET['pwd'];
}else{
	$passwd='123456';
}

echo md5(md5($passwd).'mypharma');