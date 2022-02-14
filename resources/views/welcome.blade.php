<!DOCTYPE html>

<html>

    <head>

        <title>Laravel WebSocket Example</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <style class="text/css">
                body{
                background: #efefef;
                }
                pre {
                background-color: ghostwhite;
                border: 1px solid silver;
                padding: 10px 20px;
                margin: 20px;
                border-radius: 4px;
                width: 50%;
                margin-left: auto;
                margin-right: auto;
                }

        </style>
        <script src='./js/app.js'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    </head>

    <body>
        
    <pre><code id=socket-received-data></code></pre>
      
<script>


    Object.prototype.prettyPrint = function(){
        var jsonLine = /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/mg;
        var replacer = function(match, pIndent, pKey, pVal, pEnd) {
            var key = '<span class="json-key" style="color: brown">',
                val = '<span class="json-value" style="color: navy">',
                str = '<span class="json-string" style="color: olive">',
                r = pIndent || '';
            if (pKey)
                r = r + key + pKey.replace(/[": ]/g, '') + '</span>: ';
            if (pVal)
                r = r + (pVal[0] == '"' ? str : val) + pVal + '</span>';
            return r + (pEnd || '');
        };

        return JSON.stringify(this, null, 3)
                .replace(/&/g, '&amp;').replace(/\\"/g, '&quot;')
                .replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(jsonLine, replacer);
    }

    //listen event and channel
    window.Echo.channel('we_one')
        .listen('SocketEvent', (e) => {
        console.log(e);
        $res = e.response;     
        document.querySelector('#socket-received-data').innerHTML = $res.prettyPrint(); ;
     
    });

</script>

</body>


</html>