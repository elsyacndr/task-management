document.addEventListener('DOMContentLoaded', function() {
    // Existing code...

    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if(!confirm('Are you sure you want to delete this task?')) {
                e.preventDefault();
            }
        });
    });

    const editModal = document.getElementById('editTaskModal');
    if(editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const taskId = button.getAttribute('data-task-id');
            const taskTitle = button.getAttribute('data-task-title');
            const taskDesc = button.getAttribute('data-task-desc');
            const taskStatus = button.getAttribute('data-task-status');

            document.getElementById('edit_task_id').value = taskId;
            document.getElementById('edit_title').value = taskTitle;
            document.getElementById('edit_description').value = taskDesc;
            document.getElementById('edit_status').value = taskStatus;
        });
    }

    const autoAlerts = document.querySelectorAll('.alert-dismissible');
    autoAlerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

