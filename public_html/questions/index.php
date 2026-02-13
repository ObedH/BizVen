\<?php
// Connect to the database
$dbFile = '/var/www/database/mydb.sqlite';
$db = new SQLite3($dbFile);

// Enable foreign keys
$db->exec("PRAGMA foreign_keys = ON;");

// Check if the 'questions' table exists
$tableCheck = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='questions'");
$tableExists = $tableCheck->fetchArray(SQLITE3_ASSOC);

if (!$tableExists) {
    die("Error: The 'questions' table does not exist in the database.");
}

// Fetch all open questions
$query = "SELECT q.id, q.title, q.content, u.username, q.created_at 
          FROM questions q 
          JOIN users u ON q.user_id = u.id 
          WHERE q.status = 'open' 
          ORDER BY q.created_at DESC";
$result = $db->query($query);

if (!$result) {
    die("Query failed: " . $db->lastErrorMsg());
}

// Fetch questions
$questions = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $questions[] = $row;
}

// Close the database connection
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiqhuency Forum - Questions</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <h1>Fiqhuency Forum - Questions</h1>
    <nav id="main-navbar">
        <a href="../"><button>Home</button></a>
        <a href="../questions/"><button>Questions</button></a>
        <a href="../scroll/"><button>Scroll</button></a>
        <a href="../account/"><button>Account</button></a>
    </nav>

    <div id="questions-list">
        <?php if (count($questions) > 0): ?>
            <ul>
                <?php foreach ($questions as $question): ?>
                    <li>
                        <h2><a href="view.php?id=<?php echo $question['id']; ?>"><?php echo htmlspecialchars($question['title']); ?></a></h2>
                        <p><strong>Asked by:</strong> <?php echo htmlspecialchars($question['username']); ?></p>
                        <p><strong>Posted on:</strong> <?php echo date('F j, Y, g:i a', strtotime($question['created_at'])); ?></p>
                        <p><?php echo nl2br(htmlspecialchars(substr($question['content'], 0, 200))); ?>...</p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No questions available. Be the first to ask!</p>
        <?php endif; ?>
    </div>
</body>
</html>

