function formValidator(formTarget) {
    const formValidation = document.querySelector(formTarget);
    if (formValidation) {
        formValidation.addEventListener('submit', event => {
            if (!formValidation.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            formValidation.classList.add('was-validated');
        });
    }
}

function initDeleteModal(modalTarget) {
    const deleteModal = document.getElementById(modalTarget);
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const buttonDelete = event.relatedTarget;
            deleteModal.querySelector('#deleteName').textContent = buttonDelete.dataset.name;
            deleteModal.querySelector('#confirmDelete').href = window.location.pathname + '?action=effacer&id=' + buttonDelete.dataset.id;
        });
    }
}

function initFlash() {
    const flashMessage = document.querySelector('.alert');
    if(flashMessage) {
        setTimeout(function () {
            const alert = bootstrap.Alert.getOrCreateInstance(flashMessage);
            alert.close();
        }, 3000);
    }
}