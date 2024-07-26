const message = document.getElementById("flash-message");

if (message) {
  setTimeout(() => {
    message.classList.add = "flash-message";
  }, 3000);
}
