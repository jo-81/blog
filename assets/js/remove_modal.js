const exampleModal = document.getElementById('remove_modal')

if (exampleModal) {
    exampleModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const path = button.getAttribute('data-path')
        const form = document.getElementById('form_remove')

        if (form) {
            form.action = path;
        }
    })

    exampleModal.addEventListener('hide.bs.modal', event => {
        const form = document.getElementById('form_remove')

        if (form) {
            form.action = '';
        }
    })
}