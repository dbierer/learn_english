    // script.js - Updated to use jQuery for AJAX calls

    let currentWords = [];
    let gameScore = 0;
    let gameAttempts = 0;
    let currentAudio = null; // Track current audio playback

    // Text-to-Speech function using OpenAI API with jQuery
    function speak(text, voice = 'coral') {
      if (currentAudio) {
        currentAudio.pause();
        currentAudio = null;
      }

      showLoadingIndicator();

      $.ajax({
        url: 'index.php',
        method: 'POST',
        data: { text, voice },
        dataType: 'json',
        success: function (data) {
          hideLoadingIndicator();

          if (!data || !data.success || !data.audio) {
            console.error('TTS Error payload:', data);
            alert('Error generating speech: ' + (data?.error || 'Unknown error'));
            return;
          }

          // Use mime from server if provided, otherwise assume mp3
          const mime = data.mime || 'audio/mpeg';

          // Convert base64 -> Blob -> objectURL
          const blob = base64ToBlob(data.audio, mime);
          const audioUrl = URL.createObjectURL(blob);

          currentAudio = new Audio();
          currentAudio.preload = 'auto';
          currentAudio.src = audioUrl;

          currentAudio.onended = () => {
            URL.revokeObjectURL(audioUrl);
            currentAudio = null;
          };

          currentAudio.onerror = function (e) {
            console.error('Audio playback error:', e);
            console.error('Audio error code:', currentAudio.error?.code);
            console.error('Audio error message:', currentAudio.error?.message);
            URL.revokeObjectURL(audioUrl);
          };

          currentAudio.play().catch(err => {
            console.error('Play error:', err);
            URL.revokeObjectURL(audioUrl);
            alert('Could not play audio: ' + err.message);
          });
        },
        error: function (xhr) {
          hideLoadingIndicator();
          console.error('AJAX Error:', xhr.responseText);
          alert('Error connecting to TTS service');
        }
      });
    }

    // Alternative pure jQuery approach for base64 to blob conversion
    function base64ToBlob(base64, contentType = 'audio/mpeg') {
      const byteCharacters = atob(base64);
      const byteNumbers = new Array(byteCharacters.length);

      for (let i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
      }

      return new Blob([new Uint8Array(byteNumbers)], { type: contentType });
    }

    // Alternative speak function using pure jQuery (no fetch for blob conversion)
    function speakPureJQuery(text, voice = 'coral') {
        // Stop any currently playing audio
        if (currentAudio) {
            currentAudio.pause();
            currentAudio = null;
        }
        
        // Show loading indicator
        showLoadingIndicator();
        
        // Make jQuery AJAX call to PHP backend
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: {
                text: text,
                voice: voice
            },
            dataType: 'json',
            success: function(data) {
                hideLoadingIndicator();
                
                if (data.success && data.audio) {
                    console.log('Received audio data, size:', data.size);
                    
                    // Convert base64 to blob using pure JavaScript
                    const blob = base64ToBlob(data.audio);
                    const audioUrl = URL.createObjectURL(blob);
                    
                    // Create and play audio
                    currentAudio = new Audio(audioUrl);
                    
                    currentAudio.onended = () => {
                        console.log('Audio playback completed');
                        URL.revokeObjectURL(audioUrl);
                        currentAudio = null;
                    };
                    
                    currentAudio.onerror = () => {
                        console.error('Audio playback error');
                        URL.revokeObjectURL(audioUrl);
                        alert('Error playing audio');
                    };
                    
                    currentAudio.play().then(() => {
                        console.log('Audio playing successfully');
                    }).catch(error => {
                        console.error('Play error:', error);
                        alert('Could not play audio: ' + error.message);
                    });
                    
                } else {
                    console.error('TTS Error:', data.error);
                    alert('Error generating speech: ' + (data.error || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                hideLoadingIndicator();
                console.error('AJAX Error:', error);
                alert('Error connecting to TTS service: ' + error);
            }
        });
    }

    // Fallback to browser TTS if OpenAI API fails
    function speakFallback(text, lang = 'en-US') {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = lang;
            utterance.rate = 0.8;
            speechSynthesis.speak(utterance);
        } else {
            alert('Text-to-speech is not supported in your browser.');
        }
    }

    // Show loading indicator
    function showLoadingIndicator() {
        let $indicator = $('#tts-loading');
        if ($indicator.length === 0) {
            $indicator = $('<div>')
                .attr('id', 'tts-loading')
                .addClass('tts-loading')
                .html('🔊 Loading audio...');
            $('body').append($indicator);
        }
        $indicator.show();
    }

    // Hide loading indicator
    function hideLoadingIndicator() {
        $('#tts-loading').hide();
    }

    // Load vocabulary from database via jQuery AJAX
    function loadVocabulary(category) {
        const $wordList = $('#word-list');
        $wordList.html('<p class="loading">Loading... កំពុងផ្ទុក...</p>');
        
        $.ajax({
            url: 'index.php',
            method: 'GET',
            data: {
                action: 'vocabulary',
                category: category
            },
            dataType: 'json',
            success: function(data) {
                currentWords = data;
                displayVocabulary(data);
            },
            error: function(xhr, status, error) {
                $wordList.html('<p class="error">Error loading vocabulary. សូមព្យាយាមម្តងទៀត។</p>');
                console.error('Error:', error);
            }
        });
    }

    // Display vocabulary words with jQuery
    function displayVocabulary(words) {
        const $wordList = $('#word-list');
        $wordList.empty();
        
        if (words.length === 0) {
            $wordList.html('<p>No words found in this category.</p>');
            return;
        }
        
        $.each(words, function(index, word) {
            const $wordCard = $('<div>')
                .addClass('word-card')
                .html(`
                    <div class="word-english">${word.english}</div>
                    <div class="word-khmer">${word.khmer}</div>
                    <div class="word-pronunciation">${word.pronunciation || ''}</div>
                    <button onclick="speak('${word.english.replace(/'/g, "\\'")}')" class="speak-btn">🔊 Listen</button>
                `);
            
            $wordList.append($wordCard);
            
            // Animate cards
            setTimeout(() => {
                $wordCard.css({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
            }, index * 100);
        });
    }

    // Load conversation from database via jQuery AJAX
    function loadConversation(category) {
        const $conversationArea = $('#conversation-area');
        $conversationArea.html('<p class="loading">Loading... កំពុងផ្ទុក...</p>');
        
        $.ajax({
            url: 'index.php',
            method: 'GET',
            data: {
                action: 'conversation',
                category: category
            },
            dataType: 'json',
            success: function(data) {
                displayConversation(data);
            },
            error: function(xhr, status, error) {
                $conversationArea.html('<p class="error">Error loading conversation. សូមព្យាយាមម្តងទៀត។</p>');
                console.error('Error:', error);
            }
        });
    }

    // Display conversation with jQuery
    function displayConversation(conversation) {
        const $conversationArea = $('#conversation-area');
        $conversationArea.empty();
        
        if (conversation.length === 0) {
            $conversationArea.html('<p>No conversation found in this category.</p>');
            return;
        }
        
        $.each(conversation, function(index, line) {
            const $bubble = $('<div>')
                .addClass('conversation-bubble ' + line.speaker.toLowerCase().replace(' ', '-'))
                .html(`
                    <div class="speaker-name">${line.speaker}</div>
                    <div class="conversation-english">${line.english}</div>
                    <div class="conversation-khmer">${line.khmer}</div>
                    <button onclick="speak('${line.english.replace(/'/g, "\\'")}')" class="speak-btn-small">🔊</button>
                `);
            
            $conversationArea.append($bubble);
            
            // Animate bubbles
            setTimeout(() => {
                $bubble.css({
                    'opacity': '1',
                    'transform': 'translateX(0)'
                });
            }, index * 300);
        });
        
        // Add play all button
        const $playAllBtn = $('<button>')
            .addClass('play-all-btn')
            .text('🔊 Play Entire Conversation')
            .on('click', function() {
                playConversation(conversation);
            });
        
        $conversationArea.append($playAllBtn);
    }

    // Play entire conversation with OpenAI TTS using jQuery
    function playConversation(conversation) {
        let index = 0;
        
        function playNext() {
            if (index < conversation.length) {
                showLoadingIndicator();
                
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    data: {
                        text: conversation[index].english,
                        voice: 'nova'
                    },
                    dataType: 'json',
                    success: function(data) {
                        hideLoadingIndicator();
                        
                        if (data.success && data.audio) {
                            // Convert base64 to blob
                            const mime = data.mime || 'audio/mpeg';
                            const blob = base64ToBlob(data.audio, mime);
                            const audioUrl = URL.createObjectURL(blob);
                            
                            currentAudio = new Audio(audioUrl);
                            currentAudio.play();
                            
                            currentAudio.onended = () => {
                                URL.revokeObjectURL(audioUrl);
                                index++;
                                setTimeout(playNext, 800);
                            };
                            
                            currentAudio.onerror = () => {
                                URL.revokeObjectURL(audioUrl);
                                console.error('Audio playback error in conversation');
                                index++;
                                playNext();
                            };
                        } else {
                            console.error('TTS Error:', data.error);
                            index++;
                            playNext();
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoadingIndicator();
                        console.error('Error:', error);
                        index++;
                        playNext();
                    }
                });
            }
        }
        
        playNext();
    }

    // Start matching game with jQuery
    function startGame() {
        const $gameArea = $('#game-area');
        const $scoreArea = $('#game-score');
        $gameArea.html('<p class="loading">Loading game... កំពុងផ្ទុក...</p>');
        
        $.ajax({
            url: 'index.php',
            method: 'GET',
            data: {
                action: 'game',
                limit: 6
            },
            dataType: 'json',
            success: function(data) {
                gameScore = 0;
                gameAttempts = 0;
                setupGame(data);
            },
            error: function(xhr, status, error) {
                $gameArea.html('<p class="error">Error loading game. សូមព្យាយាមម្តងទៀត។</p>');
                console.error('Error:', error);
            }
        });
    }

    // Setup matching game with jQuery
    function setupGame(words) {
        const $gameArea = $('#game-area');
        const $scoreArea = $('#game-score');
        $gameArea.html('<h4>Match the English words with their Khmer translations!</h4>');
        
        const $gameGrid = $('<div>').addClass('game-grid');
        
        // Create English cards
        const englishCards = words.map((word, index) => ({
            id: `en-${index}`,
            text: word.english,
            type: 'english',
            matchId: index
        }));
        
        // Create Khmer cards
        const khmerCards = words.map((word, index) => ({
            id: `km-${index}`,
            text: word.khmer,
            type: 'khmer',
            matchId: index
        }));
        
        // Shuffle cards
        const allCards = [...englishCards, ...khmerCards].sort(() => Math.random() - 0.5);
        
        let selectedCards = [];
        
        $.each(allCards, function(i, card) {
            const $cardElement = $('<div>')
                .addClass('game-card')
                .text(card.text)
                .data('matchId', card.matchId)
                .data('type', card.type);
            
            $cardElement.on('click', function() {
                const $this = $(this);
                
                if ($this.hasClass('matched') || $this.hasClass('selected')) {
                    return;
                }
                
                $this.addClass('selected');
                selectedCards.push($this);
                
                if (selectedCards.length === 2) {
                    gameAttempts++;
                    checkMatch(selectedCards, words);
                    selectedCards = [];
                }
            });
            
            $gameGrid.append($cardElement);
        });
        
        $gameArea.append($gameGrid);
        updateScore();
    }

    // Check if cards match with jQuery
    function checkMatch(cards, words) {
        const [$card1, $card2] = cards;
        
        if ($card1.data('matchId') === $card2.data('matchId') && 
            $card1.data('type') !== $card2.data('type')) {
            // Match found!
            setTimeout(() => {
                $card1.addClass('matched').removeClass('selected');
                $card2.addClass('matched').removeClass('selected');
                gameScore++;
                updateScore();
                
                // Speak the matched word using OpenAI TTS
                const matchedWord = words[$card1.data('matchId')];
                speak(matchedWord.english);
                
                // Check if game is complete
                if (gameScore === words.length) {
                    setTimeout(() => {
                        alert(`🎉 Congratulations! អបអរសាទរ! You completed the game in ${gameAttempts} attempts!`);
                    }, 500);
                }
            }, 500);
        } else {
            // No match
            setTimeout(() => {
                $card1.removeClass('selected');
                $card2.removeClass('selected');
            }, 1000);
        }
    }

    // Update score display with jQuery
    function updateScore() {
        $('#game-score').html(`
            <p>Score: ${gameScore} | Attempts: ${gameAttempts}</p>
        `);
    }

    // Load default content on page load with jQuery
    $(document).ready(function() {
        loadVocabulary('greetings');
        
        // Optional: Add smooth scrolling for navigation links
        $('nav a').on('click', function(e) {
            e.preventDefault();
            const target = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(target).offset().top - 50
            }, 800);
        });
    });
