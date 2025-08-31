document.addEventListener('DOMContentLoaded', function() {
    const logo = document.querySelector('.imagen_logo');
    let rotation = 0;
    const speed = 0.3; // Aumenté la velocidad para que sea visible
    
    function animate() {
        rotation += speed;
        logo.style.transform = `rotate(${rotation}deg)`;
        logo.style.transformOrigin = 'center center'; // Asegura que gire desde el centro
        logo.style.willChange = 'transform'; // Optimización para animaciones
        requestAnimationFrame(animate);
    }
    
    animate();
});