/**
 * ========================================
 * MAIN JAVASCRIPT FILE
 * ========================================
 *
 * This file contains JavaScript for interactive features.
 */

// Auto-hide alerts after 5 seconds
document.addEventListener("DOMContentLoaded", function () {
  const alerts = document.querySelectorAll(".alert");

  alerts.forEach(function (alert) {
    setTimeout(function () {
      alert.style.opacity = "0";
      alert.style.transition = "opacity 0.5s";
      setTimeout(function () {
        alert.remove();
      }, 500);
    }, 5000);
  });
});

// Confirm before delete
function confirmDelete(message) {
  return confirm(message || "Are you sure you want to delete this?");
}

// Preview image before upload
function previewImage(input, previewId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      const preview = document.getElementById(previewId);
      if (preview) {
        preview.src = e.target.result;
        preview.style.display = "block";
      }
    };

    reader.readAsDataURL(input.files[0]);
  }
}

// Form validation
function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return true;

  const requiredFields = form.querySelectorAll("[required]");
  let isValid = true;

  requiredFields.forEach(function (field) {
    if (!field.value.trim()) {
      field.style.borderColor = "#e74c3c";
      isValid = false;
    } else {
      field.style.borderColor = "#ddd";
    }
  });

  return isValid;
}
