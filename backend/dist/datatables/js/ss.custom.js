$(document).ready(function () {
    var table = $('.example').DataTable({
        "aaSorting": [],
        language: {
            url: baseurl + '/backend/dist/datatables/js/' + current_language + '.json'
        },

        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: false,
        dom: "Bfrtip",
        buttons: [

            {
                extend: 'copyHtml5',
                text: '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible:not(.axnCol)'
                }
            },

            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',

                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible:not(.axnCol)'
                }
            },

            {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible:not(.axnCol)'
                }
            },

            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                title: $('.download_label').html(),
                orientation: ($('.download_label').attr('data-orientation') || 'portrait'),
                exportOptions: {
                    columns: ':visible:not(.axnCol)'
                },
                customize: function (doc) {
                    //console.log(doc);
                }
            },

            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                title: $('.download_label').html(),
                customize: function (win) {
                    $(win.document.body)
                        .css('font-size', '10pt')
                        .prepend('<div  style=" top:0; display: flex;align-items: center;justify-content: center;flex-direction: column">' +
                            '<img width="100" height="100" src="' + logo + '" />' +
                            '<h3>' + schoolName + '</h3>' +
                            '</div>'
                        );
                    ;
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                },
                exportOptions: {
                    columns: ':visible:not(.axnCol)'
                },

            },

            {
                extend: 'colvis',
                text: '<i class="fa fa-columns"></i>',
                titleAttr: 'Columns',
                title: $('.download_label').html(),
                postfixButtons: ['colvisRestore']
            },
        ]
    });

    $('.longtable').DataTable({
        "aaSorting": [],

        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: false,
        dom: "Bfrtip",
        buttons: [

            {
                extend: 'copyHtml5',
                text: '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible:not(.axnCol)'
                }
            },

            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',

                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible:not(.axnCol)'
                }
            },

            {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible:not(.axnCol)'
                }
            }
        ]
    });
});


/*--dropify--*/
$(document).ready(function () {
    // Basic
    var drEvent = $('.filestyle').dropify({
        'errorsPosition': 'outside',
        messages: {
            'error': ''
        }
    });

    drEvent.on('dropify.beforeClear', function (event, element) {
        return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
    });

    drEvent.on('dropify.afterClear', function (event, element) {
        var fld_name = $(event.target).attr('name');
        if (fld_name.length > 0) {
            var hf = $("input[name='old_" + fld_name + "']:hidden");
            if (hf.length < 1) {
                var tmp = $(event.target).attr('data-old-hf');
                if (tmp && tmp.length > 0) {
                    hf = $("input[name='" + tmp + "']:hidden");
                }
            }
            if (hf.length > 0) {
                hf.val('');
            }
        }
    });

    drEvent.on('dropify.errors', function (event, element) {
        //
    });
});
/*--end dropify--*/

/*--nprogress--*/
$('body').show();
$('.version').text(NProgress.version);
NProgress.start();
setTimeout(function () {
    NProgress.done();
    $('.fade').removeClass('out');
}, 1000);
/*--nprogress--*/    