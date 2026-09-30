// Preloader removed for faster page loads; kept as a no-op for old onload handlers.
const preloaderFunction = () => {};

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





