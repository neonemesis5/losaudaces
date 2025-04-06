<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Los Audaces</title>
	<link href="css/style.css" rel="stylesheet">
</head>

<body>
	<div class="page-wrapper">
		<!-- Header -->
		<header class="main-header">
			<div class="logo-container">
				<img src="resources/logo.png" alt="Los Audaces" class="logo">
			</div>
			<div class="menu-container">
				<button class="menu-toggle">
					<span></span>
					<span></span>
					<span></span>
				</button>
				<nav class="main-nav">
					<ul>
						<li><a href="#">Rifas</a></li>
						<li><a href="#">Premios</a></li>
						<li><a href="#">Contacto</a></li>
						<li><a href="#">Login</a></li>
					</ul>
				</nav>
			</div>
		</header>

		<!-- Contenido principal -->
		<main class="main-content">
			<div class="hero-section">
				<img src="resources/image2.png" alt="Fondo de sorteos" class="background-image">
				<div class="hero-text">
					<h1>¡Cambia tu vida con un solo boleto! ¡Participa ya!</h1>
				</div>
				<div class="purple-bar">&nbsp;</div>
				<div class="lottery-promo">
					<div>HOY PUEDE SER TU DÍA DE SUERTE</div>
					<div>Participa en nuestros fascinantes sorteos</div>
					<div>¡La suerte está de tu lado!</div>
					<button>Comprar Boleto</button>
				</div>
			</div>
		</main>

		<!-- Footer -->
		<footer class="main-footer">
			<div class="left-footer">Pie Izquierdo</div>
			<div class="right-footer">Pie Derecho</div>
		</footer>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const menuToggle = document.querySelector('.menu-toggle');
			const mainNav = document.querySelector('.main-nav');

			menuToggle.addEventListener('click', function() {
				mainNav.classList.toggle('active');
				menuToggle.classList.toggle('active');
			});

			// Cerrar menú al hacer clic en enlaces (mobile)
			const navLinks = document.querySelectorAll('.main-nav a');
			navLinks.forEach(link => {
				link.addEventListener('click', function() {
					if (window.innerWidth <= 768) {
						mainNav.classList.remove('active');
						menuToggle.classList.remove('active');
					}
				});
			});
		});
	</script>
</body>

</html>