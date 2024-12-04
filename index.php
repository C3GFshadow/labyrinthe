<?php
session_start();

include('labyrinthe.php');

if (!isset($_SESSION['position_chat']) || isset($_GET['nouvelle_partie'])) {
    $_SESSION['position_chat'] = ['x' => 1, 'y' => 1];
    $_SESSION['labyrinthe'] = genererLabyrinthe();
}

function deplacerChat($direction) {
    $x = $_SESSION['position_chat']['x'];
    $y = $_SESSION['position_chat']['y'];
    $labyrinthe = $_SESSION['labyrinthe'];

    if ($direction == 'haut' && $y > 0 && $labyrinthe[$y - 1][$x] != 1) {
        $y--;
    } elseif ($direction == 'bas' && $y < count($labyrinthe) - 1 && $labyrinthe[$y + 1][$x] != 1) {
        $y++;
    } elseif ($direction == 'gauche' && $x > 0 && $labyrinthe[$y][$x - 1] != 1) {
        $x--;
    } elseif ($direction == 'droite' && $x < count($labyrinthe[0]) - 1 && $labyrinthe[$y][$x + 1] != 1) {
        $x++;
    }

    $_SESSION['position_chat'] = ['x' => $x, 'y' => $y];
}

function aGagne() {
    return $_SESSION['labyrinthe'][$_SESSION['position_chat']['y']][$_SESSION['position_chat']['x']] == 3;
}

if (isset($_GET['direction'])) {
    deplacerChat($_GET['direction']);
}

if (aGagne()) {
    $_SESSION['position_chat'] = ['x' => 1, 'y' => 1];
    $_SESSION['labyrinthe'] = genererLabyrinthe();
}

function afficherLabyrintheAvecBrouillard() {
    $chatX = $_SESSION['position_chat']['x'];
    $chatY = $_SESSION['position_chat']['y'];
    $labyrinthe = $_SESSION['labyrinthe'];

    for ($y = 0; $y < count($labyrinthe); $y++) {
        for ($x = 0; $x < count($labyrinthe[$y]); $x++) {
            if (abs($x - $chatX) <= 1 && abs($y - $chatY) <= 1) {
                afficherCase($x, $y, $labyrinthe[$y][$x]);
            } else {
                echo "<img src='images/brouillard.png' alt='Brouillard' />";
            }
        }
        echo '<br>';
    }
}

function afficherCase($x, $y, $case) {
    $img = '';
    if ($x == $_SESSION['position_chat']['x'] && $y == $_SESSION['position_chat']['y']) {
        $img = 'chat.png';
    } elseif ($case == 3) {
        $img = 'souris.png';
    } elseif ($case == 1) {
        $img = 'mur.png';
    } else {
        $img = 'case_vide.png';
    }
    echo "<img src='images/$img' alt='Case' />";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Labyrinthe du Chat</title>
    <link rel="stylesheet" href="style.css">
    <style>
    </style>
</head>
<body>
    <h1>Labyrinthe du Chat</h1>
    <div class="labyrinthe">
        <?php afficherLabyrintheAvecBrouillard(); ?>
    </div>

    <div class="controls">
        <a href="?direction=haut" class="up">↑</a>
        <a href="?direction=gauche" class="left">←</a>
        <a href="?direction=droite" class="right">→</a>
        <a href="?direction=bas" class="down">↓</a>
    </div>

    <?php if (aGagne()): ?>
        <p>Félicitations, vous avez trouvé la souris !</p>
        <p><a href="?nouvelle_partie=true">Nouvelle partie</a></p>
    <?php endif; ?>
</body>
</html>
