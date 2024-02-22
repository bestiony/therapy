<div wire:ignore.self class="modal fade edit_modal" id="editSectionName" tabindex="-1"
    aria-labelledby="editSectionNameLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="editSectionNameLabel">{{ __('Edit Lesson Name') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="updateSection" id="updateEditModal" class="needs-validation">
                <div class="modal-body">

                    <div class="row mb-30">
                        <div class="col-md-12">
                            <label
                                class="label-text-title color-heading font-medium font-16 mb-2">{{ __('Name') }}</label>
                            <input wire:model='section_name' type="text" name="name" class="form-control"
                                id="lessonName" placeholder="Write your lesson name" value="" required>
                        </div>
                        @if ($errors->has('name'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="modal-footer d-flex justify-content-center align-items-center">
                        <button type="submit"
                            class="theme-btn theme-button1 default-hover-btn">{{ __('Submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
