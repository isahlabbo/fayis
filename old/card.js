document.getElementById("staffForm").addEventListener("submit", function(e){
  e.preventDefault();

  // Get form values
  document.getElementById("out-name").textContent = document.getElementById("name").value;
  document.getElementById("out-id").textContent = document.getElementById("idno").value;
  document.getElementById("out-dept").textContent = document.getElementById("dept").value;
  document.getElementById("out-role").textContent = document.getElementById("role").value;
  document.getElementById("out-validity").textContent = document.getElementById("validity").value;

  // Load staff photo
  const photoFile = document.getElementById("photo").files[0];
  const reader1 = new FileReader();
  reader1.onload = function(e) {
    document.getElementById("staff-photo").src = e.target.result;
  }
  reader1.readAsDataURL(photoFile);

  // Load QR code
  const qrFile = document.getElementById("qr").files[0];
  const reader2 = new FileReader();
  reader2.onload = function(e) {
    document.getElementById("staff-qr").src = e.target.result;
  }
  reader2.readAsDataURL(qrFile);

  // Show card + download button
  document.getElementById("id-card").style.display = "flex";
  document.getElementById("download-btn").style.display = "block";

  // Scroll to ID card
  window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });
});

// Download function
function downloadCard() {
  // Download only the front card for now
  const element = document.querySelector(".id-card.front");
  const opt = {
    margin: 0,
    filename: "staff-id-card.pdf",
    image: { type: "jpeg", quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: "in", format: "a4", orientation: "portrait" }
  };
  html2pdf().set(opt).from(element).save();
  // To export both sides, consider combining front and back into one container or exporting each separately.
}
