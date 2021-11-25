<?php
	define("SITE_ADDR", "http://localhost/tutorials/search_engine");
	include("./include.php");
	$site_title = 'Simple Search Engine | HeyTuts.com tutorials';
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		
		<title><?php echo $site_title; ?></title>
		
		<!-- link to the stylesheets -->
		<link rel="stylesheet" type="text/css" href="./main.css"></link>
	</head>
	
	<body>
		
		<div id="wrapper">
			
				
			<div id="top_header">
				<div id="nav">
					<a href="<?php echo SITE_ADDR;?>/new_entry.php">New Entry</a>
				</div>

				<div id="logo">
					<h1><a href="<?php echo SITE_ADDR;?>">simple search engine</a></h1>
				</div>
			</div>


			<div id="main" class="shadow-box"><div id="content">
				
				<?php

					// check to see if the form was submitted
					if (isset($_POST['addBtn'])){
						// get all the form data
						$title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : "";
						$blurb = isset($_POST['blurb']) ? htmlspecialchars($_POST['blurb']) : "";
						$url = isset($_POST['url']) ? htmlspecialchars($_POST['url']) : "";
						$keywords = isset($_POST['keywords']) ? htmlspecialchars($_POST['keywords']) : "";

						// make sure all the form data was submitted
						if ($title && $url && $blurb & $keywords){
							// setup some vars
							$id = '';

							// CONNECT TO THE DB
							$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

							mysqli_query($conn, "INSERT INTO search_engine VALUES ('$id', '$title', '$blurb', '$url', '$keywords')");
							
							// make sure entry was created
							if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM search_engine WHERE title='{$title}' AND url='{$url}'"))){
								echo 'New entry was added';

								$title = '';
								$url = '';
								$keywords = '';
								$blurb = '';
							}
							else
								echo 'An error occured. No new entry was added.';
						}
						else
							echo 'Please provided all data. The entire form is required.';

					}
				?>

				<form action="" method="POST" name="">
					<table>
						<tr>
							<td>Title:</td>
							<td><input type="text" name="title" value="<?php echo isset($title) ? $title : ""; ?>" /></td>
						</tr>
						<tr>
							<td>URL:</td>
							<td><input type="text" name="url" value="<?php echo isset($url) ? $url : ""; ?>" /></td>
						</tr>
						<tr>
							<td>Blurb:</td>
							<td><textarea name="blurb" value="<?php echo isset($blurb) ? $blurb : ""; ?>"></textarea></td>
						</tr>
						<tr>
							<td>Keywords:</td>
							<td><textarea name="keywords" value="<?php echo isset($keywords) ? $keywords : ""; ?>"></textarea></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" name="addBtn" value="Add Entry" /></td>
						</tr>
					</table>
				</form>

			</div></div>

			<div id="footer">
				<div class="left">
					<a href="https://www.heytuts.com" target="_blank">HeyTuts.com</a>
				</div>
				<div class="right">
					<a target="_blank" href="https://www.heytuts.com/web-dev/php/simple-search-engine-in-php">read the article</a> | 
					<a target="_blank" href="https://www.heytuts.com/video/simple-search-engine-in-php">watch the video</a>
				</div>
				<div class="clear"></div>
			</div>

		</div>

	</body>
</html>