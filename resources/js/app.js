import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ===============================
// Simple browser fingerprint
// ===============================
let visitorId = localStorage.getItem("visitorId");

if (!visitorId) {
    // generate unique ID per browser
    visitorId = crypto.randomUUID();
    localStorage.setItem("visitorId", visitorId);
}

// Append hidden input to all forms
document.querySelectorAll("form").forEach(form => {
    let input = document.createElement("input");
    input.type = "hidden";
    input.name = "fingerprint";
    input.value = visitorId;
    form.appendChild(input);
});
