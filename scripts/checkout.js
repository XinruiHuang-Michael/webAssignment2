// JavaScript to handle delivery option changes
document.addEventListener("DOMContentLoaded", function () {
    const deliveryOption = document.getElementById("delivery-option");
    const addressField = document.getElementById("address-field");
    const phoneField = document.getElementById("phone-field");

    // Function to toggle address and phone fields based on delivery option
    function toggleAddressField() {
        if (deliveryOption.value === "delivery") {
            addressField.style.display = "block";
            phoneField.style.display = "block";
        } else {
            addressField.style.display = "none";
            phoneField.style.display = "none";
        }
    }

    // Add event listener for delivery option changes
    deliveryOption.addEventListener("change", toggleAddressField);

    // Initial toggle check on page load
    toggleAddressField();
});
