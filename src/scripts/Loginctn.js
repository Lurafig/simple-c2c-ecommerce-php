const login_form = document.getElementById("login-form");
const register_form = document.getElementById("register-form");
const loginCtn = document.getElementById("login-register-ctn");
const darkBack = document.getElementById("dark-background");
const exitbuttons = document.querySelectorAll(".exit");
const codeInput = document.getElementById("insert-code-input");
const insert_code_ctn = document.getElementById("insert-code-ctn");
const send_code_bttn = document.getElementById("send-code-bttn");
const expiration_timer = document.getElementById("expires-time");

register_form.addEventListener(
    "submit", (event) => {
        disableFields(event.target);
        const loading = event.target.querySelector("#loading-register");
        loading.className = "loading-running";

        SendEmail(event)
            .then((response) => {
                if(!response.err_code){
                    console.log(response);
                    expirationTimer(response.expiration_time * 1000);
                    sessionStorage.setItem("email", data.email);
                    loading.className = "loading-paused";
                    insert_code_ctn.style.display = "flex";
                    return 0;
                }

                enableFields(event.target);
                loading.className = "loading-paused";
                return 1;
        });

        event.preventDefault();
    })

login_form.addEventListener(
    "submit", (event) => {
        disableFields(event.target);
        const loading = event.target.querySelector("#loading-login");
        loading.className = "loading-running";

        loginValidate(event)
            .then(() => {
                loading.className = "loading-paused";
                enableFields(event.target);
        });

        event.preventDefault();
    })
    
const controller = new AbortController();
const signal = controller.signal;

function disableFields(thisForm){
    for(element of thisForm.children){
        element.setAttribute("disabled", "")
    }
}

function enableFields(thisForm){
    for(element of thisForm.children){
        element.removeAttribute("disabled");
    }
}

async function SendEmail(event){
    const data = {
        name: event.target.querySelector("input[name='nome']").value,
        pass: event.target.querySelector("input[name='senha']").value,
        email: event.target.querySelector("input[name='login']").value
    }
    
    return fetch("../controllers/sendEmail.php", {
        method: "POST",
        signal,
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    }).then(response => {
        if(!response.ok) {
            throw new Error("blalablbal");
        }
        return response.json()
    })
    // let sec = 1;
    // return new Promise((resolve) => {
    //     const y = setInterval(() => {
    //         console.log(sec);
    //         if (sec==3){
    //             clearInterval(y);
    //             resolve();
    //         }
    //         sec++;
    //     }, 1000)
    // })
}

async function loginValidate(event){
    const data = {
        email: event.target.querySelector("input[name='login']").value,
        password: event.target.querySelector("input[name='senha']").value
    }

    const response = await fetch(
        "../../controllers/login_process.php",
        { method: "POST",
            body: JSON.stringify(
                data
            )
        }
    ).then(response => {
        if(!response.ok){
            throw new Error("bla");
        }

        return response.json();
    })

    if(response.success){
        window.location.href = "/home";
    }

    document.getElementById("login-advice").textContent = response.message;
}

function switchForm(){
    const loginCtn = document.getElementById("login");
    const registerCtn = document.getElementById("register");

    console.log(window.getComputedStyle(
        loginCtn
    ).display);

    if (window.getComputedStyle(
        loginCtn
    ).display === "none"){
        loginCtn.style.display = "flex";
        registerCtn.style.display = "none";
    } else {
        loginCtn.style.display = "none";
        registerCtn.style.display = "flex";
    }
}

document.getElementById("do-login").addEventListener("click", () => {
    console.log("clicou")
    loginCtn.style.display = "flex";
});

document.getElementById("post-img-ctn-login").addEventListener("click", () => {
    loginCtn.style.display = "flex";
})

document.getElementById("signup-dropdown-login-link")
.addEventListener(
    "click",
    switchForm
);

document.getElementById("login-dropdown-signup-link")
.addEventListener(
    "click",
    switchForm
);

document.getElementById("resend-p").addEventListener(
    "click", () => {
        sessionStorage.removeItem("email");
        insert_code_ctn.style.display = "none";
        clearInterval(countdown);
        enableFields(register_form);
    }
)

exitbuttons.forEach(element => {
    element.addEventListener("click", () =>
        loginCtn.style.display = "none"
    )
});

darkBack.addEventListener("click", () => {
    loginCtn.style.display = "none";
});

function disableSending() {
    send_code_bttn.removeEventListener("click", checkCode);
    codeInput.setAttribute("disabled", "");
    send_code_bttn.setAttribute("disabled", "");

    return;
}

async function checkCode(){
    const givenCode = codeInput.value;

    const codeCompare = 
        await fetch("../controllers/sendEmail.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                code: givenCode
            })
        })
        .then(response => {
            if(!response.ok){
                throw new Error("abcd");
            }

            return response.json();
        })
        .then(response => response);

    const warning = document.getElementById("code-advice");

    if (codeCompare.expired){
        sessionStorage.removeItem("email");
        warning.textContent = "Código expirado";
        return
    }

    if(codeCompare.attempts_remaining == 0){
        disableSending();
        sessionStorage.removeItem("email");
        warning.textContent = "Não restam mais tentativas, o código precisa ser reenviado";
    }

    if (codeCompare.is_equal && !codeCompare.expired){
        console.log("acertou o código e não expirou");
        sessionStorage.removeItem("email");
        window.location.href = "/home";
    } else if(codeCompare.attempts_remaining > 0) {
        console.log("ainda restam tentativas");
        warning.textContent = `Código incorreto, ${
            codeCompare.attempts_remaining > 1
            ? "restam " + codeCompare.attempts_remaining + " tentativas"
            : "resta " + codeCompare.attempts_remaining + " tentativa"
        }`
    }

    return;
}

send_code_bttn.addEventListener("click", checkCode)

function validateCodeInput(event) {
    if(event.inputType === "insertLineBreak"){
        console.log(event.inputType);
        console.log("nduhuihui");
        send_code_bttn.click();
        return;
    }

    if (!/\d/.test(event.data) && event.data){
        event.preventDefault();
    }

    if (/\d{2,}/.test(event.target.value) && event.data){
        event.preventDefault();
    }
}
const login_register = document.getElementById("login-register");

codeInput.addEventListener(
    "beforeinput",
    validateCodeInput
)

let countdown;

function expirationTimer(expiresAt) {
    const minutes_span = document.getElementById("minutes");
    const seconds_span = document.getElementById("seconds");

    let remaining = expiresAt - Date.now();
    let minutes = Math.floor((remaining / 1000) / 60);
    let seconds = Math.max(0, Math.floor(remaining / 1000));

    minutes_span.textContent = minutes;
    seconds_span.textContent = seconds >= 10 ? seconds : "0" + seconds;

    countdown = setInterval(() => {
        remaining = expiresAt - Date.now();
        minutes = Math.floor((remaining / 1000) / 60);
        seconds = Math.max(0, Math.floor(remaining / 1000));

        if (remaining <= 0) {
            clearInterval(countdown);
            document.getElementById("code-advice").textContent = "Código expirado";
            disableSending();
            return;
        }
        
        minutes_span.textContent = minutes;
        seconds_span.textContent = seconds >= 10 ? seconds : "0" + seconds;
    }, 1000);
}