<?php
// Database connection configuration
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

// Create a PDO connection to the MySQL database
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

// Retrieve the country name sent from the AJAX request (if any)
$country = isset($_GET['country']) ? $_GET['country'] : '';

// If a country name is provided, run a filtered query
// Otherwise, return all countries from the database
if ($country !== "") {
    $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
    $stmt->execute(['country' => '%' . $country . '%']);
} else {
    $stmt = $conn->query("SELECT * FROM countries");
}

// Fetch the database results as an associative array
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no matching countries were found, stop execution and show a message
if (count($results) === 0) {
    echo "<p>No results found</p>";
    exit;
}

// Check if the request is specifically asking for cities instead of country data
$lookup = isset($_GET['lookup']) ? $_GET['lookup'] : '';

if ($lookup === "cities" && $country !== "") {

    // SQL query to return cities for the selected country
    // Uses a JOIN to match cities to their country
    $stmt = $conn->prepare("
        SELECT cities.name, cities.district, cities.population
        FROM cities
        JOIN countries ON cities.country_code = countries.code
        WHERE countries.name LIKE :country
    ");

    // Execute the query with the country parameter
    $stmt->execute(['country' => '%' . $country . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If no cities were found, return nothing (JS handles the empty response)
    if (count($results) === 0) {
        echo "";
        exit;
    }

    // Output an HTML table showing city data
    echo "<table class='results-table'>";
    echo "<thead>
            <tr>
                <th>City</th>
                <th>District</th>
                <th>Population</th>
            </tr>
          </thead>";
    echo "<tbody>";

    // Loop through the city results and output each row
    foreach ($results as $row) {
        echo "<tr>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['district']) . "</td>
                <td>" . number_format($row['population']) . "</td>
            </tr>";
    }

    echo "</tbody></table>";
    exit; // Prevent country output from being printed afterwards
}

// If this point is reached, show the country information table (default behaviour)
echo "<table class='results-table'>";
echo "<tr>
        <th>Country</th>
        <th>Continent</th>
        <th>Independence Year</th>
        <th>Head of State</th>
      </tr>";

// Print each matching country as a row in the table
foreach ($results as $row) {
    echo "<tr>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['continent']) . "</td>
            <td>" . htmlspecialchars($row['independence_year']) . "</td>
            <td>" . htmlspecialchars($row['head_of_state']) . "</td>
          </tr>";
}

echo "</table>";