<?php
// Include the navigation bar and database connection
include '../navbar.php';
require_once __DIR__ . '/dbConnection.php'; // brings in `$conn` (mysqli)

$query   = trim($_GET['q'] ?? '');
$sets    = [];
$total   = 0;

if ($query !== '') {
    // Prepare the LIKE clause for search functionality
    $like = '%' . $query . '%';

    // SQL query to search for sets based on title, description, or tags
    $sql  = "SELECT id, title, description, author_username, created_at
             FROM notecard_sets
             WHERE title LIKE ? OR description LIKE ? OR tags LIKE ?
             ORDER BY created_at DESC
             LIMIT 50";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $like, $like, $like);
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

        <!-- List of Search Results -->
        <ul class="set-list">
            <?php foreach ($sets as $set): ?>
                <li class="set-item">
                    <a class="set-title" href="set.php?id=<?= $set['id'] ?>">
                        <?= htmlspecialchars($set['title'], ENT_QUOTES) ?>
                    </a>
                    <div class="set-meta">
                        by <?= htmlspecialchars($set['author_username'], ENT_QUOTES) ?> • 
                        <?= (new DateTime($set['created_at']))->format('M j, Y') ?>
                    </div>
                    <?php if ($set['description']): ?>
                        <div class="set-desc">
                            <?= htmlspecialchars(mb_strimwidth($set['description'], 0, 140, '…'), ENT_QUOTES) ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
