(function (jQuery) {
    "use strict";

    function NeemaMarksheetList(instance, options) {
        let defaultOptions = {
            data: null,
            $classList: null,
            $sectionList: null,
            $examList: null,
            $btnSearch: null,
            $dataTableHolder: null,
            $dataTable: null,
            $generatorForm: null
        };
        this.instance = instance;
        this.options = jQuery.extend(true, {}, defaultOptions, options);
        this.$el = jQuery(this.instance);
        this.processing = false;
        this.dataTable = null;
        this.exam_id = 0;
        this.exam_result_publish_id = 0;
        this.exam_start = '';
        this.exam_end = '';
        this.exam_publish_id = 0;
        this.init();
    }

    NeemaMarksheetList.prototype = {
        init: function () {
            let self = this;
            self.setupEventListeners();
        },
        setupEventListeners: function () {
            let self = this;
            self.options.$classList.off().on("change", function () {
                self.options.$dataTableHolder.hide();
                self.options.$sectionList.children('option:not(:first)').remove();
                self.options.$examList.children('option:not(:first)').remove();
                let val = jQuery(this).val();
                if (val > 0 && self.isNotUndefined(self.options.data.sections) && self.isNotUndefined(self.options.data.sections[val])) {
                    let sections = self.options.data.sections[val];
                    let data = sections.map(function (section) {
                        return {value: section.section_id, text: section.section};
                    });
                    self.populateSelect(self.options.$sectionList, data);
                }
            });
            self.options.$sectionList.off().on("change", function () {
                self.options.$dataTableHolder.hide();
                self.options.$examList.children('option:not(:first)').remove();
                let c = self.options.$classList.val();
                let s = jQuery(this).val();
                if (s > 0 && self.isNotUndefined(self.options.data.exams) && self.isNotUndefined(self.options.data.exams[c]) && self.isNotUndefined(self.options.data.exams[c][s])) {
                    let exams = self.options.data.exams[c][s];
                    let data = exams.map(function (exam) {
                        return {value: exam.publish_id, text: exam.exam_name, data: {eid: exam.exam_id}};
                    });
                    self.populateSelect(self.options.$examList, data);
                }
            });
            self.options.$examList.off().on("change", function () {
                self.options.$dataTableHolder.hide();
            });

            self.options.$btnSearch.off().on("click", function () {
                let c = self.options.$classList.val();
                let s = self.options.$sectionList.val();
                let e = self.options.$examList.val();
                self.markFieldError(self.options.$classList, c > 0);
                self.markFieldError(self.options.$sectionList, s > 0);
                self.markFieldError(self.options.$examList, e > 0);
                if (c > 0 && s > 0 && e > 0) {
                    self.exam_result_publish_id = e;
                    self.exam_id = self.options.$examList.find('option:selected').attr('data-eid');
                    self.showStudentList(c, s);
                }
            });
            jQuery(document).on('click', '.genBtn', function (e) {
                e.preventDefault();
                let d = jQuery(this).attr('data-data');
                try {
                    let data = JSON.parse(d);
                    let flds = [
                        '<input type="hidden" name="start" value="' + self.exam_start + '">',
                        '<input type="hidden" name="end" value="' + self.exam_end + '">',
                        '<input type="hidden" name="student_id" value="' + data.student_id + '">',
                        '<input type="hidden" name="exam_id" value="' + self.exam_id + '">',
                        '<input type="hidden" name="exam_publish_id" value="' + self.exam_publish_id + '">',
                        '<input type="hidden" name="result_publish_id" value="' + self.exam_result_publish_id + '">'
                    ];
                    self.options.$generatorForm.html(flds.join(''));
                    self.options.$generatorForm.submit();
                } catch (e) {
                    alert("Error while parsing data");
                }
            });
            jQuery(document).on('click', '#generateRank', function(e) {
                let data = {
                    'exam_id': self.exam_id,
                    'result_publish_id': self.exam_result_publish_id
                };
                jQuery(e.target).attr('disabled', 'disabled');
                jQuery('#genMsg').show();
                jQuery.ajax({
                    url: base_url + 'admin/marksheet/generateRank',
                    data: data,
                    method: 'POST',
                    dataType: 'JSON',
                    success: function (res) {
                        //
                    },
                    error: function (err) {
                        //
                    },
                    complete: function() {
                        jQuery('#genMsg').hide();
                        jQuery(e.target).removeAttr('disabled');
                    }
                });
            });
        },
        showStudentList: function (class_id, section_id) {
            let self = this;
            self.options.$dataTableHolder.show();
            if ($.fn.DataTable.isDataTable(self.dataTable)) {
                self.dataTable.clear().draw();
                self.dataTable.ajax.reload();
                self.dataTable.columns.adjust().draw();
                return;
            }
            let actionCol = {
                "data": null,
                "title": "Action",
                "orderable": false,
                "searchable": false,
                "className": "datatable-actions",
                "defaultContent": '',
                //"width": "120px",
                render: function (data, type, row) {
                    let actionDOM = '<button type="button" class="genBtn" data-data=\'' + JSON.stringify(data) + '\'><i class="fa fa-gear"></i></button>';
                    return actionDOM;
                }
            };
            self.dataTable = self.options.$dataTable.DataTable({
                ajax: {
                    url: base_url + 'admin/marksheet/getExamAttendedStudents',
                    method: 'GET',
                    dataType: 'JSON',
                    contentType: "application/json",
                    data: function (d) {
                        d.erid = self.exam_result_publish_id;
                        d.cid = class_id;
                        d.sid = section_id;
                        d.eid = self.exam_id;
                    },
                    dataSrc: function (res) {
                        self.exam_start = res.exam_schedule.start;
                        self.exam_end = res.exam_schedule.end;
                        self.exam_publish_id = res.exam_schedule.ep_id;
                        return res.students;
                    }
                },
                columns: [
                    {data: 'admission_no'},
                    {data: 'roll_no'},
                    {data: 'student_name'},
                    {data: 'guardian_name'},
                    {data: 'guardian_phone'},
                    {data: 'guardian_relation'},
                    actionCol
                ],
                language: {
                    url: baseurl + '/backend/dist/datatables/js/' + current_language + '.json'
                },
                processing: false,
                pageLength: 10
            });
        },
        populateSelect: function ($select, data) {
            let self = this;
            let options = [];
            jQuery.each(data, function (i, d) {
                let ds = [];
                if (self.isNotUndefined(d.data) && jQuery.isPlainObject(d.data)) {
                    jQuery.each(d.data, function (x, y) {
                        ds.push("data-" + x + "=" + y);
                    });
                }
                options.push("<option value='" + d.value + "' " + ds.join(' ') + ">" + d.text + "</option>");
            });
            $select.append(options.join(''));
        },
        isNotUndefined: function (v) {
            return typeof v !== 'undefined';
        },
        markFieldError: function ($field, is_valid) {
            if (!is_valid) {
                $field.closest('.form-group').addClass('has-error');
            } else {
                $field.closest('.form-group').removeClass('has-error');
            }
        }
    };

    jQuery.fn.NeemaMarksheetList = function (options) {
        let args = Array.prototype.slice.call(arguments, 1);
        let plgName = "NeemaMarksheetList";
        return this.each(function () {
            let inst = jQuery.data(this, plgName);
            if (typeof inst === "undefined") {
                if (typeof options === "undefined" || typeof options == "string" || options instanceof String) {
                    throw "invalid options passed while creating new instance.";
                }
                let p = new NeemaMarksheetList(this, options);
                jQuery.data(this, plgName, p);
            } else if (typeof options !== "undefined") {
                if (typeof inst[options] === "function") {
                    inst[options].apply(inst, args);
                }
            }
        });
    };
})(jQuery);
