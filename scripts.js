$(document).ready(function() {
    loadTasks();

    $('#task-form').on('submit', function(e) {
        e.preventDefault();

        const taskName = $('#task-name').val().trim();
        const categories = $('input[name="categories"]:checked').map(function() {
            return $(this).val();
        }).get();

        if (taskName === "") {
            alert("Por favor, ingrese un nombre de tarea.");
            return;
        }
        if (categories.length === 0) {
            alert("Por favor, seleccione al menos una categoría.");
            return;
        }

        $.ajax({
            url: 'add_task.php',
            type: 'POST',
            data: { task_name: taskName, categories: categories },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.success) {
                    loadTasks();
                    $('#task-form')[0].reset(); 
                } else {
                    alert("Error al añadir la tarea: " + res.error);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la petición AJAX:", status, error);
            }
        });
    });

    function loadTasks() {
        $.ajax({
            url: 'get_tasks.php',
            type: 'GET',
            success: function(response) {
                const tasks = JSON.parse(response);
                const $tbody = $('#task-list tbody');
                $tbody.empty();

                tasks.forEach(function(task) {
                    const categories = task.categories.split(',').map(cat => `<span>${cat.trim()}</span>`).join('');
                    $tbody.append(`
                        <tr>
                            <td>${task.name}</td>
                            <td class="categories">${categories}</td>
                            <td><span class="actions delete-task" data-id="${task.id}">❌</span></td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error en la petición AJAX:", status, error);
            }
        });
    }

    $('#task-list').on('click', '.delete-task', function() {
        const taskId = $(this).data('id');

        $.ajax({
            url: 'delete_task.php', 
            type: 'POST',
            data: { id: taskId },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.success) {
                    loadTasks();
                } else {
                    alert("Error al eliminar la tarea: " + res.error);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la petición AJAX:", status, error);
            }
        });
    });
});
    