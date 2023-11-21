<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

function connectToDatabase() {
    global $host, $dbname, $username, $password;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function fetchData($conn, $query, $params) {
    try {
        $stmt = $conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

function displayResults($results, $columns) {
    echo "<table>";
    echo "<thead><tr>";
    foreach ($columns as $column) {
        echo "<th>$column</th>";
    }
    echo "</tr></thead>";
    echo "<tbody>";

    foreach ($results as $row) {
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<td>" . $row[$column] . "</td>";
        }
        echo "</tr>";
    }

    echo "</tbody></table>";
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userInput = filter_input(INPUT_GET, 'country', FILTER_SANITIZE_STRING);
    $lookup = isset($_GET['lookup']) ? $_GET['lookup'] : null;

    $conn = connectToDatabase();

    if ($lookup == "country") {
        $query = empty($userInput)
            ? "SELECT * FROM countries"
            : "SELECT * FROM countries WHERE name LIKE :userInput";

        $params = [':userInput' => "%$userInput%"];
        $columns = ['name', 'continent', 'independence_year', 'head_of_state'];
    }

    if ($lookup == "city") {
        $query = empty($userInput)
            ? "SELECT cities.name, cities.district, cities.population FROM cities INNER JOIN countries ON countries.code = cities.country_code"
            : "SELECT cities.name, cities.district, cities.population FROM cities INNER JOIN countries ON countries.code = cities.country_code WHERE countries.name LIKE :userInput";

        $params = [':userInput' => "%$userInput%"];
        $columns = ['name', 'district', 'population'];
    }

    echo "<h2>Results</h2>";
    echo "<hr>";

    $results = fetchData($conn, $query, $params);

    if (count($results) > 0) {
        displayResults($results, $columns);
    } else {
        echo "<p>No Results Found</p>";
        echo "<p>Enter a valid input or check spelling.</p>";
    }
}
?>
