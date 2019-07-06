<?php

ob_start();
session_start();
include 'db_connnection.php';
if ( !isset($_SESSION['userID'])) {
	header("Location: login.php");
	exit;
}
$userID = $_SESSION['userID'];
$conn = OpenCon();
$query = "SELECT * FROM piktorya_users WHERE user_name = '$userID'";
$result = $conn->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC);

$queryUserName = "SELECT user_name FROM piktorya_users";
$resultUserName = $conn->query($queryUserName);
$rowUserName_count = $resultUserName->num_rows;
$query_image = $user_likes = $postID = $user_ID = $current_likes = $imageUsername = $currentUserName = $row_count_CheckLiked = $row_count_CheckUnLiked= $fetch_queryCheckLiked[] = $fetch_queryCheckUnLiked[] =  $checklike = $row_count_getComments = "";
$unlikes = $likes = 0;

for ( $j=1; $j<=$rowUserName_count; $j++) {
	$rowUserName = $resultUserName->fetch_array(MYSQLI_ASSOC);
	$query_image = $query_image.'(select * from '.$rowUserName['user_name'].')';
	if ($j == $rowUserName_count) {
		$query_image = $query_image.' order by registered_date desc;';
	} else {
		$query_image = $query_image.' union ';
	}
}
$result_image = $conn->query($query_image);
if (!$result_image) {
	echo("Query failed: (" . $conn->errno . ") " . $conn->error );
}
$row_count = $result_image->num_rows;

