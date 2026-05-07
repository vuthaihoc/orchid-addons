<div id="accordion" role="tablist" aria-multiselectable="true">
    @forelse($logs as $key => $log)
        <div class="card mb-0 pb-0">
            <div class="card-header bg-{{ $log['level_class'] }}" role="tab" id="heading{{ $key }}">
                <a role="button" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}" class="text-white">
                    <i class="la la-{{ $log['level_img'] }}"></i>
                    <span>[{{ $log['date'] }}]</span>
                    {{ Str::limit($log['text'], 150) }}
                </a>
            </div>
            <div id="collapse{{ $key }}" class="panel-collapse collapse p-3" role="tabpanel" aria-labelledby="heading{{ $key }}">
                <div class="panel-body">
                    @php
    $text = $log['text'];

    preg_match('/(\{.*\}|\[.*\])$/s', $text, $matches);

    $prettyJson = null;

    if (!empty($matches[0])) {

        $decoded = json_decode($matches[0], true);

        if (json_last_error() === JSON_ERROR_NONE) {

            $prettyJson = json_encode(
                $decoded,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );

            $text = str_replace($matches[0], '', $text);
        }
    }
@endphp

<p>{{ trim($text) }}</p>

@if($prettyJson)
    <pre><code class="json">{{ $prettyJson }}</code></pre>
@endif
                    <pre><code class="php">
              {{ trim($log['stack']) }}
            </code></pre>
                </div>
            </div>
        </div>
    @empty
        <h3 class="text-center">No Logs to display.</h3>
    @endforelse
</div>

@section('scripts')
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>hljs.highlightAll();</script>
@endsection
