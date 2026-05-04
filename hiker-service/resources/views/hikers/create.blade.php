<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiker Service - Registrasi Pendaki</title>
    <!-- Memanggil Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F4F6F9] min-h-screen flex items-center justify-center p-4 font-sans">

    <!-- Card Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 w-full max-w-2xl">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center justify-center gap-2">
                ⛺  Form Registrasi Pendaki
            </h1>
            <p class="text-gray-500 text-sm mt-1">Lengkapi biodata di bawah ini untuk database Hiker Service.</p>
        </div>

        <!-- FORM: Perhatikan tambahan id="hikerForm" dan hilangnya action/method -->
        <form id="hikerForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Budi Santoso" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK (16 Digit)</label>
                    <input type="number" name="nik" required placeholder="35150..." class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required placeholder="budi@email.com" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" name="phone" required placeholder="0812..." class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="birth_date" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select name="gender" required class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="address" rows="2" required placeholder="Jl. Semeru No. 1, Malang" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kontak Darurat</label>
                    <input type="text" name="emergency_contact_name" required placeholder="Siti (Istri/Ibu)" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP Kontak Darurat</label>
                    <input type="text" name="emergency_contact_phone" required placeholder="0898..." class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                </div>

            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-[#4E9369] hover:bg-[#3D7854] text-white font-medium py-2.5 px-4 rounded-md transition duration-200">
                    Daftarkan Pendaki
                </button>
            </div>
        </form>
    </div>

    <!-- SCRIPT UNTUK MENEMBAK API -->
    <script>
        document.getElementById('hikerForm').addEventListener('submit', async function(e) {
            e.preventDefault(); // Ini fungsi penting untuk mencegah error 405 Method Not Allowed!

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                // Menggunakan relative URL agar otomatis menyesuaikan dengan 127.0.0.1 atau localhost
                const response = await fetch('/api/hikers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Sukses: ' + result.message);
                    this.reset(); // Kosongkan form setelah berhasil
                } else {
                    // Tampilkan pesan error validasi dari Laravel
                    alert('Gagal! Cek NIK/Email atau pastikan format benar.\n' + (result.message || ''));
                    console.log(result);
                }
            } catch (error) {
                alert('Terjadi kesalahan saat menghubungi server.');
                console.error(error);
            }
        });
    </script>
</body>
</html>
