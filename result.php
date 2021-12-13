<!DOCTYPE html>
<?php
define("SITE_ADDR", "http://localhost:8000/");
$db = new SQLite3('aqoli.db');
?>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>aqoli - Quiz Results</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="header">
        <div class="left">
          <div class="title">
            <div class="title"> <a href="index.php">Aqoli</a></div>
          </div>
          <ul class="navbar">
            <li class="navbar-links"><a href="about.html">About</a></li>
            <li class="navbar-links"><a href="qualityoflife.html">Quality of Life</a></li>
            <li class="navbar-links"><a href="citycompare.php">City Compare</a></li>
            <li class="navbar-links"><a href="myplacequiz.php">My Place Quiz</a></li>
          </ul>
        </div>
      </div>

    <div id="main">
        <div class='content'>
            <div class="result-content">
                <?php

                // getScore() - builds a string to calculate cities' 'scores' based on user's
                // input. To be used in buildSelectClause() and buildFromClause()
                function getScore() {
                    $score = [];
                    $max_score = 0.0;
                    $indices = ["quality_of_life_index",
                                "purchasing_power_index",
                                "safety_index",
                                "health_care_index",
                                "climate_index",
                                "cost_of_living_index",
                                "property_price_to_income_ratio",
                                "traffic_commute_time_index",
                		"pollution_index"];
                    $count = "(SELECT COUNT(*) FROM quality_of_life) ";
                    // scale scores based on rank so none outweigh the others
                    for ($i = 1; $i < count($indices); $i++) {
                    // rank() - get percentile to prevent ouliers from skewing numbers
                    // use importance rankings from quiz as multipliers
                        $multiplier = $_POST[$indices[$i]];
                	if ($multiplier != 0) {
                            $index_rank = "(RANK() OVER (ORDER BY " . $indices[$i];
                	    if ($i < 5) {
                                $index_rank .= " DESC) - 1) ";
                            } else {
                                $index_rank .= " ASC) - 1) ";
                            }
                	    $index_score = "(CASE WHEN (" . $indices[$i] . " IS NULL) "
                                        . "THEN (0) "
                			. "ELSE (1.0 -(" . $index_rank . " / (1.0 * " . $count . "))) "
                			. "END) ";
                            array_push($score, ("(" . $multiplier . " * (" . $index_score . "))"));
                            $max_score += $multiplier;
                        }
                    }
                    $score = implode(" + ", $score);
                    if ($score == "") {
                	    $score = "(1.0 - (RANK() OVER (ORDER BY " . $indices[0] . " DESC)) "
                                   . "/ (1.0 * " . $count . "))";
                        $max_score = "1.0";
                    }
                    return $score;
                }


                // buildSelectClause() - builds and returns a select clause for a SQL query
                //     based on relative importance of different factors as ranked by user
                function buildSelectClause() {
                  $select = "";
                  $max_score = 0.0;
                  $indices = ["quality_of_life_index",
                              "purchasing_power_index",
                              "safety_index",
                              "health_care_index",
                              "climate_index",
                              "cost_of_living_index",
                              "property_price_to_income_ratio",
                              "traffic_commute_time_index",
                              "pollution_index"];
                  for ($i = 1; $i < count($indices); $i++) {
                    $multiplier = $_POST[$indices[$i]];
                    if ($multiplier != 0) {
                      $max_score += $multiplier;
                    }
                  }
                  if ($max_score == "0.0") {
                    $max_score = "1.0";
                  }
                  $select = "SELECT *, 100.0 * score / (SELECT MAX(score) FROM (SELECT " . getScore() . " AS score FROM quality_of_life)) AS percent_match ";
                  return $select;
                };


                // buildFromClause() - builds and returns a FROM clause for a SQL query to
                //     include rank tables
                function buildFromClause() {
                    $from = "FROM cities "
                        . "LEFT JOIN countries ON cities.country_id = countries.country_id "
                        . "LEFT JOIN quality_of_life ON cities.city_id = quality_of_life.city_id "
                	. "LEFT JOIN image_urls ON cities.city_id = image_urls.city_id ";
                    $from .= "NATURAL JOIN (SELECT city_id, " . getScore()
                	   . " AS score FROM quality_of_life) ";
                    return $from;
                }


                // buildWhereClause() - builds and returns a WHERE clause for a SQL query to
                //     filter results as specified by user
                function buildWhereClause() {
                    $where = '';
                    $regions = [];
                    $chosen = explode(';;', $_POST['regionsText']);
                    for ($i = 0; $i < count($chosen); $i++) {
                        array_push($regions, $chosen[$i]);
                    }
                    if (count($regions) < 3) {
                        return '';
                    }
                    $where .= 'WHERE ';
                    $conditions = [];
                    for ($i = 1; $i < count($regions) - 1; $i++) {
                        $condition = '(';
                        $country_region = explode('::', $regions[$i]);
                        $condition .= 'country_name = "' . $country_region[0] . '"';
                        if (count($country_region) > 1) {
                            $condition .= ' AND region = "' . $country_region[1] . '"';
                        }
                        $condition .= ') ';
                        array_push($conditions, $condition);
                    }
                    $where .= implode(' OR ', $conditions);
                    return $where;
                }


		// put together clauses to form query
                $query = buildSelectClause()
                    . buildFromClause()
                    . buildWhereClause()
                    . " ORDER BY percent_match DESC";
                $results = $db->query($query);
                echo "<h1>Results - Your Top Cities</h1>";
                //echo "<div>";


                echo "<table class='result-table'>";
                for($i = 0; $i < 10; $i++) {
                echo "<tr><td>";
                $row = $results->fetchArray();
		if(!$row){
		    break;
		}
                $line = "<h2>" . ($i + 1) . '. ' . $row['city_name'] . ', ';
                if($row['region'] != '') {
                    $line .= $row['region'] . ', ';
                }

                $line .= $row['country_name'] . ':&emsp;' . round($row['percent_match'], 2) . "%</h2>";
		if($row['image_url'] != '') {
	            echo "<img src=\"" . $row['image_url'] . "\" class='result-table-img'"
		        . " alt=\"" . $row['city_name'] . "\">";
		} else {
		    echo "<p>No Image Available</p>";
		}
                echo $line;
                echo "</tr>";
                echo "<tr>";
                echo "<td>";
                echo "<table>";
                echo "<tr><td class='result-table-indexes'>Quality of Life:</td>" .
                     "<td class='result-table-indexes'>Purchasing Power</td>" .
                     "<td class='result-table-indexes'>Safety</td>" .
                     "<td class='result-table-indexes'>Health Care</td>" .
                     "<td class='result-table-indexes'>Climate</td>" .
                     "<td class='result-table-indexes'>Cost of Living</td>" .
                     "<td class='result-table-indexes'>Property Price</td>" .
                     "<td class='result-table-indexes'>Traffic</td>" .
                     "<td class='result-table-indexes'>Pollution</td></tr>";
                echo "<tr><td>" . $row['quality_of_life_index'] . "</td>" .
                     "<td>" . $row['purchasing_power_index'] . "</td>" .
                     "<td>" . $row['safety_index'] . "</td>" .
                     "<td>" . $row['health_care_index'] . "</td>" .
                     "<td>" . $row['climate_index'] . "</td>" .
                     "<td>" . $row['cost_of_living_index'] . "</td>" .
                     "<td>" . $row['property_price_to_income_ratio'] . "</td>" .
                     "<td>" . $row['traffic_commute_time_index'] . "</td>" .
                     "<td>" . $row['pollution_index'] . "</td></tr>";
                //echo "<tr><td>Quality of Life:</td><td>" . $row['quality_of_life_index'] . $row['city_name'] . "</td></tr>";
                echo "</table>";
                echo "</td></tr>";

                echo "<tr><td class='wiki-links'>";
		if($row['wiki_url'] != '') {
                  echo "<a href='https://en.wikipedia.org" . $row['wiki_url'] . "' target='_blank'>Read More</a>";
		}
		if($row['latitude'] != '') {
                echo " | <a href='https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=" . $row['latitude'] . "," . $row['longitude'] . "' target='_blank'>Go There Now!</a>";
		}
                echo "</td></tr>";
                }
                echo "</table>";
                ?>
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
