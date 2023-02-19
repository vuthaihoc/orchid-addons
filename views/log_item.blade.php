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
                    <p>{{$log['text']}}</p>
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
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
@endsection