// js/dashboard.js

// Ambil data dari localStorage
const username = localStorage.getItem("username");
const role = localStorage.getItem("role");
const isLoggedIn = localStorage.getItem("isLoggedIn");

// 🔒 Kalau belum login, kembalikan ke login
if (isLoggedIn !== "true") {
  alert("Silakan login terlebih dahulu!");
  window.location.href = "login.html";
}

// Jalankan saat halaman sudah siap
document.addEventListener("DOMContentLoaded", () => {
  // Ganti teks nama user (kalau ada)
  const nameElement = document.querySelector('[data-user-name]');
  if (nameElement) nameElement.textContent = username || "User";

  // Ganti teks role (kalau ada)
  const roleElement = document.querySelector('[data-user-role]');
  if (roleElement) roleElement.textContent = role || "Pengguna";

  // Ganti teks selamat datang (kalau ada)
  const welcomeText = document.querySelector('[data-welcome]');
  if (welcomeText) welcomeText.textContent = `Welcome back, ${username || "User"}!`;
});

// Fungsi logout global
function logout() {
  localStorage.clear();
  alert("Anda telah logout.");
  window.location.href = "login.html";
}
