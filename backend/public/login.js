// login.js
document.querySelector("form").addEventListener("submit", function (e) {
  e.preventDefault(); // Mencegah reload halaman

  const username = document.querySelector("input[name='username']").value.trim();
  const password = document.querySelector("input[name='password']").value.trim();

  // Cek apakah kolom kosong
  if (!username || !password) {
    alert("Harap isi username dan password terlebih dahulu!");
    return;
  }

  let role = "";
  let targetPage = "";

  // 🔹 Logika menentukan role dan dashboard
  if (username === "BuAkip") {
    role = "Owner";
    targetPage = "dashboard_owner.html";
  } else if (username.endsWith("m")) {
    role = "Administrator";
    targetPage = "/admin";
  } else {
    role = "Kasir";
    targetPage = "dashboard_kasir.html";
  }

  // 🔹 Simpan status login di localStorage
  localStorage.setItem("username", username);
  localStorage.setItem("role", role);
  localStorage.setItem("isLoggedIn", "true");

  // 🔹 Arahkan ke halaman sesuai role
  alert(`Login berhasil sebagai ${role}!`);
  window.location.href = targetPage;
});
