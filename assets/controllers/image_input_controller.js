import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
	static targets = ['imageInput', 'imagePreview'];

	connect() {
		// Add an event listener to the image input
		this.imageInputTarget.addEventListener('change', (event) => {
			const file = event.target.files[0]; // Get the selected file

			// Check if a file is selected and if it's an image
			if (file && file.type.startsWith('image/')) {
				const reader = new FileReader();

				// When the file is loaded, update the image preview
				reader.onload = (e) => {
					this.imagePreviewTarget.src = e.target.result; // Set the image source to the file URL
				};

				reader.readAsDataURL(file); // Read the file as a Data URL
			}
		});
	}
}
