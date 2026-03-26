<?php

$TOKEN = "8350096128:AAEcx8kB1yhEI3rbx-ySYSyCoR9dtuhKNvs";
$API = "https://api.telegram.org/bot$TOKEN";

// получаем апдейт
$update = json_decode(file_get_contents("php://input"), true);

$text = trim($update["message"]["text"] ?? "");
$chat_id = $update["message"]["chat"]["id"] ?? null;

if (!$chat_id || $text === "") exit;

// 🧠 ИНСТРУКЦИЯ ДЛЯ ИИ
$system_prompt = "
Ты — AI-ассистент по имени MiniLamma.
В конце каждого предложения перед знаком препинания ставь смайлик связанные с этим предложением.
Отвечай дружелюбно и кратко.
";

// объединяем инструкцию + вопрос пользователя
$full_prompt = $system_prompt . "\nПользователь: " . $text;

// кодируем
$question = urlencode($full_prompt);

// запрос к Pollinations
$url = "https://text.pollinations.ai/$question";
$ai_answer = file_get_contents($url);

// отправляем ответ
file_get_contents($API . "/sendMessage?" . http_build_query([
    "chat_id" => $chat_id,
    "text" => $ai_answer
]));
