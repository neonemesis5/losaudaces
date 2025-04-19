// Función para mostrar/ocultar secciones
export const showSection = (id) => {
    ['home-section', 'rifas-section', 'carton-num', 'contacto-section', 'showpremios', 'shopcart'].forEach(sec => {
        const el = document.getElementById(sec);
        if (el) el.style.display = (sec === id) ? (id === 'carton-num' || id === 'contacto-section' ? 'flex' : 'block') : 'none';
    });
    window.scrollTo(0, 0);
};

// Configuración de eventos de navegación
export const setupNavigation = () => {
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
        // showSection('shopcart');
    });
    document.querySelector('#comprar_num')?.addEventListener('click', e => {
        e.preventDefault();
        showSection('carton-num');
        // showSection('shopcart');
    });
	document.querySelector('#premios')?.addEventListener('click', e => {
		e.preventDefault();
		showSection('showpremios');

		// Inicializar la galería de premios
		initPremiosGallery();
	});

};