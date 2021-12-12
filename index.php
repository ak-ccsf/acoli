<!DOCTYPE html>
<?php
define("SITE_ADDR", "http://localhost:8000/");
//include("./include.php");
$site_title = 'aqoli';
?>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <link rel="stylesheet" href="./styles.css">
</head>

<body>
    <div class="header">
        <div class="left">
             <div class="title"> <a href="index.php">Aqoli</a></div>
          <ul class="navbar">
            <li class="navbar-links"><a href="qualityoflife.php">Quality of Life</a></li>
            <li class="navbar-links"><a href="citycompare.php">City Compare</a></li>
            <li class="navbar-links"><a href="myplacequiz.php">My Place Quiz</a></li>
          </ul>
        </div>
      </div>

    <div id="main" class="content">
        <div class="home-content">
            <h1>Search Best Places To Live</h1>
            <div id='form'>
                <form method='get' class='form'>
                    <div class='form'>
                        <input
                            type='text'
                            class='input'
                            placeholder='Enter a City'
                            required
                            size='15' maxlength = '100'
                            name='k'
                            autocomplete="off"
                        />
                    </div>
                </form>
            <?php

            // CHECK TO SEE IF THE KEYWORDS WERE PROVIDED
            if (isset($_GET['k']) && $_GET['k'] != '') {

                // save the keywords from the url
                $k = trim($_GET['k']);

                // create a base query and words string
		$query_string = "SELECT cities.city_id, city_name, region, country_name,
			wiki_url, max_contributors
			FROM cities NATURAL JOIN countries
                        LEFT JOIN quality_of_life ON cities.city_id = quality_of_life.city_id
                        WHERE city_name || ' ' || region || ' ' || country_name ";

                $display_words = "";
                // seperate each of the keywords
                $keywords = explode(' ', $k);
                foreach ($keywords as $word) {
	            $query_string .= " LIKE '%" . $word
		        . "%' AND city_name || ' ' || region || ' ' || country_name ";
                    $display_words .= $word . " ";
                }
		$query_string = substr($query_string, 0, strlen($query_string) - 55)
		    . " ORDER BY max_contributors DESC";

                // connect to the database
                // commented out mysqli example to adapt our sqlite3 db
                //$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

                $conn = new SQLite3('aqoli.db');

                // commented out mysqli_query to adapt our sqlite3 query
                //$query = mysqli_query($conn, $query_string);

                $query = $conn->query($query_string);
                error_log($query_string);

                // comment out mysqli $result_count to adapt sqlite3 compatible result_count
                //$result_count = mysqli_num_rows($query);

		$result_count_query_string = "SELECT COUNT(*) FROM cities
			NATURAL JOIN countries
                        WHERE city_name || ' ' || region || ' ' || country_name ";
                error_log($result_count_query_string);

                $display_words = "";
                foreach ($keywords as $word) {
                    $result_count_query_string .= " LIKE '%" . $word . "%' AND city_name || ' ' || region || ' ' || country_name ";
                    $display_words .= $word . " ";
                }
                $result_count_query_string = substr($result_count_query_string,
                                              0,
                                              strlen($result_count_query_string) - 55);
                error_log($result_count_query_string);

                // watch these next several lines for the hack-around to count results
                //$result_count = (int)$conn->query($count_query_string);
                $result_count_query = $conn->query($result_count_query_string);
                $result_count_array = $result_count_query->fetchArray();
                $result_count = $result_count_array[0];
                error_log("result_count:'" . $result_count . "'");

                // check to see if any results were returned
                if ($result_count > 0) {

                    // display search result count to user
                    echo '<br /><div class="right"><b><u>' . $result_count . '</u></b> results found</div>';
                    echo 'Your search for <i>' . $display_words . '</i> <hr /><br />';

                    echo '<table class="search-table">';

                    // display all the search results to the user
                    // uncoment mysqli fetch to fetch query results in an sqlite3 compatible way
                    //while ($row = mysqli_fetch_assoc($query)){
                    // consider using a for loop with a value limiting how many results per page
                    while ($row = $query->fetchArray()) {

                        // adapted example with one that works with our db
                        echo '<tr>
                                  <td>
                                    <h3><a href="./qualityoflife.php?city=' . $row['city_id'] . '">' . $row['city_name'] . '</a></h3>
                                  </td>
                                  <td>' . $row['region'] . '</td>
                                  <td><i>' . $row['country_name'] . '</i></td>
                              </tr>';
                    }

                    echo '</table>';
                } else
                    echo 'No results found. Please search something else.';
            } else
                echo '';

            ?>

        </div>
                    <div>
                        <div class="card_container">
                            <div class="cards">
                                <div class="column">
                                    <div class="card">
                                        <div>
                                            <img alt="Quality of life picture" src="quality.jpeg">
                                        </div>
                                        <div class="container">
                                            <h2>Quality of life</h2>
                                            <p>Find information on quality of life, purchasing power, property price to income ratios, and more about cities of interest.</p>
                                            <button class="card_button" onClick="location.href='qualityoflife.php'">Search city by quality</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="card">
                                        <div>
                                            <img alt="Quality of life picture" src="copmare.png">
                                        </div>
                                        <div class="container">
                                            <h2>Compare cities</h2>
                                            <p>Compare cities on quality of life, cost of living, safety and more. Explore where to move based on your personal preferences.</p>
                                            <button class="card_button" onClick="location.href='citycompare.php'">Compare cities</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="card">
                                        <div>
                                            <img alt="Quality of life picture" src="best_place.jpeg">
                                        </div>
                                        <div class="container">
                                            <h2>Where is your best place?</h2>
                                            <p>You might ask yourself “Where should I live”? This quiz will help you find the top 10 places to live based on your priorities.</p>
                                            <button class="card_button" onClick="location.href='myplacequiz.php'">Take a quiz</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    </div>

    <div class="footer">
        <ul class="bottom-links">
          <li>About Us</li>
          <li>Contact Information</li>
          <li>Copyright © 2021 by CCSF</li>
        </ul>
    </div>
    <!--<div id="footer">
        <div class="left">
            <a href="https://github.com/ak-ccsf/acoli" target="_blank">site code on github</a>
        <div class="clear"></div>
    </div>-->

</div>

</body>
</html>
