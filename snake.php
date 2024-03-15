<?php

// Initialize game parameters
$width = 20;
$height = 10;
$snake = [[1, 1]]; // Initial snake position
$direction = 'right'; // Initial direction
$food = generateFood();

// Function to generate food position
function generateFood() {
    global $width, $height, $snake;
    do {
        $food = [rand(0, $width - 1), rand(0, $height - 1)];
    } while (in_array($food, $snake));
    return $food;
}

// Function to draw the game board
function drawBoard() {
    global $width, $height, $snake, $food;
    system('cls');
    echo "Score: " . (count($snake) - 1) . "\n";
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($x == 0 || $x == $width - 1 || $y == 0 || $y == $height - 1) {
                echo "#"; // Border
            } elseif ($food[0] == $x && $food[1] == $y) {
                echo "@"; // Food
            } elseif (in_array([$x, $y], $snake)) {
                echo "O"; // Snake
            } else {
                echo " "; // Empty space
            }
        }
        echo "\n";
    }
}

// Function to move the snake
function moveSnake() {
    global $snake, $direction, $food, $width, $height;
    // Calculate new head position based on direction
    $head = $snake[0];
    switch ($direction) {
        case 'up':
            $newHead = [$head[0], $head[1] - 1];
            break;
        case 'down':
            $newHead = [$head[0], $head[1] + 1];
            break;
        case 'left':
            $newHead = [$head[0] - 1, $head[1]];
            break;
        case 'right':
            $newHead = [$head[0] + 1, $head[1]];
            break;
    }
    // Check if snake eats food
    if ($newHead == $food) {
        $food = generateFood();
    } else {
        // Remove tail segment
        array_pop($snake);
    }
    // Add new head
    array_unshift($snake, $newHead);
}

// Function to check if the game is over
function isGameOver() {
    global $snake, $width, $height;
    // Check if snake hits the wall
    $head = $snake[0];
    if ($head[0] == 0 || $head[0] == $width - 1 || $head[1] == 0 || $head[1] == $height - 1) {
        return true;
    }
    // Check if snake hits itself
    for ($i = 1; $i < count($snake); $i++) {
        if ($snake[$i] == $head) {
            return true;
        }
    }
    return false;
}

// Main game loop
while (true) {
    drawBoard();
    echo "Use arrow keys to control the snake. Press 'q' to quit.\n";
    // Read user input
    $input = ord(trim(fgetc(STDIN)));
    // Update direction based on user input
    switch ($input) {
        case 65:
            $direction = 'up';
            break;
        case 66:
            $direction = 'down';
            break;
        case 68:
            $direction = 'left';
            break;
        case 67:
            $direction = 'right';
            break;
        case 113: // 'q' key
            exit();
    }
    // Move the snake
    moveSnake();
    // Check if game is over
    if (isGameOver()) {
        drawBoard();
        echo "Game over! Your score: " . (count($snake) - 1) . "\n";
        exit();
    }
    // Slow down the game
    usleep(200000);
}

?>
