# Gezinsbond API client for PHP

https://apimqa.gezinsbond.be/

## Usage

### Initialize Client

```php
use Eightbitsnl\GezinsbondPhpClient\GezinsbondApiClient;
use Eightbitsnl\GezinsbondPhpClient\Requests\V2\Members;


$client = (new GezinsbondApiClient())
    ->setApiKey("ebaae05d-02b9-44fb-9229-392e13d38a57");

```

### GET Requests

```PHP
$memberNumber = "12345689";

// Send (and receive GezinsbondResponse object)
$response = $client->V2GetMembersIsActive($memberNumber)->send();

// Or send and receive as json
$response = $client->V2GetMembersIsActive($memberNumber)->json();
```

### POST Requests

@todo
