document.addEventListener("DOMContentLoaded", () => {
  const slides = document.querySelectorAll(".slide");
  let current = 0;
  let typingInterval;

  function typeText(element, text, speed = 60, callback) {
    clearInterval(typingInterval);
    element.textContent = "";
    let i = 0;

    typingInterval = setInterval(() => {
      if (i < text.length) {
        element.textContent += text.charAt(i);
        i++;
      } else {
        clearInterval(typingInterval);
        if (callback) callback();
      }
    }, speed);
  }

  function showSlide(index) {
    slides.forEach((slide, i) => slide.classList.toggle("active", i === index));

    const slide = slides[index];
    const title = slide.querySelector(".slide-title");
    const desc = slide.querySelector(".slide-desc");

    const titleText = title.dataset.text || "";
    const descText = desc.dataset.text || "";

    // Reset before typing
    title.textContent = "";
    desc.textContent = "";

    // Add small delay for fade transition
    setTimeout(() => {
      typeText(title, titleText, 50, () => {
        setTimeout(() => {
          typeText(desc, descText, 40);
        }, 500);
      });
    }, 1000); // waits for the image transition
  }

  function nextSlide() {
    current = (current + 1) % slides.length;
    showSlide(current);
  }

  // Initialize
  showSlide(current);
  setInterval(nextSlide, 9000);
});