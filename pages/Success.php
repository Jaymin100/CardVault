<?php
include 'dbConnection.php';
include '../navbar.php';

$set_id = $_GET['set_id'] ?? null;
if (!$set_id) {
    die("Set ID missing.");
}

// Fetch set details
$query = "SELECT * FROM all_sets WHERE set_ID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $set_id);
$stmt->execute();
$result = $stmt->get_result();
$set = $result->fetch_assoc();

if (!$set) {
    die("Set not found.");
}

$set_name = $set['set_name'];
$username = $set['username'];
$filter1 = $set['filter_1'];
$filter2 = $set['filter_2'];
$filter3 = $set['filter_3'];
$private = $set['priv'];

// Get card count
$card_result = $connection->query("SELECT COUNT(*) AS total FROM cards WHERE set_id = $set_id");
$card_count = $card_result->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Created</title>
    <link rel="stylesheet" href="Search.css">
</head>
<body>
    <h1 style="text-align:center;">🎉 Set Created Successfully</h1>

    <div class="notecard-wrapper">
        <a href="View.php?set_id=<?= $set_id ?>" class="notecard-link">
            <div class="notecard">
                <div class="notecard-header">
                    <h3 class="notecard-title"><?= htmlspecialchars($set_name, ENT_QUOTES) ?></h3>
                    <p class="notecard-author">by <?= htmlspecialchars($username, ENT_QUOTES) ?></p>
                </div>
                <div class="notecard-body">
                    <?php if (!empty($filter1) || !empty($filter2) || !empty($filter3)): ?>
                        <p class="notecard-tags">
                            Tags: <?= htmlspecialchars(implode(', ', array_filter([$filter1, $filter2, $filter3])), ENT_QUOTES) ?>
                        </p>
                    <?php endif; ?>
                    <p class="notecard-tags">
                        Total Cards: <?= $card_count ?>
                    </p>
                    <p class="notecard-tags">
                        Privacy: <?= $private ? 'Private' : 'Public' ?>
                    </p>
                </div>
            </div>
        </a>
    </div>
</body>
</html>
