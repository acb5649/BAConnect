window.onclick = function(event) {
    let modals = document.querySelectorAll("div[id$='Modal']");
    modals.forEach(function(modal) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
};