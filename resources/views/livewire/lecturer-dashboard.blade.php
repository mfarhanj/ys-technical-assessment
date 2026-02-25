<div class="container">
    <h2 class="mb-4">Lecturer Dashboard</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">Classes</h5>
                    <p class="card-text display-6">{{ $stats['classes'] }}</p>
                    <a href="{{ route('lecturer.classes') }}" class="btn btn-outline-primary btn-sm">Manage</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">Subjects</h5>
                    <p class="card-text display-6">{{ $stats['subjects'] }}</p>
                    <a href="{{ route('lecturer.subjects') }}" class="btn btn-outline-success btn-sm">Manage</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title">My Exams</h5>
                    <p class="card-text display-6">{{ $stats['exams'] }}</p>
                    <a href="{{ route('lecturer.exams') }}" class="btn btn-outline-info btn-sm">Manage</a>
                </div>
            </div>
        </div>
    </div>
</div>
