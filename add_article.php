<?php
			session_start();
			   				
	 	  		$email = $_SESSION['access'];
   		
			$conn = mysqli_connect("localhost", 'root', '', 'athena');
			if(mysqli_connect_errno()){
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}

			$sql =  "SELECT P.person_name 
	 	  			FROM Person as P, User as U
	 	  			WHERE U.user_email = '$email' and U.person_id = P.person_id";
			$retval = mysqli_query($conn, $sql);
			$row = mysqli_fetch_array($retval, MYSQL_ASSOC);

			//CoAuthors
			$sql =  "SELECT P.person_name 
	 	  			FROM Person as P, User as U
	 	  			WHERE U.user_email != '$email' and U.person_id = P.person_id";
			$retval2 = mysqli_query($conn, $sql);

			$sql =  "SELECT P.person_name 
	 	  			FROM Person as P, Outsider as O
	 	  			WHERE O.person_id = P.person_id";
			$retval9 = mysqli_query($conn, $sql);

			$sql =  "SELECT P.publisher_name 
	 	  			FROM Publisher as P";
			$retval3 = mysqli_query($conn, $sql);

			$sql =  "SELECT T.topic_name 
	 	  			FROM Topic as T";
			$retval4 = mysqli_query($conn, $sql);

			$sql =  "SELECT T.tag_text 
	 	  			FROM Tag as T";
			$retval5 = mysqli_query($conn, $sql);
			$sql =  "SELECT R.reference_text 
	 	  			FROM Reference as R";
			$retval8 = mysqli_query($conn, $sql);


			$name = '';
			if($_SERVER["REQUEST_METHOD"] == "POST"){
	 	  	$name = $_POST["aname"];
	 	  	$author = $_POST["authors"];
	 	  	$reference = $_POST["referenceList"];
	 	  	$publisher = $_POST["publisher"];
	 	  	$topic = $_POST["topic"];
	 	  	$tag = $_POST["tag"];
	 	  	$files = $_POST["files"];




	 	  	/**** DOC READ ***/

	 	  	class DocxConversion{
    private $filename;

    public function __construct($filePath) {
        $this->filename = $filePath;
    }

    private function read_doc() {
        $fileHandle = fopen($this->filename, "r");
        $line = @fread($fileHandle, filesize($this->filename));   
        $lines = explode(chr(0x0D),$line);
        $outtext = "";
        foreach($lines as $thisline)
          {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisline)==0))
              {
              } else {
                $outtext .= $thisline." ";
              }
          }
         $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
        return $outtext;
    }

    private function read_docx(){

        $striped_content = '';
        $content = '';

        $zip = zip_open($this->filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }

 /************************excel sheet************************************/

function xlsx_to_text($input_file){
    $xml_filename = "xl/sharedStrings.xml"; //content file name
    $zip_handle = new ZipArchive;
    $output_text = "";
    if(true === $zip_handle->open($input_file)){
        if(($xml_index = $zip_handle->locateName($xml_filename)) !== false){
            $xml_datas = $zip_handle->getFromIndex($xml_index);
            $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            $output_text = strip_tags($xml_handle->saveXML());
        }else{
            $output_text .="";
        }
        $zip_handle->close();
    }else{
    $output_text .="";
    }
    return $output_text;
}

/*************************power point files*****************************/
function pptx_to_text($input_file){
    $zip_handle = new ZipArchive;
    $output_text = "";
    if(true === $zip_handle->open($input_file)){
        $slide_number = 1; //loop through slide files
        while(($xml_index = $zip_handle->locateName("ppt/slides/slide".$slide_number.".xml")) !== false){
            $xml_datas = $zip_handle->getFromIndex($xml_index);
            $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            $output_text .= strip_tags($xml_handle->saveXML());
            $slide_number++;
        }
        if($slide_number == 1){
            $output_text .="";
        }
        $zip_handle->close();
    }else{
    $output_text .="";
    }
    return $output_text;
}


    public function convertToText() {

        if(isset($this->filename) && !file_exists($this->filename)) {
            return "File Not exists";
        }

        $fileArray = pathinfo($this->filename);
        $file_ext  = $fileArray['extension'];
        if($file_ext == "doc" || $file_ext == "docx" || $file_ext == "xlsx" || $file_ext == "pptx")
        {
            if($file_ext == "doc") {
                return $this->read_doc();
            } elseif($file_ext == "docx") {
                return $this->read_docx();
            } elseif($file_ext == "xlsx") {
                return $this->xlsx_to_text();
            }elseif($file_ext == "pptx") {
                return $this->pptx_to_text();
            }
        } else {
            return "Invalid File Type";
        }
    }

}

    	$docObj = new DocxConversion($files);
		//$docObj = new DocxConversion("test.docx");
		//$docObj = new DocxConversion("test.xlsx");
		//$docObj = new DocxConversion("test.pptx");

			/******DOC READ *****/
			$sql =  "SELECT P.person_id
					FROM User as U, Person as P
					WHERE U.user_email = '$email' and U.person_id = P.person_id
					";
			$retval6 = mysqli_query($conn, $sql);
			$row6=mysqli_fetch_array($retval6, MYSQL_ASSOC);
			$person_id = $row6['person_id'];

			$sql =  "SELECT T.topic_id
					FROM Topic as T
					WHERE T.topic_name = '$topic'
					";
			$retval6 = mysqli_query($conn, $sql);
			$row6=mysqli_fetch_array($retval6, MYSQL_ASSOC);
			$topic_id = $row6['topic_id'];

			$sql =  "SELECT P.person_id
					FROM Person as P
					WHERE P.person_name = '$author'
					";
			$retval6 = mysqli_query($conn, $sql);
			$row6=mysqli_fetch_array($retval6, MYSQL_ASSOC);
			$author_id = $row6['person_id'];
