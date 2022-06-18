if (typeof fileInput.files[0] !== "undefined") {
 var filename = fileInput.files[0].name;
 var selectName = document.getElementsByClassName("file-select-name")[0];
 selectName.innerText = filename;
}
