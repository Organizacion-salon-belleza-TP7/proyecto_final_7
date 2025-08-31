<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../variable_global.php');
require_once(ROOT_PATH . '/pagina_web_landing-(prototipo-2)/modelo/modelo.php');
require_once(ROOT_PATH . '/modelo/BD.php');

$modelo_pagina_landing = new pagina_landing($conn);
$funcion_traer_servicios = $modelo_pagina_landing->traer_servicios();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rose Spa | Salón de Belleza</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variables de colores */
        :root {
            --primary: #f8c6d0;
            --secondary: #d9a6b3;
            --accent: #a86a7e;
            --dark: #3c2f3d;
            --light: #fff8f8;
        }
        
        /* Reset y estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            color: var(--dark);
            background-color: var(--light);
            line-height: 1.6;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header y navegación */
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--accent);
            text-decoration: none;
        }
        
        .logo span {
            color: var(--primary);
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 25px;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav ul li a:hover {
            color: var(--accent);
        }
        
        .menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHNhbG9uJTIwZGUlMjBiZWxsZXphfGVufDB8fDB8fHww&auto=format&fit=crop&w=1200&q=80') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
            padding-top: 80px;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        .btn {
            display: inline-block;
            background-color: var(--accent);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
        }
        
        .btn:hover {
            background-color: var(--dark);
            transform: translateY(-3px);
        }
        
        /* Servicios */
        .services {
            padding: 80px 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: var(--accent);
            margin-bottom: 15px;
        }
        
        .section-title p {
            color: var(--dark);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .service-card {
            background-color: var(--light);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
        }
        
        .service-img {
            height: 200px;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        .service-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .service-content {
            padding: 20px;
        }
        
        .service-content h3 {
            margin-bottom: 10px;
            color: var(--accent);
        }
        
        /* Sobre nosotros */
        .about {
            padding: 80px 0;
            background-color: var(--light);
        }
        
        .about-content {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 40px;
        }
        
        .about-text {
            flex: 1;
            min-width: 300px;
        }
        
        .about-text h2 {
            font-size: 2.5rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        
        .about-image {
            flex: 1;
            min-width: 300px;
            height: 400px;
            background-color: var(--primary);
            border-radius: 10px;
            background: url('imagenes/liderar-exito-diversidad-equipo.jpg') no-repeat center center/cover;
        }
        
        /* Testimonios */
        .testimonials {
            padding: 80px 0;
            background-color: white;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .testimonial-card {
            background-color: var(--light);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary);
            margin-right: 15px;
            background: url('https://randomuser.me/api/portraits/women/32.jpg') no-repeat center center/cover;
        }
        
        .author-info h4 {
            color: var(--accent);
        }
        
        /* Galería */
        .gallery {
            padding: 80px 0;
            background-color: var(--light);
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .gallery-item {
            height: 250px;
            background-color: var(--primary);
            border-radius: 10px;
            overflow: hidden;
            background: url('imagenes/images_tintado_pelo.jpg') no-repeat center center/cover;
        }
        
        .gallery-item:nth-child(2) {
            background: url('imagenes/images_uñas.jpg') no-repeat center center/cover;
        }
        
        .gallery-item:nth-child(3) {
            background: url('imagenes/images_maquillaje.jpg') no-repeat center center/cover;
        }
        
        .gallery-item:nth-child(4) {
            background: url('imagenes/116053406-front-view-of-stylish-barber-in-white-shirt-and-waistcoat-looking-at-camera-posing-and-smiling-in.jpg') no-repeat center center/cover;
        }
        
        .gallery-item:nth-child(5) {
            background: url('imagenes/images_cortando_barba.jpg') no-repeat center center/cover;
        }
        
        .gallery-item:nth-child(6) {
            background: url('imagenes/images.jpg') no-repeat center center/cover;
        }

        .gallery-item:nth-child(7) {
            background: url('imagenes/images_salon.jpg') no-repeat center center/cover;
        }

        .gallery-item:nth-child(8) {
            background: url('imagenes/istockphoto-511777075-612x612.jpg') no-repeat center center/cover;
        }
        
        /* Contacto */
        .contact {
            padding: 80px 0;
            background-color: white;
        }
        
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }
        
        .contact-info {
            flex: 1;
            min-width: 300px;
        }
        
        .contact-info h2 {
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        
        .contact-details {
            margin-bottom: 30px;
        }
        
        .contact-details p {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .contact-details i {
            margin-right: 10px;
            color: var(--accent);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .social-links a:hover {
            background-color: var(--accent);
        }
        
        .contact-form {
            flex: 1;
            min-width: 300px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .form-group textarea {
            min-height: 120px;
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 40px 0 20px;
        }
        
        .footer-container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            margin-bottom: 30px;
        }
        
        .footer-col {
            flex: 1;
            min-width: 200px;
        }
        
        .footer-col h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: var(--primary);
        }
        
        .footer-col ul {
            list-style: none;
        }
        
        .footer-col ul li {
            margin-bottom: 10px;
        }
        
        .footer-col ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-col ul li a:hover {
            color: var(--primary);
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            
            nav ul {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background-color: white;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                transition: left 0.3s;
            }
            
            nav ul.active {
                left: 0;
            }
            
            nav ul li {
                margin: 15px 0;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="#inicio" class="logo">Rose<span>Spa</span></a>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            <nav>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#servicios">Servicios</a></li>
                    <li><a href="#nosotros">Nosotros</a></li>
                    <li><a href="#testimonios">Testimonios</a></li>
                    <li><a href="#galeria">Galería</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                    <li><a href="">Iniciar Sesion</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="container hero-content">
            <h1>Realza tu belleza natural</h1>
            <p>Descubre los mejores tratamientos de belleza con profesionales expertos que cuidan de ti y te ayudan a resaltar tu belleza única.</p>
            <a href="#contacto" class="btn">Reserva tu cita</a>
        </div>
    </section>

    <!-- Servicios -->
    <section class="services" id="servicios">
        <div class="container">
            <div class="section-title">
                <h2>Nuestros Servicios</h2>
                <p>Ofrecemos una amplia gama de servicios de belleza para consentirte y hacerte lucir radiante.</p>
            </div>
            <div class="services-grid">
                <?php
                if($funcion_traer_servicios && $funcion_traer_servicios->num_rows > 0){
                    while($row = $funcion_traer_servicios->fetch_assoc()){
                        echo '<div class="service-card">';
                        echo '<div class="service-img">';
                        if(!empty($row['imagen'])) {
                            echo '<img src="' . BASE_URL . '/imagenes/servicios/' . $row['imagen'] . '" alt="' . $row['nombre'] . '">';
                        } else {
                            echo '<div style="width:100%; height:100%; background:var(--secondary); display:flex; align-items:center; justify-content:center; color:white;"><i class="fas fa-spa"></i></div>';
                        }
                        echo '</div>';
                        echo '<div class="service-content">';
                        echo '<h3>' . $row['nombre'] . '</h3>';
                        echo '<p>' . $row['descripcion'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No hay servicios disponibles en este momento.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Sobre Nosotros -->
    <section class="about" id="nosotros">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Sobre Nosotros</h2>
                    <p>En Rose Spa llevamos más de 10 años ofreciendo servicios de belleza de la más alta calidad. Nuestro equipo de profesionales está en constante formación para ofrecerte las últimas tendencias y técnicas.</p>
                    <p>Nos enorgullece crear un ambiente relajante y acogedor donde nuestros clientes puedan desconectar de su rutina y sentirse consentidos. Utilizamos productos de primeras marcas que cuidan de tu salud y bienestar.</p>
                    <p>Nuestra filosofía se basa en realzar la belleza natural de cada persona, adaptándonos a sus necesidades y preferencias para lograr resultados que superen sus expectativas.</p>
                    <a href="#contacto" class="btn">Conócenos</a>
                </div>
                <div class="about-image">
                    <!-- Imagen del salón -->
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonios -->
    <section class="testimonials" id="testimonios">
        <div class="container">
            <div class="section-title">
                <h2>Lo que dicen nuestras clientas</h2>
                <p>La satisfacción de nuestras clientas es nuestra mayor recompensa.</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        <p>"Llevo años confiando en ellos para mi look. Siempre aciertan con las tendencias y me aconsejan perfectamente. ¡No cambiaría por nada!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img"></div>
                        <div class="author-info">
                            <h4>María González</h4>
                            <p>Cliente habitual</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        <p>"Me hicieron el peinado y maquillaje para mi boda y estaba espectacular. Todas mis invitadas me preguntaron por el salón. ¡Gracias por hacerme lucir tan radiante!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img"></div>
                        <div class="author-info">
                            <h4>Laura Martínez</h4>
                            <p>Novia</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        <p>"Probé su tratamiento facial rejuvenecedor y los resultados fueron increíbles. Mi piel luce más joven e hidratada. ¡Volveré pronto!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img"></div>
                        <div class="author-info">
                            <h4>Carmen Rodríguez</h4>
                            <p>Cliente satisfecha</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería -->
    <section class="gallery" id="galeria">
        <div class="container">
            <div class="section-title">
                <h2>Nuestro Trabajo</h2>
                <p>Algunos ejemplos de nuestros resultados y el ambiente de nuestro salón.</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item"></div>
                <div class="gallery-item"></div>
                <div class="gallery-item"></div>
                <div class="gallery-item"></div>
                <div class="gallery-item"></div>
                <div class="gallery-item"></div>
                <div class="gallery-item"></div>
                <div class="gallery-item"></div>
            </div>
        </div>
    </section>

    <!-- Contacto -->
    <section class="contact" id="contacto">
        <div class="container">
            <div class="section-title">
                <h2>Contacto</h2>
                <p>Reserva tu cita o solicita información sobre nuestros servicios.</p>
            </div>
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Información de Contacto</h2>
                    <div class="contact-details">
                        <p><i class="fas fa-map-marker-alt"></i> Av. Principal #123, Ciudad</p>
                        <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                        <p><i class="fas fa-envelope"></i> info@rose_spa.com</p>
                        <p><i class="fas fa-clock"></i> Lunes a Sábado: 9:00 - 20:00</p>
                    </div>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="contact-form">
                    <form>
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="tel" id="phone">
                        </div>
                        <div class="form-group">
                            <label for="service">Servicio de interés</label>
                            <select id="service">
                                <option value="">Selecciona un servicio</option>
                                <option value="corte">Corte de cabello</option>
                                <option value="color">Coloración</option>
                                <option value="facial">Tratamiento facial</option>
                                <option value="manicura">Manicura y pedicura</option>
                                <option value="maquillaje">Maquillaje profesional</option>
                                <option value="otros">Otros servicios</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Mensaje</label>
                            <textarea id="message"></textarea>
                        </div>
                        <button type="submit" class="btn">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <h3>Rose Spa</h3>
                    <p>Tu salón de belleza de confianza donde realzamos tu belleza natural con los mejores tratamientos y profesionales.</p>
                </div>
                <div class="footer-col">
                    <h3>Enlaces rápidos</h3>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#nosotros">Nosotros</a></li>
                        <li><a href="#testimonios">Testimonios</a></li>
                        <li><a href="#galeria">Galería</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Suscríbete</h3>
                    <p>Recibe nuestras promociones y novedades.</p>
                    <form>
                        <div class="form-group">
                            <input type="email" placeholder="Tu email" required>
                        </div>
                        <button type="submit" class="btn">Suscribirse</button>
                    </form>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 Rose Spa. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Menú responsive
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('nav ul').classList.toggle('active');
        });
        
        // Cerrar menú al hacer clic en un enlace
        document.querySelectorAll('nav ul li a').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelector('nav ul').classList.remove('active');
            });
        });
        
        // Smooth scrolling para enlaces de ancla
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>