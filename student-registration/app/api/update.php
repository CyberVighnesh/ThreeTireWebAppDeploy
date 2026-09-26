<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    errorResponse('Method not allowed.', 405);
}

$input = jsonInput();
$student = validateStudent($input, true);
$id = (int)$input['id'];
try {
    $statement = database()->prepare('UPDATE students SET name = :name, email = :email, phone = :phone, course = :course WHERE id = :id');
    $statement->execute([...$student, 'id' => $id]);
    if ($statement->rowCount() === 0) {
        $check = database()->prepare('SELECT id FROM students WHERE id = :id');
        $check->execute(['id' => $id]);
        if (!$check->fetch()) {
            errorResponse('Student not found.', 404);
        }
    }
    successResponse('Student updated successfully.');
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    errorResponse('The student could not be updated right now.', 500);
}
