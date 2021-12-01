<?php
$db = new SQLite3('./aqoli.db');

$q = $_REQUEST["q"];

#print_r(explode(' ', $q));

$terms = explode(' ', str_replace('%20', ' ', $q));

$suggestions = "";

$query = "SELECT city_id, city_name, region, country_name FROM "
       . "cities NATURAL JOIN countries NATURAL JOIN quality_of_life WHERE ";

$city = "city_name || ' ' || region || ' ' || country_name ";
for($i = 0; $i < count($terms); $i++) {
  if ($i != 0) {
    $query .= "AND ";
  }
  $query .= $city . " LIKE '%" . $terms[$i] . "%' ";
}

$query .= "ORDER BY max_contributors DESC LIMIT 10";

$results = $db->query($query);

while($row = $results->fetchArray()) {
  $suggestions .= $row['city_id'] . "::" . $row['city_name'];
  if($row['region'] != "") {
    $suggestions .= ", " . $row['region'];
  }
  $suggestions .= ", " . $row['country_name'] . ";;";
}
echo $suggestions === "" ? "noop" : $suggestions;

?>
