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
    <title>aqoli - Quiz</title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="shortcut icon" type="image/jpg" href="website_logo.jpg"/>
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
            <div class='quiz-content'>
                <script>
                    // temporary values for tested before database is connected
                    // countries = 'SELECT country_name FROM countries'
                    // regions = 'SELECT DISTINCT region FROM cities NATURAL JOIN countries WHERE country = ' + selected country
                    var countries = ["United States", "Canada"];
                    var country_regions = {};

                    // buildCoutryList() - adds countries from database (or list, for now) to
                    //     select menu
                    function buildCountryList() {
                        // populate countryList with countries
                        for (let i = 0; i < countries.length; i++) {
                            var country = document.createElement('option');
                            country.value = countries[i];
                            country.innerHTML = countries[i];
                            document.getElementById('selectCountry').appendChild(country);
                        }
                    }


                    // buildRegionList() - add relevant regions to region select menu
                    function buildRegionList() {
                        clearRegionList();
                        if(document.getElementById('selectCountry').value == '') {
                            regions = [];
                        }
                        var country = document.getElementById('selectCountry').value;
                        if (country in country_regions) {
                            regions = country_regions[country];
                            for (let i = 0; i < regions.length; i++) {
                                var opt = document.createElement('option');
                                opt.value = regions[i];
                                opt.innerHTML = regions[i];
                                document.getElementById('selectRegion').appendChild(opt);
                            }
                        }
                    }


                    // clearRegionList() - clear options from region select menu
                    function clearRegionList() {
                        var regions = document.getElementById('selectRegion');
                        while (regions.length > 1) {
                            regions[1].remove();
                        }
                    }


                    // addRegion() - adds country (, region) from select menus to chosen regions
                    function addRegion() {
                        var country = document.getElementById('selectCountry').value;
                        var region = document.getElementById('selectRegion').value;
                        var opt = document.createElement('option');
                        if(country == "") {
                            return;
                        }
                        if(region != "") {
                            opt.value = country + "::" + region;
                            opt.innerHTML = region + ", " + country;
                        }
                        else {
                            opt.value = country;
                            opt.innerHTML = country;
                        }
                        // don't add dups
                        var chosen = document.getElementById('chosenRegions');
                        for (let i = 0; i < chosen.length; i++) {
                            if (chosen[i].value == opt.value) {
                                return;
                            }
                        }
                        document.getElementById('chosenRegions').appendChild(opt);
                        document.getElementById('regionsText').value += opt.value + ';;';
                    }


                    // removeRegion() - remove selected region(s) from chosen regions
                    function removeRegion() {
                        var chosen = document.getElementById('chosenRegions');
                        for (let i = 0; i < chosen.length; i++) {
                            if(chosen[i].selected) {
                                document.getElementById('regionsText').value =
                                    document.getElementById('regionsText').value
                                    .replace(';' + chosen[i].value + ';', '');
                                chosen[i].remove();
                                i--;
                            }
                        }
                    }


                    // getPlaces() - puts together clauses returned by buildSelectClause() and
                    //     buildWhereClause() to create a complete SQL query
                    function getPlaces() {
                        var query;
                        select = buildSelectClause();
                        from = 'FROM quality_of_life NATURAL JOIN cities NATURAL JOIN countries ';
                        order = 'ORDER BY score DESC';
                        where = buildWhereClause();
                        query = select + from + where + order;
                        document.write(query);
                    }
                </script>
                <h1>Find your best place</h1>
                <h2>Pick your preferences and discover the cities that best match your needs.</h2>
                <div>
                  <form name="bestPlaceQuiz" id="bestPlaceQuiz" action="result.php" onsubmit="return true" method="POST">
                      <div class='quiz-item'>
                          <div><label for='purchasing_power_index'>Purchasing Power:</label></div>
                            <input type="range" min="0" max="4" id="purchasing_power_index" name="purchasing_power_index" list="my-datalist1"/>
                            <datalist id="my-datalist1" style="--list-length: 5;">
                              <option>Not Important</option>
                              <option></option>
                              <option>Somewhat Important</option>
                              <option></option>
                              <option>Very Important</option>
                            </datalist>
                        </div>
                        <br><br>
                        <div class='quiz-item'>
                            <div><label for='safety_index'>Safety:</label></div>
                            <input type="range" min="0" max="4" id="safety_index" name="safety_index" list="my-datalist2"/>
                            <datalist id="my-datalist2" style="--list-length: 5;">
                              <option>Not Important</option>
                              <option></option>
                              <option>Somewhat Important</option>
                              <option></option>
                              <option>Very Important</option>
                            </datalist>
                        </div>
                        <br><br>
                        <div class='quiz-item'>
                            <div><label for='health_care_index'>Health Care:</label></div>
                            <input type="range" min="0" max="4" id="health_care_index" name="health_care_index" list="my-datalist3"/>
                            <datalist id="my-datalist3" style="--list-length: 5;">
                              <option>Not Important</option>
                              <option></option>
                              <option>Somewhat Important</option>
                              <option></option>
                              <option>Very Important</option>
                            </datalist>
                        </div>
                        <br><br>
                        <div class='quiz-item'>
                            <div><label for='climate_index'>Climate:</label></div>
                            <input type="range" min="0" max="4" id="climate_index" name="climate_index" list="my-datalist4"/>
                            <datalist id="my-datalist4" style="--list-length: 5;">
                              <option>Not Important</option>
                              <option></option>
                              <option>Somewhat Important</option>
                              <option></option>
                              <option>Very Important</option>
                            </datalist>
                        </div>
                        <br><br>
                        <div class='quiz-item'>
                            <div><label for='cost_of_living_index'>Cost of Living:</label></div>
                            <input type="range" min="0" max="4" id="cost_of_living_index" name="cost_of_living_index" list="my-datalist5"/>
                            <datalist id="my-datalist5" style="--list-length: 5;">
                              <option>Not Important</option>
                              <option></option>
                              <option>Somewhat Important</option>
                              <option></option>
                              <option>Very Important</option>
                            </datalist>
                        </div>
                        <br><br>
                        <div>
                            <div><label for='property_price_to_income_ratio'>Property Price to Income Ratio:</label></div>
                            <input type="range" min="0" max="4" id="property_price_to_income_ratio" name="property_price_to_income_ratio" list="my-datalist6"/>
                            <datalist id="my-datalist6" style="--list-length: 5;">
                              <option>Not Important</option>
                              <option></option>
                              <option>Somewhat Important</option>
                              <option></option>
                              <option>Very Important</option>
                            </datalist>
                        </div>
                        <br><br>
                        <div class='quiz-item'>
                            <div><label for='traffic_commute_time_index'>Traffic/Commute Time:</label></div>
                            <input type="range" min="0" max="4" id="traffic_commute_time_index" name="traffic_commute_time_index" list="my-datalist7"/>
                            <datalist id="my-datalist7" style="--list-length: 5;">
                              <option>Not Important</option>
                              <option></option>
                              <option>Somewhat Important</option>
                              <option></option>
                              <option>Very Important</option>
                            </datalist>
                        </div>
                        <br><br>
                        <div class='quiz-item'>
                            <div><label for='pollution_index'>Pollution:</label></div>
                            <input type="range" min="0" max="4" id="pollution_index" name="pollution_index" list="my-datalist8"/>
                            <datalist id="my-datalist8" style="--list-length: 5;">
                              <option>Not Important</option>
                              <option></option>
                              <option>Somewhat Important</option>
                              <option></option>
                              <option>Very Important</option>
                            </datalist>
                        </div>
                        <br><br>
                        <div>
                            <div class='filters-form'>
                                <p>Filter results by location:</p>
                                <div class="filter-form-lable">
                                    <label for="selectCountry">Country:</label>
                            <?php
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
                                echo '</div>';
                                echo '<div class="filter-form-lable"><label for="selectRegion">Region:</label>
                                <select id="selectRegion">
                                    <option value="">---Any Region---</option>
                                </select>
                                <input type="button" onclick="addRegion()" value="Add">
                                </div></div>
                                <div class="filters-form-lable">
                                    <label for="chosenRegions">Selected Regions:</label>
                                        <select id="chosenRegions" multiple style="width:25em">
                                        </select>
                                        <textarea form="bestPlaceQuiz" id="regionsText" name="regionsText" readonly>;;</textarea>
                                    <input type="button" onclick="removeRegion()" value="Remove">
                                    <script> buildCountryList(); buildRegionList(); </script>
                                </div>
                                <div class="buttons filter-form-button">
                                    <input type="submit" value="Find My Place!">
                                </div>';
                            ?>
                        </div>
                    </form>


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
