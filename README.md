# PHP API

## developed on Ubuntu20.04 with PHP8.1.2

### First you need to remove php7 and install php8 on your WSL

1.   
```
dpkg -l | grep php | tee packages.txt
```
2.   
```
sudo apt-get purge php7.*
```
3.   
```
sudo apt-get autoclean && sudo apt-get autoremove
```
4.   
```
sudo add-apt-repository ppa:ondrej/php
```
5.   
```
sudo apt-get update
```
6.   
```
sudo apt-get install php8.0 
```
7.   
```
sudo service apache2 restart
```
8.   
```
sudo apt install php8.0-common php8.0-mysql php8.0-xml php8.0-xmlrpc php8.0-curl php8.0-gd php8.0-imagick php8.0-cli php8.0-dev php8.0-imap php8.0-mbstring php8.0-opcache php8.0-soap php8.0-zip php8.0-intl -y
```
9.   
```
sudo service apache2 restart
```

Now you can check your php version

10. 
```
php -v
```

## In this example I'm using a MariaDB database on my Synology NAS 

### Here you'll find the solution to install Composer on your WSL distribution [DIGITAL OCEAN](https://www.digitalocean.com/community/tutorials/how-to-install-composer-on-ubuntu-20-04-quickstart)

### The .env file is loaded with the help of [vlucas/phpdotenv](https://packagist.org/packages/vlucas/phpdotenv) on packegist.org

unfortunately you have to require it in your console 
```
composer require vlucas/phpdotenv
```
or you can require it in your composer.json

for Autoloading you can setup the autoload in your composer.json and then 
```
composer dump-autoload
```

### To read your URI in the index.php you have to be aware that me URI was something like this
```
localhost/~stephan/projects/php_api/api/tasks
``` 

this is so because I'm using a linked folder in Windows as rootfolder for my apache2 server.
normaly the api would be in the root folder of your URI 
```
localhost/api/tasks
```

## Testing with Interactive PHP
```
stephan@LAPTOP-NMN2AKOU:~$ php -a
Interactive shell

php > $encoded = base64_encode("hello world");
php > echo $encoded;
aGVsbG8gd29ybGQ=
php > echo base64_decode($encoded);
hello world
php >
```

## [JWToken](https://jwt.io/introduction)

JSON Webtokens consists of the following parts

1) header
2) payload
3) signature

1) The header contains the information about the algorithm and the type, as you can see in the JWTCodec.php on line 15 & 16 and they are base64 encoded.
2) The payload wich is the second part contains the [claims](https://www.iana.org/assignments/jwt/jwt.xhtml). Claims in simple terms are the information you want to transport with the token. You should never include sensitive data in your token. As you can see in the example "Encrypt JWT Payload" you can easily read this informations.
3) The signature secures the token by encrypting header and payload with your secret_key. As soon as the payload gets manipulated the token is no more valid.



## Encrypt JWT Payload
```
php > $payload = ["id" => 123];
php > require "src/JWTCodec.php";
php > $codec = new JWTCodec;
php > echo $codec->encode($payload);
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTIzfQ.fg6HKP-0gvXaJVk3CY5TtAV0268XAtFpj5dTOGBXvKg

```

## Test Token validation

```

Interactive shell

php > require "src/JWTCodec.php";
php > $codec = new JWTCodec;
php > $token = $codec->encode(["id" => 123]);
php > echo $token;
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTIzfQ.fg6HKP-0gvXaJVk3CY5TtAV0268XAtFpj5dTOGBXvKg
php >
php >
php > $payload = $codec->decode($token);
php > print_r($payload);
Array
(
    [id] => 123
)


php > $payload = $codec->decode($token . "xxx");
PHP Warning:  Uncaught Exception: signature doesn't match in /mnt/c/Projects/php_api/src/JWTCodec.php:52
Stack trace:
#0 php shell code(1): JWTCodec->decode()
#1 {main}
  thrown in /mnt/c/Projects/php_api/src/JWTCodec.php on line 52
php >
php > $payload = $codec->decode("xxxx");
PHP Warning:  Uncaught InvalidArgumentException: invalid token format in /mnt/c/Projects/php_api/src/JWTCodec.php:40
Stack trace:
#0 php shell code(1): JWTCodec->decode()
#1 {main}
  thrown in /mnt/c/Projects/php_api/src/JWTCodec.php on line 40
php >

```


..to be continued 🙂


