<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/jpg" href="{{ asset('/img/logo2.jpg') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script src="{{ asset('/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('/ar/ar-js-core.js') }}"></script>
    <script src="{{ asset('/ar/ar-js-pdf.js') }}"></script>
    <script src="https://unpkg.com/@popperjs/core@2" charset="utf-8"></script>
    <!-- Scripts -->
    <wireui:scripts />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

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
    @livewireChartsScripts
    <div class="hidden bg-gray-900 border-0 mr-3 block mb-1 z-50 font-normal leading-normal text-sm max-w-xs text-left no-underline break-words rounded-lg"
        id="tooltipGeneric">
        <div>
            <div id='tooltiptext'
                class="bg-gray-900 text-white font-bold p-2 border border-solid border-gray-900 rounded-lg">

            </div>
        </div>
        <div id="arrow" data-popper-arrow></div>
    </div>
    <script>
        function addTooltip(event, placement, popoverID, title) {
            document.getElementById(popoverID).classList.add("hidden");
            let element = event.target;
            while (element.nodeName !== "BUTTON") {
                element = element.parentNode;
            }
            Popper.createPopper(element, document.getElementById(popoverID), {
                delay: 100,
                placement: placement,
                modifiers: [{
                        name: 'offset',
                        options: {
                            offset: [0, 4],
                        },
                    },
                    {
                        name: 'arrow',
                        options: {
                            padding: 5, // 5px from the edges of the popper
                        },
                    },
                ],
            });
            var e = document.getElementById('tooltiptext');
            e.innerHTML = title;
            document.getElementById(popoverID).classList.remove("hidden");
        }

        function removeTooltip(event, placement, popoverID, title) {
            document.getElementById(popoverID).classList.add("hidden");
        }
    </script>
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
