@php $tour = $tour ?? null; @endphp

<div class="form-group">
    <label for="title">{{ __('Title') }} *</label>
    <input id="title" type="text" name="title" value="{{ old('title', $tour?->title) }}" class="form-control" required>
    @error('title') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="description">{{ __('Short description') }} *</label>
    <textarea id="description" name="description" class="form-control" rows="3" required>{{ old('description', $tour?->description) }}</textarea>
    <div class="form-hint">{{ __('This text is shown on tour cards. Keep it short.') }}</div>
    @error('description') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="price">{{ __('Price') }} (₼) *</label>
    <div class="input-group-prefix">
        <span class="addon">₼</span>
        <input id="price" type="number" name="price" step="0.01" min="0" value="{{ old('price', $tour?->price) }}" class="form-control" required>
    </div>
    @error('price') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="image">{{ __('Cover image') }} @if (! $tour) * @endif</label>
    @if ($tour && $tour->imageUrl())
        <div style="margin-bottom:8px;">
            <img src="{{ $tour->imageUrl() }}" alt="" style="height:80px;border-radius:8px;">
        </div>
        <div class="form-hint">{{ __('Leave empty to keep the current image.') }}</div>
    @endif
    <input id="image" type="file" name="image" accept="image/*" class="form-control" @if (! $tour) required @endif>
    <div class="form-hint">{{ __('JPG, PNG or WEBP, up to 4 MB.') }}</div>
    @error('image') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="content">{{ __('Tour content') }} *</label>
    <textarea id="content" name="content" class="form-control" rows="10">{{ old('content', $tour?->content) }}</textarea>
    <div class="form-hint">{{ __('Detailed program, itinerary, what is included, etc.') }}</div>
    @error('content') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="row-between">
    <a href="{{ route('company.tours.index') }}" class="btn btn-outline">{{ __('Cancel') }}</a>
    <button type="submit" class="btn btn-primary">{{ $tour ? __('Update') : __('Create Tour') }}</button>
</div>

@push('head')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
@endpush

@push('scripts')
<script>
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo']
        })
        .catch(function (error) { console.error(error); });
</script>
@endpush