$query_image_user = "SELECT * FROM $userID";
$result_image_user = $conn->query($query_image_user);
$row_count_user = $result_image_user->num_rows;

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
		<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>

		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="css/maincss.css">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/autosize@3.0.21/dist/autosize.min.js"></script>
		
		<script>
			$(document).ready(function(){
				$('.getPath').mouseenter(function(){ //getting image path when mouse enters any of the image or button
					imagePath = $(this).find('img').attr('src'); // gets the attribute value 
					imageID = $(this).find('img').attr('postId'); // gets the attribute value 
					userID_ = $(this).find('img').attr('username'); // gets the attribute value 
					imageUsername = $(this).find('div').attr('usrname'); // gets the attribute value 
					currentUserName = $(document).find('h1').attr('fname'); // gets the attribute value 
					userID = userID_+'_likes';
					userID_cmt = userID_+'_comments';
					like = $(this).find('.like').attr('like'); // gets the attribute value 
					unlike = $(this).find('.unlike').attr('unlike'); // gets the attribute value 
					ifComment = $(this).find('.comments').attr('comment');
					commentArea = $(this).find('.commented');
					console.log("imagePath: "+imagePath);
					console.log("imageID: "+imageID);
					console.log("imageUsername:"+imageUsername);
					console.log("currentUserName: "+currentUserName);
					console.log("userID: "+userID);
					// console.log(like);
					// console.log(unlike);
				});
				
				//image comment by users

					autosize($('textarea'));

					$('.textarea').keypress(function(event){

						var keycode = (event.keyCode ? event.keyCode : event.which);
						var comment = $(this).val();
						if (comment == '' && keycode == '13') {
							// alert('Not entered any thing in the comment');
							event.preventDefault();
						}
						else if (comment != '' && keycode == '13'){
							cmt = '<li><a class="commented-user"><?php echo $userID ?></a><span>'+comment+'</span></li>';
							commentArea.append(cmt);
							event.preventDefault();
							$(this).val('');
							$.ajax({
								type: 'post', // the method 
								url: 'home.php', // The file where my php code is
								data: {
									'postID' : imageID, // all variables i want to pass. In this case, only one.
									'userID_cmt' : userID_cmt,
									'userID' : userID_,
									'imageUsername' : imageUsername,
									'currentUserName' : currentUserName,
									'ifComment' : ifComment,
									'comment' : comment
								},
								success: function(data){
									console.log(ifComment);
								}
							});
						}
					});

					<?php 
					if (isset($_POST['ifComment'])) {
						$ifComment = $_POST['ifComment'];
						// file_put_contents("filename1.txt", $ifComment);
						
					}
						if (isset($_POST['ifComment'])) {
							$postID = $_POST['postID'];
							$userID_cmt = $_POST['userID_cmt'];
							$user_ID = $_POST['userID'];
							$imageUsername = $_POST['imageUsername'];
							$currentUserName = $_POST['currentUserName'];
							$userComment = $_POST['comment'];
							$query_comment = "INSERT INTO $userID_cmt(user_name, userID, post_id, user_comment) VALUES('$currentUserName','$userID','$postID','$userComment')";
							// file_put_contents("filename.txt", $query_comment);
							$result_comment = $conn->query($query_comment);
							if(!$result_comment){
								echo("Query failed: (" . $conn->errno . ") " . $conn->error );
							}
						}
					?>


				// $('.textarea').autoResize();

				// deleting the picture

				$('.delete').click(function(){
					$("#popup_txt").css("display","block");
					$( function() {
						$( "#dialog-confirm" ).dialog({ //dialog popup for confirmation
						  resizable: false,
						  height: "auto",
						  width: 400,
						  modal: true,
						  buttons: {
								"Yeah!": function() { //function for passing the value to php
								  	$( this ).dialog( "close" );
									$.ajax({
										type: 'post', // the method 
										url: 'home.php', // The file where my php code is
										data: {
											'ImgPath': imagePath, // all variables i want to pass. In this case, only one.
										},
										success: function(data) { // in case of success get the output, i named data
											//alert(data); // do something with the output, like an alert
											location.reload();
											alert('Deleted, Sorry ;(');
										}
									});
								},
								"Na": function() {
								  	$( this ).dialog( "close" );
								}
						  	}
						});
					});
				});

				// menu for the picture
				
				$(".dots").click(function(e){
					if ($(this).parent().find('.dottedMenu').hasClass("menuShow")) {
						$(this).parent().find('.dottedMenu').removeClass("menuShow");
					}
					else{
						$(".dottedMenu").removeClass("menuShow");
						$(this).parent().find('.dottedMenu').addClass("menuShow");
					}
				});
				$(document).on('click',function(e){
					var target = $( event.target );
					var value = $(event.target.parentNode);
					if (!target.is('div.dots') && !value.is('div.dots') ) {
						$(".dottedMenu").removeClass("menuShow");
					}
				});

				$(".like").click(function(){
						$.ajax({
							type: 'post', // the method 
							url: 'home.php', // The file where my php code is
							data: {
								'postID' : imageID, // all variables i want to pass. In this case, only one.
								'user_ID' : userID,
								'imageUsername' : imageUsername,
								'currentUserName' : currentUserName,
								'like' : like
							}
						});
					<?php 
						$current_likes = $userID.'_likes';
						if (isset($_POST['like'])) {
							$checklike = $_POST['like'];
						}
						if ($checklike == 'like') {
							$postID = $_POST['postID'];
							$user_ID = $_POST['user_ID'];
							$imageUsername = $_POST['imageUsername'];
							$currentUserName = $_POST['currentUserName'];
							$queryCheckLiked = "SELECT status FROM ".$user_ID." WHERE post_id = ".$postID." AND userID = '".$userID."'";
							$result_queryCheckLiked = $conn->query($queryCheckLiked);
							if (!$result_queryCheckLiked) {
								echo("Query failed: (" . $conn->errno . ") " . $conn->error );
							}
							$fetch_queryCheckLiked = $result_queryCheckLiked->fetch_row();
							$row_count_CheckLiked = $result_queryCheckLiked->num_rows;
							if ($fetch_queryCheckLiked[0] == 'liked') {
								$query_forLiked = "UPDATE ".$user_ID." SET status = '' WHERE post_id = ".$postID." AND userID = '".$userID."'";
								$result_forLiked = $conn->query($query_forLiked);
								if (!$result_forLiked) {
									echo("Query failed: (" . $conn->errno . ") " . $conn->error );
								}
							}
							// file_put_contents('text.txt', $row_count_CheckLiked);
						}

					?>
					if ($(this).hasClass("liked")) {
						$(this).removeClass("liked");
						$(this).text('Like');
					}
					else {
						if ($(this).parent().find('.unlike').hasClass("unliked")) {
							$(this).parent().find('.unlike').removeClass("unliked");
							$(this).parent().find('.unlike').text("Unlike");
						}
						$(this).text('Liked');
						$(this).addClass('liked');
			
						<?php
							if ($row_count_CheckLiked == '0') {
								$query_insertLike = "INSERT INTO ".$user_ID."( user_name, userID, post_id, status) VALUES('".$currentUserName."','".$userID."','".$postID."','liked')";
								$result_insertLike = $conn->query($query_insertLike);
								if (!$result_insertLike) {
									echo("Query failed: (" . $conn->errno . ") " . $conn->error );
								}
							}
							else if ($fetch_queryCheckLiked[0] == 'none' || $fetch_queryCheckLiked[0] == 'unliked'){
								$query_forLikeAgain = "UPDATE ".$user_ID." SET status = 'liked' WHERE post_id = ".$postID." AND userID = '".$userID."'";
								$result_forLikeAgain = $conn->query($query_forLikeAgain);
								if(!$result_forLikeAgain){
									echo("Query failed: (" . $conn->errno . ") " . $conn->error );
								}
							}
							else if ($fetch_queryCheckLiked[0] == 'liked') {
								$query_forLiked = "UPDATE ".$user_ID." SET status = 'none' WHERE post_id = ".$postID." AND userID = '".$userID."'";
								$result_forLiked = $conn->query($query_forLiked);
								if (!$result_forLiked) {
									echo("Query failed: (" . $conn->errno . ") " . $conn->error );
								}
							}

						?>
					}
				});
				$(".unlike").click(function(){
					$.ajax({
						type: 'post', // the method 
						url: 'home.php', // The file where my php code is
						data: {
							'postID' : imageID, // all variables i want to pass. In this case, only one.
							'user_ID' : userID,
							'imageUsername' : imageUsername,
							'currentUserName' : currentUserName,
							'like' : unlike
						}
					});
					<?php 
						if (isset($_POST['like'])) {
							$checklike = $_POST['like'];
						}
						if ($checklike == 'unlike') {
							$postID = $_POST['postID'];
							$user_ID = $_POST['user_ID'];
							$imageUsername = $_POST['imageUsername'];
							$currentUserName = $_POST['currentUserName'];
							$queryCheckUnLiked = "SELECT status FROM ".$user_ID." WHERE post_id = ".$postID." AND userID = '".$userID."'";
							$result_queryCheckUnLiked = $conn->query($queryCheckUnLiked);
							if (!$result_queryCheckLiked) {
								echo("Query failed: (" . $conn->errno . ") " . $conn->error );
							}
							$fetch_queryCheckUnLiked = $result_queryCheckUnLiked->fetch_row();
							$row_count_CheckUnLiked = $result_queryCheckUnLiked->num_rows;
						}

					?>
					if ($(this).hasClass("unliked")) {
						$(this).removeClass("unliked");
						$(this).text('Unlike');
					}
					else{
						if ($(this).parent().find('.like').hasClass("liked")) {
							$(this).parent().find('.like').removeClass("liked");
							$(this).parent().find('.like').text("Like");
						}
						$(this).text("Unliked");
						$(this).addClass("unliked");
						<?php
							if ($row_count_CheckUnLiked == '0') {
								$query_insertUnLike = "INSERT INTO ".$user_ID."( user_name, userID, post_id, status) VALUES('".$currentUserName."','".$userID."','".$postID."','unliked')";
								$result_insertUnLike = $conn->query($query_insertUnLike);
								if (!$result_insertUnLike) {
									echo("Query failed: (" . $conn->errno . ") " . $conn->error );
								}
							}
							else if ($fetch_queryCheckUnLiked[0] == 'none' || $fetch_queryCheckUnLiked[0] == 'liked'){
								$query_forUnLikeAgain = "UPDATE ".$user_ID." SET status = 'unliked' WHERE post_id = ".$postID." AND userID = '".$userID."'";
								$result_forUnLikeAgain = $conn->query($query_forUnLikeAgain);
								if(!$result_forUnLikeAgain){
									echo("Query failed: (" . $conn->errno . ") " . $conn->error );
								}
							}
							else if ($fetch_queryCheckUnLiked[0] == 'unliked') {
								$query_forLiked = "UPDATE ".$user_ID." SET status = 'none' WHERE post_id = ".$postID." AND userID = '".$userID."'";
								$result_forLiked = $conn->query($query_forLiked);
								if (!$result_forLiked) {
									echo("Query failed: (" . $conn->errno . ") " . $conn->error );
								}
							}
						?>
					}
				});
			});
		</script>
	</head>
	<body>
		<header>
			<h1 class="Piktorya">Piktorya</h1>
			<ul class="headMenu">
				<li>
					<a href="../logout.php?logout">Logout</a>
				</li>
				<li>
					<a href="../upload.php">Upload Image</a>
				</li>
			</ul>
			<?php
				if ($row_count_user == 0) {
					echo "<h2 fname='".$row['fname']."' > ...Hi ".$row['fname']." welcome to Piktorya...</h2>";
				} else {
					echo "<h2 style='padding-left:1em' fname='".$row['fname']."' >".$row['fname']." ".$row['lname']."</h2>";
				}
			?>
		</header>
		<br>
