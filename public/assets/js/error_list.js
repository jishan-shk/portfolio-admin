$(function (e) {
    let errorlogDataTable;

    let errorlogRoute = window.origin + '/error-logs-list';
    errorlogDataTable = $('#error-log-datatable').DataTable({
        language: {
            searchPlaceholder: 'Search...',
            scrollX: "100%",
            sSearch: '',
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: errorlogRoute,
            method: 'get',
        },
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex'
        },
            {
                data: 'url',
                name: 'url'
            },
            {
                data: 'line',
                name: 'line'
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'file',
                name: 'file'
            },
            {
                data: 'message',
                name: 'message'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'action',
                name: 'action'
            },
        ]
    });

});
