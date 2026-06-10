@stack('script')
<script defer>
    // Show password ===================================>
    function show(event, password) {
        let type = password.getAttribute("type");
        let eye = event.currentTarget.childNodes[0];
        if (type === "password") {
            password.type = "text";
            eye.classList.add("bi-eye-slash-fill");
            eye.classList.remove("bi-eye-fill");
        } else {
            password.type = "password";
            eye.classList.remove("bi-eye-slash-fill");
            eye.classList.add("bi-eye-fill");
        }
    }



    // CapsLock ===================================>
    function capsLock(event) {
        if (event.getModifierState("CapsLock")) {
            document.getElementById("capsLockText").classList.add("d-block")
            document.getElementById("capsLockText").classList.remove("d-none")
        } else {
            document.getElementById("capsLockText").classList.add("d-none")
            document.getElementById("capsLockText").classList.remove("d-block")
        }
    }
</script>
