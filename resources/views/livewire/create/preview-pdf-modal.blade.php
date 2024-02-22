<div wire:ignore.self class="modal fade venoBoxTypeModal" id="pdfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-header border-bottom-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                data-icon="akar-icons:cross"></span></button>

    </div>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div style="width: 100%; height:auto" id="pdfViewer"></div>
            </div>
        </div>
    </div>
    @push('style')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf_viewer.min.css">
    @endpush
    @push('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>

        <script>
            document.addEventListener('livewire:load', function() {
                console.log('Livewire Loaded');
                Livewire.on('loadPdf', function(pdfUrl) {
                    console.log('Livewire launched');
                    var loadingTask = pdfjsLib.getDocument(pdfUrl).promise;
                    loadingTask.then(function(pdf) {
                        pdf.getPage(1).then(function(page) {
                            var scale = 1.5;
                            var viewport = page.getViewport({
                                scale: scale
                            });
                            var canvas = document.createElement('canvas');
                            var context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            var renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };

                            page.render(renderContext);
                            document.getElementById('pdfViewer').appendChild(canvas);
                        });
                    });
                });
            });
        </script>
    @endpush
</div>
