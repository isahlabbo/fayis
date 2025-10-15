document.getElementById("submitForm").addEventListener("submit", function(e) {
  e.preventDefault();
  document.getElementById("submit-message").style.display = "block";
  document.getElementById("submitForm").reset();
  setTimeout(function() {
    window.location.href = "card.html";
  }, 1200); // Show message for 1.2 seconds then redirect
});
