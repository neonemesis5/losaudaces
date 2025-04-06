<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Participa en emocionantes sorteos con Los Audaces y gana premios increíbles">
    <title>Los Audaces - Sorteos y Rifas</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="resources/logo.png" type="image/png"></head>

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
		</main>

		<!-- Footer -->
		<footer class="main-footer">
			<div class="left-footer">
				<img src="resources/logo.png" alt="Los Audaces" class="footer-logo">
			</div>
			<div class="right-footer">
				<div class="footer-grid">
					<div class="footer-column">
						<h3>Rifas</h3>
						<ul>
							<li>Accesorios</li>
							<li>Números Ganadores</li>
							<li>Próximos Sorteos</li>
						</ul>
					</div>
					<div class="footer-column">
						<h3>Tienda</h3>
						<ul>
							<li><strong>Direccion:</strong> Av. 6 # 9-76 Centro Cucuta - Colombia</li>
							<li><strong>Email:</strong> admin@losaudaces.com</li>
							<li><strong>Telefono:</strong> +57-3204563721</li>
						</ul>
					</div>
					<div class="footer-column">
						<h3>Política</h3>
						<ul>
							<li>Términos y condiciones</li>
							<li>Política de reembolso</li>
							<li>Política de privacidad</li>
							<li>Política de envío</li>
							<li>Política de cookies</li>
							<li>FAQ</li>
						</ul>
					</div>
				</div>
			</div>
		</footer>
	</div>

	<script>
		// document.addEventListener('DOMContentLoaded', function() {
		// 	const menuToggle = document.querySelector('.menu-toggle');
		// 	const mainNav = document.querySelector('.main-nav');

		// 	menuToggle.addEventListener('click', function() {
		// 		mainNav.classList.toggle('active');
		// 		menuToggle.classList.toggle('active');
		// 	});

		// 	// Cerrar menú al hacer clic en enlaces (mobile)
		// 	const navLinks = document.querySelectorAll('.main-nav a');
		// 	navLinks.forEach(link => {
		// 		link.addEventListener('click', function() {
		// 			if (window.innerWidth <= 768) {
		// 				mainNav.classList.remove('active');
		// 				menuToggle.classList.remove('active');
		// 			}
		// 		});
		// 	});
		// });


		// Slider functionality
		document.addEventListener('DOMContentLoaded', function() {
			const slider = document.querySelector('.slider');
			const dotsContainer = document.querySelector('.slider-dots');
			const prevBtn = document.querySelector('.prev-btn');
			const nextBtn = document.querySelector('.next-btn');

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
		});
	</script>
</body>

</html>