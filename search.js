let input = document.querySelector(".text");
let errordiv = document.getElementById("searchmiss");
document.querySelector(".btn").addEventListener("click", () => {
 if (input.value !== "") document.querySelector(".box").submit();
 else {
  errordiv.innerText = "Give a key for search";
 }
});
input.addEventListener("click", () => {
 errordiv.innerText = "";
});
