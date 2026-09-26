<?php
declare(strict_types=1);

$allowedOrigin = getenv('APP_ALLOWED_ORIGIN') ?: '*';
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: {$allowedOrigin}");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function respond(array $body, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_SLASHES);
    exit;
}

function successResponse(string $message, array $extra = []): never
{
    respond(array_merge(['success' => true, 'message' => $message], $extra));
}

function errorResponse(string $message, int $status = 400): never
{
    respond(['success' => false, 'message' => $message], $status);
}

function jsonInput(): array
{
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        errorResponse('Request body must be valid JSON.');
    }
    return $input;
}

function validateStudent(array $input, bool $requireId = false): array
{
    if ($requireId && filter_var($input['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
        errorResponse('A valid student ID is required.');
    }

    $fields = ['name', 'email', 'phone', 'course'];
    $student = [];
    foreach ($fields as $field) {
        $value = trim((string)($input[$field] ?? ''));
        if ($value === '') {
            errorResponse('Name, email, phone, and course are required.');
        }
        $student[$field] = $value;
    }

    if (mb_strlen($student['name']) > 100 || mb_strlen($student['email']) > 150 || mb_strlen($student['phone']) > 20 || mb_strlen($student['course']) > 100) {
        errorResponse('One or more fields are too long.');
    }
    if (!filter_var($student['email'], FILTER_VALIDATE_EMAIL)) {
        errorResponse('Please enter a valid email address.');
    }
    if (!preg_match('/^[0-9+() .-]{7,20}$/', $student['phone'])) {
        errorResponse('Please enter a valid phone number.');
    }
    return $student;
}
