setTimeout(() => {
  const flashMessage = document.querySelector(".flash-message");
  const flashNotice = document.querySelector(".flash-notice");
  flashMessage.style.opacity = "0";

  setTimeout(() => {
    flashNotice.style.display = "none";
  }, 4000);
}, 5000);
