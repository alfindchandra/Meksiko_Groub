import "./bootstrap";
import Chart from "chart.js/auto";

window.Chart = Chart;

document.addEventListener("livewire:init", () => {
    // Gunakan Alpine yang sudah di-bundle Livewire 3
    const Alpine = window.Alpine;

    if (Alpine) {
        import("@alpinejs/collapse").then((module) => {
            Alpine.plugin(module.default);
        });
    }
});
