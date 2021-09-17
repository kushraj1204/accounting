(function (jQuery) {
    "use strict";

    function NeemaExamWeightageForm(instance, options) {
        let defaultOptions = {
            $classList: null,
            $examList: null,
            $btnAdd: null,
            $btnNewRow: null,
            $btnSave: null,
            $btnDelete: null,
            $title: null,
            $container: null,
            template: null,
            examList: [],
            canAdd: 0,
            language: {
                confirmDelete: 'Are you sure?',
                emptyWeightage: 'No weightage set',
                should_be_number: 'Weightage percent should be a positive number',
                total_weight_100: 'Total weightage should be 100%',
                duplicate: 'Duplicate',
                loading: 'Loading',
            }
        };
        this.instance = instance;
        this.options = jQuery.extend(true, {}, defaultOptions, options);
        this.$el = jQuery(this.instance);
        this.processing = false;
        this.init();
    }

    NeemaExamWeightageForm.prototype = {
        init: function () {
            let self = this;
            self.setupEventListeners();
        },
        getExamWeightageByClass: function (success, fail, always) {
            let self = this;
            let data = {
                class_id: self.options.$classList.val(),
                exam_id: self.options.$examList.val()
            };
            jQuery.ajax({
                url: base_url + 'admin/examweightage/getValuesByClass',
                method: 'GET',
                dataType: 'JSON',
                data: data
            }).done(function (res) {
                success(res);
            }).fail(function (req, status, error) {
                fail(error);
            }).always(function () {
                always();
            });
        },
        setupEventListeners: function () {
            let self = this;
            self.options.$btnAdd.off().on("click", function (e) {
                if (self.processing) {
                    return;
                }
                e.preventDefault();
                let selectedClass = self.options.$classList.find("option:selected").text();
                let selectedExam = self.options.$examList.find("option:selected").text();
                let selectedExamVal = self.options.$examList.val();
                // let examList = self.options.examList.filter(function (exam) {
                //     return exam.id != selectedExamVal;
                // });
                let examList = self.options.examList;
                if (examList.length > 0) {
                    self.options.$title.text(selectedExam);
                    self.$el.html(self.options.language.loading);
                    self.options.$container.show();
                    self.processing = true;
                    self.getExamWeightageByClass(function (res) {
                        if (res.length < 1) {
                            self.$el.html('<div class="alert alert-info">' + self.options.language.emptyWeightage + '</div>');
                            self.options.$btnSave.hide();
                            self.options.$btnDelete.hide();
                        } else {
                            let $row = self.options.template({
                                canAdd: self.options.canAdd,
                                rows: res,
                                exams: self.options.examList
                            });
                            self.$el.html($row);
                            self.options.$btnSave.show();
                            self.options.$btnDelete.show();
                        }
                        self.$el.append("<input type='hidden' name='class_id' value='" + self.options.$classList.val() + "'>");
                        self.$el.append("<input type='hidden' name='exam_id' value='" + self.options.$examList.val() + "'>");
                    }, function (error) {
                        self.$el.html(error);
                    }, function () {
                        self.processing = false;
                    });
                }
            });
            self.options.$btnNewRow.off().on("click", function (e) {
                e.preventDefault();
                let $row = self.options.template({
                    canAdd: self.options.canAdd,
                    rows: [{weightage: '', weight_exam_id: 0}],
                    exams: self.options.examList
                });
                //show delete button
                if (self.$el.find(".form-group").length > 0) {
                    $row = jQuery($row).find(".btn-remove-row").closest('div').css('display', 'block').end().end().wrapAll('<div/>').parent().html()
                } else {
                    self.$el.find('div.alert').remove();
                }
                self.$el.append($row);
                self.options.$btnSave.show();
                self.options.$btnDelete.show();
            });
            self.options.$btnSave.off().on("click", function (e) {
                e.preventDefault();
                self.save();
            });
            self.options.$btnDelete.off().on("click", function (e) {
                e.preventDefault();
                if (self.processing) {
                    return;
                }
                if (confirm(self.options.language.confirmDelete)) {
                    self.processing = true;
                    let data = self.$el.serializeArray();
                    jQuery.ajax({
                        url: base_url + 'admin/examweightage/delete',
                        method: "POST",
                        dataType: "JSON",
                        data: data
                    }).done(function (res) {
                        if (res.success) {
                            self.$el.empty().html('<div class="alert alert-info">' + self.options.language.emptyWeightage + '</div>');
                            self.options.$btnSave.hide();
                            self.options.$btnDelete.hide();
                        } else {
                            alert(res.message);
                        }
                    }).fail(function (req, status, error) {
                        alert(error);
                    }).always(function () {
                        self.processing = false;
                    });
                }
            });
            jQuery(document).on("click", ".btn-remove-row", function (e) {
                e.preventDefault();
                jQuery(this).closest(".form-group").remove();
            });
        },
        save: function () {
            let self = this;
            if (self.processing) {
                return;
            }
            if (!self.validateSave()) {
                return;
            }
            let data = self.$el.serializeArray();
            self.processing = true;
            let oldText = self.options.$btnSave.text();
            self.options.$btnSave.text("Saving...");
            self.$el.find("input, select").prop("readonly", true);
            jQuery.ajax({
                url: base_url + 'admin/examweightage/save',
                method: 'POST',
                dataType: 'JSON',
                data: data
            }).done(function (res) {
                if (!res.success) {
                    alert(res.message);
                }
            }).fail(function (req, status, error) {
                alert(error);
            }).always(function () {
                self.processing = false;
                self.$el.find("input, select").prop("readonly", false);
                self.options.$btnSave.text(oldText);
            });
        },
        validateSave: function () {
            let self = this;
            let valid = true;
            //check duplicate exam
            for (let i = 0, j = self.options.examList.length; i < j; i++) {
                let exam = self.options.examList[i];
                let $sel = self.$el.find("select[name='weight_exam[]'] option[value='" + exam.id + "']:selected");
                if ($sel.length > 1) {
                    valid = false;
                    alert(self.options.language.duplicate + ":" + exam.name);
                    break;
                }
            }
            if (!valid) {
                return valid;
            }
            //validate total weightage
            let $weightages = self.$el.find('input[name="weightage[]"]');
            let total = 0;
            for (let i = 0, j = $weightages.length; i < j; i++) {
                let v = jQuery($weightages[i]).val().trim();
                if (isNaN(v)) {
                    valid = false;
                    alert(self.options.language.should_be_number);
                    break;
                }
                v = parseFloat(v);
                if (v <= 0) {
                    valid = false;
                    alert(self.options.language.should_be_number);
                    break;
                }
                total += v;
            }
            if (!valid) {
                return valid;
            }
            if (parseInt(total) !== 100) {
                valid = false;
                alert(self.options.language.total_weight_100);
            }
            return valid;
        }
    };

    jQuery.fn.NeemaExamWeightageForm = function (options) {
        let args = Array.prototype.slice.call(arguments, 1);
        let plgName = "NeemaExamWeightageForm";
        return this.each(function () {
            let inst = jQuery.data(this, plgName);
            if (typeof inst === "undefined") {
                if (typeof options === "undefined" || typeof options == "string" || options instanceof String) {
                    throw "invalid options passed while creating new instance.";
                }
                if (typeof options.template !== "function") {
                    throw "template should be compiled handlebars template";
                }
                let p = new NeemaExamWeightageForm(this, options);
                jQuery.data(this, plgName, p);
            } else if (typeof options !== "undefined") {
                if (typeof inst[options] === "function") {
                    inst[options].apply(inst, args);
                }
            }
        });
    };
})(jQuery);
