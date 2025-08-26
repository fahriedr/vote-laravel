import './bootstrap';
import FingerprintJS from '@fingerprintjs/fingerprintjs';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

FingerprintJS.load().then(fp => {
    fp.get().then(result => {
        const visitorId = result.visitorId; // unique ID
        console.log("Fingerprint:", visitorId);

        // Kirim ke server via hidden input
        let input = document.createElement("input");
        input.type = "hidden";
        input.name = "fingerprint";
        input.value = visitorId;
        document.querySelector("form")?.appendChild(input);
    });
});
