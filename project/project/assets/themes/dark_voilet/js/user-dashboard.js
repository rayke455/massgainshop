
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

// add bg to nav
window.addEventListener("scroll", function () {
    let scrollpos = window.scrollY;
    const header = document.querySelector("nav");
    const headerHeight = header.offsetHeight;

    if (scrollpos >= headerHeight) {
        header.classList.add("active");
    } else {
        header.classList.remove("active");
    }
});

$(document).ready(function () {
    $('.cmn-select2').select2();
});


$(".modal-select").select2({
    dropdownParent: $("#formModal"),
});

// input file preview
const previewImage = (id) => {
    document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
};

// Tooltip
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// Copy text start
function copyTextFunc() {
    // get the container
    const element = document.querySelector('.docs-code');
    // Create a fake `textarea` and set the contents to the text
    // you want to copy
    const storage = document.createElement('textarea');
    storage.value = element.innerHTML;
    element.appendChild(storage);

    // Copy the text in the fake `textarea` and remove the `textarea`
    storage.select();
    storage.setSelectionRange(0, 99999);
    document.execCommand('copy');
    element.removeChild(storage);
}





