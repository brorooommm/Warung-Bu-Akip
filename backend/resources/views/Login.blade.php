<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login - Warung Bu Akip</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
  body { width:100vw; height:100vh; background:url('bglogin1.png') no-repeat center center fixed; background-size:cover; overflow:hidden; position:relative; }

  /* Gradien & shading */
  .gradient-bottom-right,.gradient-top-left,.center-blend,.left-glow,.shading { position:absolute; z-index:1; filter:blur(40px); }
  .gradient-bottom-right { bottom:0; right:0; width:60%; height:60%; background:radial-gradient(circle at bottom right, rgba(255,204,0,0.55), rgba(255,255,255,0) 70%); }
  .center-blend { top:0; left:35%; width:30%; height:100%; background:radial-gradient(circle at center, rgba(255,235,59,0.15), rgba(255,255,255,0) 70%); filter:blur(80px); z-index:2; }
  .left-glow { left:0; bottom:0; width:50%; height:70%; background:radial-gradient(circle at left bottom, rgba(255,235,59,0.35), rgba(255,255,255,0) 75%); filter:blur(50px); z-index:2; }
  .gradient-top-left { top:0; left:0; width:55%; height:55%; background:radial-gradient(circle at top left, rgba(255,235,59,0.5), rgba(255,255,255,0) 70%); filter:blur(60px); }
  .shading { bottom:0; left:0; width:60%; height:70%; background:radial-gradient(circle at left bottom, rgba(255,214,0,0.45), rgba(255,255,255,0) 70%); filter:blur(50px); }

  /* Balon & Partikel */
  .balon { position:absolute; border-radius:50%; opacity:0.25; animation:float 4.5s ease-in-out infinite; z-index:3; }
  .light-particle { position:absolute; width:6px; height:6px; border-radius:50%; background:rgba(255,255,255,0.6); animation:twinkle 3s infinite ease-in-out; z-index:3; }
  @keyframes twinkle { 0%,100%{opacity:0.1;transform:scale(0.9);} 50%{opacity:0.7;transform:scale(1.1);} }
  @keyframes float { 0%{transform:translateY(0) rotate(0);} 50%{transform:translateY(-25px) rotate(5deg);} 100%{transform:translateY(0) rotate(0);} }

  .balon {
  position:absolute;
  border-radius:50%;
  opacity:0.6;
  animation: float 4.5s ease-in-out infinite;
  z-index:3;
}

/* Ukuran & warna tiap balon */
.balon1 { width:30px; height:30px; background:rgba(255,193,7,0.7); left:10%; bottom:10%; animation-duration:5s;}
.balon2 { width:40px; height:40px; background:rgba(255,87,34,0.6); left:25%; bottom:5%; animation-duration:6s;}
.balon3 { width:25px; height:25px; background:rgba(76,175,80,0.5); left:40%; bottom:15%; animation-duration:4s;}
.balon4 { width:35px; height:35px; background:rgba(33,150,243,0.6); left:55%; bottom:8%; animation-duration:5.5s;}
.balon5 { width:28px; height:28px; background:rgba(156,39,176,0.5); left:70%; bottom:12%; animation-duration:4.5s;}
.balon6 { width:32px; height:32px; background:rgba(255,235,59,0.6); left:85%; bottom:7%; animation-duration:6s;}

