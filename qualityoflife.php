<!DOCTYPE html>
<?php
define("SITE_ADDR", "http://localhost:8000/");
//include("./include.php");
$site_title = 'aqoli - Quality of Life';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="shortcut icon" type="image/jpg" href="website_logo.jpg"/>
    <script src="citysuggestions.js"></script>
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
                <h1>Quality of life</h1>
                <div>
                    <span>Find information on quality of life, cost of living, purchasing power, property price to income ratios, and more about cities of interest.
                    </span>
                    <div id='form'>
                        <form method='get' class='form'>
                            <div class='form'>
                                <label for="city" class='required'>Enter the Place: </label>
                                <input
                                list='searchSuggestions'
                                class='input'
                                placeholder='Enter a City'
                                required
                                size='15' maxlength = '100'
				name='city'
                                id='city'
                                onkeyup='getSuggestions(this.value)'
				/>
                                <datalist id="searchSuggestions"></datalist>
                            </div>
                            <div>
                                <input type="submit" value="Search" class='buttons'/>
                            </div>
                        </form>


                        <?php

                        // CHECK TO SEE IF THE KEYWORDS WERE PROVIDED
                        if (isset($_GET['city']) && $_GET['city'] != '') {

                            // save the keywords from the url
                            $city = trim($_GET['city']);


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
                                quality_of_life.property_price_to_income_ratio as 'Property price to income ratio'
                                FROM cities LEFT JOIN countries ON cities.country_id = countries.country_id
                                LEFT JOIN quality_of_life on quality_of_life.city_id=cities.city_id
                                WHERE cities.city_id ";


                            //add drop-down with cities?


                            $query_string_city = $query_string . " IS '" . $city . "'";


                            // connect to the database
                            // commented out mysqli example to adapt our sqlite3 db
                            //$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

                            $conn = new SQLite3('aqoli.db');

                            // commented out mysqli_query to adapt our sqlite3 query

                            $query = $conn->query($query_string_city);

			    $numRows = 0;
			    while($query->fetchArray()) {
				$numRows++;
			    }
			    $query->reset();

			    $wikiQuery = "SELECT city_name, image_url, wiki_url, latitude, longitude
				    FROM cities JOIN image_urls ON cities.city_id = image_urls.city_id
                                    WHERE cities.city_id IS " . $city;
			    $wikiData = ($conn->query($wikiQuery))->fetchArray();

                            if ($numRows > 0) {

				echo "<table class='result-table'><tr><td>";
				if($wikiData['image_url'] != '') {
					echo '<img src="' . $wikiData['image_url']
						. '" class="result-table-img" '
						. ' alt="' . $wikiData['city_name'] . '">';
				}
				else {
				    echo 'No Image Available';
				}

				echo '</td></tr>';
				echo '<tr><td>';
                                echo '<div class ="compare-table"><table>';

                                // display all the search results to the user

                                $rows = array();
                                $row = array();
                                $row[] = "Indexes";
                                $rows[] = $row;
                                $colNums = $query->numColumns();
                                for($i = 1; $i < $colNums; $i++) {
                                    $row = array();
                                    $row[] = $query->columnName($i);
                                    $rows[] = $row;
                                }

                                while ($row = $query->fetchArray()) {
                                    for($j = 0; $j < $colNums; $j++) {
                                        $rows[$j][] = $row[$j];
                                    }
                                };

                                foreach ($rows as $row) {
                                    echo '<tr>';
                                    foreach ($row as $col) {
                                        echo '<td>' . $col . '</td>';
                                    };
                                    echo '</tr>';
                                }
				echo '</table></div>';
				echo '</td></tr>';
				echo "<tr><td class='wiki-links'>";
				if($wikiData['wiki_url'] != '') {
                                    echo "<a href='https://en.wikipedia.org"
					    . $wikiData['wiki_url']
					    . "' target='_blank'>Read More</a>";
		                }
		                if($wikiData['latitude'] != '') {
				    echo " | <a href='https://www.google.com/maps/@?api=1&map_action=pano&viewpoint="
				    . $wikiData['latitude'] . "," . $wikiData['longitude']
				    . "' target='_blank'>Go There Now!</a>";
				}
				echo "</td></tr></table>";

                            } else
                                echo '<div id="noResult">No results found for ' . $city .'. Please search something else.</div>';
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
