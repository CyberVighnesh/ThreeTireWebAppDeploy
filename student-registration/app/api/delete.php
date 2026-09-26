<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    errorResponse('Method not allowed.', 405);
}

$input = jsonInput();
$id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    errorResponse('A valid student ID is required.');
}
try {
    $statement = database()->prepare('DELETE FROM students WHERE id = :id');
    $statement->execute(['id' => $id]);
    if ($statement->rowCount() === 0) {
        errorResponse('Student not found.', 404);
    }
    successResponse('Student deleted successfully.');
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    errorResponse('The student could not be deleted right now.', 500);
}
