<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    # code...

    http_response_code(405);
    header("Allow: POST");
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"), true); // php://input stream

if ( ! array_key_exists("username", $data) || 
    ! array_key_exists("password", $data)) {
    # code...
    http_response_code(400);
    echo json_encode(["message" => "missing login credentials"]);
}

$database = new Database(
    $_ENV["DB_HOST"],
    $_ENV["DB_NAME"],
    $_ENV["DB_USER"],
    $_ENV["DB_PASS"]
);

$user_gateway = new UserGateway($database);

$user = $user_gateway->getByUsername($data["username"]);

if ($user === false) {
    # code...
    http_response_code(401);
    echo json_encode(["message" => "invalid authentication"]);
    exit;
}

if ( ! password_verify($data["password"], $user["password_hash"])) {
 # code...
 http_response_code(401);
 echo json_encode(["message" => "invalid authentication"]);
 exit;
}

$payload = [
    "id" => $user["id"],
    "name" => $user["name"]
];
$access_token = base64_encode(json_encode($payload));

echo json_encode([
    "access_token" => $access_token
]);
