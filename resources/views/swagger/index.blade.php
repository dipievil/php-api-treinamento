<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API Documentation</title>
    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset('swagger-ui.css') }}">
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="{{ l5_swagger_asset('swagger-ui-bundle.js') }}"></script>
    <script src="{{ l5_swagger_asset('swagger-ui-standalone-preset.js') }}"></script>
    <script>
        window.onload = function() {
            // Begin Swagger UI call region
            const ui = SwaggerUIBundle({
                url: "{{ l5_swagger_asset('swagger.json') }}",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            });
            // End Swagger UI call region
            window.ui = ui;
        };
    </script>
</body>
</html>
