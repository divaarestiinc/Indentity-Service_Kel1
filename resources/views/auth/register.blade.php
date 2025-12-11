<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Poliklinik Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center"
      style="background: linear-gradient(135deg, #133E87 0%, #608BC1 100%);">

    <div class="w-full max-w-md bg-white/90 backdrop-blur-md rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6 text-[#133E87]">
            Registrasi Akun Mahasiswa
        </h2>

        <!-- Alert Error -->
        <div id="errorMsg" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"></div>

        <!-- Alert Success -->
        <div id="successMsg" class="hidden bg-green-100 text-green-700 p-3 rounded mb-4 text-sm"></div>

        <form id="registerForm" class="space-y-4">

            <div>
                <label class="block text-gray-700 font-semibold mb-1">NIM</label>
                <input type="text" name="nim" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#133E87]"
                    placeholder="Masukkan NIM">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Nama Lengkap</label>
                <input type="text" name="name" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#133E87]"
                    placeholder="Masukkan nama lengkap">
            </div>

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

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#133E87]"
                    placeholder="Konfirmasi password">
            </div>

            <!-- Role mahasiswa otomatis -->
            <input type="hidden" name="role" value="mahasiswa">

            <button type="submit"
                class="w-full py-2 font-semibold rounded-lg transition text-white"
                style="background-color:#133E87;">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Sudah punya akun?
            <a href="/login" class="font-semibold" style="color:#133E87;">Login</a>
        </p>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            const errorMsg = document.getElementById('errorMsg');
            const successMsg = document.getElementById('successMsg');

            errorMsg.classList.add('hidden');
            successMsg.classList.add('hidden');

            try {
                const res = await fetch("http://localhost:8000/api/register", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const json = await res.json();

                if (!res.ok) {
                    errorMsg.textContent = json.message || json.error || "Terjadi kesalahan.";
                    errorMsg.classList.remove('hidden');
                    return;
                }

                // Simpan token jika diberikan backend
                if (json.token) {
                    localStorage.setItem('token', json.token);
                }

                successMsg.textContent = "Registrasi berhasil! Mengalihkan...";
                successMsg.classList.remove('hidden');

                setTimeout(() => {
                    window.location.href = "/login";
                }, 1000);

            } catch (error) {
                errorMsg.textContent = "Gagal terhubung ke server.";
                errorMsg.classList.remove('hidden');
            }
        });
    </script>

</body>
</html>
