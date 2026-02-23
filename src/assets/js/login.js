document.querySelector("form").addEventListener("submit", function(e) {
    const email = document.querySelector("input[name='email']").value;
    
    if (!email.includes("@")) {
        alert("Correo inválido");
        e.preventDefault();
    }
});