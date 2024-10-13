import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['flashMessage'];

  connect() {
    // Add "show" class after slight delay to trigger entrance animation
    setTimeout(() => {
      this.flashMessageTarget.classList.remove('opacity-0', 'translate-y-[-10px]');
      this.flashMessageTarget.classList.add('opacity-100', 'translate-y-0');
    }, 100);

    // Automatically dismiss the flash message after 10 seconds
    /* setTimeout(() => {
      this.dismiss();
    }, 10000); */
  }

  dismiss() {
    // Trigger exit animation
    this.flashMessageTarget.classList.remove('opacity-100', 'translate-y-0');
    this.flashMessageTarget.classList.add('opacity-0', 'translate-y-[-10px]');

    // Remove the element after animation completes
    setTimeout(() => {
      this.flashMessageTarget.remove();
    }, 500); // Match the duration of the transition
  }
}
