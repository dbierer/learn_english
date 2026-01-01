<?php

class TtsHandler
{

    public const TTS_MODEL = 'gpt-4o-mini-tts'; // 'tts-1'
    public const TTS_VOICE = 'coral';   // good for children
    public const TTS_FORMAT = 'mp3';    // wav | mp3
    public const API_ENDPOINT = 'https://api.openai.com/v1/audio/speech';
    #[TtsHandle\handle(
        "@param string \$api_key : API key",
        "@param string \$text    : text to convert",
        "@param string \$voice   : voice to use in audio conversion",
        "@return string JSON     : returns a JSON string"
    )]
    public function handle(string $approach, string $api_key, string $text, ?string $voice = NULL): string
    {
        $voice ??= static::TTS_VOICE;
        return ($approach = 'streams') 
               ? $this->handleWithStreams($api_key, $text, $voice)
               : $this->handleWithCurl($api_key, $text, $voice);
    }
    public function handleWithCurl(string $api_key, string $text, string $voice): string
    {
        $payload = [
            'model' => static::TTS_MODEL,
            'input' => $text,
            'voice' => $voice,
            'instructions' => 'Speak in a cheerful and positive tone.',
            'response_format' => static::TTS_FORMAT, // mp3
        ];

        $ch = curl_init(static::API_ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_POST            => true,
            CURLOPT_HTTPHEADER      => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key,
            ],
            CURLOPT_POSTFIELDS      => json_encode($payload),
            CURLOPT_RETURNTRANSFER  => true,   // return body as string (binary-safe)
            CURLOPT_HEADER          => true,   // include headers so we can split + read status
            CURLOPT_TIMEOUT         => 60,
        ]);

        $raw = curl_exec($ch);
        if ($raw === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception('cURL error: ' . $err);
        }

        $status     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $headers = substr($raw, 0, $headerSize);
        $body    = substr($raw, $headerSize); // this is the MP3 binary if status is 200

        if ($status >= 300) {
            // OpenAI errors are JSON; pass it through for logging/debugging
            throw new Exception("OpenAI API HTTP $status: " . $body);
        }

        return json_encode([
            'success' => true,
            'audio'   => base64_encode($body),
            'mime'    => static::TTS_FORMAT === 'mp3' ? 'audio/mpeg' : 'audio/wav',
            'size'    => strlen($body),
        ]);
    }
    public function handleWithStreams(string $api_key, string $text, string $voice) : string
    {
        $voice ??= static::TTS_VOICE;
        $data = [
            'model' => static::TTS_MODEL,  // Use tts-1 for faster response, tts-1-hd for higher quality
            'input' => $text,
            'voice' => $voice,   // Options: alloy, echo, fable, onyx, nova, shimmer
            'instructions' => 'Speak in a cheerful and positive tone.',
            'response_format' => static::TTS_FORMAT
        ];
        $post_data = json_encode($data);
        // error_log('Data: ' . var_export($data, TRUE));
        // error_log('JSON: ' . $post_data);
        // Disable this when done testing
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ];
        $opts = [
          'http' => [
            'method'        => 'POST',
            'header'        => implode("\r\n", $headers),
            'content'       => $post_data,
            'ignore_errors' => true,  // allow reading body even on 4xx/5xx
            'timeout'       => 60,
          ]
        ];
        $context = stream_context_create($opts);
        $body = file_get_contents(static::API_ENDPOINT, false, $context);

        $statusLine = $http_response_header[0] ?? '';
        preg_match('#HTTP/\d\.\d\s+(\d+)#', $statusLine, $m);
        $status = isset($m[1]) ? (int)$m[1] : 0;
        if ($status >= 300) {
          throw new Exception("OpenAI API HTTP $status: " . $body);
        }
        return json_encode([
          'success' => true,
          'audio'   => base64_encode($body),
          'mime'    => 'audio/mpeg',
          'size'    => strlen($body),
        ]);
    }
}
// working curl example:
/*
curl https://api.openai.com/v1/audio/speech   -H "Authorization: Bearer $OPENAI_API_KEY"   -H "Content-Type: application/json"   -d '{
    "model": "gpt-4o-mini-tts",
    "input": "Today is a wonderful day to build something people love!",
    "voice": "coral",
    "instructions": "Speak in a cheerful and positive tone.",
    "response_format": "mp3"
  }'
*/
