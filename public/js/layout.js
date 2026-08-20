(function (window, document, $) {
    'use strict';

    function populateSelect(select, items, placeholder) {
        select.innerHTML = placeholder ? '<option value="">' + placeholder + '</option>' : '';
        Object.keys(items).forEach(function (key) {
            var option = document.createElement('option');
            option.value = key;
            option.textContent = items[key];
            select.appendChild(option);
        });
    }

    function bindDependentSelect(sourceName, targetName, urlBuilder, placeholder) {
        var source = document.querySelector('select[name="' + sourceName + '"]');
        var target = document.querySelector('select[name="' + targetName + '"]');
        if (!source || !target) return;

        source.addEventListener('change', function () {
            if (!source.value) {
                target.innerHTML = '';
                return;
            }
            fetch(urlBuilder(source.value), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (response) {
                    if (!response.ok) throw new Error('Request failed');
                    return response.json();
                })
                .then(function (items) { populateSelect(target, items, placeholder); })
                .catch(function () { target.innerHTML = ''; });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindDependentSelect('state', 'lga', function (id) { return '/ajax/address/state/' + id + '/get-lgas'; });
        bindDependentSelect('section', 'class', function (id) { return '/ajax/section/' + id + '/get-classes'; }, 'Select Student Class');
        bindDependentSelect('class', 'subject', function (id) { return '/ajax/section/class/' + id + '/get-subjects'; }, 'Select Student Subject');

        var picture = document.getElementById('picture');
        var preview = document.getElementById('picture_preview_container');
        if (picture && preview) {
            picture.addEventListener('change', function () {
                if (!picture.files || !picture.files[0]) return;
                var reader = new FileReader();
                reader.onload = function (event) { preview.src = event.target.result; };
                reader.readAsDataURL(picture.files[0]);
            });
        }

        if ($ && document.getElementById('myTable')) {
            var dataTables = document.createElement('script');
            dataTables.src = 'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js';
            dataTables.onload = function () { $('#myTable').DataTable(); };
            document.body.appendChild(dataTables);
        }
    });

    window.printDiv = function (id) {
        var printable = document.getElementById(id);
        if (!printable) return;
        var original = document.body.innerHTML;
        document.body.innerHTML = printable.innerHTML;
        window.print();
        document.body.innerHTML = original;
        window.location.reload();
    };
})(window, document, window.jQuery);
