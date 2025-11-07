document.addEventListener("DOMContentLoaded", function() {
  const slides = document.querySelectorAll(".slide");
  let current = 0;

  function typeText(element, text, speed = 50) {
    element.textContent = "";
    let i = 0;
    const interval = setInterval(() => {
      element.textContent += text.charAt(i);
      i++;
      if (i >= text.length) clearInterval(interval);
    }, speed);
  }

  function showSlide(index) {
    slides.forEach((slide, i) => slide.classList.toggle("active", i === index));

    const titleEl = slides[index].querySelector(".slide-title");
    const descEl = slides[index].querySelector(".slide-desc");

    const titleText = titleEl.getAttribute("data-text");
    const descText = descEl.getAttribute("data-text");

    // Reset text before typing
    titleEl.textContent = "";
    descEl.textContent = "";
    titleEl.style.width = "0";
    descEl.style.width = "0";

    // Animate typing
    setTimeout(() => {
      typeText(titleEl, titleText, 60);
      setTimeout(() => typeText(descEl, descText, 40), titleText.length * 60 + 400);
    }, 700);
  }

  function nextSlide() {
    current = (current + 1) % slides.length;
    showSlide(current);
  }

  // Initialize
  showSlide(current);
  setInterval(nextSlide, 9000); // every 9 seconds
});
