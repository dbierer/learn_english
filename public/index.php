<?php
// Handle API requests
$action = isset($_GET['action']) ? $_GET['action'] : '';

if (!empty($action)) {
    header('Content-Type: application/json');
    require __DIR__ . '/../src/DataSource.php';
    $db = new DataSource();
    switch ($action) {
        case 'vocabulary':
            $category = isset($_GET['category']) ? $_GET['category'] : 'greetings';
            echo json_encode($db->getVocabulary($category));
            break;            
        case 'conversation':
            $category = isset($_GET['category']) ? $_GET['category'] : 'introduction';
            echo json_encode($db->getConversation($category));
            break;
        case 'game':
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 6;
            echo json_encode($db->getRandomWords($limit));
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
    exit;
}

if (!empty($_POST)) {
    header('Content-Type: application/json');
    require __DIR__ . '/../src/TtsHandler.php';
    $response = '';
    $api_key = trim(file_get_contents(__DIR__ . '/../config/api_key.txt'));
    // Get the text to convert to speech
    $text = trim(strip_tags($_POST['text'] ?? ''));
    $voice = trim(strip_tags($_POST['voice'] ?? TtsHandler::TTS_VOICE));

    if (empty($text)) {
        http_response_code(400);
        $response = json_encode(['success' => false, 'error' => 'No text provided']);
    } else {
        try {
            $response = (new TtsHandler())->handle('curl', $api_key, $text, $voice);
        } catch (Throwable $t) {
            $response = json_encode(['success' => false, 'error' => $t->getMessage()]);
        }
    }
    // error_log(basename(__FILE__) . ':' . __LINE__ . ':' .var_export($response, TRUE));
    echo $response;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn English - រៀនភាសាអង់គ្លេស</title>
    <link href="/css/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
        <h1>🌟 Welcome to Learn English! 🌟</h1>
        <p>សូមស្វាគមន៍មកកាន់ការរៀនភាសាអង់គ្លេស</p>
    </header>
    <nav>
        <ul>
            <li><a href="#vocabulary">📚 Vocabulary</a></li>
            <li><a href="#conversations">💬 Conversations</a></li>
            <li><a href="#games">🎮 Games</a></li>
        </ul>
    </nav>
    <main>
        <section id="vocabulary" class="section">
            <h2>📚 Vocabulary - វាក្យសព្ទ</h2>
            <div class="category-buttons">
                <button onclick="loadVocabulary('greetings')" class="category-btn">Greetings</button>
                <button onclick="loadVocabulary('family')" class="category-btn">Family</button>
                <button onclick="loadVocabulary('numbers')" class="category-btn">Numbers</button>
                <button onclick="loadVocabulary('colors')" class="category-btn">Colors</button>
                <button onclick="loadVocabulary('house')" class="category-btn">House</button>
            </div>
            <div id="word-list" class="word-list"></div>
        </section>
        
        <section id="conversations" class="section">
            <h2>💬 Conversations - សន្ទនា</h2>
            <div class="category-buttons">
                <button onclick="loadConversation('introduction')" class="category-btn">Introduction</button>
                <button onclick="loadConversation('shopping')" class="category-btn">Shopping</button>
                <button onclick="loadConversation('school')" class="category-btn">At School</button>
            </div>
            <div id="conversation-area" class="conversation-area"></div>
        </section>
        
        <section id="games" class="section">
            <h2>🎮 Games - ល្បែង</h2>
            <div class="game-container">
                <h3>Word Matching Game</h3>
                <button onclick="startGame()" class="game-btn">Start Game</button>
                <div id="game-area" class="game-area"></div>
                <div id="game-score" class="game-score"></div>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Learn English | Made with ❤️ for learning</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/js/script.js"></script>
</body>
</html>
