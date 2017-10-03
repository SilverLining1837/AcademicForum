<?php
session_start();
			
				$email = $_SESSION['access'];


			$conn = mysqli_connect("localhost", 'root', '', 'athena');
			if(mysqli_connect_errno()){
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
	 	  	$sql = "SELECT A.article_name
	 	  			FROM Article as A";
	 	  	$retval = mysqli_query($conn, $sql);

	 	  	if($_SERVER["REQUEST_METHOD"] == "POST"){
		 	  	$article_name = $_POST["articles"];
		 	  	$header = $_POST["header"];
		 	  	$explanation = $_POST["explanation"];

		 	  	$sqlid = "SELECT U.person_id
	 	  			FROM User as U
	 	  			WHERE U.user_email = '$email'";
			 	  	$retval2 = mysqli_query($conn, $sqlid);
					$row = mysqli_fetch_array($retval2, MYSQL_ASSOC);
					$user_id = $row['person_id'];

				$sqlid = "SELECT A.post_id
	 	  			FROM Article as A
	 	  			WHERE A.article_name = '$article_name'";
			 	  	$retval2 = mysqli_query($conn, $sqlid);
					$row = mysqli_fetch_array($retval2, MYSQL_ASSOC);
					$article_id = $row['post_id'];


    			$current_date = date("Y.m.d");

		 	  	$current_date = date("Y.m.d");
				$sql =  "INSERT INTO Post VALUES(DEFAULT, '$current_date', 0, NULL)";
				$retval2 = mysqli_query($conn, $sql);

				$sql = "SELECT MAX(P.post_id) as maxp
	                	FROM Post as P";
				$retval6 = mysqli_query($conn, $sql);
				$row6=mysqli_fetch_array($retval6, MYSQL_ASSOC);
				$post_id = $row6['maxp'];

				$sql =  "INSERT INTO Discussion VALUES('$header', '$explanation', $post_id)";
				$retval = mysqli_query($conn, $sql);

				$sql =  "INSERT INTO Forum VALUES($post_id, $article_id)";
				$retval = mysqli_query($conn, $sql);

				$sql =  "INSERT INTO Uploads VALUES($post_id, $user_id)";
				$retval = mysqli_query($conn, $sql);



		        ?>

		        <html>
		        <body>
		        <?php
		        echo "<form method='POST' action='./homepage.php' id='forumform'>";
		        echo "<input type='Hidden' name='email' value='$email'>";
		        echo "</form>";
		        ?>
		        <script type="text/javascript">
		            document.getElementById('forumform').submit(); // SUBMIT FORM
		        </script>
		        
		        </body>
		        </html><?php
		 	  }

    		
?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Original Hover Effects with CSS3" />
        <meta name="keywords" content="css3, transitions, thumbnail, animation, hover, effect, description, caption" />
        <meta name="author" content="Alessio Atzeni for Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
 			 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,500,300,100italic,300italic,400italic,500italic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="add_forum.css" />
        <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		
<title>Athena</title>
</head>
<header class="site-header">
			<nav class="site-nav" role="navigation">
				<div id="logo">
					<img src="logo.png" alt="image" width="80" height="65" class="logo_pic">
				</div>
				<ul>
					<li><a href="./homepage.php" class="home">Home</a></li>
					<li><a href="./forum.php" class="forum">Forum</a></li>
					<li><a href="./question.php" class="quest">Questions</a></li>		
					<li><a class="added"><div id="add_sel">
							<select id="add">
							    <option value="null">Add</option>
							    <option value="A">Article</option>
							    <option value="F">Forum</option>
							</select>
							</div></a></li>
					<li>
					<script >
							$('#add').on('change', function() {
								if($('#add').val() == "A")
								   	window.location.href = "./add_article.php";
								else
									window.location.href = "./add_forum.php";
						});

					</script>				</ul>
			<div id="profile">
			<li>
					<div class="notifications">
					  <div class="toggle"></div>
					  <div class="messages">
						<a href="http://9gag.com/" class="message">Someone added you</a>
						<a href="#" class="message">Someone added you</a>
						<a href="#" class="message">Someone added you</a>
					  </div>
					</div>

					<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
					<script>
						$(function() {
					  $(".notifications .messages").hide();
					  $(".notifications").click(function() {
						if ($(this).children(".messages").children().length > 0) {
						  $(this).children(".messages").fadeToggle(300);
						}
					  });
					});
					</script></li>		
			<form action="./result.php" method="GET">
				<input type="text" name="search" placeholder="Search..." class="nav_search">
				<input type="hidden">
				</form>
				<div id="img">
					<a href="./overview.php"><img src="image.jpg" alt="image" width="45" height="45" class="profile_pic"></a>
				</div>
			</div>
			</nav>
	
		</header>
<body>
<div id="suggested">
	<h2>Suggested People</h2>
	<div id="person1">
		<img src="image.jpg" alt="image" width="100" height="100" class="person_pic">
		<h2>Can Alkan</h2><br>
		<p>Bilkent University</p>
		<ol>
					<li>Likes<br>1000</li>
					<li>Variable2<br>2000</li>
					<li>Variable3<br>3000</li>
					
		</ol>

	</div>
	<div id="person2">
		<img src="image.jpg" alt="image" width="100" height="100" class="person_pic">
		<h2>Can Alkan</h2><br>
		<p>Bilkent University</p>
		<ol>
					<li>Likes<br>1000</li>
					<li>Variable2<br>2000</li>
					<li>Variable3<br>3000</li>
					
				</ol>

	</div>
	<div id="tags">
		<h2>Tags</h2><br>
		<ul>
					<li>#CS</li>
					<li>#Bioinformatic</li>
					<li>#Database</li>
					
				</ul>

	</div>
</div>
<div id = "page" class = "combined">
	<h1>Start Forum Discussion</h1>
	<br></br>
	<form action="./add_forum.php" method = "POST">
		<div id="forum" class = "combined">
		<div class="line">
            <label><b>Select Article: </b></label>
            <select class="select1" name = "articles">
					<option value="null">Please choose the referred article...</option>
					<?php
	                while($row = mysqli_fetch_array($retval, MYSQL_ASSOC)){                                              
	                   echo "<option value='".$row['article_name']."'>".$row['article_name']."</option>";
	                }
	                ?>
			</select>
            <br></br>
        </div>
        <div class="line">
            <label><b>Header:</b></label>
            <input type="text" name="header" required>
            <br></br>
        </div>
            <label><b>Explanation:</b></label>
            <input type="text" name="explanation" placeholder="Please enter an explanation..." required>
            <br></br>
            <br></br>
            <center><button class="submit_button" type="submit">Add Article</button>
		</div>
	</form>
</div>	
</body>
</html>