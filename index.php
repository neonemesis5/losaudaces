<?php
// Configuraciones de seguridad
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self' https: 'unsafe-inline' 'unsafe-eval'; img-src 'self' data:;");

// Configuración de sesión segura
session_start([
	'cookie_lifetime' => 86400,
	'cookie_secure' => true,
	'cookie_httponly' => true,
	'cookie_samesite' => 'Strict',
	'use_strict_mode' => true
]);

require __DIR__ . '/controllers/sorteocontroller.php';

// Crear instancia del controlador
$sorteoController = new SorteoController();
$sorteosActivos = $sorteoController->getActiveSorteos();

// Protección contra session fixation
session_regenerate_id(true);

// Configuración de zona horaria
date_default_timezone_set('America/Bogota');

// Protección básica contra inyecciones
if (isset($_SERVER['QUERY_STRING'])) {
	$queryString = $_SERVER['QUERY_STRING'];
	if (preg_match('/[^a-zA-Z0-9_-]/', $queryString)) {
		header("HTTP/1.1 400 Bad Request");
		exit('Solicitud inválida');
	}
}

// Configuración de manejo de errores
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Participa en emocionantes sorteos con Los Audaces y gana premios increíbles">
	<title>Los Audaces - Sorteos y Rifas</title>
	<link href="css/style.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link rel="icon" href="resources/logo.png" type="image/png">
</head>

