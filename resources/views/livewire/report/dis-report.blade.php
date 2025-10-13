<div>
    <div class="container-fluid table-responsive">
        <table id="returnTable" class="data-table table table-striped table-hover display table-bordered">
            <thead class="text-white text-center">
                <tr>
                    <th class="text-center">{{ __('SL') }}</th>
                    <th class="text-center">{{ __('Client Type') }}</th>
                    <th class="text-center">{{ __('Connection Type') }}</th>
                    <th class="text-center">{{ __('Client Name') }}</th>
                    <th class="text-center">{{ __('Bandwidth Distribution Point') }}</th>
                    <th class="text-center">{{ __('Connectivity Type') }}</th>
                    <th class="text-center">{{ __('Activation Date') }}</th>
                    <th class="text-center">{{ __('Bandwidth Allocation') }}</th>
                    <th class="text-center">{{ __('Allocated IP') }}</th>
                    <th class="text-center">{{ __('Division') }}</th>
                    <th class="text-center">{{ __('District') }}</th>
                    <th class="text-center">{{ __('Thana') }}</th>
                    <th class="text-center">{{ __('Address') }}</th>
                    <th class="text-center">{{ __('Client Mobile') }}</th>
                    <th class="text-center">{{ __('Unit Price') }}</th>
                    <th class="text-center">{{ __('Clint Email') }}</th>
                    <th class="text-center">{{ __('Selling Price Excluding VAT') }}</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var table = $('#returnTable').DataTable({
                processing: true,
                // serverSide: true,
                // responsive: true,
                pagingType: 'full_numbers',
                pageLength: 10,
                lengthChange: true,
                searchable: true,
                select: true,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                dom: 'Bfrtip',
                buttons: ['pageLength',
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-journal-arrow-down"></i> (Excel)', // For Bootstrap Icons
                        titleAttr: 'Export to Excel',
                        exportOptions: {
                            columns: [0,':visible']
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-filetype-pdf"></i> (PDF)', // For Bootstrap Icons
                        titleAttr: 'Export to PDF',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [0,':visible']
                        },
                        customize: function (doc) {
                            var col = doc.content[1].table.body;
                            for (i = 1; i < col.length; i++) {
                                col[i][0]["text"] = i;
                            }
                        },
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bi bi-journal-arrow-down"></i> (Selected Download)', // For Bootstrap Icons
                        exportOptions: {
                            modifier: {
                                selected: true
                            }
                        },
                    },{
                        extend: 'print',
                        text: '<i class="bi bi-printer-fill"></i> (Print)', // Bootstrap Icons
                        exportOptions: {
                            columns: [0, ':visible']
                        },
                        customize: function (win) {
                            // Add numbering for each row in the printed table
                            $(win.document.body).find('table tbody tr').each(function (index) {
                                $(this).find('td:first').text(index + 1);
                            });
                            // Center the title in the print preview
                            $(win.document.body).find('h1').css('text-align', 'center');
                        }
                    },
                    'colvis'
                ],
                ajax: {
                    url: "{{ route('dis-report-table') }}",
                },
                columns: [
                    { data: null, defaultContent: '', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'official.client_type', name: 'official.client_type', className: 'text-start' },
                    { data: 'official.connection_type', name: 'official.connection_type', className: 'text-start' },
                    { data: 'customer_name', name: 'customer_name', className: 'text-start' },
                    { data: 'official.distribution_location', name: 'official.distribution_location', className: 'text-start' },
                    { data: 'official.connectivity_type', name: 'official.connectivity_type', className: 'text-start' },
                    { data: 'connection_date', name: 'connection_date',className: 'text-start', render: function (data, type, row) {
                        const date = new Date(data);
                        const options = { day: '2-digit', month: 'short', year: 'numeric' };
                        return date.toLocaleDateString('en-GB', options); // "01 Jan 2025"
                    }, },
                    { data: 'package.description', name: 'package.description', className: 'text-start' },
                    { data: 'ppp_remote_ip', name: 'ppp_remote_ip', className: 'text-start' },
                    { data: 'division', name: 'division', className: 'select-search text-start' },
                    { data: 'district', name: 'district', className: 'select-search text-start' },
                    { data: 'thana', name: 'thana', className: 'select-search text-start' },
                    { data: 'area', name: 'area', className: 'select-search text-start' },
                    { data: 'mobile', name: 'mobile', className: 'select-search text-start' },
                    { data: 'package.price', name: 'package.price', className: 'text-start' },
                    { data: 'email', name: 'email', className: 'select-search text-start' },
                    { data: 'package.price', name: 'package.price', className: 'text-start' },
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    api.column(0, { page: 'current' }).nodes().each(function(cell, i) {
                        var pageInfo = api.page.info();
                        var serial = pageInfo.start + i + 1;
                        $(cell).html(serial);
                    });
                },
            });

            $('#search, .closeModal').click(function () {
                table.ajax.reload();
            });
        });
    </script>
@endpush
