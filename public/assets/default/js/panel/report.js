
function ajax(data, url, method, callback, callbackError) {
    $.ajax({
        url: url,
        data: data,
        type: method,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (json, text) {
            json = json;
            callback(json);
        },
        error: function (err) {
            callbackError == null ?
                toastr.error(err?.responseJSON?.message ?? "Tidak Dapat Mengakses Server")
                : callbackError(err);
        }
    });
}
function reloadJsonDataTable(dtable, json) {
    dtable.clear().draw();
    dtable.rows.add(json).draw();
}

function blockUI() {
    $.blockUI({
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        }
    });
}
function unblockUI() {
    $.unblockUI()
}

function toast(message, icon = "warning") {
    $.toast({
        heading: 'Information',
        text: message,
        position: 'top-right',
        icon: icon,
        loader: true,        // Change it to false to disable loader
    })
}