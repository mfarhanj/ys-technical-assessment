<div>
    @foreach($classes as $class)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{ $class->id }}" id="class_{{ $class->id }}"
                   wire:model="selectedClasses">
            <label class="form-check-label" for="class_{{ $class->id }}">{{ $class->name }}</label>
        </div>
    @endforeach
    @if($classes->isEmpty())
        <p class="text-muted small">Create classes first from the Classes page.</p>
    @endif
</div>
