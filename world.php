<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

function sanitizeInput($input) {
    return ucwords(trim(filter_var($input, FILTER_SANITIZE_STRING)));
}

function executeQuery($conn, $query, $params = []) {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $country = isset($_GET['country']) ? sanitizeInput($_GET['country']) : '';
    $context = isset($_GET['context']) ? $_GET['context'] : '';

    if (!empty($country)) {
        $countryParam = "%$country%";
        $results = [];

        if (empty($context)) {
            $results = executeQuery($conn, "SELECT * FROM countries WHERE name LIKE ?", [$countryParam]);
        } elseif ($context == "cities") {
            $results = executeQuery($conn, "SELECT cities.name, cities.district, cities.population FROM cities
                JOIN countries ON cities.country_code = countries.code WHERE countries.name LIKE ?", [$countryParam]);
        }
    } else {
        $results = [];

        if (empty($context)) {
            $results = executeQuery($conn, "SELECT * FROM countries");
        } elseif ($context == "cities") {
            $results = executeQuery($conn, "SELECT * FROM cities");
        }
    }

    if (!empty($results)) {
?>
        <table>
            <?php if ($context == "cities") : ?>
                <tr>
                    <th>Name</th>
                    <th>District</th>
                    <th>Population</th>
                </tr>
                <tbody>
                    <?php foreach ($results as $row) : ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['district'] ?></td>
                            <td><?= $row['population'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            <?php else : ?>
                <tr>
                    <th>Name</th>
                    <th>Continent</th>
                    <th>Independence</th>
                    <th>Head of State</th>
                </tr>
                <tbody>
                    <?php foreach ($results as $row) : ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['continent'] ?></td>
                            <td><?= $row['independence_year'] ?></td>
                            <td><?= $row['head_of_state'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            <?php endif; ?>
        </table>
<?php
    }
}
?>
