let newUser = {};

function continueRegisteration(event) {
    event.preventDefault();

    const email = document.getElementById("email").value.trim();
    const name = document.getElementById("name").value.trim();
    const tel = document.getElementById("tel").value.trim();
    const type = document.getElementById("type").value;
    const password = document.querySelector('input[name="password"]').value;

    if (!email || !name || !tel || !password) {
        alert("Please fill all fields");
        return;
    }

    
    if (type === "company") {
        window.location.href = "../pages/company-pages/signup-company.php";
    } else if (type === "passenger") {
        window.location.href = "../pages/user-pages/signup-user.php";
    }
}
