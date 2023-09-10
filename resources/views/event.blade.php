<style>
    .notification {
        background:black;
        color:white;
        border-radius:5px;
        padding:8px;
        white-space:nowrap;
        width:530px;
    }
    .notification p {
        overflow:hidden;
        text-overflow:ellipsis;
        margin:0;
    }
    .title = {
        color:yellow;
    }
    pre, .notification {
        font-size:16px;
    }
</style>
<pre class="notification">
  <p class="title">[*] Suspicious event: {{ $eventClass }}</p>
  <p>{{ $eventMessage }}</p>
</pre>