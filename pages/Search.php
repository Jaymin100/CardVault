<?php
// Include the navigation bar and database connection
include '../navbar.php';
include_once 'dbConnection.php';

$query   = trim($_GET['q'] ?? '');
$sets    = [];
$total   = 0;
$username = "1234";// replace with user logic using session
if ($query !== '') {
    // Prepare the LIKE clause for search functionality
    $like = '%' . $query . '%';

    // SQL query to search for sets based on title, description, or tags
    $sql = "SELECT set_ID, set_name, username, filter_1, filter_2, filter_3 
    FROM all_sets 
    WHERE (priv = 0 OR username = ?)
      AND (
          set_name LIKE ? 
          OR filter_1 LIKE ? 
          OR filter_2 LIKE ? 
          OR filter_3 LIKE ?
      )
    ORDER BY set_ID 
    LIMIT 50";
    // Prepare and execute the query
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('sssss', $username, $like, $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all matching sets
    $sets = $result->fetch_all(MYSQLI_ASSOC);
    $total = count($sets);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="Search.css" />
<head>
    <meta charset="UTF-8" />
    <title>Search | CardVault</title>
</head>
<body>
    <h1>Find Study Sets</h1>

    <!-- Search Form -->
    <form class="search-form" method="get" action="search.php">
        <input 
            type="text" 
            name="q" 
            placeholder="Search sets…" 
            value="<?= htmlspecialchars($query, ENT_QUOTES) ?>" 
            required 
        />
        <button type="submit">Search</button>
    </form>

    <!-- Display Message When No Query or No Results -->
    <?php if ($query === ''): ?>
        <p>Type a keyword above to discover public notecard sets.</p>
    <?php elseif ($total === 0): ?>
        <p>No sets matched “<strong><?= htmlspecialchars($query, ENT_QUOTES) ?></strong>”. Try another keyword.</p>
    <?php else: ?>
        <h2><?= $total ?> set<?= $total === 1 ? '' : 's' ?> found</h2>

        <!-- Displaying Search Results as Notecards -->
        <div class="notecard-wrapper">
            <?php foreach ($sets as $set): ?>
                <div class="notecard">
                    <a href="View.php?set_id=<?= $set['set_ID'] ?>" class="notecard-link">
                        <div class="notecard-header">
                            <h3 class="notecard-title"><?= htmlspecialchars($set['set_name'], ENT_QUOTES) ?></h3>
                            <p class="notecard-author">by <?= htmlspecialchars($set['username'], ENT_QUOTES) ?></p>
                        </div>
                        <div class="notecard-body">
                            <p class="notecard-tags">
                                Tags: <?= htmlspecialchars(implode(', ', array_filter([$set['filter_1'], $set['filter_2'], $set['filter_3']])), ENT_QUOTES) ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>
