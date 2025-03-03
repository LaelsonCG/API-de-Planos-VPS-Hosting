<?php

header("Content-Type: application/json");
// Desativando a proteção CORS temporariamente
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Se houver uma requisição OPTIONS, apenas retorne a resposta com os cabeçalhos
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$database_file = __DIR__ . "/database.json";

// Se o arquivo do banco não existir, cria um novo
if (!file_exists($database_file)) {
    file_put_contents($database_file, json_encode([
        "usuarios" => [["usuario" => "admin", "senha" => password_hash("123456", PASSWORD_DEFAULT)]],
        "planos" => []
    ]));
}

// Função para carregar os dados do JSON
function loadData() {
    global $database_file;
    return json_decode(file_get_contents($database_file), true);
}

// Função para salvar os dados no JSON
function saveData($data) {
    global $database_file;
    file_put_contents($database_file, json_encode($data, JSON_PRETTY_PRINT));
}

// Função para gerar um token simples
function generateToken() {
    return bin2hex(random_bytes(32));
}

// Obtém os dados da requisição
$method = $_SERVER["REQUEST_METHOD"];
$uri = explode("/", trim($_GET["route"] ?? "", "/"));
$data = json_decode(file_get_contents("php://input"), true);
$db = loadData();

// ===================================
//          AUTENTICAÇÃO
// ===================================
if ($method === "POST" && $uri[0] === "login") {
    $usuario = $data["usuario"] ?? "";
    $senha = $data["senha"] ?? "";

    foreach ($db["usuarios"] as $user) {
       if ($user["usuario"] === $usuario && $user["senha"] === $senha) {
            echo json_encode(["status" => "success", "token" => generateToken()]);
            exit;
        }
    }
    echo json_encode(["status" => "error", "message" => "Usuário ou senha inválidos"]);
    exit;
}

// ===================================
//          LISTAR PLANOS
// ===================================
if ($method === "GET" && $uri[0] === "planos") {
    echo json_encode($db["planos"]);
    exit;
}

// ===================================
//          ADICIONAR PLANO
// ===================================
if ($method === "POST" && $uri[0] === "planos") {
    if (!isset($data["token"], $data["categoria"], $data["nome"], $data["descricao"], $data["url_compra"])) {
        echo json_encode(["status" => "error", "message" => "Campos obrigatórios ausentes"]);
        exit;
    }

    $novo_plano = [
        "id" => count($db["planos"]) + 1,
        "categoria" => $data["categoria"],
        "nome" => $data["nome"],
        "descricao" => $data["descricao"],
        "url_compra" => $data["url_compra"]
    ];

    $db["planos"][] = $novo_plano;
    saveData($db);

    echo json_encode(["status" => "success", "message" => "Plano adicionado com sucesso", "id" => $novo_plano["id"]]);
    exit;
}

// ===================================
//          EDITAR PLANO
// ===================================
if ($method === "PUT" && isset($uri[1]) && $uri[0] === "planos") {
    $id = intval($uri[1]);

    foreach ($db["planos"] as &$plano) {
        if ($plano["id"] === $id) {
            $plano["categoria"] = $data["categoria"] ?? $plano["categoria"];
            $plano["nome"] = $data["nome"] ?? $plano["nome"];
            $plano["descricao"] = $data["descricao"] ?? $plano["descricao"];
            $plano["url_compra"] = $data["url_compra"] ?? $plano["url_compra"];

            saveData($db);
            echo json_encode(["status" => "success", "message" => "Plano atualizado com sucesso"]);
            exit;
        }
    }

    echo json_encode(["status" => "error", "message" => "Plano não encontrado"]);
    exit;
}

// ===================================
//          EXCLUIR PLANO
// ===================================
if ($method === "DELETE" && isset($uri[1]) && $uri[0] === "planos") {
    $id = intval($uri[1]);

    foreach ($db["planos"] as $index => $plano) {
        if ($plano["id"] === $id) {
            array_splice($db["planos"], $index, 1);
            saveData($db);
            echo json_encode(["status" => "success", "message" => "Plano excluído com sucesso"]);
            exit;
        }
    }

    echo json_encode(["status" => "error", "message" => "Plano não encontrado"]);
    exit;
}

// Se nenhuma rota for encontrada
echo json_encode(["status" => "error", "message" => "Rota inválida"]);
exit;
