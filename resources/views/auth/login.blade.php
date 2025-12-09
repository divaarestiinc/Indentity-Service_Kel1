<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;

            background-image: url('{{ asset("asset/images/kampus.jpeg") }}');
            background-size: cover;
            background-position: center 70%;
            background-repeat: no-repeat;

            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(6px);
            z-index: -1;
        }
    </style>
</head>

<body>

    <div class="w-full max-w-md bg-white/90 backdrop-blur-md rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6 text-[#133E87]">
            Selamat Datang
        </h2>

        <div id="errorBox" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"></div>

        <form id="loginForm" class="space-y-4">

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#133E87]"
                    placeholder="Masukkan email">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#133E87]"
                    placeholder="Masukkan password">
            </div>

            <button type="submit"
                class="w-full py-2 font-semibold rounded-lg transition text-white"
                style="background-color:#133E87;">
                Login
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Belum punya akun?
            <a href="/register" class="font-semibold" style="color:#133E87;">Register</a>
        </p>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            const email = document.querySelector("[name=email]").value.trim();
            const password = document.querySelector("[name=password]").value.trim();
            const errorBox = document.getElementById("errorBox");

            errorBox.classList.add("hidden");
            errorBox.innerText = "";

            try {
                const response = await fetch("http://localhost:8000/api/login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                    },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();

                if (response.ok) {

                    // Simpan token JWT
                    localStorage.setItem("token", result.token);

                    // Simpan role (jika diberikan backend)
                    if (result.role) {
                        localStorage.setItem("role", result.role);
                    }

                    // Redirect default
                    window.location.href = "/dashboard";

                } else {
                    errorBox.classList.remove("hidden");
                    errorBox.innerText = 
                        result.error || result.message || "Email atau password salah.";
                }

            } catch (error) {
                errorBox.classList.remove("hidden");
                errorBox.innerText = "Tidak dapat terhubung ke server.";
            }
        });
    </script>

</body>
</html>
