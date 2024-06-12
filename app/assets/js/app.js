document.addEventListener('DOMContentLoaded', () => {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');

    navbarToggle.addEventListener('click', () => {
        navbarMenu.classList.toggle('active');
    });

    // check if var errors is defined
    // var errors = {"error":"invalid credentials"};
    if(errors) {

        Object.keys(errors).forEach(function (key) {

            console.error('ERROR: ' + errors[key] + ' (code: 1718218683807)');

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
