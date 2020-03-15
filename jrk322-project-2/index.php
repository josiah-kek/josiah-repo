<?php include("includes/init.php");

$db = open_sqlite_db("secure/catalog.sqlite");

$error_messages = array();
$valid_messages = array();

function print_record($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["common_name"]); ?></td>
    <td><?php echo htmlspecialchars($record["scientific_name"]); ?></td>
    <td><?php echo htmlspecialchars($record["wing_color"]); ?></td>
    <td><?php echo htmlspecialchars($record["size"]); ?></td>
    <td><?php echo htmlspecialchars($record["us_region"]); ?></td>
    <td><?php echo htmlspecialchars($record["conservation_priority"]); ?></td>
  </tr>
<?php
}

function print_messages($messages, $message_type)
{
  foreach ($messages as $message) {
    echo "<p class=\"$message_type\"><strong>" . htmlspecialchars($message) . "</strong></p>\n";
  }
}

// Search Form

const SEARCH_FIELDS = [
  "all" => "All",
  "common_name" => "Common Name",
  "scientific_name" => "Scientific Name",
  "wing_color" => "Wing Color",
  "size" => "Size",
  "us_region" => "US Region",
  "conservation_priority" => "Conservation Priority"
];

if (isset($_GET['search'])) {
  $execute_search = TRUE;
  $category = filter_input(INPUT_GET, 'categories', FILTER_SANITIZE_STRING);
  if (in_array($category, array_keys(SEARCH_FIELDS))) {
    $search_field = $category;
  } else {
    array_push($error_messages, "Search category is invalid.");
    $execute_search = FALSE;
  }

  // Get the search terms
  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
} else {
  // No search provided, so set the product to query to NULL
  $execute_search = FALSE;
  $category = NULL;
  $search = NULL;
}

// Add Form