<body>
	<div class="page-wrapper">
		<!-- Header -->
		<header class="main-header">
			<div class="logo-container">
				<a id="inicio" href="index.php"><img src="resources/logo.png" alt="Los Audaces" class="logo"></a>
			</div>
			<div class="menu-container">
				<button class="menu-toggle">
					<span></span>
					<span></span>
					<span></span>
				</button>
				<nav class="main-nav">
					<ul>
						<li><a href="#" id="rifas">Rifas</a></li>
						<li><a href="#" id="premios">Premios</a></li>
						<li><a href="#" id="contact">Contacto</a></li>
						<li><a href="#" id="login">Login</a></li>
					</ul>
				</nav>
			</div>
		</header>

		<!-- Contenido principal -->
		<main class="main-content">
			<div id="home-section" style="display: block;"> <!-- MOSTRAR POR DEFECTO -->
				<div class="hero-section">
					<div class="purple-bar"></div>
					<div class="hero-text">
						<h1>¡Cambia tu vida con un solo boleto! ¡Participa ya!</h1>
					</div>
					<img src="resources/image2.png" alt="Fondo de sorteos" class="background-image">
					<div class="lottery-promo">
						<div><strong>HOY PUEDE SER TU DÍA DE SUERTE</strong></div>
						<div>Participa en nuestros fascinantes sorteos</div>
						<div>¡La suerte está de tu lado!</div>
						<button>Comprar Boleto</button>
					</div>
				</div>

				<!-- Slider de Premios -->
				<section class="premios-section">
					<h2 class="section-title">NUESTROS PREMIOS</h2>
					<div class="slider-container">
						<div class="slider">
							<!-- Las imágenes se cargarán dinámicamente con JavaScript -->
						</div>
						<button class="slider-btn prev-btn">&lt;</button>
						<button class="slider-btn next-btn">&gt;</button>
						<div class="slider-dots"></div>
					</div>
				</section>
			</div>

			<div id="rifas-section" style="display: none; "> <!-- OCULTA POR DEFECTO -->
				<section class="sorteos-container" >
					<div class="sorteos">
						<img src="resources/premios/<?php echo htmlspecialchars($sorteosActivos['FOTO'] ?? 'default.jpg'); ?>" alt="Premios de Sorteo" class="sorteo-image">
					</div>
					<div class="sorteo-info" >
						<h2><?php echo htmlspecialchars($sorteosActivos['titulo'] ?? 'Sorteo'); ?></h2>
						<p class="sorteo-precio">$<?php echo number_format($sorteosActivos['precio'] ?? 0, 2); ?></p>
						<button class="btn-comprar" data-sorteo="<?php echo htmlspecialchars($sorteosActivos['id'] ?? ''); ?>">Comprar Boleto</button>
					</div>
				</section>

				<!-- Modal para compra -->
				<div id="compraModal" class="modal">
					<div class="modal-content">
						<span class="close-modal">&times;</span>
						<h3 id="modalSorteoTitulo"></h3>
						<div id="modalSorteoContent"></div>
					</div>
				</div>
			</div>

		</main>

		<!-- Footer -->
		<footer class="main-footer" role="contentinfo">
			<div class="footer-container">
				<div class="footer-brand">
					<img src="resources/logo.png" alt="Los Audaces" class="footer-logo" width="150" height="auto">
				</div>

				<div class="footer-content">
					<div class="footer-section">
						<h3 class="footer-heading">Rifas</h3>
						<ul class="footer-list">
							<li><a href="#" class="footer-link">Accessorios</a></li>
							<li><a href="#" class="footer-link">Números Ganadores</a></li>
							<li><a href="#" class="footer-link">Próximos Sorteos</a></li>
						</ul>
					</div>
					<div class="footer-section">
						<h3 class="footer-heading">Tienda</h3>
						<ul class="footer-list">
							<li>Dirección: Av. 6 # 9-76 Centro Cucuta - Colombia</li>
							<li>Email: <a href="mailto:admin@losaudaces.com" class="footer-link">admin@losaudaces.com</a></li>
							<li>Teléfono: <a href="tel:+573204563721" class="footer-link">+57-3204563721</a></li>
						</ul>
					</div>

					<div class="footer-section">
						<h3 class="footer-heading">Política</h3>
						<ul class="footer-list">
							<li><a href="#" class="footer-link">Términos y condiciones</a></li>
							<li><a href="#" class="footer-link">Política de reembolso</a></li>
							<li><a href="#" class="footer-link">Política de privacidad</a></li>
							<li><a href="#" class="footer-link">Política de envío</a></li>
							<li><a href="#" class="footer-link">Política de cookies</a></li>
							<li><a href="#" class="footer-link">FAQ</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="footer-bottom">
				<p class="copyright">&copy; 2023 Los Audaces. Todos los derechos reservados.</p>
			</div>
		</footer>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const slider = document.querySelector('.slider');
			const dotsContainer = document.querySelector('.slider-dots');
			const prevBtn = document.querySelector('.prev-btn');
			const nextBtn = document.querySelector('.next-btn');
			const menuToggle = document.querySelector('.menu-toggle');
			const mainNav = document.querySelector('.main-nav');

			menuToggle.addEventListener('click', function() {
				this.classList.toggle('active');
				mainNav.classList.toggle('active');

				// Cambiar aria-expanded para accesibilidad
				const isExpanded = this.getAttribute('aria-expanded') === 'true';
				this.setAttribute('aria-expanded', !isExpanded);
			});

			// Cerrar menú al hacer clic en enlaces (mobile)
			document.querySelectorAll('.main-nav a').forEach(link => {
				link.addEventListener('click', function() {
					if (window.innerWidth <= 768) {
						menuToggle.classList.remove('active');
						mainNav.classList.remove('active');
						menuToggle.setAttribute('aria-expanded', 'false');
					}
				});
			});

			// Obtener todas las imágenes de premios de la carpeta
			const premiosImages = [
				'resources/premios/celular.jpeg',
				'resources/premios/Moto.jpeg',
				'resources/premios/Viaje.jpeg'
				// Agrega más rutas según necesites
			];

			let currentSlide = 0;

			// Cargar imágenes en el slider
			function loadImages() {
				slider.innerHTML = '';
				dotsContainer.innerHTML = '';

				premiosImages.forEach((imgSrc, index) => {
					// Crear slide
					const slide = document.createElement('div');
					slide.className = 'slide';
					slide.style.minWidth = '100%';

					const img = document.createElement('img');
					img.src = imgSrc;
					img.alt = `Premio ${index + 1}`;
					slide.appendChild(img);
					slider.appendChild(slide);

					// Crear dots
					const dot = document.createElement('div');
					dot.className = 'dot';
					if (index === 0) dot.classList.add('active');
					dot.addEventListener('click', () => goToSlide(index));
					dotsContainer.appendChild(dot);
				});
			}

			// Ir a slide específico
			function goToSlide(slideIndex) {
				currentSlide = slideIndex;
				updateSlider();
			}

			// Actualizar slider
			function updateSlider() {
				slider.style.transform = `translateX(-${currentSlide * 100}%)`;

				// Actualizar dots
				const dots = document.querySelectorAll('.dot');
				dots.forEach((dot, index) => {
					dot.classList.toggle('active', index === currentSlide);
				});
			}

			// Siguiente slide
			function nextSlide() {
				currentSlide = (currentSlide + 1) % premiosImages.length;
				updateSlider();
			}

			// Slide anterior
			function prevSlide() {
				currentSlide = (currentSlide - 1 + premiosImages.length) % premiosImages.length;
				updateSlider();
			}

			// Event listeners
			nextBtn.addEventListener('click', nextSlide);
			prevBtn.addEventListener('click', prevSlide);

			// Auto-slide cada 5 segundos
			let slideInterval = setInterval(nextSlide, 5000);

			// Pausar auto-slide al interactuar
			slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
			slider.addEventListener('mouseleave', () => {
				clearInterval(slideInterval);
				slideInterval = setInterval(nextSlide, 5000);
			});

			// Cargar imágenes iniciales
			loadImages();


			// Cambiar entre secciones
			document.querySelector('#rifas').addEventListener('click', function(e) {
				e.preventDefault();

				document.getElementById('home-section').style.display = 'none';
				document.getElementById('rifas-section').style.display = 'block';
				window.scrollTo(0, 0); // Opcional: volver arriba
			});

			// Si tienes un botón o link con ID "inicio", puedes hacer esto:
			document.querySelector('#inicio')?.addEventListener('click', function(e) {
				e.preventDefault();

				document.getElementById('home-section').style.display = 'block';
				document.getElementById('rifas-section').style.display = 'none';
				window.scrollTo(0, 0);
			});



		});
	</script>
</body>

</html>