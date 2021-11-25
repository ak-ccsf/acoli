<?php
define("SITE_ADDR", "http://localhost:8000/");
//include("./include.php");
$site_title = 'aqoli';
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
        <!--<div id="nav">
            <a href="<?php echo SITE_ADDR; ?>/new_entry.php">New Entry</a>
        </div>-->

        <div id="logo">
            <h1><a href="<?php echo SITE_ADDR; ?>">city search</a></h1>
        </div>
    </div>

    <div id="main" class="shadow-box">
        <div id="content">

            <center>
                <form action="" method="GET" name="">
                    <table>
                        <tr>
                            <td><input type="text" name="k" placeholder="search for a city" autocomplete="off"></td>
                            <td><input type="submit" name="" value="search"></td>
                        </tr>
                    </table>
                </form>
            </center>

            <?php

            // CHECK TO SEE IF THE KEYWORDS WERE PROVIDED
            if (isset($_GET['k']) && $_GET['k'] != '') {

                // save the keywords from the url
                $k = trim($_GET['k']);

                // create a base query and words string
                $query_string = "SELECT city_name, region, country_name, wiki_url FROM cities NATURAL JOIN countries WHERE city_name ";

                $display_words = "";
                // seperate each of the keywords
                $keywords = explode(' ', $k);
                foreach ($keywords as $word) {
                    $query_string .= " LIKE '%" . $word . "%' AND city_name ";
                    $display_words .= $word . " ";
                }
                $query_string = substr($query_string, 0, strlen($query_string) - 14);

                // connect to the database
                // commented out mysqli example to adapt our sqlite3 db
                //$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

                $conn = new SQLite3('acoli.db');

                // commented out mysqli_query to adapt our sqlite3 query
                //$query = mysqli_query($conn, $query_string);

                $query = $conn->query($query_string);
                error_log($query_string);

                // comment out mysqli $result_count to adapt sqlite3 compatible result_count
                //$result_count = mysqli_num_rows($query);

                $result_count_query_string = "SELECT COUNT(*) FROM cities WHERE city_name ";
                error_log($result_count_query_string);

                $display_words = "";
                foreach ($keywords as $word) {
                    $result_count_query_string .= " LIKE '%" . $word . "%' AND city_name ";
                    $display_words .= $word . " ";
                }
                $result_count_query_string = substr($result_count_query_string,
                                              0,
                                              strlen($result_count_query_string) - 14);
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

                    echo '<table class="search">';

                    // display all the search results to the user
                    // uncoment mysqli fetch to fetch query results in an sqlite3 compatible way
                    //while ($row = mysqli_fetch_assoc($query)){
                    // consider using a for loop with a value limiting how many results per page
                    while ($row = $query->fetchArray()) {

                        // adapted example with one that works with our db
                        echo '<tr>
                                  <td><h3><a href=https://en.wikipedia.org' . $row['wiki_url'] . '>' . $row['city_name'] . '</a></h3></td>
                              </tr>
                              <tr>
                                  <td>' . $row['region'] . '</td>
                              </tr>
                              <tr>
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
    </div>

    <div id="footer">
        <div class="left">
            <a href="https://github.com/ak-ccsf/acoli" target="_blank">site code on github</a>
        <div class="clear"></div>
    </div>

</div>

</body>
</html>
