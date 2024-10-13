import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['timezoneInput']

    connect() {
        // Get the current timezone from the browser
        const currentTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        
        // Set the value of the timezone input to the current timezone
        if (this.hasTimezoneInputTarget) {
            this.timezoneInputTarget.value = currentTimezone;
        }
    }
}
