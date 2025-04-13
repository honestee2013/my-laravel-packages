    <div wire:ignore.self class="modal fade" id="{{ $modalId }}" tabindex="-1"
        role="dialog"aria-labelledby="addEditModalLabel" aria-hidden="true" wire:key='"{{ $modalId }}'>

        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bolder text-info text-gradient" id="exampleModalLabel">
                        Crop Image
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body my-2  p-2 border rounded-3">
                    <div id="cropper-image-container{{ $modalId }}" style="width: 100%; height: 70vh;" wire:ignore>
                        <img id="image-to-crop{{ $modalId }}" src="" alt="Image to Crop"
                            style="width: 100%;" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="save-croped-image{{ $modalId }}" type="button" class="btn btn-primary">OK</button>
                </div>
            </div>
        </div>
    </div>
