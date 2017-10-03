<?php
			session_start();  				
	 	  	$email = '';
	 	  	$email = $_GET['email'];
	 	  	if($email == '')
	 	  		$email = $_SESSION['access'];


			$conn = mysqli_connect("localhost", 'root', '', 'athena');
			if(mysqli_connect_errno()){
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}

	 	  	$sqlid = "SELECT U.person_id, U.degree, U.position, U.language, P.person_name
	 	  			FROM User as U, Person as P
	 	  			WHERE U.user_email = '$email' and P.person_id = U.person_id";
	 	  	$retval = mysqli_query($conn, $sqlid);
			$row = mysqli_fetch_array($retval, MYSQL_ASSOC);
			$user_id = $row['person_id'];
			
			$sql =  "SELECT P.person_name, A.article_name, P1.date

						FROM User as U, Uploads as U1, Discussion as D, Person as P, Article as A, Post as P1

						WHERE '$user_id' = U.person_id and U.person_id = U1.person_id

						and U1.post_id = D.post_id and P.person_id = U.person_id and D.post_id = P1.post_id and A.post_id = D.post_id

						ORDER BY P1.date DESC";
			
			$retval = mysqli_query($conn, $sql)

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
        <link rel="stylesheet" type="text/css" href="overview.css" />
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

					</script>
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
				</ul>
			<div id="profile">	
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

<div id="profile_main">
		<img src="image.jpg" alt="image" width="150" height="150" class="person_pic">
		<h2><?php echo $row['person_name'] ?></h2><br>
		<p><?php echo 'Position: ',$row['position'],'<br>Language: ',$row['language'],'<br>Degree: ',$row['degree'] ?></p>

</div>

<div id = "page">
		<ul>
					<li><a href="./overview.php?email=<?php echo $email ?>" class="home">Overview</li></a>
					<?php if($email == $_SESSION['access']){?><li><a href="./information.php" class="home">Information</li></a><?php }?>
					<li><a href="./myarticle.php?email=<?php echo $email ?>" class="home">Article</li></a>
					<li><a href="./myquestion.php?email=<?php echo $email ?>" class="home">Question</li></a>
					<li><a href="./myforum.php?email=<?php echo $email ?>" class="home">Forums</li></a>
					
				</ul>
		<?php 
		while($row2 = mysqli_fetch_array($retval, MYSQL_ASSOC)) {
		?>
		<p style="color: red; font-size: 1.4em;"><?php
				echo 'delete';
		?></p>
		<h3><?php
				echo $row2['person_name'], ' uploaded a new article: ';
		 		echo $row2['article_name'];
		 	
		 ?></h3>
		<p><?php 
		echo $row2['date'],'<br>';
		}
		?></p>
</div>	
</body>
</html>