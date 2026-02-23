import "./bootstrap";
import Chart from "chart.js/auto";

window.Chart = Chart;

import Alpine from "alpinejs";
window.Alpine = Alpine;

document.addEventListener("livewire:init", () => {
    import("@alpinejs/collapse").then((module) => {
        window.Alpine.plugin(module.default);
    });
});

// Fungsi inisialisasi grafik yang dapat dipanggil ulang
