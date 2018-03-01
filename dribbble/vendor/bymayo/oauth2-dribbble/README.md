# Dribbble Provider for OAuth 2.0 Client
[![Latest Version](https://img.shields.io/github/release/CrewLabs/oauth2-dribbble.svg?style=flat-square)](https://github.com/CrewLabs/oauth2-dribbble/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/CrewLabs/oauth2-dribbble/master.svg?style=flat-square)](https://travis-ci.org/CrewLabs/oauth2-dribbble)
[![Quality Score](https://img.shields.io/scrutinizer/g/CrewLabs/oauth2-dribbble.svg?style=flat-square)](https://scrutinizer-ci.com/g/CrewLabs/oauth2-dribbble)

This package provides Dribbble OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require crewlabs/oauth2-dribbble
```

## Usage

Usage is the same as The League's OAuth client, using `\CrewLabs\OAuth2\Client\Provider\Dribbble` as the provider.

### Authorization Code Flow

```php

require_once('./vendor/autoload.php');
session_start();

$provider = new \CrewLabs\OAuth2\Client\Provider\Dribbble([
    'clientId'          => '{ dribbble-client-id }',
    'clientSecret'      => '{ dribbble-secret }',
    'redirectUri'       => 'https://example.com/callback-url',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getName());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}

```

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/CrewLabs/oauth2-dribbble/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Dave Baker](https://github.com/fullybaked)
- [Crew Labs](https://github.com/CrewLabs)
- [All Contributors](https://github.com/CrewLabs/oauth2-dribbble/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/CrewLabs/oauth2-dribbble/blob/master/LICENSE) for more information.
