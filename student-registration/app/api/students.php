<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse('Method not allowed.', 405);
}

try {
    $statement = database()->query('SELECT id, name, email, phone, course, created_at FROM students ORDER BY created_at DESC, id DESC');
    respond(['success' => true, 'students' => $statement->fetchAll()]);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    errorResponse('The student list is temporarily unavailable.', 500);
}
