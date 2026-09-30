const preloaderFunction = () => {
    try {
        // Hide the preloader only if everything is successful
        preloader.style.display = "none";
    } catch (error) {
        console.error("An error occurred:", error);

        // Optionally display an error message to the user
        preloader.innerHTML = `
            <div style="color: red; text-align: center;">
                An error occurred. Please try again later.
            </div>
        `;
    }
};

// Fallback: Ensure preloader is visible indefinitely on error
window.addEventListener("load", preloaderFunction);

// Fallback timeout to ensure visibility
setTimeout(() => {
    if (preloader.style.display !== "none") {
        console.warn("Fallback triggered. Preloader is still visible.");
    }
}, 5000); // 10 seconds fallback (adjust as necessary)

// toggleSideMenu start
const toggleSideMenu = () => {
    document.body.classList.toggle("toggle-sidebar");
};
// toggleSideMenu end

// cmn select2 start
$(document).ready(function () {
    $('.cmn-select2').select2();
});
// cmn select2 end

// input file preview
const previewImage = (id) => {
    document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
};

// Tooltip
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// cmn select2 start
$(document).ready(function () {
    $('.cmn-select2').select2();
});
// cmn select2 end

// cmn-select2-modal
$(".modal-select").select2({
    dropdownParent: $("#formModal"),
});





