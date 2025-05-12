$(function() {
    $("#inicio").datepicker({
        dateFormat: "dd-mm-yy"
    });
    $("#fin").datepicker({
        dateFormat: "dd-mm-yy"
    });
    $("#fecha_creacion").datepicker({
        dateFormat: "yy-mm-dd"
    });
});

const menuBtn = document.querySelector("#as-menu-btn");
const menu = document.querySelector("#as-menu");

menuBtn.addEventListener("click", () => {
    menu.classList.toggle("show-menu");
});

const subMenuBtn = document.querySelectorAll(".as-submenu-btn");

for (let index = 0; index < subMenuBtn.length; index++) {
    subMenuBtn[index].addEventListener("click", () => {

        if (window.innerWidth < 992) {
            const subMenu = subMenuBtn[index].nextElementSibling;
            const height = subMenu.scrollHeight;

            if (subMenu.classList.contains("deploy")) {
                subMenu.classList.remove("deploy");
                subMenu.removeAttribute("style");
            } else {
                subMenu.classList.add("deploy");
                subMenu.style.height = height + "px";
            }
        }
    });

}

const valideKey = (evt) => {
    // code is the decimal ASCII representation of the pressed key.
    let code = (evt.which) ? evt.which : evt.keyCode;

    if (code == 8) { // backspace.
        return true;
    } else if (code >= 48 && code <= 57 || code === 46) { // is a number.
        return true;
    } else { // other keys.
        return false;
    }
}

if (document.getElementById("identificacion")) {
    document.getElementById("identificacion").addEventListener("input", (e) => {
        let value = e.target.value;
        e.target.value = value.replace(/[^A-Z\d-]/g, "");
        if (value.length >= 12) {
            e.target.value = "";
        }
    });
}

const habilitarCalificacion = (actividad, nota) => {
    const actividadClick = document.querySelector("#" + actividad);
    const notaClick = document.querySelector("#" + nota);

    if (actividadClick.checked) {
        notaClick.disabled = false;
    } else {
        notaClick.disabled = true;
        notaClick.value = '';
    }
}