@keyframes float {
  0% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(-25px) rotate(5deg); }
  100% { transform: translateY(0) rotate(0deg); }
}

  /* Kotak login */
  .login-box { position:absolute; right:10%; top:50%; transform:translateY(-50%); width:380px; border-radius:25px; box-shadow:0 8px 25px rgba(0,0,0,0.15); padding:40px 35px; z-index:5; background: rgba(0,0,0,0.4); }
  .logo { display:block; width:150px; margin:0 auto 15px; }
  h2,h3 { text-align:center; color:#fafafa; margin-bottom:25px; }
  h3 { text-decoration:wavy; }
  .input-group { position:relative; margin-bottom:20px; }
  .input-group img { width:22px; position:absolute; left:10px; top:50%; transform:translateY(-50%); opacity:0.7; }
  .input-group input { width:100%; padding:12px 12px 12px 40px; border:1px solid #ccc; border-radius:10px; font-size:15px; outline:none; }

  .btn { width:100%; padding:12px; border:none; border-radius:10px; background:linear-gradient(90deg,#FFC107,#FFB300); color:white; font-size:16px; cursor:pointer; transition:0.3s; }
  .btn:hover { transform:translateY(-2px); background:linear-gradient(90deg,#FFB300,#FFA000); }

  .forgot-password, .signup-link a { color:#FFB300; text-decoration:underline; font-size:14px; transition:color 0.3s; }
  .forgot-password:hover, .signup-link a:hover { color:#FFA000; }
  .forgot-password { display:block; text-align:right; margin-top:10px; margin-bottom:30px; }
  .signup-link { text-align:center; margin-top:25px; font-size:14px; color:#333; }

  /* POPUP */
  .popup-overlay { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:999; display:none; }
  .popup { background:#fff; padding:30px; border-radius:15px; text-align:center; max-width:400px; box-shadow:0 10px 30px rgba(0,0,0,0.25); }
  .popup button { margin-top:15px; padding:10px 20px; border:none; border-radius:8px; cursor:pointer; background:linear-gradient(90deg,#FFC107,#FFB300); color:white; }
</style>
</head>
<body>

  <!-- Gradien & Partikel -->
  <div class="gradient-top-left"></div>
  <div class="gradient-bottom-right"></div>
  <div class="center-blend"></div>
  <div class="shading"></div>
  <div class="left-glow"></div>

  <div class="balon balon1"></div>
  <div class="balon balon2"></div>
  <div class="balon balon3"></div>
  <div class="balon balon4"></div>
  <div class="balon balon5"></div>
  <div class="balon balon6"></div>
  <div class="light-particle p1"></div>
  <div class="light-particle p2"></div>
  <div class="light-particle p3"></div>
  <div class="light-particle p4"></div>

  <!-- Ilustrasi -->
  <img src="loginillu.png" alt="Ilustrasi" class="illustration" />

  <!-- Popup Informasi Awal -->
  <div class="popup-overlay" id="popupInfo">
    <div class="popup">
      <h2>Informasi</h2>
      <p>Selamat datang di Warung Bu Akip! Silakan login untuk melanjutkan.</p>
      <button id="closeInfo" class="btn">Tutup</button>
    </div>
  </div>

  <!-- Popup Pilih Role -->
  <div class="popup-overlay" id="popupRole">
    <div class="popup">
      <h2>Pilih Role Login</h2>
      <button class="roleBtn" data-role="admin">Admin</button>
      <button class="roleBtn" data-role="owner">Owner</button>
      <button class="roleBtn" data-role="cashier">Cashier</button>
    </div>
  </div>

  <!-- Kotak Login -->
   <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
    @csrf
  <div class="login-box">
    <img src="LOGO1O.png" alt="Logo" class="logo" />
    <h2>Selamat Datang!</h2>
    <h3>Warung Bu Akip</h3>
    <div class="input-group">
      <img src="account_circle.png" alt="Username" />
      <input type="text" name="username" placeholder="Username" required/>
    </div>
    <div class="input-group">
      <img src="uis_padlock.png" alt="Password" />
      <input type="password" name="password" placeholder="Password" required/>
    </div>

    <a href="#" class="forgot-password">Lupa kata sandi?</a>
    <button type="submit" id="loginButton" class="btn">Masuk</button>
    <p class="signup-link">Belum punya akun? <a href="{{ route('register.form') }}">Daftar di sini</a></p>
  </div>
</form>
<script>
  window.scrollTo(0,0);
</script>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    let username = document.querySelector('input[name="username"]').value.trim();
    let password = document.querySelector('input[name="password"]').value.trim();

    if (!username || !password) {
        e.preventDefault();
        alert("Username dan Password wajib diisi!");
    }
});
</script>
<script>
window.addEventListener('load', function(){
  const popupInfo = document.getElementById('popupInfo');
  const popupRole = document.getElementById('popupRole');
  popupInfo.style.display = 'flex';

  // Tutup popup info
  document.getElementById('closeInfo').addEventListener('click', function(){
    popupInfo.style.display = 'none';
    popupRole.style.display = 'flex';
  });

  // Pilih role
  document.querySelectorAll('.roleBtn').forEach(btn=>{
    btn.addEventListener('click', function(){
      popupRole.style.display = 'none';
      const role = this.getAttribute('data-role');
      let hidden = document.querySelector('.login-box input[name="role"]');
      if(!hidden){
        hidden = document.createElement('input');
        hidden.type='hidden';
        hidden.name='role';
        document.querySelector('.login-box').appendChild(hidden);
      }
      hidden.value = role;
    });
  });

  // Tombol login
  document.getElementById('loginButton').addEventListener('click', function(){
    const roleInput = document.querySelector('.login-box input[name="role"]');
    if(!roleInput || !roleInput.value){
      alert("Silakan pilih role login terlebih dahulu!");
      event.preventDefault();
    }
  });
});
</script>

</body>
</html>
