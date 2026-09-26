<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Method not allowed.', 405);
}

$student = validateStudent(jsonInput());
try {
    $statement = database()->prepare('INSERT INTO students (name, email, phone, course) VALUES (:name, :email, :phone, :course)');
    $statement->execute($student);
    successResponse('Student registered successfully.', ['id' => (int)database()->lastInsertId()]);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    errorResponse('The student could not be registered right now.', 500);
}
