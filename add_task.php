<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_name'], $_POST['categories'])) {
    $task_name = trim($_POST['task_name']); 
    $categories = $_POST['categories'];

    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (name) VALUES (:task_name)");
        $stmt->execute(['task_name' => $task_name]);

        $task_id = $pdo->lastInsertId();

        foreach ($categories as $category_id) {
            $stmt = $pdo->prepare("INSERT INTO task_categories (task_id, category_id) VALUES (:task_id, :category_id)");
            $stmt->execute(['task_id' => $task_id, 'category_id' => $category_id]);
        }

        echo json_encode(['success' => true]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos no válidos']);
}
?>
