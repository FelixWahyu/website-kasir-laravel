let formToSubmit = null;
const modal = document.getElementById("confirmModal");
const modalBox = document.getElementById("modalBox");
const cancelBtn = document.getElementById("cancelBtn");
const confirmBtn = document.getElementById("confirmBtn");

export function openConfirmModal(form, event) {
    event.preventDefault();
    formToSubmit = form;

    modal.classList.remove("hidden");
    modal.classList.add("flex");

    setTimeout(() => {
        modalBox.classList.remove("scale-90", "opacity-0");
        modalBox.classList.add("scale-100", "opacity-100");
    }, 10);

    cancelBtn.addEventListener("click", function () {
        closeModal();
    });

    confirmBtn.addEventListener("click", function () {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });

    function closeModal() {
        modalBox.classList.add("scale-90", "opacity-0");
        modalBox.classList.remove("scale-100", "opacity-100");
        setTimeout(() => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }, 200);

        formToSubmit = null;
    }
}