<?php 
		if ($row_count_user == 0) {
			echo "<p>There are no images of yours to display Please upload one to display any!</p>";
		}
		if (isset($result_image)) {
			// echo "number of image table rows executed ".$row_count;
			// Display all images if any
			for ($j = 0; $j < $row_count; $j++)
			{
				// $file = 'abc';
				$row_display = $result_image->fetch_array(MYSQLI_ASSOC);

				$user_Likes = $row_display['userID'].'_likes';
				$user_Comments = $row_display['userID'].'_comments';
				$eachPostID = $row_display['post_id'];

				$query_getLikes = "SELECT status FROM $user_Likes WHERE post_id = $eachPostID";
				$resut_getLikes = $conn->query($query_getLikes);
				if(!$resut_getLikes){
					echo("Query failed: (" . $conn->errno . ") " . $conn->error);
				}
				$row_countGetLikes = $resut_getLikes->num_rows;

				for ($i = 0; $i < $row_countGetLikes; $i++) { 
					$display_getLikes = $resut_getLikes->fetch_array(MYSQLI_ASSOC);
					if ($display_getLikes['status'] == 'liked') {
						$likes = $likes + 1;
					}
					else if($display_getLikes['status'] == 'unliked'){
						$unlikes = $unlikes + 1;
					}
				}
				echo "<script> console.log('likes'+".$likes.") </script>";
				echo "<script> console.log('unlikes'+".$unlikes.") </script>";
				$query_getComments = "SELECT * FROM (SELECT * FROM $user_Comments WHERE post_id = $eachPostID ORDER BY serial_no DESC LIMIT 3) SUB ORDER BY serial_no ASC";
				$result_getComments = $conn->query($query_getComments);
				if (!$result_getComments) {
					echo("Query failed: (" . $conn->errno . ") " . $conn->error );
				}
				$row_count_getComments = $result_getComments->num_rows;
				// file_put_contents("filename.txt", $row_count_getComments);

				$query_getCurUsrLikes = "SELECT status FROM $user_Likes WHERE post_id = $eachPostID AND userID = '$userID'";
				$result_getCurUsrLikes = $conn->query($query_getCurUsrLikes);
				$display_getCurUsrLikes = $result_getCurUsrLikes->fetch_array(MYSQLI_ASSOC);
				// $row_display = mysqli_fetch_row($result_image);
				// echo $row_display[2];
				// echo "<br /><br/>".$row_display['image'];
	?>	<br><br>

		<div class="getPath display">
			<div class="userName-head" usrname="<?php echo $row_display['user_name'] ?>">
				<div class="ts_align">
					<span class="username" style="text-indent: 1em;"><?php echo $row_display['user_name'] ?></span>
					<div class="dots" style="float: right;">
						<li></li>
						<li></li>
						<li></li>
					
					<div class="dottedMenu">
					<?php 
						if ($userID == $row_display['userID']) {
					?>
						<div class="delete" >
							<li id="imgDeleteBtn" name="dlt" type="submit" >Delete</li>
							<div id="dialog-confirm" title="Delete the picture?">
							  	<p id="popup_txt" style="display: none;"><span class="ui-icon ui-icon-alert" style="margin:12px 12px 20px 0;"></span>Are you sure?</p>
							</div>
						</div>
						<li>edit</li>
					<?php 
						}
					?>
						<li>share</li>
					</div>
					</div>
				</div>
			</div>
			<img id="image" src="../<?php echo $row_display['image_location']?>" alt="<?php echo $row_display['user_name']?> Image" postID="<?php echo $row_display['post_id'] ?>" username="<?php echo $row_display['userID']?>">
			<br>
			<ul class="opinion" >
				<li class="like <?php if($display_getCurUsrLikes['status'] == 'liked')echo("likes")?>" like="like"> Like </li>
				<li><?php if ($likes>0) { echo "+".$likes; } ?></li>
				<li style="display: inline-block;">|</li>
				<li class="unlike <?php if($display_getCurUsrLikes['status'] == 'unliked')echo("unlikes")?>" unlike="unlike"> Unlike </li>
				<li><?php if ($unlikes>0) {	echo "+".$unlikes; } ?></li>
			</ul>
			<div class="comments" comment="comment">
				<div class="comment">
					<div class="cmtborder">
						<ul class="commented">
						<?php
							if ($row_count_getComments !="") {
								for ($i=0; $i < $row_count_getComments; $i++) { 
									$display_getComments = $result_getComments->fetch_array(MYSQLI_ASSOC);
									echo "<li><a class=\"commented-user\"> ".$display_getComments['userID']." </a><span>".$display_getComments['user_comment']."</span></li>";
								}
							}
						?>
						</ul>
					</div>
					<form class="textarea-form">
						<textarea class="textarea" style="height: 18px;" placeholder="comment here..."></textarea>
					</form>
				</div>				
			</div>
			<br>
			<br>
		</div>

<?php
			if ($display_getCurUsrLikes['status'] == 'liked') {
				echo('<script>$(".likes").text("Liked");
					$(".likes").addClass("liked");</script>');
			}
			else if ($display_getCurUsrLikes['status'] == 'unliked') {
				echo("<script>$('.unlikes').text('Unliked');
					$('.unlikes').addClass('unliked');</script>");
			}
			$likes = $unlikes = 0;
		}
		$result_image->free();
	}
	if(isset($_POST['ImgPath'])) { //if i have this post
		$ImgPath = $_POST['ImgPath'];
		// echo $ImgPath;
		$trimPath = trim($ImgPath,"../");
		$query_delete = "DELETE FROM $userID WHERE image_location='$trimPath'";
		$result_delete = $conn->query($query_delete);
		if (!$result_delete) {
			echo("Query failed: (" . $conn->errno . ") " . $conn->error );
		}
		unlink($ImgPath);
		// $var = var_export($trimpath, true);
		// file_put_contents('text.txt', $ImgPath);
	}
	
?>

<?php CloseCon(); ?><br><br>
	</body>
</html>