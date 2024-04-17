<?php

use Fieg\Bayes\Classifier;
use Fieg\Bayes\Tokenizer\WhitespaceAndPunctuationTokenizer;

class FoodClassifier {
    private $tokenizer;
    private $classifier;

    public function __construct() {
        include './vendor/autoload.php';

        $this->tokenizer = new WhitespaceAndPunctuationTokenizer();
        $this->classifier = new Classifier($this->tokenizer);

        // Training data...
        $this->trainData();
    }

    private function trainData() {
        $this->classifier->train('desserts', 'ice cream');
        $this->classifier->train('desserts', 'cake');
        $this->classifier->train('desserts', 'pie');
        $this->classifier->train('desserts', 'Gelato');
        $this->classifier->train('desserts', 'macarons');
        $this->classifier->train('desserts', 'Chocolate');
        $this->classifier->train('desserts', 'Gelatin');
        $this->classifier->train('desserts', 'Salad');
        $this->classifier->train('desserts', 'Tiramisu');
        $this->classifier->train('drinks', 'espresso');
        $this->classifier->train('drinks', 'mojito');
        $this->classifier->train('drinks', 'tea');
        $this->classifier->train('drinks', 'Iced Coffee');
        $this->classifier->train('drinks', 'Coffee');
        $this->classifier->train('drinks', 'sake');
        $this->classifier->train('drinks', 'beer');
        $this->classifier->train('drinks', 'water');
        $this->classifier->train('drinks', 'pina colada');
        $this->classifier->train('drinks', 'Hot chocolate');
        $this->classifier->train('drinks', 'coke cola');
        $this->classifier->train('drinks', 'juice');
        $this->classifier->train('drinks', 'milk shake');
        $this->classifier->train('drinks', 'milk tea');
        $this->classifier->train('drinks', 'Frappe');
        $this->classifier->train('drinks', 'Lemonade');
        $this->classifier->train('meals', 'Spaghetti');
        $this->classifier->train('meals', 'tapsilog');
        $this->classifier->train('meals', 'chiksilog');
        $this->classifier->train('meals', 'fried chicken');
        $this->classifier->train('meals', 'Sinigang');
        $this->classifier->train('meals', 'adobo');
        $this->classifier->train('meals', 'paksiw');
        $this->classifier->train('meals', 'tacos');
        $this->classifier->train('meals', 'burger');
        $this->classifier->train('meals', 'fries');
        $this->classifier->train('meals', 'stew');
        $this->classifier->train('meals', 'jollof rice');
        $this->classifier->train('meals', 'pizza');
        $this->classifier->train('meals', 'Fish');
        $this->classifier->train('meals', 'tilapia');
        $this->classifier->train('meals', 'sushi');
        $this->classifier->train('meals', 'rendang');
        $this->classifier->train('meals', 'pecking duck');
        $this->classifier->train('meals', 'shawarma');
        $this->classifier->train('meals', 'pares');
        $this->classifier->train('meals', 'lomi');
        $this->classifier->train('meals', 'sisig');
        $this->classifier->train('meals', 'goto');
        $this->classifier->train('meals', 'mami');
        $this->classifier->train('meals', 'lechon');
        $this->classifier->train('meals', 'Steak');
        $this->classifier->train('meals', 'mac n cheese');
        $this->classifier->train('meals', 'pancit canton');
    }

    public function classifyFood($foodQuery) {
        $result = $this->classifier->classify($foodQuery);

        // Find the category with the highest score
        $highestCategory = '';
        $highestScore = -INF; // Initialize with negative infinity

        foreach ($result as $category => $score) {
            if ($score > $highestScore) {
                $highestScore = $score;
                $highestCategory = $category;
            }
        }

        // Return the highest category with its score
        return [
            'food_query' => $foodQuery,
            'highest_category' => $highestCategory,
            'highest_score' => $highestScore
        ];
    }
}

// Initialize food classifier
$foodClassifier = new FoodClassifier();

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["food_query"])) {
    $result = $foodClassifier->classifyFood($_POST["food_query"]);

    // Output the classification result
    echo "<hr>Food query: " . $result['food_query'] . "<br>";
    echo "Highest Category: " . $result['highest_category'] . " with Score: " . $result['highest_score'];
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // If no food query is provided, display an error message
    echo "<hr>Please provide a food query.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Classifier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(foods.avif);
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #a67c00;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            color: #333;
        }
        
        form {
            text-align: center;
        }
        
        label {
            font-weight: bold;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid black;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #5c5346;
        }
        
        input[type="submit"] {
            background-color: #5c5346;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background-color: #cba328;
        }

        .result {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Food Classifier</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="food_query">Enter your food query:</label><br>
            <input type="text" id="food_query" name="food_query"><br><br>
            <input type="submit" value="Classify">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["food_query"])) {
            // Output the classification result
            echo "<div class='result'>";
            $result = $foodClassifier->classifyFood($_POST["food_query"]);
            echo "<hr>Food query: " . $result['food_query'] . "<br>";
            echo "Highest Category: " . $result['highest_category'] . " with Score: " . $result['highest_score'];
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
