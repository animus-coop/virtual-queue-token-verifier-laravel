# Virtual Queue Token Verifier SDK for Laravel

SDK for verifying virtual queue tokens on Laravel projects.

## Installation

### Installation in Laravel

1. Add the package to your Laravel project:
   ```bash
   composer require virtual-queue/token-verifier
   ```

2. Publish the configuration file:
   ```bash
   php artisan vendor:publish --provider="VirtualQueue\TokenVerifier\Laravel\TokenVerifierServiceProvider" --tag="config"
   ```

3. Set up the environment variables in your `.env` file:
   ```env
   VIRTUAL_QUEUE_BASE_URL=https://app.virtual-queue.com
   VIRTUAL_QUEUE_TIMEOUT=30
   ```

## Usage in Laravel

You can use the SDK in your Laravel controllers in two ways: via dependency injection or using the Facade.

### Dependency Injection

```php
use VirtualQueue\TokenVerifier\TokenVerifier;

class YourController extends Controller
{
    protected $tokenVerifier;

    public function __construct(TokenVerifier $tokenVerifier)
    {
        $this->tokenVerifier = $tokenVerifier;
    }

    public function verify($request)
    {
        if($request->token) {
            // For details about the queue:
            $result = $this->tokenVerifier->verifyToken($token);
            // [
            //      'success' => true,
            //      'data' => [
            //           'token': 'f1a10b71-f37d-499f-b30d-7542a7b4f5db',
            //           'finished_line': [
            //               'finished_at': '2024-09-17T09:47:10Z',
            //               'ingressed_at': '2024-09-17T09:15:53Z'
            //       ]
            // ]

            // For true/false
            $result = $this->tokenVerifier->isTokenValid($token);
            // true | false

            //Return error or redirect if token is invalid...
        }

        //Rest of the logic
    }
}
```

### Using the Facade

```php
use VirtualQueue\TokenVerifier\Laravel\Facades\TokenVerifier;

class YourController extends Controller
{
    public function verify($request)
    {
        if($request->token) {
            // For details about the queue:
            $result = $this->tokenVerifier->verifyToken($token);
            // [
            //      'success' => true,
            //      'data' => [
            //           'token': 'f1a10b71-f37d-499f-b30d-7542a7b4f5db',
            //           'finished_line': [
            //               'finished_at': '2024-09-17T09:47:10Z',
            //               'ingressed_at': '2024-09-17T09:15:53Z'
            //       ]
            // ]

            // For true/false
            $result = $this->tokenVerifier->isTokenValid($token);
            // true | false

            //Return error or redirect if token is invalid...
        }

        //Rest of the logic
    }
}
```

## Available Methods

### verifyToken(string $token): array

Verifies a token and returns associated data if valid.

### isTokenValid(string $token): bool

Checks if a token is valid (returns true or false).

## License

MIT