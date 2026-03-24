document.getElementById("registerForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const form = e.target;

  const data = {
    representante_legal: form.representante_legal.value,
    tipo_documento: form.tipo_documento.value,
    numero_documento: form.numero_documento.value,
    correo: form.correo.value,
    password: form.password.value,
    razon_social: form.razon_social.value || null
  };

  try {
    const response = await fetch("../../controllers/LogController.php?action=register", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    });

    const result = await response.json();

    if (result.success) {
      alert(result.message);
      window.location.href = "../../auth/login/login.php";
    } else {
      alert(result.error);
    }

  } catch (error) {
    console.error(error);
    alert("Error en la conexión");
  }
});