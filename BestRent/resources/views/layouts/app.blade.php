<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Best-Rent</title>
    <link rel="icon" type="image/png" href="{{ asset('images/best-rent-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite('resources/css/app.css')
    @yield('css')
</head>

<body>
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Keep alerts visible and make them collapsible/closable.
        document.querySelectorAll('.alert').forEach(function(alert) {
            if (alert.dataset.enhanced === '1') {
                return;
            }

            alert.dataset.enhanced = '1';

            const content = document.createElement('div');
            content.className = 'alert-content flex-grow-1';

            while (alert.firstChild) {
                content.appendChild(alert.firstChild);
            }

            const actions = document.createElement('div');
            actions.className = 'd-flex align-items-center gap-2';

            const toggleButton = document.createElement('button');
            toggleButton.type = 'button';
            toggleButton.className = 'btn btn-sm btn-outline-secondary py-0 px-2';
            toggleButton.textContent = 'Összecsuk';

            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.className = 'btn-close';
            closeButton.setAttribute('aria-label', 'Bezárás');

            let collapsed = false;

            toggleButton.addEventListener('click', function() {
                collapsed = !collapsed;
                content.style.display = collapsed ? 'none' : '';
                toggleButton.textContent = collapsed ? 'Kinyit' : 'Összecsuk';
            });

            closeButton.addEventListener('click', function() {
                alert.remove();
            });

            actions.appendChild(toggleButton);
            actions.appendChild(closeButton);

            const wrapper = document.createElement('div');
            wrapper.className = 'd-flex justify-content-between align-items-start gap-3';
            wrapper.appendChild(content);
            wrapper.appendChild(actions);

            alert.appendChild(wrapper);
        });
    </script>
    @yield('js')
</body>

</html>
