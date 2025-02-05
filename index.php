
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic-Tac-Toe</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>


<?php
session_start();

// Initialize game board
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, '');
    $_SESSION['turn'] = 'X';
    $_SESSION['winner'] = null;
}

// Handle player move
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cell']) && $_SESSION['winner'] === null) {
    $cell = intval($_POST['cell']);
    
    if ($_SESSION['board'][$cell] === '') {
        $_SESSION['board'][$cell] = $_SESSION['turn'];
        
        // Check for winner
        $winning_combinations = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // Columns
            [0, 4, 8], [2, 4, 6]             // Diagonals
        ];

        foreach ($winning_combinations as $combo) {
            if ($_SESSION['board'][$combo[0]] !== '' &&
                $_SESSION['board'][$combo[0]] === $_SESSION['board'][$combo[1]] &&
                $_SESSION['board'][$combo[1]] === $_SESSION['board'][$combo[2]]) {
                $_SESSION['winner'] = $_SESSION['turn'];
                break;
            }
        }

        // Check for draw
        if (!in_array('', $_SESSION['board']) && $_SESSION['winner'] === null) {
            $_SESSION['winner'] = "Draw";
        }

        // Switch turn
        $_SESSION['turn'] = ($_SESSION['turn'] === 'X') ? 'O' : 'X';
    }
}

// Reset game
if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<h2>Tic-Tac-Toe</h2>
<p>Player Turn: <strong><?php echo $_SESSION['turn']; ?></strong></p>

<form method="POST">
    <div class="board">
        <?php for ($i = 0; $i < 9; $i++): ?>
            <button type="submit" class="cell" name="cell" value="<?php echo $i; ?>" 
                <?php echo ($_SESSION['board'][$i] !== '' || $_SESSION['winner'] !== null) ? 'disabled' : ''; ?>>
                <?php echo $_SESSION['board'][$i]; ?>
            </button>
        <?php endfor; ?>
    </div>
</form>

<?php if ($_SESSION['winner']): ?>
    <p class="winner"><?php echo $_SESSION['winner'] === "Draw" ? "It's a Draw!" : $_SESSION['winner'] . " Wins!"; ?></p>
    <form method="POST">
        <button type="submit" name="reset">Play Again</button>
    </form>
<?php endif; ?>

</body>
</html>
