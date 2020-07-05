let menuBtn = document.querySelector(".menuBtn");
let wrapper = document.querySelector(".wrapper");
let sideMenu = document.querySelector(".menu");
let showMenu = true;

menuBtn.onclick = function () {
    if(showMenu){
        //sideMenu.style.transition = "0.5s";
        sideMenu.style.marginLeft = "0%";
        sideMenu.style.width = "90%";
        wrapper.style.display = "none";
        menuBtn.innerHTML = "<";
        showMenu = false;
    }else{
        //sideMenu.style.transition = "";
        sideMenu.style.marginLeft = "";
        sideMenu.style.width = "";
        wrapper.style.display = "block";
        menuBtn.innerHTML = ">";

        showMenu = true;
    }
}