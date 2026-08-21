@props(['status'])
@php($value = $status instanceof \BackedEnum ? $status->value : $status)
<span {{ $attributes->class(['inline-flex rounded-full px-2.5 py-1 text-xs font-bold','bg-green-100 text-green-800'=>in_array($value,['published','verified','active']),'bg-amber-100 text-amber-900'=>in_array($value,['pending','draft','unverified']),'bg-neutral-200 text-neutral-800'=>!in_array($value,['published','verified','active','pending','draft','unverified'])]) }}>{{ str($value)->headline() }}</span>
