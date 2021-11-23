<?php
$db = new SQLite3('acoli.db');


// buildSelectClause() - builds and returns a select clause for a SQL query
//     based on relative importance of different factors as ranked by user
function buildSelectClause() {
  $select = "";
  $score = [];
  $max_score = 0.0;
  $indices = ["purchasing_power_index", 
              "safety_index",
              "health_care_index",
              "climate_index",
              "cost_of_living_index",
              "property_price_to_income_ratio",
              "traffic_commute_time_index",
              "pollution_index"];
  $index_calc = [];
  // scale scores - all range from 0-100 so none outweigh the others
  for ($i = 0; $i < count($indices); $i++) {
    $count = "(SELECT COUNT(*) FROM quality_of_life WHERE "
               . $indices[$i] . " IS NOT NULL)";
    // rank() - get percentile to prevent ouliers from skewing numbers
    $rank = "(RANK() OVER (ORDER BY IFNULL(" . $indices[$i] . ", ";
    if ($i < 4) {
      $rank .= " -9999) DESC";
    } else {
      $rank .= " 9999) ASC";
    }

    $rank .= "))";
    $index_calc[$i] = "(100 *( 1 + " . $count . " - " . $rank . ") / (1.0 * " . $count . "))";
  }
  // use importance rankings from quiz as multipliers
  for ($i = 0; $i < count($indices); $i++) {
    $multiplier = $_POST[$indices[$i]];
    if ($multiplier != 0) {
      array_push($score, ("(" . $multiplier . " * " . $index_calc[$i] . ")"));
      $max_score += 100 * $multiplier;
    }
  }
  $score = implode(" + ", $score);
  if ($score == "") {
    $score = "quality_of_life_index";
    $max_score = "(SELECT MAX(quality_of_life_index) FROM quality_of_life)";
  }
  //$select = "SELECT city_id, city_name, region, country_name, (". $score . ") AS score, " . "((" . $score . ") / ( 1.0 * " . $max_score . ")) * 100 AS percent_match ";
  $select = "SELECT *, " . "((" . $score . ") / ( 1.0 * " . $max_score . ")) * 100 AS percent_match ";
  return $select;
};


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


$query = buildSelectClause() . "FROM cities NATURAL JOIN countries NATURAL JOIN quality_of_life JOIN image_urls ON cities.city_id = image_urls.city_id "
       . buildWhereClause() . " ORDER BY percent_match DESC";
$results = $db->query($query);
echo "<h1>Results - Your Top Cities</h1>\n<hr>";
echo "<table id='resultTable'>";
for($i = 0; $i < 10; $i++) {
    echo "<tr><td colspan='2'>";
    $row = $results->fetchArray();
    $line = "<h2>" . ($i + 1) . '. ' . $row['city_name'] . ', ';
    if($row['region'] != '') {
        $line .= $row['region'] . ', ';
    }
    $line .= $row['country_name'] . ':&emsp;' . $row['percent_match'] . "%</h2>";
    echo $line;
    echo "</tr>";
    echo "<tr>";
    echo "<td>";
    echo "<img style='float:left' src=\"" . $row['image_url'] . "\" width=\"500\" />";
    echo "</td><td>";
    echo "<table style='float:right' class='city_stats'  border='1'>";
    echo "<tr><td>Quality of Life:</td>" .
         "<td>Purchasing Power</td>" .
         "<td>Safety</td>" .
         "<td>Health Care</td>" .
         "<td>Climate</td>" .
         "<td>Cost of Living</td>" .
         "<td>Property Price</td>" .
         "<td>Traffic</td>" .
         "<td>Pollution</td></tr>";
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
    echo "</td>";
    echo "</tr><tr><td colspan='2' style='text-align:center'>";
    echo "<a href='https://en.wikipedia.org" . $row['wiki_url'] . "' target='_blank'>Read More</a> | ";
    echo "<a href='https://www.google.com/maps/place/" . $row['latitude'] . "," . $row['longitude'] . "' target='_blank'>Go There Now!</a>";
    echo "</td></tr>";
    echo "<tr><td colspan='2'><hr></td></tr>";
}
echo "</table>";
?>
