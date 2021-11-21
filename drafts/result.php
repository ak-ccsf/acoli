<?php
$db = new SQLite3('acoli.db');
//echo $_POST['safety_index'];
$results = $db->query('SELECT * FROM cities WHERE city_name = "New York" OR city_name = "San Francisco"');
//while ($row = $results->fetchArray()) {
//    var_dump($row);
//}
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
  for ($i = 0; $i < 2; $i++) {
    $count = "(SELECT COUNT(*) FROM quality_of_life WHERE " . $indices[$i] . " IS NOT NULL)";
    $rank = "(RANK() OVER (ORDER BY IFNULL(" . $indices[$i] . ", ";
    if ($i < 4) {
      $rank .= " -9999) DESC";
    } else {
      $rank .= " 9999) ASC";
    }

    $rank .= "))";
    $index_calc[$i] = "(100 *( 1 + " . $count . " - " . $rank . ") / (1.0 * " . $count . "))";
  }
  for ($i = 0; $i < 2; $i++) {
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
  $select = "SELECT city_name, region, country_name, image_url, wiki_url, " . "((" . $score . ") / ( 1.0 * " . $max_score . ")) * 100 AS percent_match ";
  return $select;
};

$query = buildSelectClause() . "FROM cities NATURAL JOIN countries NATURAL JOIN quality_of_life JOIN image_urls ON cities.city_id = image_urls.city_id ORDER BY percent_match DESC";
$results = $db->query($query);
echo "<h1>Results - Your Top Cities</h1>\n<hr>";
for($i = 0; $i < 10; $i++) {
    $row = $results->fetchArray();
    $line = "<h2>" . ($i + 1) . '. ' . $row['city_name'] . ', ';
    if($row['region'] != '') {
        $line .= $row['region'] . ', ';
    }
    $line .= $row['country_name'] . ':&emsp;' . $row['percent_match'] . "%</h2>";
    echo $line;
    echo "<a href='https://en.wikipedia.org" . $row['wiki_url'] . "' target='_blank'><img src=\"" . $row['image_url'] . "\" width=\"500\" /></a>";
    echo "<hr>";
}
?>
