<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/pagina_web_landing-(prototipo-2)/modelo/modelo.php');
require_once(ROOT_PATH . '/modelo/BD.php');

$modelo_pagina_landing = new pagina_landing($conn);
$funcion_traer_servicios = $modelo_pagina_landing->traer_servicios();

$funcion_traer_lugares = $modelo_pagina_landing->traer_lugares();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rose Spa | Salón de Belleza Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Variables de colores mejoradas */
        :root {
            --primary: #ff6b9d;
            --primary-light: #ff8bb3;
            --secondary: #c44569;
            --accent: #f8b500;
            --dark: #2d1b36;
            --light: #fef7f7;
            --white: #ffffff;
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-rose: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
            --shadow-light: 0 8px 32px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 16px 64px rgba(0, 0, 0, 0.15);
            --shadow-heavy: 0 24px 96px rgba(0, 0, 0, 0.2);
        }
        
        /* Reset y estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--light);
            line-height: 1.7;
            overflow-x: hidden;
        }
        
        .container {
            width: min(90%, 1400px);
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Efectos especiales */
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .gradient-text {
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Header y navegación mejorado */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-light);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        header.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-medium);
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--gradient-rose);
            transition: width 0.3s ease;
        }

        .logo:hover::after {
            width: 100%;
        }
        
        .logo span {
            color: var(--secondary);
        }
        
        nav ul {
            display: flex;
            list-style: none;
            align-items: center;
        }
        
        nav ul li {
            margin-left: 2rem;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }

        nav ul li a::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-rose);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        nav ul li a:hover::before {
            width: 100%;
        }
        
        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary);
        }
        
        /* Hero Section espectacular */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            background: linear-gradient(45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHNhbG9uJTIwZGUlMjBiellaXphfGVufDB8fDB8fHww&auto=format&fit=crop&w=1200&q=80') center/cover;
            opacity: 0.3;
            z-index: 1;
        }

        .hero-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: particleFloat 20s infinite linear;
        }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }
        
        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            z-index: 3;
            position: relative;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .hero h1 .highlight {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn {
            display: inline-block;
            background: var(--gradient-rose);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-light);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }
        
        .btn:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }

        /* Servicios mejorados */
        .services {
            padding: 120px 0;
            background: var(--white);
            position: relative;
        }

        .services::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff6b9d' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 80px;
            position: relative;
            z-index: 2;
        }
        
        .section-title h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            width: 80px;
            height: 4px;
            background: var(--gradient-rose);
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .section-title p {
            color: var(--dark);
            max-width: 650px;
            margin: 0 auto;
            font-size: 1.2rem;
            opacity: 0.8;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            position: relative;
            z-index: 2;
        }
        
        .service-card {
            background: var(--white);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(255, 107, 157, 0.1);
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-rose);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
        }
        
        .service-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: var(--shadow-heavy);
        }

        .service-card:hover::before {
            opacity: 0.05;
        }
        
        .service-img {
            height: 250px;
            position: relative;
            overflow: hidden;
            z-index: 2;
        }
        
        .service-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .service-card:hover .service-img img {
            transform: scale(1.1);
        }
        
        .service-content {
            padding: 30px;
            position: relative;
            z-index: 2;
        }
        
        .service-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--primary);
            font-weight: 600;
        }

        .service-content p {
            color: var(--dark);
            opacity: 0.8;
            line-height: 1.6;
        }

        /* Sobre nosotros mejorado */
        .about {
            padding: 120px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
            overflow: hidden;
        }

        .about::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 107, 157, 0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .about-text {
            padding: 20px;
        }
        
        .about-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--primary);
        }

        .about-text p {
            margin-bottom: 25px;
            font-size: 1.1rem;
            color: var(--dark);
            opacity: 0.9;
        }
        
        .about-image {
            height: 500px;
            border-radius: 25px;
            background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/liderar-exito-diversidad-equipo.jpg') no-repeat center center/cover;
            box-shadow: var(--shadow-heavy);
            position: relative;
            overflow: hidden;
        }

        .about-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-rose);
            opacity: 0.1;
        }

        /* Equipo mejorado */
        .team {
            padding: 120px 0;
            background: var(--white);
        }
        
        .equipo-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-top: 80px;
        }
        
        .miembro-equipo {
            background: var(--white);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(255, 107, 157, 0.1);
        }

        .miembro-equipo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 107, 157, 0.1), transparent);
            animation: rotate 10s linear infinite;
            z-index: 1;
        }

        .miembro-equipo::after {
            content: '';
            position: absolute;
            inset: 2px;
            background: var(--white);
            border-radius: 23px;
            z-index: 2;
        }
        
        .miembro-equipo:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-heavy);
        }
        
        .foto-miembro {
            width: 100%;
            height: 300px;
            overflow: hidden;
            position: relative;
            z-index: 3;
        }
        
        .foto-miembro img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .miembro-equipo:hover .foto-miembro img {
            transform: scale(1.05);
        }
        
        .miembro-info {
            padding: 30px 25px;
            position: relative;
            z-index: 3;
        }
        
        .miembro-info h3 {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            margin-bottom: 10px;
            font-size: 1.4rem;
            font-weight: 600;
        }
        
        .cargo {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 15px;
            font-style: italic;
            font-size: 1rem;
        }

        .miembro-info p {
            color: var(--dark);
            opacity: 0.8;
            line-height: 1.6;
        }

        /* Testimonios mejorados */
        .testimonials {
            padding: 120px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: relative;
        }

        .testimonials::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .testimonials .section-title h2,
        .testimonials .section-title p {
            color: white;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
        }
        
        .testimonial-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 25px;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: url('https://randomuser.me/api/portraits/women/32.jpg') no-repeat center center/cover;
            margin-right: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .author-info h4 {
            color: white;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .author-info p {
            opacity: 0.8;
        }

        /* Galería mejorada */
        .gallery {
            padding: 120px 0;
            background: var(--white);
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .gallery-item {
            height: 300px;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: var(--shadow-light);
        }

        .gallery-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-rose);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 2;
        }

        .gallery-item:hover::before {
            opacity: 0.3;
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-heavy);
        }

        .gallery-item:nth-child(1) { background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/images_tintado_pelo.jpg') center/cover; }
        .gallery-item:nth-child(2) { background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/images_uñas.jpg') center/cover; }
        .gallery-item:nth-child(3) { background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/images_maquillaje.jpg') center/cover; }
        .gallery-item:nth-child(4) { background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/116053406-front-view-of-stylish-barber-in-white-shirt-and-waistcoat-looking-at-camera-posing-and-smiling-in.jpg') center/cover; }
        .gallery-item:nth-child(5) { background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/images_cortando_barba.jpg') center/cover; }
        .gallery-item:nth-child(6) { background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/images.jpg') center/cover; }
        .gallery-item:nth-child(7) { background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/images_salon.jpg') center/cover; }
        .gallery-item:nth-child(8) { background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/istockphoto-511777075-612x612.jpg') center/cover; }
        .gallery-item:nth-child(9) { background: url('<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/images_masaje.jpg') center/cover; }

        /* Contacto mejorado */
        .contact {
            padding: 120px 0;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .contact .section-title h2,
        .contact .section-title p {
            color: white;
        }
        
        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
        }
        
        .contact-info h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 30px;
        }
        
        .contact-details {
            margin-bottom: 40px;
        }
        
        .contact-details p {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }
        
        .contact-details i {
            margin-right: 15px;
            font-size: 1.2rem;
            width: 25px;
        }
        
        .social-links {
            display: flex;
            gap: 20px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .social-links a:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }
        
        .contact-form {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: white;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            transition: all 0.3s ease;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* Footer mejorado */
        footer {
            background: var(--dark);
            color: white;
            padding: 60px 0 30px;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff6b9d' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-col ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .footer-col ul li a:hover {
            color: var(--primary);
            padding-left: 10px;
        }

        .footer-col p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .footer-col form {
            margin-top: 20px;
        }

        .footer-col .form-group input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 15px;
        }

        .footer-col .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-col .btn {
            margin-top: 10px;
            width: 100%;
            justify-content: center;
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
        }

        /* Scroll to top button */
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--gradient-rose);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: var(--shadow-medium);
        }

        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .scroll-to-top:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }

        /* Loading animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            transition: opacity 0.5s ease;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 107, 157, 0.2);
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive mejorado */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
                z-index: 1001;
            }
            
            nav ul {
                position: fixed;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100vh;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                align-items: center;
                justify-content: center;
                transition: left 0.3s ease;
                z-index: 1000;
            }
            
            nav ul.active {
                left: 0;
            }
            
            nav ul li {
                margin: 20px 0;
            }

            nav ul li a {
                font-size: 1.2rem;
                font-weight: 600;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .section-title h2 {
                font-size: 2.5rem;
            }

            .about-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .about-text h2 {
                font-size: 2.2rem;
            }

            .contact-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .services-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .equipo-container {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 30px;
            }

            .testimonials-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }

            .container {
                width: 95%;
                padding: 0 15px;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .btn {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }

        /* Animations on scroll */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.6s ease;
        }

        .slide-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .slide-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.6s ease;
        }

        .slide-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-rose);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary);
        }

         /* Sección de Lugares - Compacta */
        .locations {
            padding: 80px 0;
            background: linear-gradient(135deg, #f9fafb 0%, #e5e9f0 100%);
            position: relative;
            overflow: hidden;
        }

        .locations::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 107, 157, 0.08) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        .locations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
            position: relative;
            z-index: 2;
        }

        .location-card {
            background: var(--white);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid rgba(255, 107, 157, 0.1);
            max-width: 360px;
            margin: 0 auto;
        }

        .location-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-rose);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .location-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-medium);
        }

        .location-card:hover::before {
            opacity: 0.04;
        }

        .location-img {
            height: 180px;
            position: relative;
            overflow: hidden;
            z-index: 2;
        }

        .location-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .location-card:hover .location-img img {
            transform: scale(1.08);
        }

        .location-content {
            padding: 22px;
            position: relative;
            z-index: 2;
        }

        .location-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            margin-bottom: 12px;
            color: var(--primary);
            font-weight: 600;
            line-height: 1.3;
        }

        .location-content p {
            color: var(--dark);
            opacity: 0.8;
            line-height: 1.5;
            margin-bottom: 18px;
            font-size: 0.95rem;
        }

        .map-link {
            display: inline-flex;
            align-items: center;
            background: var(--gradient-rose);
            color: white;
            padding: 8px 18px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-light);
        }

        .map-link:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .map-link i {
            margin-right: 6px;
            font-size: 0.9rem;
        }

    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Header -->
    <header id="header">
        <div class="container header-container">
            <a href="#inicio" class="logo">Rose<span>Spa</span></a>
            <div class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </div>
            <nav>
                <ul id="navMenu">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#servicios">Servicios</a></li>
                    <li><a href="#nosotros">Nosotros</a></li>
                    <li><a href="#lugares">Lugares</a></li>
                    <li><a href="#equipo">Equipo</a></li>
                    <li><a href="#testimonios">Testimonios</a></li>
                    <li><a href="#galeria">Galería</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                    <li><a href="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/controlador/controlador.php?accion=login_nav" class="btn" style="padding: 8px 20px; font-size: 0.9rem;">Iniciar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="hero-particles" id="particles"></div>
        <div class="container hero-content">
            <h1 class="fade-in">Realza tu <span class="highlight">belleza natural</span></h1>
            <p class="fade-in">Descubre los mejores tratamientos de belleza con profesionales expertos que cuidan de ti y te ayudan a resaltar tu belleza única en un ambiente de lujo y relajación.</p>
            <a href="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/controlador/controlador.php?accion=login_cita" class="btn fade-in">Reserva tu cita ahora</a>
        </div>
    </section>

    <!-- Servicios -->
    <section class="services" id="servicios">
        <div class="container">
            <div class="section-title fade-in">
                <h2 class="gradient-text">Nuestros Servicios</h2>
                <p>Ofrecemos una amplia gama de servicios de belleza premium para consentirte y hacerte lucir radiante con los mejores productos y técnicas del mercado.</p>
            </div>
            <div class="services-grid">
                <?php
                if($funcion_traer_servicios && $funcion_traer_servicios->num_rows > 0){
                    $delay = 0;
                    while($row = $funcion_traer_servicios->fetch_assoc()){
                        echo '<div class="service-card fade-in" style="animation-delay: ' . $delay . 's;">';
                        echo '<div class="service-img">';
                        if(!empty($row['imagen'])) {
                            echo '<img src="' . BASE_URL . '/imagenes/servicios/' . $row['imagen'] . '" alt="' . $row['nombre'] . '">';
                        } else {
                            echo '<div style="width:100%; height:100%; background:var(--gradient-rose); display:flex; align-items:center; justify-content:center; color:white; font-size:2rem;"><i class="fas fa-spa"></i></div>';
                        }
                        echo '</div>';
                        echo '<div class="service-content">';
                        echo '<h3>' . $row['nombre'] . '</h3>';
                        echo '<p>' . $row['descripcion'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        $delay += 0.2;
                    }
                } else {
                    echo '<div class="service-card fade-in">';
                    echo '<div class="service-img">';
                    echo '<div style="width:100%; height:100%; background:var(--gradient-rose); display:flex; align-items:center; justify-content:center; color:white; font-size:2rem;"><i class="fas fa-spa"></i></div>';
                    echo '</div>';
                    echo '<div class="service-content">';
                    echo '<h3>Servicios Temporalmente No Disponibles</h3>';
                    echo '<p>Estamos trabajando para ofrecerte los mejores servicios. Pronto tendremos novedades increíbles.</p>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Sobre Nosotros -->
    <section class="about" id="nosotros">
        <div class="container">
            <div class="about-content">
                <div class="about-text slide-in-left">
                    <h2>Sobre Nosotros</h2>
                    <p>En Rose Spa llevamos más de 10 años de experiencia en el sector de la belleza, liderando con innovación y excelencia. María González, nuestra fundadora, dirige nuestro equipo con pasión y una visión revolucionaria en tratamientos estéticos.</p>
                    <p>Nos enorgullece crear un santuario de relajación y bienestar donde nuestros clientes pueden desconectar completamente de su rutina diaria y experimentar un nivel de lujo excepcional. Utilizamos exclusivamente productos de las mejores marcas internacionales.</p>
                    <p>Nuestra filosofía se fundamenta en realzar y celebrar la belleza natural única de cada persona, creando tratamientos personalizados que superan todas las expectativas y brindan resultados transformadores.</p>
                    <a href="#contacto" class="btn">Descubre más</a>
                </div>
                <div class="about-image slide-in-right floating-animation">
                    <!-- Imagen del salón -->
                </div>
            </div>
        </div>
    </section>

    <!-- Nueva sección de Lugares -->
    <section class="locations" id="lugares">
        <div class="container">
            <div class="section-title fade-in">
                <h2 class="gradient-text">Nuestras Sucursales</h2>
                <p>Descubre nuestros exclusivos espacios diseñados para brindarte la mejor experiencia de belleza y bienestar.</p>
            </div>
            
            <div class="locations-grid">
                <?php
                if($funcion_traer_lugares && $funcion_traer_lugares->num_rows > 0){
                    while($bucle_lugares = $funcion_traer_lugares->fetch_assoc()){
                        if($bucle_lugares['activo'] == 1){
                            $coordenadas = $bucle_lugares['cooordenadas'];
                            echo '<div class="location-card fade-in">';
                            echo '<div class="location-img">';
                            if(!empty($bucle_lugares['imagen_lugar'])) {
                                echo '<img src="' . BASE_URL . '/imagenes/lugares/' . $bucle_lugares['imagen_lugar'] . '" alt="' . $bucle_lugares['nombre_lugar'] . '">';
                            } else {
                                echo '<div style="width:100%; height:100%; background:var(--gradient-rose); display:flex; align-items:center; justify-content:center; color:white; font-size:2rem;"><i class="fas fa-map-marker-alt"></i></div>';
                            }
                            echo '</div>';
                            echo '<div class="location-content">';
                            echo '<h3>' . $bucle_lugares['nombre_lugar'] . '</h3>';
                            echo '<a href="https://www.google.com/maps?q=' . $coordenadas . '" target="_blank" class="map-link"><i class="fas fa-map-marked-alt"></i> Ver en Google Maps</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                } else {
                    echo '<div class="location-card fade-in">';
                    echo '<div class="location-img">';
                    echo '<div style="width:100%; height:100%; background:var(--gradient-rose); display:flex; align-items:center; justify-content:center; color:white; font-size:2rem;"><i class="fas fa-map-marker-alt"></i></div>';
                    echo '</div>';
                    echo '<div class="location-content">';
                    echo '<h3>Próximamente</h3>';
                    echo '<p>Estamos trabajando para abrir nuevas sucursales. ¡Muy pronto tendremos novedades!</p>';
                    echo '<a href="#contacto" class="map-link"><i class="fas fa-info-circle"></i> Más información</a>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Nuestro Equipo -->
    <section class="team" id="equipo">
        <div class="container">
            <div class="section-title fade-in">
                <h2 class="gradient-text">Nuestro Equipo de Expertos</h2>
                <p>Conoce a los maestros profesionales que harán que te sientas como una auténtica estrella y luzcas absolutamente increíble.</p>
            </div>
            <div class="equipo-container">
                <div class="miembro-equipo fade-in">
                    <div class="foto-miembro">
                        <img src="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/equipo/81285963-charming-woman-stylist-with-tools-in-hands-posing-on-camera-isolated-on-white-background.jpg" alt="María González">
                    </div>
                    <div class="miembro-info">
                        <h3>María González</h3>
                        <div class="cargo">Directora General & Fundadora</div>
                        <p>Con más de 15 años de experiencia revolucionando el sector de la belleza, María lidera nuestro equipo con pasión innovadora y visión vanguardista. Especialista reconocida internacionalmente en tratamientos faciales avanzados.</p>
                    </div>
                </div>

                <div class="miembro-equipo fade-in">
                    <div class="foto-miembro">
                        <img src="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/equipo/23551074-male-hair-stylist-holding-three-brushes.jpg" alt="Carlos Mendoza">
                    </div>
                    <div class="miembro-info">
                        <h3>Carlos Mendoza</h3>
                        <div class="cargo">Maestro Estilista Principal</div>
                        <p>Virtuoso en cortes de autor y colorimetría artística, Carlos aporta creatividad extraordinaria y técnica impecable. Ha perfeccionado su arte en los salones más exclusivos de Europa y es pionero en tendencias vanguardistas.</p>
                    </div>
                </div>

                <div class="miembro-equipo fade-in">
                    <div class="foto-miembro">
                        <img src="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/equipo/¿Cual-es-la-diferencia-entre-un-psicologo-y-un-terapeuta-1024x536.png" alt="Ana Rodríguez">
                    </div>
                    <div class="miembro-info">
                        <h3>Ana Rodríguez</h3>
                        <div class="cargo">Terapeuta Senior & Wellness Expert</div>
                        <p>Maestra en terapias holísticas y tratamientos corporales de lujo. Con certificaciones internacionales exclusivas, Ana fusiona magistralmente técnicas ancestrales milenarias con los enfoques más innovadores del bienestar moderno.</p>
                    </div>
                </div>

                <div class="miembro-equipo fade-in">
                    <div class="foto-miembro">
                        <img src="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/equipo/dwd2.png" alt="Sofía Torres">
                    </div>
                    <div class="miembro-info">
                        <h3>Sofía Torres</h3>
                        <div class="cargo">Artista del Maquillaje Profesional</div>
                        <p>Visionaria del maquillaje artístico con experiencia estelar en haute couture, fotografía editorial y producciones cinematográficas. Sofía crea obras maestras únicas que realzan la personalidad más auténtica de cada cliente.</p>
                    </div>
                </div>

                <div class="miembro-equipo fade-in">
                    <div class="foto-miembro">
                        <img src="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/equipo/1718863511411.jpg" alt="Sofía Torres">
                    </div>
                    <div class="miembro-info">
                        <h3>Marilin Monteros</h3>
                        <div class="cargo">Peluquera Profesional</div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil veniam consequuntur quae doloremque numquam voluptatem deserunt rerum aperiam iste? Dicta soluta repellendus ducimus autem odio doloremque, explicabo aliquid nulla quos.</p>
                    </div>
                </div>

                <div class="miembro-equipo fade-in">
                    <div class="foto-miembro">
                        <img src="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/imagenes/equipo/masajista_empleado.jpg" alt="Sofía Torres">
                    </div>
                    <div class="miembro-info">
                        <h3>Jose Martines</h3>
                        <div class="cargo">Masajista Profesional</div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil veniam consequuntur quae doloremque numquam voluptatem deserunt rerum aperiam iste? Dicta soluta repellendus ducimus autem odio doloremque, explicabo aliquid nulla quos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonios -->
    <section class="testimonials" id="testimonios">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Lo que dicen nuestras clientas</h2>
                <p>La satisfacción y felicidad genuina de nuestras clientas es nuestra mayor recompensa y fuente de inspiración diaria.</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card fade-in">
                    <div class="testimonial-text">
                        <p>"¡Absolutamente extraordinario! Llevo años confiando ciegamente en ellos para mi look y nunca me han decepcionado. Siempre están a la vanguardia de las tendencias más exclusivas y me aconsejan con una precisión impecable. ¡No cambiaría este lugar por nada en el mundo!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img"></div>
                        <div class="author-info">
                            <h4>María González</h4>
                            <p>Cliente desde 2018</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card fade-in">
                    <div class="testimonial-text">
                        <p>"Me transformaron completamente para mi boda y el resultado fue simplemente mágico. Estaba absolutamente espectacular y radiante. Todas mis invitadas quedaron fascinadas preguntando por este salón increíble. ¡Gracias por hacer realidad el día más importante de mi vida!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img"></div>
                        <div class="author-info">
                            <h4>Laura Martínez</h4>
                            <p>Novia Radiante 2024</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card fade-in">
                    <div class="testimonial-text">
                        <p>"Su tratamiento facial rejuvenecedor premium es pura magia en estado líquido. Los resultados fueron absolutamente milagrosos e inmediatos. Mi piel luce décadas más joven, tersa e irradiante. ¡Esta experiencia ha cambiado mi vida por completo!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img"></div>
                        <div class="author-info">
                            <h4>Carmen Rodríguez</h4>
                            <p>Cliente Fascinada</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card fade-in">
                    <div class="testimonial-text">
                        <p>"Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil veniam consequuntur quae doloremque numquam voluptatem deserunt rerum aperiam iste? Dicta soluta repellendus ducimus autem odio doloremque, explicabo aliquid nulla quos."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img"></div>
                        <div class="author-info">
                            <h4>Carmen Rodríguez</h4>
                            <p>Cliente Fascinada</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería -->
    <section class="gallery" id="galeria">
        <div class="container">
            <div class="section-title fade-in">
                <h2 class="gradient-text">Galería de Transformaciones</h2>
                <p>Descubre algunos ejemplos espectaculares de nuestros resultados más impresionantes y el ambiente de lujo absoluto de nuestras instalaciones</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item fade-in"></div>
                <div class="gallery-item fade-in"></div>
                <div class="gallery-item fade-in"></div>
                <div class="gallery-item fade-in"></div>
                <div class="gallery-item fade-in"></div>
                <div class="gallery-item fade-in"></div>
                <div class="gallery-item fade-in"></div>
                <div class="gallery-item fade-in"></div>
                <div class="gallery-item fade-in"></div>
            </div>
        </div>
    </section>

    <!-- Contacto -->
    <section class="contact" id="contacto">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Contacto</h2>
                <p>Reserva tu experiencia de lujo personalizada o solicita información exclusiva sobre nuestros servicios premium.</p>
            </div>
            <div class="contact-container">
                <div class="contact-info slide-in-left">
                    <h2>Información de Contacto</h2>
                    <div class="contact-details">
                        <p><i class="fas fa-map-marker-alt"></i> Av. Principal #123, Zona Exclusiva</p>
                        <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                        <p><i class="fas fa-envelope"></i> rosespasalonbelleza23@gmail.com</p>
                        <p><i class="fas fa-clock"></i> Lunes a Sábado: 9:00 - 20:00</p>
                        <p><i class="fas fa-crown"></i> Domingos: Solo citas VIP</p>
                    </div>
                    <div class="social-links">
                        <a href="#" class="pulse-animation"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="pulse-animation"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="pulse-animation"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="pulse-animation"><i class="fab fa-pinterest"></i></a>
                        <a href="#" class="pulse-animation"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="contact-form slide-in-right">
                    <form action="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/controlador/controlador.php" method="post">
                        <input type="hidden" name="contactos" value="formulario_contactos_landing">
                        <div class="form-group">
                            <label for="name">Nombre Completo</label>
                            <input type="text" name="name" id="name" placeholder="Tu nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="tu@email.com" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="tel" id="phone" name="phone" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="form-group">
                            <label for="service">Servicio de Interés</label>
                            <select id="service" name="service">
                                <option value="">Selecciona un servicio premium</option>
                                <option value="corte">Corte de cabello exclusivo</option>
                                <option value="color">Coloración artística</option>
                                <option value="facial">Tratamiento facial de lujo</option>
                                <option value="manicura">Manicura & pedicura </option>
                                <option value="maquillaje">Maquillaje profesional</option>
                                <option value="paquete">Paquete completo </option>
                                <option value="otros">Consulta personalizada</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Mensaje Personal</label>
                            <textarea id="message" name="message" placeholder="Cuéntanos cómo podemos hacer tu día especial..."></textarea>
                        </div>
                        <button type="submit" class="btn">Enviar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </section>  


    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-container">
                <div class="footer-col fade-in">
                    <h3>Rose Spa</h3>
                    <p>Tu santuario de belleza y bienestar de confianza, donde transformamos y realzamos tu belleza natural con los tratamientos más exclusivos y profesionales de clase mundial.</p>
                </div>
                <div class="footer-col fade-in">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#servicios">Servicios Premium</a></li>
                        <li><a href="#nosotros">Nuestra Historia</a></li>
                        <li><a href="#equipo">Equipo Experto</a></li>
                        <li><a href="#testimonios">Testimonios </a></li>
                        <li><a href="#galeria">Galería Exclusiva</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-col fade-in">
                    <h3>Newsletter</h3>
                    <p>Suscríbete para recibir ofertas exclusivas, promociones especiales y las últimas novedades en tendencias de belleza.</p>
                    <form>
                        <div class="form-group">
                            <input type="email" placeholder="Tu email" required>
                        </div>
                        <button type="submit" class="btn">Suscribirse</button>
                    </form>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2024 Rose Spa. Todos los derechos reservados. | Diseñado con 💖</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to top button -->
    <div class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script>
        // Loading screen
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loadingOverlay').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('loadingOverlay').style.display = 'none';
                }, 500);
            }, 1000);
        });

        // Create particles
        function createParticles() {
            const particles = document.getElementById('particles');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particles.appendChild(particle);
            }
        }

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            const scrollToTop = document.getElementById('scrollToTop');
            
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
                scrollToTop.classList.add('visible');
            } else {
                header.classList.remove('scrolled');
                scrollToTop.classList.remove('visible');
            }
        });

        // Mobile menu
        document.getElementById('menuToggle').addEventListener('click', function() {
            const navMenu = document.getElementById('navMenu');
            const icon = this.querySelector('i');
            
            navMenu.classList.toggle('active');
            
            if (navMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close mobile menu when clicking on links
        document.querySelectorAll('nav ul li a[href^="#"]').forEach(item => {
            item.addEventListener('click', function() {
                const navMenu = document.getElementById('navMenu');
                const menuToggle = document.getElementById('menuToggle');
                const icon = menuToggle.querySelector('i');
                
                navMenu.classList.remove('active');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });

        // Smooth scrolling
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

        // Scroll to top
        document.getElementById('scrollToTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right').forEach(el => {
            observer.observe(el);
        });

        // Form enhancement
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Initialize particles
        createParticles();

        // Dynamic gradient animation
        let gradientAngle = 45;
        setInterval(() => {
            gradientAngle = (gradientAngle + 1) % 360;
            document.querySelector('.hero').style.background = `linear-gradient(${gradientAngle}deg, #667eea, #764ba2, #f093fb, #f5576c)`;
        }, 100);

        // Add some interactive elements
        document.querySelectorAll('.service-card, .miembro-equipo, .gallery-item').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Enhanced testimonial cards interaction
        document.querySelectorAll('.testimonial-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(255, 255, 255, 0.2)';
                this.style.transform = 'translateY(-10px) rotateY(5deg)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.background = 'rgba(255, 255, 255, 0.1)';
                this.style.transform = 'translateY(0) rotateY(0deg)';
            });
        });

        // Gallery lightbox effect
        document.querySelectorAll('.gallery-item').forEach((item, index) => {
            item.addEventListener('click', function() {
                // Create lightbox overlay
                const lightbox = document.createElement('div');
                lightbox.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.9);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10000;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                `;
                
                const img = document.createElement('div');
                img.style.cssText = `
                    max-width: 90%;
                    max-height: 90%;
                    background: ${window.getComputedStyle(this).background};
                    background-size: cover;
                    background-position: center;
                    width: 800px;
                    height: 600px;
                    border-radius: 15px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
                `;
                
                const closeBtn = document.createElement('div');
                closeBtn.innerHTML = '<i class="fas fa-times"></i>';
                closeBtn.style.cssText = `
                    position: absolute;
                    top: 20px;
                    right: 30px;
                    color: white;
                    font-size: 2rem;
                    cursor: pointer;
                    padding: 10px;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.1);
                    backdrop-filter: blur(10px);
                    transition: all 0.3s ease;
                `;
                
                lightbox.appendChild(img);
                lightbox.appendChild(closeBtn);
                document.body.appendChild(lightbox);
                
                // Animate in
                setTimeout(() => lightbox.style.opacity = '1', 10);
                
                // Close functionality
                const closeLightbox = () => {
                    lightbox.style.opacity = '0';
                    setTimeout(() => document.body.removeChild(lightbox), 300);
                };
                
                closeBtn.addEventListener('click', closeLightbox);
                lightbox.addEventListener('click', (e) => {
                    if (e.target === lightbox) closeLightbox();
                });
                
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeLightbox();
                });
            });
        });
       

        // Newsletter form
        document.querySelector('.footer-col form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button');
            const input = this.querySelector('input');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'Suscribiendo...';
            submitBtn.style.background = 'var(--gradient-3)';
            
            setTimeout(() => {
                submitBtn.textContent = '¡Suscrito! ✓';
                submitBtn.style.background = 'linear-gradient(135deg, #4CAF50, #45a049)';
                input.value = '';
                
                setTimeout(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.style.background = 'var(--gradient-rose)';
                }, 2000);
            }, 1000);
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            const heroContent = document.querySelector('.hero-content');
            
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
                heroContent.style.transform = `translateY(${scrolled * 0.3}px)`;
            }
        });

        // Dynamic counter animation for stats (if you want to add stats later)
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                element.textContent = Math.floor(current);
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                }
            }, 20);
        }

        // Enhanced social media hover effects
        document.querySelectorAll('.social-links a').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) rotateY(15deg)';
                this.style.boxShadow = '0 15px 35px rgba(255, 107, 157, 0.4)';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) rotateY(0deg)';
                this.style.boxShadow = 'none';
            });
        });

        // Typing effect for hero title
        function typeWriter(element, text, speed = 100) {
            element.textContent = '';
            let i = 0;
            
            function type() {
                if (i < text.length) {
                    element.textContent += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                }
            }
            
            type();
        }

        // Initialize typing effect after page load
        setTimeout(() => {
            const heroTitle = document.querySelector('.hero h1');
            if (heroTitle) {
                const originalText = heroTitle.textContent;
                typeWriter(heroTitle, originalText, 80);
            }
        }, 1500);

        // Mouse trail effect
        let mouseTrail = [];
        document.addEventListener('mousemove', function(e) {
            mouseTrail.push({
                x: e.clientX,
                y: e.clientY,
                time: Date.now()
            });
            
            // Keep only recent points
            mouseTrail = mouseTrail.filter(point => Date.now() - point.time < 1000);
            
            // Create trail particles occasionally
            if (Math.random() < 0.3) {
                createTrailParticle(e.clientX, e.clientY);
            }
        });

        function createTrailParticle(x, y) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                left: ${x}px;
                top: ${y}px;
                width: 4px;
                height: 4px;
                background: var(--primary);
                border-radius: 50%;
                pointer-events: none;
                z-index: 9999;
                animation: trailFade 1s ease-out forwards;
            `;
            
            document.body.appendChild(particle);
            
            setTimeout(() => {
                if (particle.parentNode) {
                    particle.parentNode.removeChild(particle);
                }
            }, 1000);
        }

        // Add trail animation CSS
        const trailStyle = document.createElement('style');
        trailStyle.textContent = `
            @keyframes trailFade {
                0% {
                    opacity: 1;
                    transform: scale(1);
                }
                100% {
                    opacity: 0;
                    transform: scale(0);
                }
            }
        `;
        document.head.appendChild(trailStyle);

        // Service card hover sound effect (visual feedback)
        document.querySelectorAll('.service-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                // Add a subtle glow effect
                this.style.boxShadow = '0 20px 60px rgba(255, 107, 157, 0.3), inset 0 0 20px rgba(255, 107, 157, 0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = 'var(--shadow-light)';
            });
        });

        // Scroll-triggered animations with stagger
        function staggerAnimation(elements, delay = 100) {
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.classList.add('visible');
                }, index * delay);
            });
        }

        // Enhanced intersection observer
        const advancedObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    
                    // Check if it's a container with multiple children
                    if (element.classList.contains('services-grid')) {
                        const cards = element.querySelectorAll('.service-card');
                        staggerAnimation(cards, 200);
                    } else if (element.classList.contains('equipo-container')) {
                        const members = element.querySelectorAll('.miembro-equipo');
                        staggerAnimation(members, 300);
                    } else if (element.classList.contains('testimonials-grid')) {
                        const testimonials = element.querySelectorAll('.testimonial-card');
                        staggerAnimation(testimonials, 250);
                    } else {
                        element.classList.add('visible');
                    }
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        });

        // Observe containers
        document.querySelectorAll('.services-grid, .equipo-container, .testimonials-grid').forEach(el => {
            advancedObserver.observe(el);
        });

        console.log('🌹 Rose Spa Premium - Página totalmente cargada y optimizada');
    </script>
</body>
</html>