/*
			if($author_id == ''){
				$sql = "INSERT INTO Person VALUES(DEFAULT, '$author', 1,1)";
				$retval6 = mysqli_query($conn, $sql);


				$sql = "SELECT MAX(P.person_id) as maxper
               		 	FROM Person as P";
				$retval6 = mysqli_query($conn, $sql);
				$row6=mysqli_fetch_array($retval6, MYSQL_ASSOC);
				$author_id = $row6['maxper'];
			}	
			*/

			$sql = "SELECT P.publisher_id
                FROM Publisher as P
                WHERE P.publisher_name = '$publisher'";
			$retval6 = mysqli_query($conn, $sql);
			$row6=mysqli_fetch_array($retval6, MYSQL_ASSOC);
			$publisher_id = $row6['publisher_id'];	

			$sql = "SELECT R.indexR
                FROM Reference as R
                WHERE R.reference_text = '$reference'";
			$retval6 = mysqli_query($conn, $sql);
			$row6=mysqli_fetch_array($retval6, MYSQL_ASSOC);
			$reference_id = $row6['indexR'];


			$sql = "SELECT T.tag_id
                FROM Tag as T
                WHERE T.tag_text = '$tag'";
			$retval6 = mysqli_query($conn, $sql);
			$row6=mysqli_fetch_array($retval6, MYSQL_ASSOC);
			$tag_id = $row6['tag_id'];

    		$current_date = date("Y.m.d");
			
			$sql =  "INSERT INTO Post VALUES(DEFAULT, '$current_date', 0, NULL)";
        	$retval6 = mysqli_query($conn, $sql);

        	$sql = "SELECT MAX(P.post_id) as maxp
                	FROM Post as P";
			$retval6 = mysqli_query($conn, $sql);
			$row6=mysqli_fetch_array($retval6, MYSQL_ASSOC);
			$post_id = $row6['maxp'];

			$sql =  "INSERT INTO Uploads VALUES($post_id, $person_id)";
        	$retval6 = mysqli_query($conn, $sql);


        	$sql =  "INSERT INTO Belongs_to VALUES($topic_id, $post_id)";
        	$retval6 = mysqli_query($conn, $sql);


        	$sql =  "INSERT INTO Refers VALUES($post_id, $reference_id)";
        	$retval6 = mysqli_query($conn, $sql);


        	$sql =  "INSERT INTO Contains VALUES($post_id, $tag_id)";
        	$retval6 = mysqli_query($conn, $sql);
        	$docText= $docObj->convertToText();
        	$sql =  "INSERT INTO Article VALUES('$name', '$docText', '$current_date', $post_id, $publisher_id)";
        	$retval6 = mysqli_query($conn, $sql);

	 	  
        	}
 			if($name == ''){

 			}
 			else{
        mysqli_close($conn);
           ?>

        ?>

        <html>
        <body>
        <?php
        echo "<form method='POST' action='./homepage.php' id='fchangeform'>";
        echo "<input type='Hidden' name='email' value='$email'>";
        echo "</form>";
        ?>
        <script type="text/javascript">
            document.getElementById('fchangeform').submit(); // SUBMIT FORM
        </script>
        
        </body>
        </html>
<?php

        }
 			
        
        mysqli_close($conn);


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
        <link rel="stylesheet" type="text/css" href="add_article.css" />
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
<div id = "page">
	<h1>Add Article</h1>
		<form action="./add_article.php" method = "POST">
		<div id="article" class = "combined">
            <div class = "line">
            <label><b>Name: </b></label>
            <input type="text" name="aname" required>
            <br></br>
            </div>
            <div class = "line">
            <label><b>Author(s):           <?php echo $row['person_name']?> and</b></label>
            <select id="aut" name="authors">
            	<option value="null">Select Co-Author</option>
            	<?php
                while($row2=mysqli_fetch_array($retval2, MYSQL_ASSOC)){                                              
                   echo "<option value='".$row2['person_name']."'>".$row2['person_name']."</option>";
                }
                while($row9=mysqli_fetch_array($retval9, MYSQL_ASSOC)){                                              
                   echo "<option value='".$row9['person_name']."'>".$row9['person_name']."</option>";
                }
                ?>
            </select>
            <br></br>
            </div>
            <div class = "line">
            <label><b>References:</b></label>
            <select id="ref" name="referenceList">
            	<option value="null">Select References</option>
            	<?php
                while($row2=mysqli_fetch_array($retval8, MYSQL_ASSOC)){                                              
                   echo "<option value='".$row2['reference_text']."'>".$row2['reference_text']."</option>";
                }
                ?>
            </select>
            <br></br>
            </div>
            <div class = "line">
            <label><b>Publisher:</b></label>
            <select id="publisher" name="publisher">
            	<option value="null">Select Publisher</option>
            	<?php
                while($row3=mysqli_fetch_array($retval3, MYSQL_ASSOC)){                                              
                   echo "<option value='".$row3['publisher_name']."'>".$row3['publisher_name']."</option>";
                }
                ?>
            </select>
            <br></br>
            <div class = "line">
            <label><b>Topic:</b></label>
            <select id="topic" name="topic">
            	<option value="null">Select Topic</option>
            	<?php
                while($row4=mysqli_fetch_array($retval4, MYSQL_ASSOC)){                                              
                   echo "<option value='".$row4['topic_name']."'>".$row4['topic_name']."</option>";
                }
                ?>
            </select>
            <br></br>
            </div>
            <div class = "line">
            <label><b>Tag:</b></label>
            <select id="tag" name="tag">
            	<option value="null">Select Tag</option>
            	<?php
                while($row5=mysqli_fetch_array($retval5, MYSQL_ASSOC)){                                              
                   echo "<option value='".$row5['tag_text']."'>".$row5['tag_text']."</option>";
                }
                ?>
            </select>
            <br></br>
            </div>
            <div class = "line">
            <label><b>Upload the article:</b></label>
            <input type="file" name = "files" accept="application/msword">
            <br></br>
            </div>
            <br></br>
            <center><button type="submit">Add Article</button></center>
		</div>
		</form>
</div>	
</body>
</html>