<?php?>

<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen flex">

    <!-- IZQUIERDA – FORM -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white px-8 py-12">
    
    <div class="w-full max-w-3xl bg-white border-2 border-gray-200 rounded-3xl shadow-xl p-10">

        <!-- Título -->
        <h2 class="text-3xl font-bold text-gray-800 text-center md:col-span-2 bg-gray">
        Crear Cuenta
        </h2>

        <p class="text-gray-500 text-center mt-2 mb-10 md:col-span-2">
        Regístrate para acceder al observatorio tecnológico
        </p>

        <form id="registerForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Representante -->
        <div>
            <label class="text-sm font-medium">
            Nombre del representante legal <span class="text-red-500">*</span>
            </label>
            <input type="text" name="representante" placeholder="Carlos Ocampo"
            class="w-full mt-1 px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#39A900] focus:bg-white transition" />
        </div>

        <!-- Empresa -->
        <div>
            <label class="text-sm font-medium">
            Nombre de la empresa <span class="text-red-500">*</span>
            </label>
            <input type="text" name="empresa" placeholder="Ejemplo S.A.S"
            class="w-full mt-1 px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#39A900] focus:bg-white transition" />
        </div>

        <!-- Email -->
        <div class="md:col-span-2">
            <label class="text-sm font-medium">
            Correo electrónico <span class="text-red-500">*</span>
            </label>
            <input type="email" name="email" placeholder="ejemplo@ejemplo.com"
            class="w-full mt-1 px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#39A900] focus:bg-white transition" />
        </div>

        <!-- Razón social -->
        <div class="md:col-span-2">
            <label class="text-sm font-medium">
            Razón social
            </label>
            <input type="text" name="razon_social" placeholder="Razón social..."
            class="w-full mt-1 px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#39A900] focus:bg-white transition" />
        </div>

        <!-- Tipo documento -->
        <div>
            <label class="text-sm font-medium">
            Tipo de documento <span class="text-red-500">*</span>
            </label>
            <select name="tipo_documento"
            class="w-full mt-1 px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#39A900] focus:bg-white transition">
            <option value="">Seleccione</option>
            <option value="CC">Cédula</option>
            <option value="NIT">NIT</option>
            <option value="CE">RUT</option>
            </select>
        </div>

        <!-- Documento -->
        <div>
            <label class="text-sm font-medium">
            Número de documento <span class="text-red-500">*</span>
            </label>
            <input type="text" name="documento" placeholder="148444112453"
            class="w-full mt-1 px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#39A900] focus:bg-white transition" />
        </div>

        <!-- Contraseña -->
        <div class="relative">
            <label class="text-sm font-medium">
            Contraseña <span class="text-red-500">*</span>
            </label>
            <input type="password" id="password" name="password" placeholder="*****"
            class="w-full mt-1 px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#39A900] focus:bg-white transition" />
            
            <button type="button" id="togglePassword" 
            class="absolute right-3 top-9 text-gray-500">
            <i data-lucide="eye" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Confirmar contraseña -->
        <div class="relative">
            <label class="text-sm font-medium">
            Confirmar Contraseña <span class="text-red-500">*</span>
            </label>
            <input type="password" id="confirmPassword" name="confirm_password" placeholder="*****"
            class="w-full mt-1 px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#39A900] focus:bg-white transition " />
            
            <button type="button" id="toggleConfirmPassword"
            class="absolute right-3 top-9 text-gray-500 ">
            <i data-lucide="eye" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Botón -->
        <button type="submit"
            class="md:col-span-2 w-full bg-[#39A900] text-white py-3 rounded-lg hover:bg-green-700 transition font-medium ">
            Registrarse
        </button>

        <!-- Login -->
        <p class="md:col-span-2 text-center text-sm text-gray-600">
            ¿Ya tienes una cuenta?
            <a href="../../auth/login/login.php"
            class="text-[#39A900] font-medium hover:underline">
            Inicia Sesión aquí
            </a>
        </p>

        <!-- Volver -->
        <div class="md:col-span-2 text-center">
            <a href="../../view/landing/landing.php"
            class="text-sm text-[#39A900] font-medium hover:underline">
            ← Volver al inicio
            </a>
        </div>

        </form>
    </div>
    </div>

  <!-- DERECHA – IMAGEN -->
  <div class="hidden lg:block lg:w-1/2 relative">
    <img img src="../../assets/img/sena.jpeg"
      class="w-full h-full object-cover" />

    <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white px-10">
      <div>
        <h1 class="text-4xl font-bold">
          Observatorio de Perfiles Tecnológicos
        </h1>
        <p class="mt-4 text-gray-200">
          Necesidades empresariales clasificadas por líneas tecnológicas
        </p>
      </div>
    </div>
  </div>

</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>src/assets/js/register.js"></script>

<?php 