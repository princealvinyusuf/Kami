@props(['name','label','rows'=>4])
<div>
    <label for="{{ $name }}" class="mb-2 block text-sm font-bold text-neutral-800">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror {{ $attributes->class('filter-input w-full resize-y') }}>{{ old($name) }}</textarea>
    @error($name)<p id="{{ $name }}-error" class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
</div>
