<div>
    <hr class="horizontal dark my-0" />
</div>
<div class="d-flex justify-content-end m-4">
    <button type="button" class="btn bg-gradient-secondary rounded-pill me-2"
    {{--click="$dispatch('close-modal{{ $modalId }}')"--}}
    onclick="Livewire.dispatch('closeModalEvent', [{'modalId': '{{$modalId}}' }])">Close</button>
    @if ($modalId !== "detail") {{--Only show on form--}}
        <button type="button" class="btn bg-gradient-primary rounded-pill"
            wire:click="saveRecord('{{$modalId}}')">
            {{ $isEditMode ? 'Save Changes' : 'Add Record' }}
        </button>
    @endif
</div>

