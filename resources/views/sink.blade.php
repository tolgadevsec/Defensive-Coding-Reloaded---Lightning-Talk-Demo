<style>
    .notification {
        background:black;
        color:white;
        border-radius:5px;
        padding:8px;
        white-space:nowrap;
        width:550px;
    }
    .notification p {
        margin:0;
    }
    .title {
        color:yellow;
    }
    .suspicious {
        color:yellow;
        font-weight:bold;
    }
    .code {
        padding:10px;
        margin-top:5px;
        background:#262839;
    }
    .location {
        color:lime;
    }
    .tainted {
        background:yellow;
        color:black;
    }
    pre, .notification {
        font-size:16px;
    }
</style>

@if(!empty($suspiciousInput))
    <pre class="notification">
        <p class="title">[*] Sink access: {{ $eventClass }}</p>
        <p>{{ str_repeat('-',57) }}</p>

        @foreach ($suspiciousInput as $inputKey => $taintedSinkValueParts)
            <p>Value of <span class="suspicious">{{ $inputKey }}</span> might be tainted:</p>
            
            <p class="code">
                @foreach ($taintedSinkValueParts as $taintedValuePart)
                    @if($taintedValuePart['tainted'])
                        <span class="tainted">{{ $taintedValuePart['value'] }}</span>
                    @else
                        {{ $taintedValuePart['value'] }}
                    @endif
                @endforeach
            </p>
        @endforeach
        <p class="location">{{ str_replace(app('path'), '', $eventLocation['file']) }}:{{ $eventLocation['line'] }}</p>
        <p>{{ str_repeat('-',57) }}</p>
    </pre>    
@endif