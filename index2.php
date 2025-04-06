<!DOCTYPE html>
<html lang="es" dir="ltr">

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
	<!-- Cabecera -->
	<header class="main-header" role="banner">
		<div class="container">
			<a href="/" class="logo-container" aria-label="Ir a inicio">
				<img src="resources/logo.png" alt="Los Audaces" class="logo" width="180" height="60">
			</a>
			
			<button class="menu-toggle" aria-label="Menú" aria-expanded="false" aria-controls="main-navigation">
				<span class="menu-bar"></span>
				<span class="menu-bar"></span>
				<span class="menu-bar"></span>
			</button>
			
			<nav id="main-navigation" class="main-nav" role="navigation">
				<ul class="nav-list">
					<li class="nav-item"><a href="#rifas" class="nav-link">Rifas</a></li>
					<li class="nav-item"><a href="#premios" class="nav-link">Premios</a></li>
					<li class="nav-item"><a href="#contacto" class="nav-link">Contacto</a></li>
					<li class="nav-item"><a href="#login" class="nav-link">Login</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<!-- Contenido principal -->
	<main id="main-content" role="main">
		<!-- Sección Hero -->
		<section class="hero-section" aria-labelledby="main-heading">
			<div class="hero-overlay"></div>
			<img src="resources/image2.png" alt="" class="background-image" aria-hidden="true">
			
			<div class="hero-content">
				<h1 id="main-heading" class="hero-title">¡Cambia tu vida con un solo boleto! ¡Participa ya!</h1>
				
				<div class="cta-container">
					<p class="cta-text">HOY PUEDE SER TU DÍA DE SUERTE</p>
					<p class="cta-text">Participa en nuestros fascinantes sorteos</p>
					<p class="cta-text">¡La suerte está de tu lado!</p>
					<button class="cta-button" aria-label="Comprar boleto">Comprar Boleto</button>
				</div>
			</div>
		</section>

		<!-- Sección de Premios -->
		<section id="premios" class="premios-section" aria-labelledby="premios-heading">
			<div class="section-container">
				<h2 id="premios-heading" class="section-title">NUESTROS PREMIOS</h2>
				
				<div class="slider-container">
					<div class="slider" aria-live="polite">
						<!-- Slider se llena dinámicamente con JS -->
					</div>
					
					<button class="slider-btn prev-btn" aria-label="Anterior premio">&lt;</button>
					<button class="slider-btn next-btn" aria-label="Siguiente premio">&gt;</button>
					
					<div class="slider-dots" role="tablist">
						<!-- Puntos se generan dinámicamente -->
					</div>
				</div>
			</div>
		</section>
	</main>

	<!-- Pie de página -->
	<footer class="main-footer" role="contentinfo">
		<div class="footer-container">
			<div class="footer-brand">
				<img src="resources/logo.png" alt="Los Audaces" class="footer-logo" width="150" height="50">
			</div>
			
			<div class="footer-content">
				<div class="footer-section">
					<h3 class="footer-heading">Rifas</h3>
					<ul class="footer-list">
						<li><a href="#" class="footer-link">Accesorios</a></li>
						<li><a href="#" class="footer-link">Números Ganadores</a></li>
						<li><a href="#" class="footer-link">Próximos Sorteos</a></li>
					</ul>
				</div>
				
				<div class="footer-section">
					<h3 class="footer-heading">Tienda</h3>
					<address class="footer-address">
						<p><strong>Dirección:</strong> Av. 6 # 9-76 Centro Cucuta - Colombia</p>
						<p><strong>Email:</strong> <a href="mailto:admin@losaudaces.com" class="footer-link">admin@losaudaces.com</a></p>
						<p><strong>Teléfono:</strong> <a href="tel:+573204563721" class="footer-link">+57-3204563721</a></p>
					</address>
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

	<script>
		// Menú móvil
		document.addEventListener('DOMContentLoaded', function() {
			const menuToggle = document.querySelector('.menu-toggle');
			const mainNav = document.querySelector('.main-nav');
			
			menuToggle.addEventListener('click', function() {
				const isExpanded = this.getAttribute('aria-expanded') === 'true';
				this.setAttribute('aria-expanded', !isExpanded);
				mainNav.classList.toggle('active');
			});
			
			// Cerrar menú al hacer clic en enlaces (mobile)
			document.querySelectorAll('.nav-link').forEach(link => {
				link.addEventListener('click', function() {
					if (window.innerWidth <= 768) {
						menuToggle.setAttribute('aria-expanded', 'false');
						mainNav.classList.remove('active');
					}
				});
			});
			
			// Slider
			const initSlider = () => {
				const slider = document.querySelector('.slider');
				const dotsContainer = document.querySelector('.slider-dots');
				const prevBtn = document.querySelector('.prev-btn');
				const nextBtn = document.querySelector('.next-btn');
				
				const premiosImages = [
					'resources/premios/celular.jpeg',
					'resources/premios/Moto.jpeg',
					'resources/premios/Viaje.jpeg'
				];
				
				let currentSlide = 0;
				let slideInterval;
				
				const createSlide = (src, alt, index) => {
					const slide = document.createElement('div');
					slide.className = 'slide';
					slide.setAttribute('role', 'tabpanel');
					slide.setAttribute('aria-roledescription', 'slide');
					slide.setAttribute('aria-label', `${index + 1} de ${premiosImages.length}`);
					slide.style.minWidth = '100%';
					
					const img = document.createElement('img');
					img.src = src;
					img.alt = alt;
					img.loading = 'lazy';
					slide.appendChild(img);
					
					return slide;
				};
				
				const createDot = (index) => {
					const dot = document.createElement('button');
					dot.className = 'dot';
					dot.setAttribute('role', 'tab');
					dot.setAttribute('aria-label', `Ir al premio ${index + 1}`);
					dot.setAttribute('aria-selected', index === 0);
					
					dot.addEventListener('click', () => {
						goToSlide(index);
					});
					
					return dot;
				};
				
				const loadImages = () => {
					slider.innerHTML = '';
					dotsContainer.innerHTML = '';
					
					premiosImages.forEach((imgSrc, index) => {
						const slide = createSlide(imgSrc, `Premio ${index + 1}`, index);
						slider.appendChild(slide);
						
						const dot = createDot(index);
						dotsContainer.appendChild(dot);
					});
				};
				
				const updateSlider = () => {
					slider.style.transform = `translateX(-${currentSlide * 100}%)`;
					
					document.querySelectorAll('.dot').forEach((dot, index) => {
						dot.setAttribute('aria-selected', index === currentSlide);
					});
				};
				
				const goToSlide = (slideIndex) => {
					currentSlide = slideIndex;
					updateSlider();
					resetInterval();
				};
				
				const nextSlide = () => {
					currentSlide = (currentSlide + 1) % premiosImages.length;
					updateSlider();
				};
				
				const prevSlide = () => {
					currentSlide = (currentSlide - 1 + premiosImages.length) % premiosImages.length;
					updateSlider();
				};
				
				const resetInterval = () => {
					clearInterval(slideInterval);
					slideInterval = setInterval(nextSlide, 5000);
				};
				
				prevBtn.addEventListener('click', () => {
					prevSlide();
					resetInterval();
				});
				
				nextBtn.addEventListener('click', () => {
					nextSlide();
					resetInterval();
				});
				
				slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
				slider.addEventListener('mouseleave', resetInterval);
				
				loadImages();
				slideInterval = setInterval(nextSlide, 5000);
			};
			
			initSlider();
		});
	</script>
</body>
</html>