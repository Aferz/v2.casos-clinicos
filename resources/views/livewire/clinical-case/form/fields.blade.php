@foreach (fields() as $field)
    <div class="{{ $field->renderContainerClass() }}">
        {!! $field->renderForm($errors) !!}

        @error($field->name())
        <p class="text-error-500 mt-2 text-sm">
            {{ $message }}
        </p>
        @enderror
    </div>
@endforeach