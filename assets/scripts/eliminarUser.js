document.querySelectorAll('a.eliminar').forEach(function (enlace) {
    enlace.addEventListener('click', function (e) {
        if (!confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
            e.preventDefault();
        }
    });
});