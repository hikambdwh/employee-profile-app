<x-dashboard.card
    :totalEmployees="$totalEmployees"
    :completedEmployees="$completedEmployees"
    :pendingEmployees="$pendingEmployees"
    :completionPercentage="$completionPercentage"
    :hrIncompleteEmployees="$hrIncompleteEmployees"
/>

<x-dashboard.progress
    :fullyCompleteEmployees="$fullyCompleteEmployees"
    :totalEmployees="$totalEmployees"
    :fullCompletionPercentage="$fullCompletionPercentage"
    :fullyIncompleteEmployees="$fullyIncompleteEmployees"
/>