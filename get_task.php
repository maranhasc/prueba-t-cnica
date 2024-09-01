<?php
include 'conexion.php';

try {
    $stmt = $pdo->query("
        SELECT tasks.id, tasks.name, GROUP_CONCAT(categories.name SEPARATOR ', ') AS categories
        FROM tasks
        LEFT JOIN task_categories ON tasks.id = task_categories.task_id
        LEFT JOIN categories ON task_categories.category_id = categories.id
        GROUP BY tasks.id, tasks.name
        ORDER BY tasks.id DESC
    ");
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tasks);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
