<form id="formTranslationSaveUpdate" data-action="{{ route('whendy.translation.translation.save') }}">
    <div class="modal-body">
        <input type="hidden" name="code" value="{{ $code }}">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group mb-3">
                    <label for="input_translation_namespace">@lang('Namespace') <strong class="text-danger">*</strong> </label>
                    <select id="input_translation_namespace_select" name="namespace" class="form-control form-control-sm" required>
                        @foreach($namespaces as $namespace)
                            <option value="{{ $namespace->namespace }}" {{ ($translation->namespace == $namespace->namespace ? 'selected':'') }}>{{ $namespace->namespace }}</option>
                        @endforeach
                    </select>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="input_translation_namespace_check">
                        <label class="form-check-label" for="input_translation_namespace_check">@lang('New Namespace')</label>
                    </div>
                    <input id="input_translation_namespace" name="namespace" value="" class="form-control form-control-sm d-none" placeholder="@lang('Namespace')" required disabled>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group mb-3">
                    <label for="input_translation_group">@lang('Group') <strong class="text-danger">*</strong> </label>
                    <select id="input_translation_group_select" name="group" class="form-control form-control-sm" required>
                        @foreach($groups as $group)
                            <option value="{{ $group->group }}" {{ ($translation->group == $group->group ? 'selected':(!$translation->group&&$group->group=='*' ? 'selected':'')) }}>{{ $group->group }}</option>
                        @endforeach
                    </select>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="input_translation_group_check">
                        <label class="form-check-label" for="input_translation_group_check">@lang('New Group')</label>
                    </div>
                    <input id="input_translation_group" name="group" value="" class="form-control form-control-sm d-none" placeholder="@lang('Group')" required disabled>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group mb-3">
                    <label for="input_translation_item">@lang('Key Usage') <strong class="text-danger">*</strong> </label>
                    <input id="input_translation_item" name="item" value="{{ $translation->item }}" class="form-control form-control-sm" placeholder="@lang('Key Usage')" required>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <?php
            foreach ($locales as $index => $locale){
                $name_language = $locale->name . ' [' . $locale->locale .']';
                $has_translation = new \Whendy\Translation\Models\LanguageEntry();
                if (!empty($code)){
                    $has_translation = $languageEntryProvider->findByLangCode($locale->locale, $code);
                }
                echo '<div class="col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="input_translation_text_'.$locale->locale.'">'.$name_language . ($index == 0 ? ' <strong class="text-danger">*</strong>':'') .' </label>
                        <div class="input-group">
                            <input type="hidden" name="translations['.$locale->locale.'][id]" value="'.($has_translation?$has_translation->id:'').'">
                            <input type="hidden" name="translations['.$locale->locale.'][locale]" value="'.$locale->locale.'">
                            <textarea id="input_translation_text_{{ $locale->locale }}" name="translations['.$locale->locale.'][text]" class="form-control" placeholder="Translate '.$name_language.'" '.($index == 0 ? 'required':'').'>'.($has_translation?$has_translation->text:'').'</textarea>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
