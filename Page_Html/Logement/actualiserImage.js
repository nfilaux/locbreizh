function updateAvatar(photo, avatar) {
    const [file] = photo.files;

    if (file) {
        avatar.src = URL.createObjectURL(file);
    }
}


document.querySelector("#image1").addEventListener("change", function () {
    updateAvatar(this, document.querySelector("#in_image1"));
});

document.querySelector("#image2").addEventListener("change", function () {
    updateAvatar(this, document.querySelector("#in_image2"));
});

document.querySelector("#image3").addEventListener("change", function () {
    updateAvatar(this, document.querySelector("#in_image3"));
});

document.querySelector("#image4").addEventListener("change", function () {
    updateAvatar(this, document.querySelector("#in_image4"));
});

document.querySelector("#image5").addEventListener("change", function () {
    updateAvatar(this, document.querySelector("#in_image5"));
});

document.querySelector("#image6").addEventListener("change", function () {
    updateAvatar(this, document.querySelector("#in_image6"));
});