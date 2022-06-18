let fileInput = document.getElementById("file-upload-input");
let fileSelect = document.getElementsByClassName("file-upload-select")[0];
fileSelect.onclick = function () {
 fileInput.click();
};
// fileInput.classList.toggle("showinput");
fileInput.style.display = "block";

if (fileInput.attributes.value.value !== "") {
 var selectName = document.getElementsByClassName("file-select-name")[0];
 selectName.innerText = fileInput.attributes.value.value;
}
fileInput.style.display = "";
fileInput.onchange = function () {
 let filename = fileInput.files[0].name;
 let selectName = document.getElementsByClassName("file-select-name")[0];
 selectName.innerText = filename;
};
