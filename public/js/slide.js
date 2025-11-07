
document.addEventListener("DOMContentLoaded", function() {
  const slides = document.querySelectorAll(".slide");
  let current = 0;

  function typeText(element, text, speed = 50) {
    element.textContent = "";
    let i = 0;
    element.style.width = "0";
    const interval = setInterval(() => {
      element.style.width = i + "ch";
      element.textContent += text.charAt(i);
      i++;
      if (i >= text.length) clearInterval(interval);
    }, speed);
  }

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.remove("active");
      if (i === index) slide.classList.add("active");
    });

    const titleEl = slides[index].querySelector(".slide-title");
    const descEl = slides[index].querySelector(".slide-desc");

    const titleText = titleEl.getAttribute("data-text");
    const descText = descEl.getAttribute("data-text");

    // Clear texts before typing
    titleEl.textContent = "";
    descEl.textContent = "";
    titleEl.style.width = "0";
    descEl.style.width = "0";

    // Start typing animation
    setTimeout(() => {
      typeText(titleEl, titleText, 60);
      setTimeout(() => {
        typeText(descEl, descText, 40);
      }, titleText.length * 60 + 500);
    }, 800);
  }

  function nextSlide() {
    current = (current + 1) % slides.length;
    showSlide(current);
  }

  // Initialize
  showSlide(current);
  setInterval(nextSlide, 9000); // 9 seconds per slide
});

