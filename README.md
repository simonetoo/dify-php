# Dify PHP SDK

This is the PHP SDK for the Dify API, which allows you to easily integrate Dify into your PHP applications.

## Requirements
- PHP: ^7.2|^8.0
- guzzlehttp/guzzle: ^7.8
- ext-json: *

## Usage

```php
<?php

use \Simoneto\Dify\Dify;

$apiKey = 'your-api-key-here';

// Set the base uri.

Dify::setBaseUri('https://dify.xx.com');

// Create a http client.
$client = Dify::create();

// Create a http client with api key.
$client = Dify::createWithApiKey($apiKey);

// Create a chat app.
$chat = Dify::chat($apiKey);

// Create a completion app.
$completion = Dify::completion($apiKey);

// Get the dify app information.
$response = $chat->parameters();

// Get the app meta information.
$response = $chat->meta('user-id');

// Send a request to the chat application.
$response = $chat->send('user-id', 'Hello World!');

// Send a request to the chat application with enable streaming mode.
$streamResponse = $chat->stream('user-id','hello World!');

// Provide feedback for a message
$response = $chat->messageFeedback('user-id','message-id','like or dislike');

// Other methods:
// $chat->suggested();
// $chat->conversations();
// $chat->messages();
// ....
// 

```

Replace 'your-api-key-here' with your actual Dify API key.


## License

This SDK is released under the MIT License.
