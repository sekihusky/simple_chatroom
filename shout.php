<?php
session_start();
//ini_set('display_errors','1');
//error_reporting(E_ALL);
####### db config ##########
$db_username = 'chatroom';
$db_password = 'mypassword';
$db_name = 'chat';
$db_host = 'localhost';
####### db config end ##########

if($_POST)
{
	//connect to mysql db
	try	{
		$dsn = "mysql:host=$db_host;dbname=".$db_name;
		$db = new PDO($dsn, $db_username, $db_password );

		$db->exec('set character set utf8mb4');
		$db->exec('SET NAMES utf8mb4');
		
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connected Successfully";
	}catch(PDOException $e)	{
		echo "Connection failed: ".$e->getMessage();
	}
	
	//check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    } 
	
	if(isset($_POST["message"]) &&  strlen($_POST["message"])>0)
	{
		//sanitize user name and message received from chat box
		//You can replace username with registerd username, if only registered users are allowed.
		//$username = filter_var(trim($_POST["username"]),FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
		$username = $_POST["username"];
		$_SESSION['username'] = $username ;
		//$message = filter_var(trim($_POST["message"]),FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
		$message = $_POST["message"];
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$vip_level = 'none' ;
		//insert new message in db
		$sql="INSERT INTO shout_box(user, message, ip_address,user_id,level) value('$username','$message','$user_ip','$user_id','$vip_level')" ;

		$rs = $db->query($sql);
		$rs->fetchall(PDO::FETCH_ASSOC);
		if($rs)
		{
			$msg_time = date('h:i A M d',time()); // current time
			echo '<div class="shout_msg"><time>'.$msg_time.'</time><span class="vip">'.$vip_level.'</span><span class="username" style="color:blue !important;">'.$username.'</span><span class="message">'.$message.'</span></div>';
		}
		
		// delete all records except last 10, if you don't want to grow your db size!
		//$sql_delete = "DELETE FROM shout_box WHERE id NOT IN (SELECT * FROM (SELECT id FROM shout_box ORDER BY id DESC LIMIT 0, 10) as sb)" ;
		//$db->exec($sql_delete); 

		$db = null; 
	}
	elseif($_POST["fetch"]==1)
	{

		$sql = "SELECT user, message, date_time, level FROM (select * from shout_box ORDER BY id DESC LIMIT 50) shout_box ORDER BY shout_box.id ASC" ;
		$rs = $db->query($sql);
		$data = $rs->fetchall(PDO::FETCH_ASSOC);
		for($i=0;isset($data[$i]);$i++){
			$msg_time = date('h:i A M d',strtotime($data[$i]["date_time"])); //message posted time
			if($data[$i]["user"] == $_SESSION['username']){
				echo '<div class="shout_msg"><time>'.$msg_time.'</time><span class="vip">'.$data[$i]["level"].' </span> <span class="username" style="color:blue !important;">'.$data[$i]["user"].'</span> <span class="message">'.$data[$i]["message"].'</span></div>';
			}else{
				echo '<div class="shout_msg"><time>'.$msg_time.'</time><span class="vip">'.$data[$i]["level"].' </span> <span class="username">'.$data[$i]["user"].'</span> <span class="message">'.$data[$i]["message"].'</span></div>';
			}
		}
		$db = null; 
	}
	else
	{
		header('HTTP/1.1 500 Are you kiddin me?');
    	exit();
	}
}