document.addEventListener('DOMContentLoaded', function() {
    const countryField = document.querySelector('input[name="country"]'); // Assurez-vous que le champ du pays a cet attribut name
    const zipcodeField = document.querySelector('input[name="zipcode"]');

    // Désactiver le champ zipcode par défaut
    zipcodeField.disabled = true;
    zipcodeField.style.backgroundColor = '#e0e0e0'; // Couleur grise

    countryField.addEventListener('input', function() {
        const country = countryField.value.toLowerCase();
        if (country === 'netherlands' || country === 'nederlands') {
            zipcodeField.disabled = false;
            zipcodeField.style.backgroundColor = ''; // Réinitialiser la couleur
        } else {
            zipcodeField.disabled = true;
            zipcodeField.value = ''; // Efface le champ si désactivé
            zipcodeField.style.backgroundColor = '#e0e0e0'; // Couleur grise
        }
    });
});