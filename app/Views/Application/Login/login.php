<div class="d-flex justify-content justify-content-center">
    <div class="d-flex justify-content-center flex-wrap vertical-center" style="">

        <?php if (isset($_GET["register"])) : ?>
            <div class="d-flex flex-column my-auto text-center my-5" style="width: 50rem" id="regContainer">
                <p class="mx-auto mt-3 mb-0 fs-5 text-light">Regisztrációhoz kérem töltse ki az alábbi mezőket:</p>
                <hr class="mx-auto" style="width: 20rem; border-top: 2px solid #171717">

                <form id="regForm" style="width: 50rem; text-align: left" action="Login/register" method="post"
                      class="mx-auto d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <div>
                            <label for="rFamilyName" class=" ps-3  text-light mx-auto">Vezetéknév</label>
                            <input type="text" class="form-control" id="rFamilyName" autocomplete="family-name"
                                   name="rFamilyName"
                                   style="width: 95%" required>
                        </div>
                        <div>
                            <label for="rGivenName" class=" ps-3 text-light mx-auto">Keresztnév</label>
                            <input type="text" class="form-control" id="rGivenName" autocomplete="given-name"
                                   name="rGivenName"
                                   style="width: 95%" required>
                        </div>
                    </div>
                    <label for="remail" class="mt-3 mb-0 text-light mx-auto" style="width: 65%">Email cím</label>
                    <input type="email" class="form-control mx-auto" id="remail" autocomplete="email" value="root@localhost.com" name="remail"
                           style="width: 70%" required>
                    <label for="password" class="mt-3 mb-0 text-light mx-auto" style="width: 65%">Jelszó</label>
                    <input type="password" class="form-control mx-auto" style="width: 70%" name="rpassword"
                           autocomplete="new-password" id="rpassword" required>
                    <label for="password" class="mt-3 mb-0 text-light mx-auto" style="width: 65%">Jelszó ismét</label>
                    <input type="password" class="form-control mx-auto" style="width: 70%" name="rpassword2"
                           autocomplete="new-password" id="rpassword2" required>
                    <input type="submit" class="btn mx-auto mt-2" style="width: 30%; color: white" value="regisztráció">
                </form>

                <hr class="mx-auto my-2" style="width: 20rem; border-top: 2px solid #171717">
                <div class="mt-1 d-flex justify-content-evenly">
                    <a class="btn" style="width: 50%; color: white" href="/login">inkább bejelentkezek</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!isset($_GET["register"])) : ?>
            <div class="d-flex flex-column my-5 text-center" style="width: 35rem" id="loginContainer">
                <p class="mx-auto mt-3 mb-0 fs-5 text-light">Kérem jelentkezzen be az alábbi módok egyikén</p>
                <hr class="mx-auto" style="width: 20rem; border-top: 2px solid #171717">
                <form id="loginForm" style="width: 30rem; text-align: left" action="Login/login" method="post"
                      onsubmit="getData('Login/login', 'loginForm')"
                      class="mx-auto d-flex flex-column ">

                    <label for="email" class=" mb-0 text-light mx-auto" style="width: 65%">Email cím</label>
                    <input type="email" class="form-control mx-auto" id="email" name="email" style="width: 70%"
                           required>
                    <label for="password" class="mt-3 mb-0 text-light mx-auto" style="width: 65%">Jelszó</label>
                    <input type="password" class="form-control mx-auto" style="width: 70%" name="password" id="password"
                           required>
                    <div style="width: 65%" class="mx-auto mt-3 mb-0">
                        <label for="rememberMe" class=" text-light mx-auto" style="">Bejelentkezve
                            marad</label>
                        <input type="checkbox" class="form-check-input" style="" name="rememberMe" id="rememberMe" checked>
                    </div>
                    <input type="submit" class="btn mx-auto mt-2" style="width: 30%; color: white"
                           value="bejelentkezés">
                </form>
                <hr class="mx-auto my-2" style="width: 20rem; border-top: 2px solid #171717">
                <div class="mt-1 d-flex justify-content-evenly">
                    <a class="btn" style="width: 25%; color: white" href="Login?register=true">Regisztráció</a>
                    <a class="btn" style="width: 40%; color: white" href="Login/pwreset" >Elfelejtett jelszó</a>
                </div>

                <hr class="mx-auto" style="width: 20rem; border-top: 2px solid #171717">
                <button class="btn btn-danger mx-auto mb-3" style="width: 15rem"
                        onclick="window.location='<?php if (isset($data)) echo $data["loginURL"]; ?>'">Login with Google
                    <img src="/public/resources/png-transparent-google-logo-google-text-trademark-logo.png" alt=""
                         style="height: 1.5rem"></button>
            </div>
        <?php endif; ?>

    </div>
</div>
<style>
    html {
        height: 100%;
    }

    body {
        background-image: linear-gradient(to bottom right, #233236, #648c8e);
        height: 100%;
    }

    /* #regContainer {
         display: none !important;
     }*/
</style>

<!--<script>
    function getRequest(url, formId) {
        return fetch(url, {method: "POST", body: new FormData(document.getElementById(formId))})
            .then(res => {
                if (res.ok) { // Checks server response (if there is one)
                    return res.json();
                } else {
                    throw new Error("Bad response");
                }
            });
    }

    function getData(url, formId) {
        event.preventDefault();
        getRequest(url, formId)
            .then(data => {
                if (data["StatusCode"] !== 0)
                    alert(data["Message"])
                console.log(data["Message"]);
            })
            .catch(err => console.log(err)); // Catch handles an error
    }

    let callReg = () => {
        document.getElementById("loginContainer").setAttribute('style', 'display:none !important');
        document.getElementById("regContainer").setAttribute('style', 'display:block !important; width: 50rem');
    }
    let callLogin = () => {
        document.getElementById("loginContainer").setAttribute('style', 'display:block !important; width: 30rem');
        document.getElementById("regContainer").setAttribute('style', 'display:none !important');
    }
</script>-->


<!--<form id="regForm2" style="width: 50rem; text-align: left" action="Login/register" method="post">
    <div class="d-flex justify-content-center">
        <div>
            <label for="rFamilyName" class=" ps-3  text-light mx-auto">Vezetéknév</label>
            <input type="text" class="form-control" id="rFamilyName" autocomplete="family-name"
                   name="rFamilyName"
                   style="width: 95%" required>
        </div>
        <div>
            <label for="rGivenName" class=" ps-3 text-light mx-auto">Keresztnév</label>
            <input type="text" class="form-control" id="rGivenName" autocomplete="given-name"
                   name="rGivenName"
                   style="width: 95%" required>
        </div>
    </div>
    <label for="remail" class="mt-3 mb-0 text-light mx-auto" style="width: 65%">Email cím</label>
    <input type="email" class="form-control mx-auto" id="remail" autocomplete="email" name="remail"
           style="width: 70%" required>
    <label for="password" class="mt-3 mb-0 text-light mx-auto" style="width: 65%">Jelszó</label>
    <input type="password" class="form-control mx-auto" style="width: 70%" name="rpassword"
           autocomplete="new-password" id="rpassword" required>
    <label for="password" class="mt-3 mb-0 text-light mx-auto" style="width: 65%">Jelszó ismét</label>
    <input type="password" class="form-control mx-auto" style="width: 70%" name="rpassword2"
           autocomplete="new-password" id="rpassword2" required>
    <input type="submit" class="btn mx-auto mt-2" style="width: 30%; color: white" value="regisztráció">
</form>-->