// script for navbar dropdown toggle
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('profileDropdownButton');
    const dropdownMenu = document.getElementById('profileDropdownMenu');
    
    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener('click', function(e) {
            e.preventDefault();
            dropdownMenu.classList.toggle('hidden');
            const expanded = dropdownButton.getAttribute('aria-expanded') === 'true' ? 'false' : 'true';
            dropdownButton.setAttribute('aria-expanded', expanded);
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
                dropdownButton.setAttribute('aria-expanded', 'false');
            }
        });
    }
});