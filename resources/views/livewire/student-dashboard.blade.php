<div class="container">
    <h2 class="mb-4">Student Dashboard</h2>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">Available Exams</h5>
                    <p class="card-text display-6">{{ $availableExams }}</p>
                    <a href="{{ route('student.exams') }}" class="btn btn-outline-primary btn-sm">View Exams</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">My Attempts</h5>
                    <p class="card-text display-6">{{ $attemptsCount }}</p>
                    <a href="{{ route('student.results') }}" class="btn btn-outline-success btn-sm">View Results</a>
                </div>
            </div>
        </div>
    </div>
</div>
