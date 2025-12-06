<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

$country = isset($_GET['country']) ? $_GET['country'] : '';

// Filter if search provided
if ($country !== "") {
    $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
    $stmt->execute(['country' => '%' . $country . '%']);
} else {
    $stmt = $conn->query("SELECT * FROM countries");
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no results, print nothing (or a message)
if (count($results) === 0) {
    echo "<p>No results found</p>";
    exit;
}

echo "<table class='results-table'>";
echo "<tr>
        <th>Country</th>
        <th>Continent</th>
        <th>Independence Year</th>
        <th>Head of State</th>
      </tr>";

foreach ($results as $row) {
    echo "<tr>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['continent']) . "</td>
            <td>" . htmlspecialchars($row['independence_year']) . "</td>
            <td>" . htmlspecialchars($row['head_of_state']) . "</td>
          </tr>";
}

echo "</table>";
