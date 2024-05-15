<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <head profile="https://www.w3.org/2005/10/profile">
        <link rel="icon" type="image/jpg" href="{{ asset('/img/logo2.jpg') }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
        <script src="{{ asset('/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>

        <script src="{{ asset('/ar/ar-js-core.js') }}"></script>
        <script src="{{ asset('/ar/ar-js-pdf.js') }}"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
    <script>
        async function exportPdf(reportName, reportUrl, exportName) {
            const reportResponse = await fetch("/reports/" + reportName);
            const report = await reportResponse.json();
            report.DataSources[0].ConnectionProperties.ConnectString = "jsondoc=" + reportUrl;
            var ARJS = GC.ActiveReports.Core;
            var PDF = GC.ActiveReports.PdfExport;

            var onProgressCallback = function(pageCount) {
                console.log(pageCount);
            }
            var settings = {
                info: {
                    creator: 'Pedro de la Vega'
                }
            }
            var pageReport = new ARJS.PageReport();
            pageReport.load(report)
                .then(() => pageReport.run())
                .then(pageDocument => PDF.exportDocument(pageDocument, settings, onProgressCallback))
                .then(result => result.download(exportName));
        }
        window.addEventListener('generarPdfReport', event => {
            exportPdf(event.detail.reportName + ".rdlx-json", event.detail.reportUrl, event.detail.exportName);
        })
    </script>
</body>

</html>
