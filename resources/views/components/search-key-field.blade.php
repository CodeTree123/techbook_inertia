@props(['placeholder' => 'Search...', 'btn' => 'btn-primary'])
<div class="input-group w-auto flex-fill">
    <input type="search" name="search" class="form-control" placeholder="{{ __($placeholder) }}" value="{{ request()->search }}">
    <button class="btn btn-primary" type="submit"><i class="fas fa-search fa-sm"></i></button>
</div>
