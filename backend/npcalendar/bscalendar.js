(function (jQuery) {
    "use strict";

    function FullBSCalendar(instance, options) {
        let defaultOptions = {
            template: "<div>Default template</div>",
            events: []
        };
        this.instance = instance;
        this.options = jQuery.extend(true, {}, defaultOptions, options);
        this.$el = jQuery(this.instance);
        this.calendarData = {
            bsMonthsEN: ["Baisakh", "Jestha", "Asar", "Shrawan", "Bhadra", "Ashwin", "Kartik", "Mangshir", "Poush", "Magh", "Falgun", "Chaitra"],
            bsMonths: ["बैशाख", "जेठ", "असार", "सावन", "भदौ", "असोज", "कार्तिक", "मंसिर", "पौष", "माघ", "फागुन", "चैत"],
            bsDays: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            devanagariNumbers: {
                0: '०',
                1: '१',
                2: '२',
                3: '३',
                4: '४',
                5: '५',
                6: '६',
                7: '७',
                8: '८',
                9: '९'
            },
            minBsYear: 1970,
            maxBsYear: 2100
        };
        let m = moment();
        this.eventDataObj = {};
        this.eventDataArr = [];
        this.datePicker = moment();
        this.bsToday = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
        this.init();
    }

    FullBSCalendar.prototype = {
        init: function () {
            let self = this;
            if (!self.options.events) {
                self.options.events = [];
            }
            self.setupEventData();
            self.addEventsListeners();
            self.render();
        },
        setupEventData: function () {
            let self = this;
            let events = self.options.events;
            for (let i = 0, j = events.length; i < j; i++) {
                let event = events[i];
                let sd = moment(event.start, "YYYY-MM-DD HH:mm:ss");
                let ed = moment(event.end, "YYYY-MM-DD HH:mm:ss");
                event.start_date_m = sd;
                event.end_date_m = ed;
                if (typeof self.eventDataObj[sd.format('YYYY-MM-DD')] === 'undefined') {
                    self.eventDataObj[sd.format('YYYY-MM-DD')] = [];
                }
                self.eventDataObj[sd.format('YYYY-MM-DD')].push(event);
                self.eventDataArr.push(event);
            }
        },
        getEventHtml: function (event) {
            let start = 'fc-start';
            let end = 'fc-end';
            return `<a class="fc-day-grid-event fc-h-event fc-event ${start} ${end}" style="background-color:${event.backgroundColor};border-color:${event.borderColor}" title="" data-toggle="tooltip" data-original-title="${event.title}" data-type="${event.event_type}" data-id="${event.id}">
<div class="fc-content">
<span class="fc-title">${event.title}</span>
</div>
</a>`;
        },
        addEventsListeners: function () {
            let self = this;
            jQuery(document).on("click", ".fc-prev-button", function () {
                self.datePicker.subtract(1, 'month');
                self.render();
            });
            jQuery(document).on("click", ".fc-next-button", function () {
                self.datePicker.add(1, 'month');
                self.render();
            });
            jQuery(document).on("click", ".fc-today-button", function () {
                self.datePicker = moment();
                self.render();
            });
            jQuery(document).on("click", ".fc-new-event, .fc-day-grid-event", function (e) {
                e.stopPropagation();
                if (jQuery(e.currentTarget).hasClass('fc-day-grid-event')) {
                    if (jQuery(e.currentTarget).attr('data-type') != 'task') {
                        view_event2(jQuery(e.currentTarget).attr('data-id'));
                    }
                    return;
                }

                jQuery("#input-field").val('');
                jQuery("#desc-field").text('');
                jQuery(".event_date").val(self.bsToday.bsYear + "-" + self.bsToday.bsMonth + "-" + self.bsToday.bsDate);
                jQuery(".event_time").timepicker('setTime', '10:00 AM');
                jQuery('#newEventModal').modal('show');
            });
            jQuery(document).on("click", ".fc-day-top, .fc-day-foot, .evt_empty_td", function (e) {
                e.stopPropagation();
                let $currentTarget = jQuery(e.currentTarget);
                let ad_date = $currentTarget.attr('data-date');
                let bs_date = $currentTarget.attr('data-bsdate');
                if ($currentTarget.hasClass('evt_empty_td')) {
                    let $cp = $currentTarget.closest('tr').find('td');
                    let tp = $currentTarget.closest('table').find('.fc-day-top');
                    let prevSibs = $currentTarget.prevAll();
                    let a = 0;
                    jQuery.each(prevSibs, function (x, s) {
                        let cp = jQuery(s).attr('colspan');
                        if (typeof cp !== 'undefined') {
                            a += parseInt(cp) - 1;
                        }
                    });
                    let i = $cp.index($currentTarget);
                    i += a;
                    ad_date = jQuery(tp[i]).attr('data-date');
                    bs_date = jQuery(tp[i]).attr('data-bsdate');
                }
                jQuery("#input-field").val('');
                jQuery("#desc-field").text('');
                jQuery(".event_date").val(bs_date);
                jQuery(".event_time").timepicker('setTime', '10:00 AM');
                jQuery('#newEventModal').modal('show');
            });
        },
        render: function () {
            let self = this;
            let m = {};
            try {
                m = calendarFunctions.getBsDateByAdDate(parseInt(self.datePicker.format("YYYY")), parseInt(self.datePicker.format("MM")), parseInt(self.datePicker.format("DD")));
            } catch (e) {
                alert('Date out of range');
                return;
            }
            self.datePickerData = calendarFunctions.getBsMonthInfoByBsDate(m.bsYear, m.bsMonth, m.bsDate, null);
            self.datePickerData.bsMonthName = self.calendarData.bsMonths[m.bsMonth - 1];
            self.datePickerData.bsYearText = self.getDevanagariNumber(self.datePickerData.bsYear);
            let calendar_body = self.getCalendarBody().wrapAll('<div />').parent().html();
            let bs_ad_months = [];
            let bsMonthFirstAdDate = moment(self.datePickerData.bsMonthFirstAdDate);
            let nextAdMonth = bsMonthFirstAdDate.clone().add(1, 'month');
            let first = bsMonthFirstAdDate.format('MMMM');
            if (!bsMonthFirstAdDate.isSame(nextAdMonth, 'year')) {
                first += " " + bsMonthFirstAdDate.format('YYYY');
            }
            bs_ad_months.push(first);
            bs_ad_months.push(nextAdMonth.format('MMMM') + " " + nextAdMonth.format('YYYY'));
            self.$el.html(self.options.template({
                bs_ad_months: bs_ad_months.join("/"),
                info: self.datePickerData,
                calendar_body: calendar_body
            }));
        },
        getCalendarBody: function () {
            let self = this;
            let datePickerData = self.datePickerData;
            let calendarData = self.calendarData;
            let weekCoverInMonth = Math.ceil((datePickerData.bsMonthFirstAdDate.getDay() + datePickerData.bsMonthDays) / 7);
            let preMonth = (datePickerData.bsMonth - 1 !== 0) ? datePickerData.bsMonth - 1 : 12;
            let preYear = preMonth === 12 ? datePickerData.bsYear - 1 : datePickerData.bsYear;
            let preMonthDays = preYear >= calendarData.minBsYear ? calendarFunctions.getBsMonthDays(preYear, preMonth) : 30;

            let nextMonth = (datePickerData.bsMonth === 12) ? 1 : datePickerData.bsMonth + 1;
            let nextYear = nextMonth === 1 ? datePickerData.bsYear + 1 : datePickerData.bsYear;

            let minBsDate = null;
            let maxBsDate = null;
            let calendarBody = jQuery('<div class="fc-scroller fc-day-grid-container">');
            let grid = jQuery('<div class="fc-day-grid fc-unselectable">');
            let weekDates = {};
            for (let i = 0; i < weekCoverInMonth; i++) {
                weekDates[i] = [];
                let calContentWeek = jQuery("<div class='fc-row fc-week fc-widget-content'>");
                let calContentBGDiv = jQuery('<div class="fc-bg">');
                let calContentDiv = jQuery('<div class="fc-content-skeleton">');
                let calContentTbl = jQuery("<table>");
                calContentTbl.css('min-height', '88px');
                let calContentBGTbl = jQuery("<table>");
                let calContentTblHead = jQuery("<thead>");
                let calContentTblFoot = jQuery("<tfoot>");
                calContentTblFoot.addClass('bsCalFoot');
                let calContentTblBody = jQuery("<tbody>");
                calContentTblBody.attr('id', 'w_' + i);
                let calContentBGTblBody = jQuery("<tbody>");
                let calContentHeadRow = jQuery("<tr>");
                let calContentFootRow = jQuery("<tr>");
                //let calContentBodyRow = jQuery("<tr>");
                let calContentBGBodyRow = jQuery("<tr>");
                for (let k = 1; k <= 7; k++) {
                    let cd = self.calendarData.bsDays[k - 1].toLowerCase();
                    let calendarDate = (i * 7) + k - datePickerData.bsMonthFirstAdDate.getDay();
                    //console.log("calendar date", calendarDate, datePickerData);
                    let isCurrentMonthDate = true;
                    let isPastMonthDate = false;
                    let isFutureMonthDate = false;
                    let fullCalBSDate = datePickerData.bsYear + '-' + datePickerData.bsMonth + '-' + calendarDate;
                    if (calendarDate <= 0) {
                        calendarDate = preMonthDays + calendarDate;
                        isCurrentMonthDate = false;
                        isPastMonthDate = true;
                        fullCalBSDate = preYear + '-' + preMonth + '-' + calendarDate;
                    } else if (calendarDate > datePickerData.bsMonthDays) {
                        calendarDate = calendarDate - datePickerData.bsMonthDays;
                        isCurrentMonthDate = false;
                        isFutureMonthDate = true;
                        fullCalBSDate = nextYear + '-' + nextMonth + '-' + calendarDate;
                    }
                    let bsp = fullCalBSDate.split('-');
                    let adDateObj = calendarFunctions.getAdDateByBsDate(parseInt(bsp[0]), parseInt(bsp[1]), parseInt(bsp[2]));
                    let adDateMoment = moment(adDateObj);
                    let fullCalADDate = adDateMoment.format('YYYY-MM-DD');
                    if (isCurrentMonthDate) {
                        let $td = jQuery('<td class="fc-day-top fc-' + cd + '" data-bsdate="' + fullCalBSDate + '" data-date="' + fullCalADDate + '" data-weekDay="' + (k - 1) + '"><span class="fc-day-number">' +
                            self.getDevanagariNumber(calendarDate) + '</span></td>');
                        let $tdBG = jQuery('<td class="fc-day fc-widget-content fc-' + cd + '" data-bsdate="' + fullCalBSDate + '" data-date="' + fullCalADDate + '"></td>');
                        if (self.bsToday.bsYear == datePickerData.bsYear && self.bsToday.bsMonth == datePickerData.bsMonth && calendarDate == datePickerData.bsDate) {
                            $td.addClass("fc-today");
                            $tdBG.addClass("fc-today");
                        } else if (calendarDate > datePickerData.bsDate) {
                            $td.addClass("fc-future");
                            $tdBG.addClass("fc-future");
                        } else if (calendarDate < datePickerData.bsDate) {
                            $td.addClass("fc-past");
                            $tdBG.addClass("fc-past");
                        }
                        self.disableIfOutOfRange($td, datePickerData, minBsDate, maxBsDate, calendarDate);
                        calContentHeadRow.append($td);
                        calContentBGBodyRow.append($tdBG);
                    } else if (isPastMonthDate) {
                        calContentHeadRow.append('<td class="fc-day-top fc-' + cd + ' fc-other-month fc-past" data-bsdate="' + fullCalBSDate + '" data-date="' + fullCalADDate + '"><span class="fc-day-number">' + self.getDevanagariNumber(calendarDate) + '</span></td>');
                        calContentBGBodyRow.append('<td class="fc-day fc-widget-content fc-' + cd + ' fc-other-month fc-past" data-bsdate="' + fullCalBSDate + '" data-date="' + fullCalADDate + '"></td>');
                    } else {
                        calContentHeadRow.append('<td class="fc-day-top fc-' + cd + ' fc-other-month fc-future" data-bsdate="' + fullCalBSDate + '" data-date="' + fullCalADDate + '"><span class="fc-day-number">' + self.getDevanagariNumber(calendarDate) + '</span></td>');
                        calContentBGBodyRow.append('<td class="fc-day fc-widget-content fc-' + cd + ' fc-other-month fc-future" data-bsdate="' + fullCalBSDate + '" data-date="' + fullCalADDate + '"></td>');
                    }
                    calContentFootRow.append('<td class="fc-day-foot" data-bsdate="' + fullCalBSDate + '" data-date="' + fullCalADDate + '"><span class="fc-day-number">' + adDateMoment.format('DD') + '</span></td>');
                    calContentBGTblBody.append(calContentBGBodyRow);
                    weekDates[i].push(adDateMoment);
                    //calContentBodyRow.append("<td data-bsdate='" + fullCalBSDate + "' data-date='" + fullCalADDate + "' class='fc-day-td ed_" + fullCalADDate + "'></td>");
                }
                calContentTblHead.append(calContentHeadRow);
                calContentTblFoot.append(calContentFootRow);
                //calContentTblBody.append(calContentBodyRow);
                calContentTbl.append(calContentTblHead);
                calContentTbl.append(calContentTblBody);
                calContentTbl.append(calContentTblFoot);
                calContentDiv.append(calContentTbl);
                calContentBGTbl.append(calContentBGTblBody);
                calContentBGDiv.append(calContentBGTbl);
                calContentWeek.css('min-height', '88px');
                calContentWeek.append(calContentBGDiv);
                calContentWeek.append(calContentDiv);
                grid.append(calContentWeek);
            }
            calendarBody.append(grid);
            //events
            jQuery.each(weekDates, function (week_no, w_moments) {
                let week_events = {};
                let week_dates = {};
                jQuery.each(w_moments, function (i, wm) {
                    week_dates[i] = wm;
                    week_events[i] = self.eventDataArr.filter(function (ev) {
                        if (i === 0) {
                            return wm.isBetween(ev.start_date_m.clone().startOf('day'), ev.end_date_m.clone().endOf('day'), null, '[]');
                        } else {
                            let pd = wm.clone().subtract(1, 'day');
                            return (wm.isBetween(ev.start_date_m.clone().startOf('day'), ev.end_date_m.clone().endOf('day'), null, '[]') && !pd.isBetween(ev.start_date_m.clone().startOf('day'), ev.end_date_m.clone().endOf('day'), null, '[]'));
                        }
                    });
                });
                //console.log("week events", week_events);
                let $w_e_body = calendarBody.find('#w_' + week_no);
                let level_data = [];
                let levels = {};
                jQuery.each(week_events, function (w_d, wm_events) {
                    jQuery.each(wm_events, function (ii, wmev) {
                        let diff = week_dates[6].clone().startOf('day').diff(wmev.end_date_m.clone().startOf('day'), 'day');
                        let start = w_d;
                        let end = (diff >= 0) ? 6 - diff : 6;
                        let width = end - start;
                        level_data.push({
                            start: start,
                            end: end,
                            width: width,
                            day: w_d,
                            event: wmev
                        });
                    });
                    /*jQuery.each(wm_events, function (ii, wmev) {
                        let $tr = jQuery("<tr>");
                        let $td = jQuery("<td>");
                        if (w_d > 0) {
                            for (let x = 0, y = w_d; x < y; x++) {
                                $tr.append(jQuery("<td>"));
                            }
                        }
                        $td.addClass('fc-event-container').append(self.getEventHtml(wmev));
                        $tr.append($td);
                        let day_diff = wmev.end_date_m.clone().startOf('day').diff(week_dates[w_d].clone().startOf('day'), 'day');
                        //multi day event
                        if (day_diff > 0) {
                            //fits in one week?
                            let w_diff = wmev.end_date_m.clone().startOf('day').diff(week_dates[6].clone().startOf('day'), 'day');
                            if (w_diff <= 0) {
                                $td.attr('colspan', day_diff + 1);
                            } else {
                                $td.attr('colspan', 7 - w_d);
                            }
                        }
                        $w_e_body.append($tr);
                    });*/
                });
                level_data = level_data.sort(function (a, b) {
                    return (a.width < b.width) ? 1 : ((b.width < a.width) ? -1 : 0);
                });
                level_data = level_data.sort(function (a, b) {
                    return (a.day > b.day) ? 1 : ((b.day > a.day) ? -1 : 0);
                });
                let l_tmp = 1;
                jQuery.each(level_data, function (li, ld) {
                    if (typeof levels[l_tmp] === 'undefined') {
                        levels[l_tmp] = {
                            start: ld.start,
                            end: ld.end,
                            events: [ld]
                        };
                    } else {
                        //search appropriate level
                        let done = false;
                        jQuery.each(levels, function (l_no, l_arr) {
                            if (!done && ld.start > l_arr.end) {
                                levels[l_no].events.push(ld);
                                levels[l_no].end = ld.end;
                                done = true;
                            } else if (!done && ld.start < l_arr.start) {
                                levels[l_no].events.push(ld);
                                levels[l_no].start = ld.start;
                                done = true;
                            }
                        });
                        if (!done) {
                            l_tmp++;
                            levels[l_tmp] = {
                                start: ld.start,
                                end: ld.end,
                                events: [ld]
                            };
                        }
                    }
                });
                //console.log("level_data", level_data);
                //console.log("level", levels);
                jQuery.each(levels, function (li, ld) {
                    let $tr = jQuery("<tr>");
                    let tdc = 0;
                    let md = ld.events.reduce(function (m, ldm) {
                        return ldm.start < m ? ldm.start : m;
                    }, ld.events[0].start);
                    if (md > 0) {
                        tdc = parseInt(md);
                        let s = '<td class="evt_empty_td"></td>'.repeat(md);
                        $tr.append(s);
                    }
                    let prd = 0;
                    jQuery.each(ld.events, function (lii, ldd) {
                        let tmp = ldd.day - md - prd;
                        if (tmp > 1) {
                            tdc += tmp - 1;
                            let s = '<td class="evt_empty_td"></td>'.repeat(tmp - 1);
                            $tr.append(s);
                        }
                        prd = ldd.day + ldd.width;
                        let $td = jQuery("<td>");
                        $td.addClass('fc-event-container').append(self.getEventHtml(ldd.event));
                        tdc += 1;
                        if (ldd.width > 0) {
                            tdc += ldd.width;
                            $td.attr('colspan', ldd.width + 1);
                        }
                        $tr.append($td);
                    });
                    if (tdc < 7) {
                        $tr.append('<td class="evt_empty_td"></td>'.repeat(7 - tdc));
                    }
                    $w_e_body.append($tr);
                });
                if (level_data.length === 0) {
                    let $tr = jQuery("<tr>");
                    $tr.append('<td class="evt_empty_td">&nbsp;</td>'.repeat(7));
                    $w_e_body.append($tr);
                }
            });
            return calendarBody;
        },
        getDevanagariNumber: function (strNum) {
            let self = this;
            let arrNumNe = strNum.toString().split('').map(function (ch) {
                if (ch === '.' || ch === ',') {
                    return ch;
                }
                return self.calendarData.devanagariNumbers[Number(ch)];
            });
            return arrNumNe.join('');
        },
        disableIfOutOfRange: function ($td, datePickerData, minBsDate, maxBsDate, calendarDate) {
            if (minBsDate !== null) {
                if (datePickerData.bsYear < minBsDate.bsYear) {
                    $td.addClass("disable");
                } else if (datePickerData.bsYear === minBsDate.bsYear && datePickerData.bsMonth < minBsDate.bsMonth) {
                    $td.addClass("disable");
                } else if (datePickerData.bsYear === minBsDate.bsYear && datePickerData.bsMonth === minBsDate.bsMonth && calendarDate < minBsDate.bsDate) {
                    $td.addClass("disable");
                }
            }

            if (maxBsDate !== null) {
                if (datePickerData.bsYear > maxBsDate.bsYear) {
                    $td.addClass("disable");
                } else if (datePickerData.bsYear === maxBsDate.bsYear && datePickerData.bsMonth > maxBsDate.bsMonth) {
                    $td.addClass("disable");
                } else if (datePickerData.bsYear === maxBsDate.bsYear && datePickerData.bsMonth === maxBsDate.bsMonth && calendarDate > maxBsDate.bsDate) {
                    $td.addClass("disable");
                }
            }

            return $td;
        }
    };
    jQuery.fn.FullBSCalendar = function (options) {
        let args = Array.prototype.slice.call(arguments, 1);
        let plgName = "FullBSCalendar";
        return this.each(function () {
            let inst = jQuery.data(this, plgName);
            if (typeof inst === "undefined") {
                if (typeof options === "undefined" || typeof options == "string" || options instanceof String) {
                    throw "invalid options passed while creating new instance.";
                }
                let p = new FullBSCalendar(this, options);
                jQuery.data(this, plgName, p);
            } else if (typeof options !== "undefined") {
                if (typeof inst[options] === "function") {
                    inst[options].apply(inst, args);
                }
            }
        });
    };
})(jQuery);
