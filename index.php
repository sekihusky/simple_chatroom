<?php
include("_config.php") ;
ini_set('display_errors','1');
error_reporting(E_ALL);
if(isset($_POST['shout_username'])){
	$user_name = $_POST['shout_username'];
}else{
	$user_name = 'your name' ;
}
echo '123' ;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chat Box</title>
<style type="text/css">
<!--
body {
			box-sizing: border-box;
			padding: 0px;
			margin: 0px;
		}

		.toggle_chat {
			display: flex;
			flex-direction: column;
			height: 100vh;
			background-color: #ffffff;
		}

		.shout_box .header .close_btn {
			background: url(images/close_btn.png) no-repeat 0px 0px;
			float: right;
			width: 15px;
			height: 15px;
		}

		.shout_box .header .close_btn:hover {
			background: url(images/close_btn.png) no-repeat 0px -16px;
		}

		.shout_box .header .open_btn {
			background: url(images/close_btn.png) no-repeat 0px -32px;
			float: right;
			width: 15px;
			height: 15px;
		}

		.shout_box .header .open_btn:hover {
			background: url(images/close_btn.png) no-repeat 0px -48px;
		}

		.shout_box .header {
			padding: 5px 3px 5px 5px;
			font: 11px 'lucida grande', tahoma, verdana, arial, sans-serif;
			font-weight: bold;
			color: #fff;
			border: 1px solid rgba(0, 39, 121, .76);
			border-bottom: none;
			cursor: pointer;
		}

		.shout_box .header:hover {
			background-color: #627BAE;
		}

		.shout_box .message_box {
			background: #FFFFFF;
			overflow: auto;
			height: calc(100vh - 4rem);
			border: 1px solid #CCC;
		}

		.shout_msg {
			margin-bottom: 10px;
			display: block;
			border-bottom: 1px solid #F3F3F3;
			padding: 0px 5px 5px 5px;
			font: 11px 'lucida grande', tahoma, verdana, arial, sans-serif;
			color: #7C7C7C;
		}

		.message_box:last-child {
			border-bottom: none;
		}

		time {
			font: 11px 'lucida grande', tahoma, verdana, arial, sans-serif;
			font-weight: normal;
			float: right;
			color: #D5D5D5;
		}

		.shout_msg .username {
			margin-bottom: 10px;
			margin-top: 10px;
		}

		.user_info {
			padding: 0.25rem;
		}

		.user_info>div {
			display: flex;
			margin-bottom: 1rem;
			height: 2.5rem;
		}

		.user_info input {
			width: 75%;
			border: 1px solid #CCC;
			padding: 3px;
			font: 11px 'lucida grande', tahoma, verdana, arial, sans-serif;
		}

		#messagebtn {
			width: 25%;
			background-color: #eed77d;
		}

		.shout_msg .username {
			font-weight: bold;
			display: block;
		}

		.shout_msg .vip {
			font-weight: bold;
			display: block;
			float: left;
			color: #FF0000;
			margin-right: 5px;
		}
-->
</style>


<script src="js/jquery-1.9.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	// load messages every 1000 milliseconds from server.
	load_data = {'fetch':1};
	window.setInterval(function(){
	 $.post('shout.php', load_data,  function(data) {
		$('.message_box').html(data);
		var scrolltoh = $('.message_box')[0].scrollHeight;
		//$('.message_box').scrollTop(scrolltoh);
	 });
	}, 1000);
	
	//method to trigger when user hits enter key
	$("#shout_message").keypress(function(evt) {
		if(evt.which == 13) {
			
			var iusername = $('#shout_username').val();
			var imessage = $('#shout_message').val();
			post_data = {'username':iusername, 'message':imessage};
			
			//send data to "shout.php" using jQuery $.post()
			$.post('shout.php', post_data, function(data) {
				
				//append data into messagebox with jQuery fade effect!
				$(data).hide().appendTo('.message_box').fadeIn();

				//keep scrolled to bottom of chat!
				var scrolltoh = $('.message_box')[0].scrollHeight;
				$('.message_box').scrollTop(scrolltoh);
				
				//reset value of message box
				$('#shout_message').val('');
				
			}).fail(function(err) { 
				//alert HTTP server error
				alert(err.statusText); 
				
			});
		}
	});
	
	$("#messagebtn").click(function (e) {
		var iusername = $('#shout_username').val();
		var imessage = $('#shout_message').val();
		post_data = {'username':iusername, 'message':imessage};
		
		//send data to "shout.php" using jQuery $.post()
		$.post('shout.php', post_data, function(data) {
			
			//append data into messagebox with jQuery fade effect!
			$(data).hide().appendTo('.message_box').fadeIn();

			//keep scrolled to bottom of chat!
			var scrolltoh = $('.message_box')[0].scrollHeight;
			$('.message_box').scrollTop(scrolltoh);
			
			//reset value of message box
			$('#shout_message').val('');
			
		}).fail(function(err) { 
			//alert HTTP server error
			alert(err.statusText); 
			
			
		});
	});
	
	//toggle hide/show shout box
	$(".close_btn").click(function (e) {
		//get CSS display state of .toggle_chat element
		var toggleState = $('.toggle_chat').css('display');
		
		//toggle show/hide chat box
		$('.toggle_chat').slideToggle();
		
		//use toggleState var to change close/open icon image
		if(toggleState == 'block')
		{
			$(".header div").attr('class', 'open_btn');
		}else{
			$(".header div").attr('class', 'close_btn');
		}
	});
});

</script>
</head>

<body>
<div class="shout_box">

  <div class="toggle_chat">
  <div class="message_box">
    </div>
    <div class="user_info">
 

    	<input name="shout_username" id="shout_username" type="text" value="<?php echo $user_name; ?>" maxlength="15"  />
			<div>
				<input name="shout_message" id="shout_message" type="text" placeholder="balabala!" maxlength="100" />
				<input name="submessage" type="button" id="messagebtn" value="shout" >
			</div>
		 
    </div>
    </div>
</div>
</body>
</html>
