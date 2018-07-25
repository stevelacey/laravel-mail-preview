<div id="MailPreviewDriverBox" style="
    position: absolute;
    top: 0;
    z-index: 99999;
    background: #fff;
    border: solid 1px #ccc;
    padding: 15px;
">
    An email was just sent: <a href="{{ $url }}">Preview Sent Email</a>
</div>

@if($timeout > 0)
    <script type="text/javascript">
        setTimeout(
            function () {
                document.body.removeChild(document.getElementById('MailPreviewDriverBox'));
            },
            {{ $timeout }}
        );
    </script>
@endif
