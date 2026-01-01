# learn_english
Teaches English to Khmer students -- makes call to OpenAI gpt-4o-mini-tts model

# To Run
To run:
0. Copy your OpenAI API key into `config/api_key.txt`
1. Make sure you have PHP installed
2. Change to this directory and run:
```
php -S localhost:8888 -t public
```

# Adding Data
1. Install SQLite3
2. Have a look at data/learn_english.sql
3. Insert data as appropriate

# Files
├── config
│   └── api_key.txt.replace.me
├── data
│   ├── english_learning.db
│   └── english_learning.sql
├── public
│   ├── css
│   │   └── styles.css
│   ├── index.php
│   └── js
│       └── script.js
├── README.md
└── src
    ├── DataSource.php
    └── TtsHandler.php


