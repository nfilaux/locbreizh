function changer(id1, id2){
    var x = document.getElementById(id2).src;
    var img = x.substring(x.lastIndexOf("/")+1,x.length);
    if (img == "oeil.svg"){
        document.getElementById(id1).setAttribute("type", "text");
        document.getElementById(id2).src="../svg/oeilFermer.svg";
    }
    else{
        document.getElementById(id1).setAttribute("type", "password");
        document.getElementById(id2).src="../svg/oeil.svg";
    }
}