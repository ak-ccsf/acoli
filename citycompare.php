<!DOCTYPE html>
<?php
define("SITE_ADDR", "http://localhost:8000/");
//include("./include.php");
$site_title = 'aqoli - Compare Cities';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="shortcut icon" type="image/jpg" href="website_logo.jpg"/>
    <script src='citysuggestions.js'></script>
</head>
<body>
    <div class="header">
        <div class="left">
            <div class="title"> <a href="index.php">Aqoli</a></div>
          <ul class="navbar">
            <li class="navbar-links"><a href="about.html">About</a></li>
            <li class="navbar-links"><a href="qualityoflife.php">Quality of Life</a></li>
            <li class="navbar-links"><a href="citycompare.php">City Compare</a></li>
            <li class="navbar-links"><a href="myplacequiz.php">My Place Quiz</a></li>
          </ul>
        </div>
      </div>
    <div id="main">
        <div class="content">
            <div class='compare-content'>
                <h1>City compare</h1>
                <div>
                    <span> Compare cities in different categories. The most popular comparisons are quality of life, cost of living, health care, pollution, purchasing power, property price to income, safety, and commute time.
                    </span>
                    <div id='form'>
                        <form method='get' class='form'>
                            <div class='form'>
                                <label for="city1" class='required'>Enter 1st Place: </label>
                                <input
                                list="searchSuggestions"
                                class='input'
                                placeholder='Enter a City'
                                required
                                size='15' maxlength = '100'
                                name='city1'
                                id='city1'
                                onkeyup='getSuggestions(this.value)'
                                />
                               <datalist id="searchSuggestions"></datalist>
                            </div>
                            <div class='form'>
                                <label for="city2" class='required'>Enter 2nd Place: </label>
                                <input
                                list="searchSuggestions"
                                class='input'
                                placeholder='Enter a City'
                                required
                                size='15' maxlength = '100'
				name='city2'
                                id='city2'
                                onkeyup='getSuggestions(this.value)'
                                />
                            </div>
                            <div>
                                <input type="submit" value="Compare Now" class='buttons'/>
                            </div>
                        </form>


                        <?php

                        // CHECK TO SEE IF THE KEYWORDS WERE PROVIDED
                        if (isset($_GET['city1']) && $_GET['city1'] != '' && isset($_GET['city2']) && $_GET['city2'] != '') {

                            // save the keywords from the url
                            $city1 = trim($_GET['city1']);
                            $city2 = trim($_GET['city2']);

                            // create a base query and words string
			    $query_string = "SELECT
				CASE WHEN region = '' THEN cities.city_name || ', ' || country_name
				ELSE cities.city_name || ', ' || region || ', ' || country_name END,
                                quality_of_life.climate_index as 'Climate Index',
                                quality_of_life.cost_of_living_index as 'Cost of living index',
                                quality_of_life.health_care_index as 'Health care index',
                                quality_of_life.pollution_index as 'Pollution index',
                                quality_of_life.purchasing_power_index as 'Purchasing power index',
                                quality_of_life.quality_of_life_index as 'Quality of life index',
                                quality_of_life.safety_index as 'Safety index',
                                quality_of_life.traffic_commute_time_index as 'Traffic commute time index',
				quality_of_life.property_price_to_income_ratio as 'Property price to income ratio',
                                quality_of_life.max_contributors
				FROM cities
                                LEFT JOIN countries ON cities.country_id = countries.country_id
				LEFT JOIN quality_of_life on quality_of_life.city_id=cities.city_id
                                WHERE cities.city_id ";


                            //add drop-down with cities?


			    $query_string_city1 = $query_string . " IS '" . $city1
				                . "' ORDER BY max_contributors DESC";
                            $query_string_city2 = $query_string . " IS '" . $city2
				                . "' ORDER BY max_contributors DESC";
                            // $display_words .= $word . " ";

                            // connect to the database
                            // commented out mysqli example to adapt our sqlite3 db
                            //$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

                            $conn = new SQLite3('aqoli.db');

                            // commented out mysqli_query to adapt our sqlite3 query

                            $query1 = $conn->query($query_string_city1);
                            $query2 = $conn->query($query_string_city2);

			    $query1rows = 0;
			    while($query1->fetchArray())
				$query1rows++;
			    $query1->reset();
			    $query2rows = 0;
			    while($query2->fetchArray())
				$query2rows++;
			    $query2->reset();
                            if ($query1rows > 0 && $query2rows > 0) {

                                echo '<div class ="compare-table"><table>';

                                // display all the search results to the user

                                $rows = array();
                                $row = array();
                                $row[] = "Indices";
                                $rows[] = $row;
                                $colNums = $query1->numColumns() - 1;
                                for($i = 1; $i < $colNums; $i++) {
                                    $row = array();
                                    $row[] = $query1->columnName($i);
                                    $rows[] = $row;
                                }

                                while ($row = $query1->fetchArray()) {
                                    for($j = 0; $j < $colNums; $j++) {
                                        $rows[$j][] = $row[$j];
                                    }
                                };
                                while ($row = $query2->fetchArray()) {
                                    for($j = 0; $j < $colNums; $j++) {
                                        $rows[$j][] = $row[$j];
                                    }
                                };
                                foreach ($rows as $row) {
                                    echo '<tr>';
                                    foreach ($row as $col) {
			                echo '<td>';
					if($col == '') {
						echo 'Not Enough Data';
					} else {
					    echo $col;
					}
			    		echo '</td>';
                                    };
                                    echo '</tr>';
                                }
                                echo '</table></div>';

                            } else {
				echo '<div id="noResult">No results found for ';
			        if($query1rows == 0){
			            echo $city1;
				    if($query2rows == 0) {
					echo ' or ';
				    }
				}
				if($query2rows == 0) {
				    echo $city2;
				}
				echo '. Please search something else.</div>';
			    }
                        } else
                            echo '';

                        ?>


                    </div>
                </div>
            </div>
        </div>
    </div>


      <div class="footer">
        <ul class="bottom-links">
	  <li class="footer-links"><a href="aboutus.html">About Us</a></li>
          <li><a href="mailto:aqoli2021info@gmail.com">Contact Us</a></li>
          <li>Copyright © 2021 by CCSF</li>
        </ul>
      </div>
</body>
</html>
