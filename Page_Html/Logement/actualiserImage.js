let avatar = document.querySelector("#avatar");
let elem = document.querySelector("#photo");
elem.addEventListener("change", actualiser);

function actualiser(event){
    const[file] = photo.files;
    if (file){
        avatar.src = URL.createObjectURL(file);
    }
}