const profilButton = document.querySelector(".user_nav_profil");

const burger = document.querySelector(".user_nav_burger");

profilButton.addEventListener("click", () => {
  console.log("coucou");
  burger.style.display = "flex";
});
