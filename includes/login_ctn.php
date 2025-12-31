        <div id = "login-register-ctn">
            <div id = "dark-background"></div>
            <div id = "login">
                <button class = "exit" title = "Fechar">
                    X
                </button>
                <p class = "switch-form">
                    Ainda não tem uma conta?
                    <a id = "login-dropdown-signup-link">
                        Registrar-se
                    </a>
                </p>
                <form id = "login-form" method = "POST" action = "register.php">
                    <input
                        type="email"
                        placeholder = "Email"
                        name="login"
                        id="login_input"
                        autocomplete="off"
                        required
                    >
                    <input
                        type="password"
                        placeholder = "Senha"
                        name="senha"
                        id="passw_input"
                        autocomplete="off"
                        required
                    >
                    <button type="submit" id="log" name="log" class = "wide_button">
                        Logar
                        <div class = "loading" id = "loading-login"></div>
                    </button>
                    <p id = "login-advice"></p>
                </form>
            </div>
            <div id = "register">
                <button class = "exit" title = "Fechar">
                    X
                </button>
                <p class = "switch-form">
                    Já tem uma conta?
                    <a id = "signup-dropdown-login-link">
                        Logar
                    </a>
                </p>
                <form id = "register-form" method = "POST" action = "controllers/register_process.php">
                    <input
                        type="email"
                        placeholder = "Email"
                        name="login"
                        id="login-register"
                        autocomplete="email"
                        required
                    >
                    <input
                        type="text"
                        placeholder = "Nome"
                        name="nome"
                        id="input-name"
                        autocomplete="off"
                        required
                    >
                    <input
                        type="password"
                        placeholder = "Senha"
                        name="senha"
                        id="passw-register"
                        autocomplete="current-password"
                        required
                    >
                    <button type="submit" id="register-bttn" name="register" class = "wide_button">
                        Registrar
                        <div class = "loading" id = "loading-register"></div>
                    </button>
                </form>
            </div>
            <div id = "insert-code-ctn">
                <div id = "inst-ctn">
                    <h2 id = "send-code-h2">
                        Um código de dois digitos foi enviado para seu email,
                        insira-o abaixo.
                    </h2>
                    <div id = "expires-time">
                        Expira em <span id = "minutes">0</span>:<span id = "seconds">00</span>
                    </div>
                    <p id = "resend-p">Reenviar código</p>
                </div>
                <input id = "insert-code-input"
                    autocomplete="off"
                    maxlength="2"
                    placeholder="00"
                >
                <button id = "send-code-bttn" class = "wide_button">
                    Enviar
                </button>
                <p id = "code-advice"></p>
            </div>
            <style>
                input {
                    border: 5px solid var(--background3);
                }

                #inst-ctn {
                    display: grid;
                    justify-items: center;
                }

                #resend-p {
                    cursor: pointer;
                    color: var(--secondary-color);
                    margin:0;
                }

                #resend-p:hover {
                    text-decoration: underline;
                }

                #log,
                #register-bttn {
                    position: relative;
                    display:flex;
                    justify-content: center;
                    align-items: center;
                }

                #loading-login,
                #loading-register {
                    position: absolute;
                    left: 10px;
                    border-color: black rgba(49, 49, 49, 0.21);
                    border-width: 5px;
                    width: 30px;
                    height: 30px;
                }

                #code-advice {
                    margin: 0 0 50px 0;
                    position: absolute;
                    bottom: 0;
                }
            </style>
        </div>
        <script src = "/src/scripts/Loginctn.js"></script>
        <script>
            const expiresAt =
            <?php 
                if (isset($_SESSION["verify_expires"]) && !(time() > $_SESSION["verify_expires"])){
                    echo $_SESSION["verify_expires"] * 1000;
                } else {
                    echo 0;
                }
            ?> 
            || null;

            if(!expiresAt){
                sessionStorage.removeItem("email");
            } else{
                expirationTimer(expiresAt);
            }

            if(sessionStorage.getItem("email")){
                document.getElementById("login-register-ctn").style.display = "flex";
                document.getElementById("insert-code-ctn").style.display = "flex";
            }
        </script>