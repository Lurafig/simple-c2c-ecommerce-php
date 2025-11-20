const configs_arrow = document.getElementById("configs-arrow");
const configs = document.getElementById("configs");
const arrow_ctn = document.getElementById("account-configs");
const logout = document.getElementById("logout");

if (arrow_ctn){
    arrow_ctn.addEventListener("click",
        () => {
            console.log(window.getComputedStyle(
                configs_arrow
            ).height);
            if(window.getComputedStyle(
                configs
            ).height === "0px"){
                configs_arrow.style.marginTop = "-10px";
                configs_arrow.style.transform = "rotate(135deg)";
                document.getElementById("configs").style.height = "fit-content";
            } else {
                configs_arrow.style.marginTop = "10px";
                configs_arrow.style.transform = "rotate(316deg)";
                configs.style.height = "0";
            }
        }
    )
}

if(logout){
    logout.addEventListener("click", () => {
        fetch("../../controllers/logout_process.php", { method: "GET" })
            .then(response => response)
            .then(() => {
                window.location.href = "/home";
            });
    });
}
