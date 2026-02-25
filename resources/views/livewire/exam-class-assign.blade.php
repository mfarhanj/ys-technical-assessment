<div>
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show py-2">{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
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
