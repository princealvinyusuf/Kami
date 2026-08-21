@props(['name','label','type'=>'text','value'=>null])
<div>
    <label for="{{ $name }}" class="mb-2 block text-sm font-bold text-neutral-800">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror {{ $attributes->class('filter-input w-full') }}>
    @error($name)<p id="{{ $name }}-error" class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
</div>
