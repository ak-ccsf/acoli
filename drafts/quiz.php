<?php
include('quiz.html');

$db = new SQLite3('aqoli.db');
$countries = $db->query('SELECT country_id, country_name FROM countries ORDER BY country_name');
$selectCountry = '<select id="selectCountry" onchange="buildRegionList()">
<option value="">---Select Country---</option>';
while($row = $countries->fetchArray()) {
    //echo $row['country_id'] . ':' . $row['country_name'] . '<br>';
    $selectCountry .= "\n<option value=\"" . $row['country_name'] . "\">" . 
                      $row['country_name'] . "</option>";
}
$selectCountry .= '</select>';
echo $selectCountry;
$country_regions = $db->query('SELECT DISTINCT region, country_name FROM cities NATURAL JOIN countries WHERE region != "" ORDER BY region');
echo '<script>';
while($row = $country_regions->fetchArray()) {
echo 'if("' . $row['country_name'] . '" in country_regions) {';
echo 'country_regions["' . $row['country_name'] . '"].push("' . $row['region'] . '");}';
echo 'else { country_regions["' . $row['country_name'] . '"] = ["' . $row['region'] . '"];}';
}
echo '</script>';
echo '<label for="regions">Region:</label>
<select id="selectRegion">
<option value="">---Any Region---</option>
</select>
<input type=\'button\' onclick="addRegion()" value="Add">
<br><br>
<label for="chosenRegions">Selected Regions:</label>
<br>
<select id="chosenRegions" multiple style="width:400px">
</select>
<textarea form="bestPlaceQuiz" id="regionsText" name="regionsText" readonly style="display:none">;;</textarea>
<input type=\'button\' onclick=\'removeRegion()\' value="Remove">
<script> buildCountryList(); buildRegionList(); </script>
<br><br>
<input type=\'submit\' value=\'Find My Place!\'>
</form>
</body>
</html>
<!--
Code borrowed from stackoverflow:
Sliders:
https://stackoverflow.com/questions/26612700/ticks-for-type-range-html-input
--->';
        
?>
