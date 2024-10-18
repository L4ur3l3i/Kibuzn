import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
	static targets = ['endDate', 'iterations', 'duration'];

	connect() {
        console.log('Connected to RecurringTransactionDurationController');
		this.toggleFields(); // Exécute dès la connexion du contrôleur
	}

	toggleFields() {
		const duration = this.durationTarget.value;

		if (duration == 1) {
			this.hide(this.endDateTarget);
			this.hide(this.iterationsTarget);
			this.clearField(this.endDateTarget);
			this.clearField(this.iterationsTarget);
		} else if (duration == 2) {
			this.show(this.endDateTarget);
			this.hide(this.iterationsTarget);
			this.clearField(this.iterationsTarget);
		} else if (duration == 3) {
			this.hide(this.endDateTarget);
			this.show(this.iterationsTarget);
			this.clearField(this.endDateTarget);
		}
	}

	hide(element) {
		element.parentElement.classList.add('hidden');
	}

	show(element) {
		element.parentElement.classList.remove('hidden');
	}

	clearField(element) {
		element.value = '';
	}

	durationChange(event) {
		this.toggleFields();
	}
}
