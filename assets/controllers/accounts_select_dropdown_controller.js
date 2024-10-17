import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['button', 'menu', 'option', 'selectedText'];

    connect() {
        this.selectedIndex = 0; // Initialize the selected index
    }

    // Toggle the dropdown menu
    toggleMenu() {
        const expanded = this.buttonTarget.getAttribute('aria-expanded') === 'true';
        this.buttonTarget.setAttribute('aria-expanded', !expanded);
        this.menuTarget.classList.toggle('hidden');
    }

    // Handle the account selection
    selectOption(event) {
        const selectedOption = event.currentTarget;
        const accountId = selectedOption.dataset.accountId;  // Get the account ID
        const accountName = selectedOption.dataset.accountName;  // Get the account name
        const accountIcon = selectedOption.querySelector('img').src;  // Get the account icon (logo)

        // Update the button text and logo to reflect the selected account
        this.selectedTextTarget.textContent = accountName;
        this.buttonTarget.querySelector('img').src = accountIcon;

        // Send an AJAX request to set the selected account
        this.submitAccountSelection(accountId);

        // Close the menu after selection
        this.toggleMenu();
    }

    // Submit the selected account ID via AJAX
    submitAccountSelection(accountId) {
        fetch(`/account/set-selected-account/${accountId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to reflect the selected account
                window.location.reload();
                console.log('Account selection saved:', data);
            } else {
                // Handle failure
                console.error('Failed to save account selection:', data);
            }
        });
    }
}