$butterflies = exec_sql_query($db, "SELECT DISTINCT common_name FROM catalog", NULL)->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $valid_entry = TRUE;
  $common_name = filter_input(INPUT_POST, 'common_name', FILTER_SANITIZE_STRING);
  $scientific_name = filter_input(INPUT_POST, 'scientific_name', FILTER_SANITIZE_STRING);
  $wing_color = filter_input(INPUT_POST, 'wing_color', FILTER_SANITIZE_STRING);
  $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_STRING);
  $us_region = filter_input(INPUT_POST, 'us_region', FILTER_SANITIZE_STRING);
  $conservation_priority = filter_input(INPUT_POST, 'conservation_priority', FILTER_SANITIZE_STRING);

  // common name required
  if ($common_name == "") $valid_entry = FALSE;

  // scientific name required
  if ($scientific_name == "") $valid_entry = FALSE;

  // insert valid entry into database
  if ($valid_entry) {
    $sql = "INSERT INTO catalog (common_name, scientific_name, wing_color, size, us_region, conservation_priority) VALUES (:common_name, :scientific_name, :wing_color, :size, :us_region, :conservation_priority)";
    $params = array(':common_name'=>$common_name, ':scientific_name'=>$scientific_name, ':wing_color'=>$wing_color, ':size'=>$size, ':us_region'=>$us_region, ':conservation_priority'=>$conservation_priority);
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      array_push($valid_messages, "Your entry has been added. Thank you!");
    } else {
      array_push($error_messages, "Failed to add new entry.");
    }
  } else {
    array_push($error_messages, "Failed to add new entry. Invalid common name or scientific name.");
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="styles/styles.css" type="text/css" rel="stylesheet" />
  <title>The Butterfly Book</title>
</head>

<body>
  <header>
    <!-- http://www.careers.ox.ac.uk/wp-content/uploads/2014/09/Butterfly-with-USA-flag.jpg -->
    <img src="images/logo_photo.jpg" alt="logo" id="logo" />
    <p class="ctrtext darktext">Source: <a href="http://www.careers.ox.ac.uk/wp-content/uploads/2014/09/Butterfly-with-USA-flag.jpg" class="darklink">http://www.careers.ox.ac.uk/wp-content/uploads/2014/09/Butterfly-with-USA-flag.jpg</a></p>
  </header>

  <div class="feature_photo_container">
    <figure>
      <!-- Source: https://www.fccnn.com/incoming/4618352-t0nr6b-qwtemp-1.jpg/alternates/BASE_LANDSCAPE/qwtemp%20%281%29.jpg -->
      <img src="images/feature_photo.jpg" alt="Feature Photo of Butterfly" id="feature_photo" class="feature_photo" />
      <figcaption class="feature_photo_caption">Welcome to Your Guide to Butterflies in the US!
      </figcaption>
    </figure>
    <p class="ctrtext darktext">Source: <a href="https://www.fccnn.com/incoming/4618352-t0nr6b-qwtemp-1.jpg/alternates/BASE_LANDSCAPE/qwtemp%20%281%29.jpg" class="darklink">https://www.fccnn.com/incoming/4618352-t0nr6b-qwtemp-1.jpg/alternates/BASE_LANDSCAPE/qwtemp%20%281%29.jpg</a></figcaption>
  </div>

  <h1>Featured Articles</h1>

  <section class="wide-flex-container">

    <figure class="leftshift">
      <!-- Source: https://i2.wp.com/cornellsun.com/wp-content/uploads/2017/10/Pg-1-Applefest-by-Michael-Suguitan.jpg?resize=1170%2C780 -->
      <img src="images/home_photo_1.jpg" alt="Blue Butterfly" class="medium_photo" />
      <figcaption class="caption-container"><a href="https://www.woodtv.com/news/kent-county/its-back-butterflies-are-blooming-at-meijer-gardens/" class="bluelink">Butterflies are Blooming at Meijer Gardens</a></figcaption>
      <figcaption class="darktext narrowtext">
        Source: <a href="https://www.woodtv.com/news/kent-county/its-back-butterflies-are-blooming-at-meijer-gardens/" class="darklink">https://www.woodtv.com/news/kent-county/its-back-butterflies-are-blooming-at-meijer-gardens/</a>
      </figcaption>
    </figure>

    <figure class="leftshift">
      <!-- Source: https://napavalleyregister.com/lifestyles/home-and-garden/columnists/master-gardener/napa-county-master-gardeners-welcoming-a-sign-of-spring/article_da8c0de2-264b-56c9-93d9-2e2122849388.html -->
      <img src="images/home_photo_2.jpg" alt="Black Butterfly" class="medium_photo" />
      <figcaption class="caption-container"><a href="https://www.woodtv.com/news/kent-county/its-back-butterflies-are-blooming-at-meijer-gardens/" class="bluelink">Welcoming a Sign of Spring to Napa Valley</a></figcaption>
      <figcaption class="darktext narrowtext">
        Source: <a href="https://napavalleyregister.com/lifestyles/home-and-garden/columnists/master-gardener/napa-county-master-gardeners-welcoming-a-sign-of-spring/article_da8c0de2-264b-56c9-93d9-2e2122849388.html" class="darklink">https://napavalleyregister.com/lifestyles/home-and-garden/columnists/master-gardener/napa-county-master-gardeners-welcoming-a-sign-of-spring/article_da8c0de2-264b-56c9-93d9-2e2122849388.html</a>
      </figcaption>
    </figure>

    <!-- <div class="break"></div> -->

    <figure class="leftshift">
      <!-- Source: https://www.nature.com/articles/d41586-019-03521-4 -->
      <img src="images/home_photo_3.jpg" alt="Orange and Black Butterfly" class="medium_photo" />
      <figcaption class="caption-container"><a href="https://www.nature.com/articles/d41586-019-03521-4" class="bluelink">Sequencing the Genomes of US Butterflies</a></figcaption>
      <figcaption class="caption-container darktext narrowtext">
        Source: <a href="https://www.nature.com/articles/d41586-019-03521-4" class="darklink">https://www.nature.com/articles/d41586-019-03521-4</a></figcaption>
    </figure>
  </section>

  <h1 id="catalog_heading">Butterfly Catalog</h1>

  <?php
  // Write out any messages to the user.
  print_messages($error_messages, "error_message");
  print_messages($valid_messages, "valid_message");
  ?>

  <h2>Search</span></h2>
  <form id="search_form" action="index.php#catalog_heading" method="get" novalidate>
    <select name="categories" id="categories">
      <?php foreach (SEARCH_FIELDS as $field_name => $label) { ?>
        <option value="<?php echo $field_name; ?>"><?php echo $label;
                                                  } ?></option>
    </select>

    <input type="search" placeholder="Search for a Butterfly" name="search" id="search" />
    <button type="submit" name="submit_search" class="submit_button">Go</button>
  </form>

  <h2 id="entries_heading">Entries</h2>

  <?php
  if ($execute_search) {
    if ($search_field == "all") {
      echo "<p class=\"search_scope_text\">Showing search results</p>";
      $sql = "SELECT * FROM catalog WHERE common_name LIKE '%' || :search || '%' OR scientific_name LIKE '%' || :search || '%' OR wing_color LIKE '%' || :search || '%' OR size LIKE '%' || :search || '%' OR us_region LIKE '%' || :search || '%' OR conservation_priority LIKE '%' || :search || '%'";
      $params = array(':search' => $search);
    } else {
      echo "<p class=\"search_scope_text\">Showing search results</p>";
      $sql = "SELECT * FROM catalog WHERE $search_field LIKE '%' || :search || '%'";
      $params = array(':search' => $search);
    }
  } else {
    echo "<p class=\"search_scope_text\">Showing all entries</p>";
    $sql = "SELECT * FROM catalog";
    $params = array();
  }

  $result = exec_sql_query($db, $sql, $params);
  if ($result) {
    $records = $result->fetchAll();
  }

  if (count($records) > 0) {
  ?>

    <table>
      <tr>
        <th>Common Name</th>
        <th>Scientific Name</th>
        <th>Wing Color</th>
        <th>Size</th>
        <th>US Region</th>
        <th>Conservation Priority</th>
      </tr>

      <?php foreach ($records as $record) {
        print_record($record);
      }
      ?>
    </table>
  <?php
  } else echo "<p id=\"search_error_text\">Sorry, no matching entries were found.</h3>";
  ?>

  <h2>Add a Butterfly</h2>
  <p>Spotted a new type of butterfly in the US? Add it to our catalog and contribute to shared knowledge!

    <form id="add_form" method="post" action="index.php#catalog_heading" novalidate>

      <fieldset id="form_fieldset">
        <legend id="legend_text">New Butterfly</legend>
        <p id="form_instructions"> Common name and scientific name fields are <strong>required.</strong><br />Capitalize only the first word <em>(e.g. Monarch butterfly)</em> of text that you enter. </p>
        <div>
          <label for="common_name" class="form_text">Common Name: </label>
          <input id="common_name" type="text" class="formbox" name="common_name" />
        </div>

        <div>
          <label for="scientific_name" class="form_text">Scientific Name: </label>
          <input id="scientific_name" type="text" class="formbox" name="scientific_name" />
        </div>

        <div>
          <label for="wing_color" class="form_text">Wing Color: </label>
          <input id="wing_color" type="text" class="formbox" name="wing_color" />
        </div>

        <div>
          <label for="size" class="form_text">Size:</label>
          <select id="size" name="size" class="formbox selectbox" size="3">
            <option value="Small" selected>Small (under 2'' wingspan)</option>
            <option value="South">Medium (2'' to 6'' wingspan)</option>
            <option value="East">Large (over 6'' wingspan)</option>
          </select>
        </div>

        <div>
          <label for="us_region" class="form_text">US Region:</label>
          <select id="us_region" name="us_region" class="formbox selectbox" size="4">
            <option value="North" selected>North</option>
            <option value="South">South</option>
            <option value="East">East</option>
            <option value="West">West</option>
          </select>
        </div>

        <div>
          <label for="conservation_priority" class="form_text">Conservation Priority:</label>
          <select id="conservation_priority" name="conservation_priority" class="formbox selectbox" size="3">
            <option value="Low" selected>Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
          </select>
        </div>

        <button type="submit" class="submit_button" name="submit_add" value="Submit">Submit</button>
      </fieldset>
    </form>

</body>

</html>
