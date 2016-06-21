var Bpd = Bpd || {};

Bpd.Api = (function () {
    return {
        list: list,
        download: download,
        deleteList: deleteList,
        hello: hello,
    };

    function list() {
        $.ajax({
            type: 'GET',
            url: 'api/list',
            success: function (data) {
                console.log(data);
                console.log(data.files);
                Bpd.Ui.listFiles(data);
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });
    };

    function download(data) {
        $.ajax({
            type: 'PUT',
            url: 'api/download',
            data: data,
            success: function (data) {
                console.log(data);
                console.log(data.files);
                Bpd.Ui.listFiles(data);
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });
    };

    function deleteList(data) {
        $.ajax({
            type: 'DELETE',
            url: 'api/delete',
            data: data,
            success: function (data) {
                console.log(data);
                console.log(data.files);
                Bpd.Ui.listFiles(data);
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });
    };

    function hello(data) {
        $.getJSON('api/hello/' + data, function (data) {
            console.log(data);
        });
    };
})();

Bpd.Ui = (function () {
    return {
        listFiles: listFiles,
        onDownload: onDownload,
        onCheckAll: onCheckAll,
        onDelete: onDelete,
        onHelloWorld, onHelloWorld,
    };

    function listFiles(data) {
        var directory = data.directory;
        var files = data.files;
        $('#iFiles').empty();
        files.forEach(function (file, id) {
            var elt = $('<div>');
            var checkbox = $('<input>', {
                type: 'checkbox',
                class: 'cCheck',
                id: 'iCheck' + id,
                name: 'file',
                value: file
            });
            var label = $('<label>', {
                for: 'iCheck' + id
            });
            var link = $('<a>', {
                href: directory + file
            });
            elt.append(checkbox);
            elt.append(label);
            label.append(link);
            link.append(file)
            $("#iFiles").append(elt);
        });
        $('#iCheckAll').prop('checked', false);
    };

    function onDownload() {
        console.log('On Download');

        var form = $('#iDownload').serializeArray();
        console.log(form);
        console.log(form[0]);

        Bpd.Api.download($('#iDownload').serializeArray());
    };

    function onCheckAll() {
        $('.cCheck').prop('checked', $('#iCheckAll').prop('checked'));
    };

    function onDelete() {
        console.log('On Delete');

        var files = [];
        $('.cCheck').each(function () {
            if (this.checked) {
                files.push(this.value);
            }
        });
        console.log(files);

        if (files.length <= 0) {
            console.log('Nothing to delete');
            return;
        }
        var data = { files: files };

        Bpd.Api.deleteList(data);
    };

    function onHelloWorld() {
        Bpd.Api.hello('World');
    }
})();
