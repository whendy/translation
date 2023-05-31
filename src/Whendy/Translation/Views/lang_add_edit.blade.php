<form id="formLanguage" class="needs-validation" action="{{ route('whendy.translation.language.save') }}">
    <input type="hidden" name="id" value="{{ $language->id }}">
    <div class="modal-body">
        <div class="mb-3">
            <label for="locale" class="col-form-label">Locale <strong class="text-danger">*</strong> </label>
            <input id="locale" type="text" name="locale" class="form-control" value="{{ $language->locale }}" placeholder="e.g: en|id" required>
            <div class="invalid-feedback invalid-feedback-locale"></div>
        </div>
        <div class="mb-3">
            <label for="name" class="col-form-label">Name <strong class="text-danger">*</strong> </label>
            <input id="name" type="text" name="name" class="form-control" value="{{ $language->name }}" placeholder="Name" required>
            <div class="invalid-feedback invalid-feedback-name"></div>
        </div>
        <div class="mb-3">
            <label for="status" class="col-form-label">Status <strong class="text-danger">*</strong> </label>
            <select id="status" name="status" class="form-control" required>
                <option value="1" {{ ((int)$language->status == 1?'selected':'') }}>Active</option>
                <option value="0" {{ ((int)$language->status == 0?'selected':'') }}>Not Active</option>
            </select>
            <div class="invalid-feedback invalid-feedback-status"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
