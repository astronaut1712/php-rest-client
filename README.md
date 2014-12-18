php-rest-client
===============

Rest Client for PHP

RestClient class is a simplest to use Restful Client for PHP.

It supports GET/POST/PUT/DELETE/OPTIONS and CUSTOM METHOD.

You can use proxy option if needed.


##Using

```php
<?php
    $client = new RestClient();
    $client->get("http://api.example.com/");//post("http://api.example.com/", $data=''), put("http://api.example.com/", $data=''), delete("http://api.example.com/", $data=''), options("http://api.example.com/", $data='')
    echo $client->_getBody();
?>
```

or
```php
<?php
    $client = new RestClient(array(
        'url' => 'http://api.example.com/',
        'method' => 'PUT',
        'data' => '{"name":"QUANG"}'
    ));
    $client->execute();
?>
```

##Use proxy
```php
$client = new RestClient();
$client->setProxy($proxy='');
...
```
or
```php
$client = new RestClient(array(
    ...
    'proxy' => ''
    ...
));
```

##Supported Functions:
 * `setURL('http://api.example.com')`
 * `setReqData('example')`
 * `setUserAgent('PHP RestClient 1.0')`
 * `setHeaders(array())`
 * `setProxy('123.4.5.6:8080')`
 * `putHeader('X-API-Key: 1234567')`
 * `getResHeader()`
 * `getHttpStatus()`
 * `getResBody()`