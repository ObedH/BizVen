<?php
// Connect to the database
$dbFile = '/var/www/database/mydb.sqlite';
$db = new SQLite3($dbFile);

// Enable foreign keys
$db->exec("PRAGMA foreign_keys = ON;");

// Check if an 'id' is provided in the URL
if (isset($_GET['id'])) {
    $questionId = intval($_GET['id']); // Sanitize the input to prevent SQL injection

    // Fetch the specific question by its ID
    $query = "SELECT q.id, q.title, q.content, u.username, q.created_at 
              FROM questions q 
              JOIN users u ON q.user_id = u.id 
              WHERE q.id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $questionId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    // Fetch the question data
    $question = $result->fetchArray(SQLITE3_ASSOC);

    if (!$question) {
        echo "Question not found!";
        exit;
    }

    // Fetch answers for this question
    $answersQuery = "SELECT a.id, a.content, u.username, a.created_at 
                     FROM answers a 
                     JOIN users u ON a.user_id = u.id 
                     WHERE a.question_id = :id 
                     ORDER BY a.created_at ASC";
    $answersStmt = $db->prepare($answersQuery);
    $answersStmt->bindValue(':id', $questionId, SQLITE3_INTEGER);
    $answersResult = $answersStmt->execute();

    // Fetch all answers
    $answers = [];
    while ($answer = $answersResult->fetchArray(SQLITE3_ASSOC)) {
        $answers[] = $answer;
    }

    // Close the database connection
    $db->close();
} else {
    echo "No question ID provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiqhuency Forum - View Question</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <h1>Question Details</h1>
    <nav id="main-navbar">
        <a href="../"><button>Home</button></a>
        <a href="../questions/"><button>Questions</button></a>
        <a href="../scroll/"><button>Scroll</button></a>
        <a href="../account/"><button>Account</button></a>
    </nav>

    <div id="question-details">
        <h2><?php echo htmlspecialchars($question['title']); ?></h2>
        <p><strong>Asked by:</strong> <?php echo htmlspecialchars($question['username']); ?></p>
        <p><strong>Posted on:</strong> <?php echo date('F j, Y, g:i a', strtotime($question['created_at'])); ?></p>
        <p><strong>Content:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($question['content'])); ?></p>

        <hr>

        <h3>Answers:</h3>
        <?php if (count($answers) > 0): ?>
            <ul>
                <?php foreach ($answers as $answer): ?>
                    <li>
                        <p><strong>Answered by:</strong> <?php echo htmlspecialchars($answer['username']); ?></p>
                        <p><strong>Posted on:</strong> <?php echo date('F j, Y, g:i a', strtotime($answer['created_at'])); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($answer['content'])); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No answers yet. Be the first to answer!</p>
        <?php endif; ?>
    </div>

    <!-- Add Answer Form -->
    <hr>
    <h3>Add an Answer:</h3>
    <form action="post_answer.php" method="POST">
        <textarea name="answer_content" rows="4" cols="50" required></textarea><br>
        <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
        <button type="submit">Submit Answer</button>
    </form>
</body>
</html>
