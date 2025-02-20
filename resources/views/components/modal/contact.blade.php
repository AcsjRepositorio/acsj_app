<!-- Modal do Mapa -->
<div class="modal fade" id="contact" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel"  style="color: #00C49A">Nossa Localização</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                
                <div id="leafletMap" style="height: 400px; width: 100%;"></div>
            </div>


            <div class="d-flex  flex-column align-items-center">
    <div class="text-dark">
        Alameda Professor Hernâni Monteiro<br>
    </div>

    <div class="mb-3"> 4200-319, Porto</div>

    <div class="mb-5">
        <span class="text-dark "><b>Telf.:</b> 1511515155 </span>
        <span> <b> Email: </b>teste@teste.com</span>
    </div>
    </div>
</div>


        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    var leafletMap; // Variável global para evitar recriação do mapa

    document.addEventListener("DOMContentLoaded", function () {
        var modal = document.getElementById("contact");

        modal.addEventListener("shown.bs.modal", function () {
            // Se o mapa ainda não foi inicializado, cria um novo mapa
            if (!leafletMap) {
                leafletMap = L.map("leafletMap").setView([41.1799, -8.6057], 16); // Coordenadas exatas do Hospital São João, Porto, Portugal

                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: "© OpenStreetMap contributors"
                }).addTo(leafletMap);

                L.marker([41.1799, -8.6057])
                    .addTo(leafletMap)
                    .bindPopup("Hospital São João, Porto, Portugal")
                    .openPopup();
            } else {
                setTimeout(() => {
                    leafletMap.invalidateSize(); // Corrige possíveis problemas de renderização
                }, 300);
            }
        });

        modal.addEventListener("hidden.bs.modal", function () {
            leafletMap.invalidateSize(); // Garante que o mapa se ajusta ao fechar
        });
    });
</script>


