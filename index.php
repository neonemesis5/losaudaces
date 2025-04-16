<?php
// Configuraciones de seguridad
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self' https: 'unsafe-inline' 'unsafe-eval'; img-src 'self' data:;");
// header("Content-Security-Policy: default-src 'self' https: 'unsafe-inline' 'unsafe-eval'; img-src 'self' data:; script-src 'self' https://maps.googleapis.com https://maps.gstatic.com; frame-src https://www.google.com;");

// Configuración de sesión segura
session_start([
	'cookie_lifetime' => 86400,
	'cookie_secure' => true,
	'cookie_httponly' => true,
	'cookie_samesite' => 'Strict',
	'use_strict_mode' => true
]);

require __DIR__ . '/controllers/sorteocontroller.php';
require __DIR__ . '/controllers/locationcontroller.php';
require __DIR__ . '/controllers/cartoncontroller.php';
require __DIR__ . '/controllers/premioscontroller.php';


// Crear instancia del controlador
$sorteoController = new SorteoController();
$sorteosActivos = $sorteoController->getActiveSorteos();

$countryController = new LocationController();
$countries = $countryController->getAllCountries();


$ticketsController = new CartonController();
$tickets = $ticketsController->getCartonSellBySorteo($sorteosActivos['id']);

$premiosController = new PremiosController();
$premios = $premiosController->getPremiosSorteo($sorteosActivos['id']);

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
			<section id="home-section" style="display: block;"> <!-- MOSTRAR POR DEFECTO -->
				<div class="hero-section">
					<div class="hero-background">
						<div class="purple-bar"></div>
						<div class="hero-text">
							<h1>¡Cambia tu vida con un solo boleto! ¡Participa ya!</h1>
						</div>
					</div>
					<div class="lottery-promo">
						<div><strong>HOY PUEDE SER TU DÍA DE SUERTE</strong></div>
						<div>Participa en nuestros fascinantes sorteos</div>
						<div>¡La suerte está de tu lado!</div>
						<button id="comprar_numprin" class="btn_comprarprin">Comprar Boleto</button>
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
			</section>

			<section id="rifas-section" style="display: none; "> <!-- OCULTA POR DEFECTO -->
				<section class="sorteos-container">
					<div class="sorteos">
						<img src="resources/premios/<?php echo htmlspecialchars($sorteosActivos['FOTO'] ?? 'default.jpg'); ?>" alt="Premios de Sorteo" class="sorteo-image">
					</div>
					<div class="sorteo-info">
						<h2><?php echo htmlspecialchars($sorteosActivos['titulo'] ?? 'Sorteo'); ?></h2>
						<p class="sorteo-precio">$<?php echo number_format($sorteosActivos['precio'] ?? 0, 2); ?></p>
						<button id="comprar_num" class="btn-comprar" data-sorteo="<?php echo htmlspecialchars($sorteosActivos['id'] ?? ''); ?>">Comprar Boleto</button>
					</div>
				</section>
			</section>

			<section id="carton-num" class="carton-numeros" style="display: none;">
				<div class="rango-num">
					<?php
					$totalPages = ceil($sorteosActivos['qtynumeros'] / 100);
					if ($totalPages > 1) {
						for ($i = 0; $i < $totalPages; $i++) {
							$startNum = $i * 100;
							$endNum = ($i + 1) * 100 - 1;
							echo '<a href="#" class="rango-btn" data-page="' . $i . '" data-start="' . $startNum . '" data-end="' . $endNum . '">';
							echo '<div class="numrango">' . str_pad($startNum, 3, '0', STR_PAD_LEFT) . ' - ' . str_pad($endNum, 3, '0', STR_PAD_LEFT) . '</div>';
							echo '</a>';
						}
					}
					?>
				</div>
				<!-- Esta tabla será llenada dinámicamente por JavaScript -->
				<table class="numeros-tabla"></table>
			</section>

			<section id="form-comprar" style="display: none;"> <!-- OCULTA POR DEFECTO formulario de registro de usuario para compra -->
				<form id="registroForm" method="post" action="" style="display: none; ">
					<h1>Registrate</h1>

					<div class="form-group">
						<label for="nombre_apellido">Nombre y Apellido:</label>
						<input type="text" id="nombre_apellido" name="nombre_apellido" placeholder="Ingrese su nombre completo" required value="Ana Reimy">
					</div>

					<div class="form-group">
						<label for="telefono">Teléfono:</label>
						<input type="tel" id="telefono" name="telefono" placeholder="Ingrese su número de teléfono" required value="04241234567">
					</div>

					<div class="form-group">
						<label for="correo">Correo Electrónico:</label>
						<input type="email" id="correo" name="correo" placeholder="Ingrese su correo electrónico" required value="usuario@ejemplo.com">
					</div>

					<div class="form-group">
						<label for="ubicacion">Ubicación:</label>
						<select id="ubicacion" name="ubicacion" required>
							<option value="">Seleccione su país</option>
							<?php foreach ($countries as $code => $name): ?>
								<option value="<?php echo $code; ?>" <?php echo ($name == 'Venezuela') ? 'selected' : ''; ?>>
									<?php echo $name; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label for="identificacion">Número de Identificación:</label>
						<input type="text" id="identificacion" name="identificacion" placeholder="Ingrese su número de identificación" required value="V12345678">
					</div>

					<div class="form-group checkbox-group">
						<input type="checkbox" id="condiciones" name="condiciones" required>
						<label for="condiciones">He leído las condiciones y acepto las mismas.</label>
					</div>

					<button type="submit" class="submit-btn">ENVIAR</button>
				</form>
			</section>

			<section id="contacto-section" style="display: none;">
				<!-- MAPA DE GOOGLE -->
				<div id="mapa" style="width: 100%; height: 300px; margin-top: 20px;"></div>

				<form id="formContacto" method="POST" action="sendMail.php">
					<h2>Contáctanos</h2>

					<div class="form-group">
						<label for="nombre">Nombre:</label>
						<input type="text" id="nombre" name="nombre" required>
					</div>

					<div class="form-group">
						<label for="email">Correo Electrónico:</label>
						<input type="email" id="email" name="email" required>
					</div>

					<div class="form-group">
						<label for="mensaje">Mensaje:</label>
						<textarea id="mensaje" name="mensaje" rows="5" required></textarea>
					</div>

					<!-- RECAPTCHA -->
					<div class="g-recaptcha" data-sitekey="TU_SITE_KEY_RECAPTCHA"></div>

					<button type="submit">Enviar</button>
				</form>


			</section>

			<section id="showpremios" style="display: none;">
				<div id="thumbsnail">
					<?php
					foreach ($premios as $k => $v) {
						echo '<a target="_blank" >';
						echo '<img src="resources/premios/' . htmlspecialchars($v['foto']) . '" alt="Forest">';
						echo '</a>';
					}
					?>
				</div>
				<div id="detailpremio">
					<div id="foto">

					</div>
					<div id="detalle">

					</div>
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
		document.addEventListener('DOMContentLoaded', () => {
			// Utilidad: mostrar una sección y ocultar el resto
			const showSection = (id) => {
				['home-section', 'rifas-section', 'carton-num', 'contacto-section', 'showpremios'].forEach(sec => {
					const el = document.getElementById(sec);
					if (el) el.style.display = (sec === id) ? (id === 'carton-num' || id === 'contacto-section' ? 'flex' : 'block') : 'none';
				});
				window.scrollTo(0, 0);
			};

			// Navegación
			document.querySelector('#inicio')?.addEventListener('click', e => {
				e.preventDefault();
				showSection('home-section');
			});
			document.querySelector('#rifas')?.addEventListener('click', e => {
				e.preventDefault();
				showSection('rifas-section');
			});
			document.querySelector('#contact')?.addEventListener('click', e => {
				e.preventDefault();
				showSection('contacto-section');
				loadGoogleMaps();
			});
			document.querySelector('#comprar_numprin')?.addEventListener('click', e => {
				e.preventDefault();
				showSection('carton-num');
			});
			document.querySelector('#comprar_num')?.addEventListener('click', e => {
				e.preventDefault();
				showSection('carton-num');
			});


			// Valor total de números disponibles, esto deberías pasarlo desde PHP si es dinámico
			const totalNumeros = <?php echo intval($sorteosActivos['qtynumeros']); ?>;

			function generarTablaNumeros(start, end) {
				const tabla = document.querySelector('.numeros-tabla');
				tabla.innerHTML = ''; // Limpiamos la tabla actual
				// Convertir los números vendidos a formato 3 dígitos (001, 015, etc.)
				const numerosVendidos = <?php echo json_encode(array_map(function ($t) {
											return str_pad($t['numero'], 3, '0', STR_PAD_LEFT);
										}, $tickets)); ?>;
				let num = start;
				for (let i = 0; i < 10; i++) {
					const row = document.createElement('tr');
					for (let j = 0; j < 10; j++) {
						if (num <= end && num < totalNumeros) {
							const numeroFormateado = String(num).padStart(3, '0');
							const estaVendido = numerosVendidos.includes(numeroFormateado);

							const cell = document.createElement('td');
							const link = document.createElement('a');
							link.href = estaVendido ? 'javascript:void(0)' : '#'; // link.href = '#';
							const div = document.createElement('div');
							div.className = 'carton-numero ' + (estaVendido ? 'vendido' : 'disponible');
							div.textContent = numeroFormateado;

							if (estaVendido) {
								div.title = 'Número ya vendido';
							} else {
								div.addEventListener('click', function() {
									// Aquí puedes agregar la lógica para seleccionar el número
									console.log('Número seleccionado:', numeroFormateado);
								});
							}
							link.appendChild(div);
							cell.appendChild(link);
							row.appendChild(cell);
							num++;
						}
					}
					tabla.appendChild(row);
				}
			}

			// Activamos el comportamiento en todos los botones de rango
			document.querySelectorAll('.rango-btn').forEach(btn => {
				btn.addEventListener('click', function(e) {
					e.preventDefault();

					const start = parseInt(this.getAttribute('data-start'));
					const end = parseInt(this.getAttribute('data-end'));
					generarTablaNumeros(start, end);
				});
			});

			// Llenamos la tabla inicialmente con los primeros 100 números
			generarTablaNumeros(0, 99);


			// Slider de premios
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

			function renderSlider() {
				if (!slider || !dotsContainer) return;
				slider.innerHTML = '';
				dotsContainer.innerHTML = '';

				premiosImages.forEach((src, i) => {
					const slide = document.createElement('div');
					slide.className = 'slide';
					slide.style.minWidth = '100%';

					const img = document.createElement('img');
					img.src = src;
					img.alt = `Premio ${i + 1}`;
					slide.appendChild(img);
					slider.appendChild(slide);

					const dot = document.createElement('div');
					dot.className = 'dot' + (i === 0 ? ' active' : '');
					dot.addEventListener('click', () => goToSlide(i));
					dotsContainer.appendChild(dot);
				});

				startAutoSlide();
			}

			function goToSlide(i) {
				currentSlide = i;
				slider.style.transform = `translateX(-${i * 100}%)`;
				document.querySelectorAll('.dot').forEach((d, index) => d.classList.toggle('active', index === i));
			}

			function nextSlide() {
				goToSlide((currentSlide + 1) % premiosImages.length);
			}

			function prevSlide() {
				goToSlide((currentSlide - 1 + premiosImages.length) % premiosImages.length);
			}

			function startAutoSlide() {
				clearInterval(slideInterval);
				slideInterval = setInterval(nextSlide, 5000);
			}

			nextBtn?.addEventListener('click', () => {
				nextSlide();
				startAutoSlide();
			});
			prevBtn?.addEventListener('click', () => {
				prevSlide();
				startAutoSlide();
			});

			slider?.addEventListener('mouseenter', () => clearInterval(slideInterval));
			slider?.addEventListener('mouseleave', startAutoSlide);

			renderSlider();

			// Cargar Google Maps solo si no ha sido cargado
			let mapLoaded = false;

			function initMap() {
				const ubicacion = {
					lat: 7.8891,
					lng: -72.5078
				};
				const map = new google.maps.Map(document.getElementById("mapa"), {
					zoom: 16,
					center: ubicacion,
				});
				new google.maps.Marker({
					position: ubicacion,
					map: map,
					title: "Los Audaces",
				});
			}

			function loadGoogleMaps() {
				if (mapLoaded) return;
				const script = document.createElement('script');
				script.src = 'https://maps.googleapis.com/maps/api/js?key=TU_API_KEY_MAPS&callback=initMap';
				script.async = true;
				script.defer = true;
				document.body.appendChild(script);
				mapLoaded = true;
			}
		});
	</script>


	<script async defer src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY_MAPS&callback=initMap"></script>
</body>

</html>