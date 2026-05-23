@props([
    'value' => '',
    'filename' => '',
    'download' => false
])
@php
    $href = is_string($value) ? \App\Services\YandexTemporaryUrlService::makeFromUrl($value) : $value;
@endphp
<span class="dropzone-file-icon">
    <x-moonshine::icon icon="document" />
</span>
<h5 class="dropzone-file-name">
    <a
        @if($download ?? false) download href="{{ $href }}" @endif
    >
        {{ $filename ?? $value }}
    </a>
</h5>
