import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
	static targets = ['menu'];

	connect() {
		this.isOpen = false;
	}

	toggle() {
		this.isOpen = !this.isOpen;

		if (this.isOpen) {
			// Show the dropdown
			this.menuTarget.classList.remove(
				'opacity-0',
				'scale-95',
				'invisible'
			);
			this.menuTarget.classList.add(
				'opacity-100',
				'scale-100',
				'visible'
			);
		} else {
			// Hide the dropdown
			this.menuTarget.classList.remove(
				'opacity-100',
				'scale-100',
				'visible'
			);
			this.menuTarget.classList.add('opacity-0', 'scale-95', 'invisible');
		}

		// Update ARIA attributes
		const expanded = this.isOpen ? 'true' : 'false';
		this.element
			.querySelector('#user-menu-button')
			.setAttribute('aria-expanded', expanded);
	}
}
