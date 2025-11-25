document.querySelectorAll(".alert-message").forEach((alert) => {
    setTimeout(() => {
        if (alert) {
            alert.style.transition = "opacity 0.4s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 400);
        }
    }, 2500);
});
