document.addEventListener('DOMContentLoaded', () => {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');

    navbarToggle.addEventListener('click', () => {
        navbarMenu.classList.toggle('active');
    });

    if(errors) {

        Object.keys(errors).forEach(function (key) {
            var element = document.getElementById(key);
            if (element) {
                var errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerText = errors[key];
                element.parentNode.insertBefore(errorDiv, element.nextSibling);
            }
        });

    }

});
