// Code phần mô tả
ClassicEditor.create(document.querySelector("#description")).catch((error) => {
    console.error(error);
});
// Code phần mô tả

//code phần ảnh
const imageUploadInput = document.getElementById("image-upload");
const previewImage = document.getElementById("preview-image");

imageUploadInput.addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith("image/")) {
        previewImage.src = URL.createObjectURL(file);
        previewImage.style.display = "block";
    }
});
//code phần ảnh


