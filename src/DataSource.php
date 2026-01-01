<?php
// api.php

class DataSource 
{
    private $db;
    
    public function __construct() {
        try {
            $this->db = new SQLite3(__DIR__ . '/../data/english_learning.db');
            $this->initDatabase();
        } catch (Throwable $t) {
            error_log(__METHOD__ . ':' . $t->getMessage());
            die(json_encode(['error' => 'Database connection failed']));
        }
    }
    
    private function initDatabase() {
        // Create vocabulary table
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS vocabulary (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT NOT NULL,
                english TEXT NOT NULL,
                khmer TEXT NOT NULL,
                pronunciation TEXT
            )
        ");
        
        // Create conversations table
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS conversations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT NOT NULL,
                speaker TEXT NOT NULL,
                english TEXT NOT NULL,
                khmer TEXT NOT NULL,
                order_num INTEGER
            )
        ");
        
        // Check if tables are empty and populate with sample data
        $result = $this->db->querySingle("SELECT COUNT(*) FROM vocabulary");
        if ($result == 0) {
            $this->populateSampleData();
        }
    }
    
    private function populateSampleData() {
        // Greetings
        $greetings = [
            ['greetings', 'Hello', 'សួស្តី', 'soo-s-dey'],
            ['greetings', 'Good morning', 'អរុណសួស្តី', 'a-run soo-s-dey'],
            ['greetings', 'Good night', 'រាត្រីសួស្តី', 'rea-trey soo-s-dey'],
            ['greetings', 'Goodbye', 'លាហើយ', 'lea-haey'],
            ['greetings', 'Thank you', 'អរគុណ', 'or-kun'],
            ['greetings', 'You are welcome', 'មិនអីទេ', 'min ey te']
        ];
        
        // Family
        $family = [
            ['family', 'Mother', 'ម្តាយ', 'm-day'],
            ['family', 'Father', 'ឪពុក', 'ov-puk'],
            ['family', 'Sister', 'បងស្រី/ប្អូនស្រី', 'bong srey/p-oun srey'],
            ['family', 'Brother', 'បងប្រុស/ប្អូនប្រុស', 'bong pros/p-oun pros'],
            ['family', 'Grandmother', 'យាយ', 'yeay'],
            ['family', 'Grandfather', 'តា', 'ta']
        ];
        
        // Numbers
        $numbers = [
            ['numbers', 'One', 'មួយ', 'muoy'],
            ['numbers', 'Two', 'ពីរ', 'pii'],
            ['numbers', 'Three', 'បី', 'bey'],
            ['numbers', 'Four', 'បួន', 'buon'],
            ['numbers', 'Five', 'ប្រាំ', 'pram'],
            ['numbers', 'Six', 'ប្រាំមួយ', 'pram-muoy'],
            ['numbers', 'Seven', 'ប្រាំពីរ', 'pram-pii'],
            ['numbers', 'Eight', 'ប្រាំបី', 'pram-bey'],
            ['numbers', 'Nine', 'ប្រាំបួន', 'pram-buon'],
            ['numbers', 'Ten', 'ដប់', 'dop']
        ];
        
        // Colors
        $colors = [
            ['colors', 'Red', 'ក្រហម', 'kra-hom'],
            ['colors', 'Blue', 'ខៀវ', 'khiev'],
            ['colors', 'Green', 'បៃតង', 'bai-tong'],
            ['colors', 'Yellow', 'លឿង', 'lueng'],
            ['colors', 'White', 'ស', 's-aw'],
            ['colors', 'Black', 'ខ្មៅ', 'khmao']
        ];
        
        $allVocab = array_merge($greetings, $family, $numbers, $colors);
        
        $stmt = $this->db->prepare("INSERT INTO vocabulary (category, english, khmer, pronunciation) VALUES (?, ?, ?, ?)");
        foreach ($allVocab as $word) {
            $stmt->bindValue(1, $word[0], SQLITE3_TEXT);
            $stmt->bindValue(2, $word[1], SQLITE3_TEXT);
            $stmt->bindValue(3, $word[2], SQLITE3_TEXT);
            $stmt->bindValue(4, $word[3], SQLITE3_TEXT);
            $stmt->execute();
        }
        
        // Conversations
        $conversations = [
            ['introduction', 'Student', 'Hello! What is your name?', 'សួស្តី! តើអ្នកឈ្មោះអ្វី?', 1],
            ['introduction', 'Friend', 'My name is Sophea. What is your name?', 'ខ្ញុំឈ្មោះសុភា។ តើអ្នកឈ្មោះអ្វី?', 2],
            ['introduction', 'Student', 'My name is Dara. Nice to meet you!', 'ខ្ញុំឈ្មោះដារា។ រីករាយដែលបានជួបអ្នក!', 3],
            ['introduction', 'Friend', 'Nice to meet you too!', 'រីករាយដែលបានជួបអ្នកដែរ!', 4],
            
            ['shopping', 'Customer', 'How much is this?', 'នេះថ្លៃប៉ុន្មាន?', 1],
            ['shopping', 'Seller', 'This is 5 dollars.', 'នេះថ្លៃ ៥ ដុល្លារ។', 2],
            ['shopping', 'Customer', 'I will take it. Thank you!', 'ខ្ញុំយកវា។ អរគុណ!', 3],
            ['shopping', 'Seller', 'You are welcome!', 'មិនអីទេ!', 4],
            
            ['school', 'Teacher', 'Good morning, class!', 'អរុណសួស្តី សិស្សានុសិស្ស!', 1],
            ['school', 'Students', 'Good morning, teacher!', 'អរុណសួស្តី លោកគ្រូ!', 2],
            ['school', 'Teacher', 'Please open your books.', 'សូមបើកសៀវភៅរបស់អ្នក។', 3],
            ['school', 'Students', 'Yes, teacher!', 'បាទ/ចាស លោកគ្រូ!', 4]
        ];
        
        $stmt = $this->db->prepare("INSERT INTO conversations (category, speaker, english, khmer, order_num) VALUES (?, ?, ?, ?, ?)");
        foreach ($conversations as $conv) {
            $stmt->bindValue(1, $conv[0], SQLITE3_TEXT);
            $stmt->bindValue(2, $conv[1], SQLITE3_TEXT);
            $stmt->bindValue(3, $conv[2], SQLITE3_TEXT);
            $stmt->bindValue(4, $conv[3], SQLITE3_TEXT);
            $stmt->bindValue(5, $conv[4], SQLITE3_INTEGER);
            $stmt->execute();
        }
    }
    
    public function getVocabulary($category) {
        $stmt = $this->db->prepare("SELECT * FROM vocabulary WHERE category = ? ORDER BY id");
        $stmt->bindValue(1, $category, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        $words = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $words[] = $row;
        }
        
        return $words;
    }
    
    public function getConversation($category) {
        $stmt = $this->db->prepare("SELECT * FROM conversations WHERE category = ? ORDER BY order_num");
        $stmt->bindValue(1, $category, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        $conversation = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $conversation[] = $row;
        }
        
        return $conversation;
    }
    
    public function getRandomWords($limit = 6) {
        $result = $this->db->query("SELECT * FROM vocabulary ORDER BY RANDOM() LIMIT " . intval($limit));
        
        $words = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $words[] = $row;
        }
        
        return $words;
    }
}
