/*
 * JalaliJSCalendar - Jalali Extension for Date Object 
 * Copyright (c) 2008 Ali Farhadi (http://farhadi.ir/)
 * Released under the terms of the GNU General Public License.
 * See the GPL for details (http://www.gnu.org/licenses/gpl.html).
 * 
 * Based on code from http://farsiweb.info
 */

JalaliDate = {
    g_days_in_month: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
    j_days_in_month: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29]
};


JalaliDate.jalaliToGregorian = function (j_y, j_m, j_d) {
    j_y = parseInt(j_y);
    j_m = parseInt(j_m);
    j_d = parseInt(j_d);
    var jy = j_y - 979;
    var jm = j_m - 1;
    var jd = j_d - 1;

    var j_day_no = 365 * jy + parseInt(jy / 33) * 8 + parseInt((jy % 33 + 3) / 4);
    for (var i = 0; i < jm; ++i) j_day_no += JalaliDate.j_days_in_month[i];

    j_day_no += jd;

    var g_day_no = j_day_no + 79;

    var gy = 1600 + 400 * parseInt(g_day_no / 146097);
    /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
    g_day_no = g_day_no % 146097;

    var leap = true;
    if (g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
    {
        g_day_no--;
        gy += 100 * parseInt(g_day_no / 36524);
        /* 36524 = 365*100 + 100/4 - 100/100 */
        g_day_no = g_day_no % 36524;

        if (g_day_no >= 365)
            g_day_no++;
        else
            leap = false;
    }

    gy += 4 * parseInt(g_day_no / 1461);
    /* 1461 = 365*4 + 4/4 */
    g_day_no %= 1461;

    if (g_day_no >= 366) {
        leap = false;

        g_day_no--;
        gy += parseInt(g_day_no / 365);
        g_day_no = g_day_no % 365;
    }

    for (var i = 0; g_day_no >= JalaliDate.g_days_in_month[i] + (i == 1 && leap); i++)
        g_day_no -= JalaliDate.g_days_in_month[i] + (i == 1 && leap);
    var gm = i + 1;
    var gd = g_day_no + 1;

    return [gy, gm, gd];
}

JalaliDate.checkDate = function (j_y, j_m, j_d) {
    return !(j_y < 0 || j_y > 32767 || j_m < 1 || j_m > 12 || j_d < 1 || j_d >
        (JalaliDate.j_days_in_month[j_m - 1] + (j_m == 12 && !((j_y - 979) % 33 % 4))));
}

JalaliDate.gregorianToJalali = function (g_y, g_m, g_d) {
    g_y = parseInt(g_y);
    g_m = parseInt(g_m);
    g_d = parseInt(g_d);
    var gy = g_y - 1600;
    var gm = g_m - 1;
    var gd = g_d - 1;

    var g_day_no = 365 * gy + parseInt((gy + 3) / 4) - parseInt((gy + 99) / 100) + parseInt((gy + 399) / 400);

    for (var i = 0; i < gm; ++i)
        g_day_no += JalaliDate.g_days_in_month[i];
    if (gm > 1 && ((gy % 4 == 0 && gy % 100 != 0) || (gy % 400 == 0)))
    /* leap and after Feb */
        ++g_day_no;
    g_day_no += gd;

    var j_day_no = g_day_no - 79;

    var j_np = parseInt(j_day_no / 12053);
    j_day_no %= 12053;

    var jy = 979 + 33 * j_np + 4 * parseInt(j_day_no / 1461);

    j_day_no %= 1461;

    if (j_day_no >= 366) {
        jy += parseInt((j_day_no - 1) / 365);
        j_day_no = (j_day_no - 1) % 365;
    }

    for (var i = 0; i < 11 && j_day_no >= JalaliDate.j_days_in_month[i]; ++i) {
        j_day_no -= JalaliDate.j_days_in_month[i];
    }
    var jm = i + 1;
    var jd = j_day_no + 1;


    return [jy, jm, jd];
}

Date.prototype.setJalaliFullYear = function (y, m, d) {
    y = parseInt(y);
    m = parseInt(m);
    d = parseInt(d);

    var gd = this.getDate();
    var gm = this.getMonth();
    var gy = this.getFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    if (y < 100) y += 1300;
    j[0] = y;
    if (m != undefined) {
        if (m > 11) {
            j[0] += Math.floor(m / 12);
            m = m % 12;
        }
        j[1] = m + 1;
    }
    if (d != undefined) j[2] = d;
    var g = JalaliDate.jalaliToGregorian(j[0], j[1], j[2]);
    return this.setFullYear(g[0], g[1] - 1, g[2]);
}

Date.prototype.setJalaliMonth = function (m, d) {
    var gd = this.getDate();
    var gm = this.getMonth();
    var gy = this.getFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    if (m > 11) {
        j[0] += Math.floor(m / 12);
        m = m % 12;
    }
    j[1] = m + 1;
    if (d != undefined) j[2] = d;
    var g = JalaliDate.jalaliToGregorian(j[0], j[1], j[2]);
    return this.setFullYear(g[0], g[1] - 1, g[2]);
}

Date.prototype.setJalaliDate = function (d) {
    var gd = this.getDate();
    var gm = this.getMonth();
    var gy = this.getFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    j[2] = d;
    var g = JalaliDate.jalaliToGregorian(j[0], j[1], j[2]);
    return this.setFullYear(g[0], g[1] - 1, g[2]);
}

Date.prototype.getJalaliFullYear = function () {
    var gd = this.getDate();
    var gm = this.getMonth();
    var gy = this.getFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    return j[0];
}

Date.prototype.getJalaliMonth = function () {
    var gd = this.getDate();
    var gm = this.getMonth();
    var gy = this.getFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    return j[1] - 1;
}

Date.prototype.getJalaliDate = function () {
    var gd = this.getDate();
    var gm = this.getMonth();
    var gy = this.getFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    return j[2];
}

Date.prototype.getJalaliDay = function () {
    var day = this.getDay();
    day = (day + 1) % 7;
    return day;
}

Date.prototype.getmonthName = function (d) {
    switch (d) {
        case 0:
            day = 'فروردین';
            break;
        case 1:
            day = 'اردیبهشت';
            break;
        case 2:
            day = 'خرداد';
            break;
        case 3:
            day = 'تیر';
            break;
        case 4:
            day = 'مرداد';
            break;
        case 5:
            day = 'شهریور';
            break;
        case 6:
            day = 'مهر';
            break;
        case 7:
            day = 'آبان';
            break;
        case 8:
            day = 'آذر';
            break;
        case 9:
            day = 'دی';
            break;
        case 10:
            day = 'بهمن';
            break;
        case 11:
            day = 'اسفند';
            break;
    }
    ;
    return day;
}

Date.prototype.getMonthNameGregorian = function (d) {
    switch (d) {
        case 0:
            day = 'January';
            break;
        case 1:
            day = 'Febuary';
            break;
        case 2:
            day = 'March';
            break;
        case 3:
            day = 'April';
            break;
        case 4:
            day = 'May';
            break;
        case 5:
            day = 'June';
            break;
        case 6:
            day = 'July';
            break;
        case 7:
            day = 'August';
            break;
        case 8:
            day = 'September';
            break;
        case 9:
            day = 'October';
            break;
        case 10:
            day = 'November';
            break;
        case 11:
            day = 'December';
            break;
    }
    ;
    return day;
}

Date.prototype.getFirstDayMonth = function (y, m, d) {
    var greDate = this.setJalaliFullYear(y, m, d);
    var date = new Date(greDate);
    var day = (date.getDay() + 1) % 7;
    return day;
}

Date.prototype.getFirstDayMonthGre = function (y, m, d) {
    var greDate = this.setFullYear(y, m, d);
    var date = new Date(greDate);
    return ((date.getDay() - 1) % 7);
}

/**
 * Jalali UTC functions
 */

Date.prototype.setJalaliUTCFullYear = function (y, m, d) {
    var gd = this.getUTCDate();
    var gm = this.getUTCMonth();
    var gy = this.getUTCFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    if (y < 100) y += 1300;
    j[0] = y;
    if (m != undefined) {
        if (m > 11) {
            j[0] += Math.floor(m / 12);
            m = m % 12;
        }
        j[1] = m + 1;
    }
    if (d != undefined) j[2] = d;
    var g = JalaliDate.jalaliToGregorian(j[0], j[1], j[2]);
    return this.setUTCFullYear(g[0], g[1] - 1, g[2]);
}

Date.prototype.setJalaliUTCMonth = function (m, d) {
    var gd = this.getUTCDate();
    var gm = this.getUTCMonth();
    var gy = this.getUTCFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    if (m > 11) {
        j[0] += Math.floor(m / 12);
        m = m % 12;
    }
    j[1] = m + 1;
    if (d != undefined) j[2] = d;
    var g = JalaliDate.jalaliToGregorian(j[0], j[1], j[2]);
    return this.setUTCFullYear(g[0], g[1] - 1, g[2]);
}

Date.prototype.setJalaliUTCDate = function (d) {
    var gd = this.getUTCDate();
    var gm = this.getUTCMonth();
    var gy = this.getUTCFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    j[2] = d;
    var g = JalaliDate.jalaliToGregorian(j[0], j[1], j[2]);
    return this.setUTCFullYear(g[0], g[1] - 1, g[2]);
}

Date.prototype.getJalaliUTCFullYear = function () {
    var gd = this.getUTCDate();
    var gm = this.getUTCMonth();
    var gy = this.getUTCFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    return j[0];
}

Date.prototype.getJalaliUTCMonth = function () {
    var gd = this.getUTCDate();
    var gm = this.getUTCMonth();
    var gy = this.getUTCFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    return j[1] - 1;
}

Date.prototype.getJalaliUTCDate = function () {
    var gd = this.getUTCDate();
    var gm = this.getUTCMonth();
    var gy = this.getUTCFullYear();
    var j = JalaliDate.gregorianToJalali(gy, gm + 1, gd);
    return j[2];
}

Date.prototype.getJalaliUTCDay = function () {
    var day = this.getUTCDay();
    day = (day + 1) % 7;
    return day;
}




function getCalendar(y, m, type) {
    $("#select_month_" + type).val(m + '-' + y);

    if (type == 'go')
        var kind = 0;
    else
        var kind = 1;

    var text;
    var numOfDay = date.getLocalMonthDays('jalali', m);
    var firstDay = date.getFirstDayMonth(y, m, 1);
    var day;
    var nextMonth = 1;

    if (m == 0)
        var numOfDayLastMonth = date.getLocalMonthDays('jalali', 11);
    else
        var numOfDayLastMonth = date.getLocalMonthDays('jalali', m - 1);

    for (var i = 0; i < 6; i++) {
        text = '';
        if (i == 0) {
            for (var j = 1; j <= firstDay; j++) {
                day = numOfDayLastMonth - firstDay + j;
                text += '<td><div class="prev-month">' + day + '</div></td>';
            }
            day = 1;
            for (var j = 0; j < 7 - firstDay; j++, day++) {
                if (day < nowDate && m == nowMonth && y == nowYear)
                    text += '<td><div class="prev-month">' + day + '</div></td>';
                else {
                    if ((selectDays[0][0] == y && selectDays[0][1] == m && selectDays[0][2] == day) || (selectDays[1][0] == y && selectDays[1][1] == m && selectDays[1][2] == day))
                        text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv current-day ">' + day + '</div></td>';
                    else {
                        if (y > selectDays[0][0] && selectDays[1][0] != null) {
                            if (m < selectDays[1][1] && y <= selectDays[1][0])
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                            else if (m == selectDays[1][1] && day < selectDays[1][2] && y <= selectDays[1][0])
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                            else
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')" ><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv">' + day + '</div></td>';
                        }
                        else if (y < selectDays[1][0] && y == selectDays[0][0] && m > selectDays[0][1]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y < selectDays[1][0] && y == selectDays[0][0] && m == selectDays[0][1] && day > selectDays[0][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m > selectDays[0][1] && m < selectDays[1][1] && selectDays[1][0] != null) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[0][1] && m < selectDays[1][1] && day > selectDays[0][2] && selectDays[1][0] != null) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[1][1] && m > selectDays[0][1] && day < selectDays[1][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[1][1] && m == selectDays[0][1] && day < selectDays[1][2] && day > selectDays[0][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')" ><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv">' + day + '</div></td>';
                        }
                    }
                }
            }
            document.getElementById('row_' + type + '_0').innerHTML = text;
        }
        else {
            for (var j = 0; j < 7; j++, day++) {
                if (day < nowDate && m == nowMonth && y == nowYear) {
                    text += '<td><div class="next-month">' + day + '</div></td>';
                }
                else if (day > numOfDay) {
                    text += '<td><div class="next-month">' + nextMonth + '</div></td>';
                    nextMonth++;
                }
                else {
                    if ((selectDays[0][0] == y && selectDays[0][1] == m && selectDays[0][2] == day) || (selectDays[1][0] == y && selectDays[1][1] == m && selectDays[1][2] == day))
                        text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv current-day">' + day + '</div></td>';
                    else {
                        if (y > selectDays[0][0] && selectDays[1][0] != null) {
                            if (m < selectDays[1][1] && y <= selectDays[1][0])
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                            else if (m == selectDays[1][1] && day < selectDays[1][2] && y <= selectDays[1][0])
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                            else
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')" ><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv">' + day + '</div></td>';
                        }
                        else if (y < selectDays[1][0] && y == selectDays[0][0] && m > selectDays[0][1]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y < selectDays[1][0] && y == selectDays[0][0] && m == selectDays[0][1] && day > selectDays[0][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m > selectDays[0][1] && m < selectDays[1][1] && selectDays[1][0] != null) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[0][1] && m < selectDays[1][1] && day > selectDays[0][2] && selectDays[1][0] != null) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[1][1] && m > selectDays[0][1] && day < selectDays[1][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[1][1] && m == selectDays[0][1] && day < selectDays[1][2] && day > selectDays[0][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')" ><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv">' + day + '</div></td>';
                        }
                    }
                }
            }
            document.getElementById('row_' + type + '_' + i).innerHTML = text;
        }
    }
}

function getGregorianCalendar(y, m, type) {
    $("#select_month_gre_" + type).val(m + '-' + y);

    if (type == 'go')
        var kind = 0;
    else
        var kind = 1;

    var text;
    var numOfDay = date.getLocalMonthDays('gregorian', m);
    var firstDay = date.getFirstDayMonthGre(y, m, 1);
    var day;
    var nextMonth = 1;

    if (m == 0)
        var numOfDayLastMonth = date.getLocalMonthDays('gregorian', 11);
    else
        var numOfDayLastMonth = date.getLocalMonthDays('gregorian', m - 1);

    for (var i = 0; i < 6; i++) {
        text = '';
        if (i == 0) {
            for (var j = 1; j <= firstDay; j++) {
                day = numOfDayLastMonth - firstDay + j;
                text += '<td><div class="prev-month">' + day + '</div></td>';
            }
            day = 1;
            for (var j = 0; j < 7 - firstDay; j++, day++) {
                if (day < nowDateGre && m == nowMonthGre && y == nowYearGre)
                    text += '<td><div class="prev-month">' + day + '</div></td>';
                else {
                    if ((selectDays[0][0] == y && selectDays[0][1] == m && selectDays[0][2] == day) || (selectDays[1][0] == y && selectDays[1][1] == m && selectDays[1][2] == day))
                        text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv current-day">' + day + '</div></td>';
                    else {
                        if (y > selectDays[0][0] && selectDays[1][0] != null) {
                            if (m < selectDays[1][1] && y <= selectDays[1][0])
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                            else if (m == selectDays[1][1] && day < selectDays[1][2] && y <= selectDays[1][0])
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                            else
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')" ><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv">' + day + '</div></td>';
                        }
                        else if (y < selectDays[1][0] && y == selectDays[0][0] && m > selectDays[0][1]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y < selectDays[1][0] && y == selectDays[0][0] && m == selectDays[0][1] && day > selectDays[0][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m > selectDays[0][1] && m < selectDays[1][1] && selectDays[1][0] != null) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[0][1] && m < selectDays[1][1] && day > selectDays[0][2] && selectDays[1][0] != null) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[1][1] && m > selectDays[0][1] && day < selectDays[1][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[1][1] && m == selectDays[0][1] && day < selectDays[1][2] && day > selectDays[0][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')" ><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv">' + day + '</div></td>';
                        }
                    }
                }
            }
            document.getElementById('row_gre_' + type + '_0').innerHTML = text;
        }
        else {
            for (var j = 0; j < 7; j++, day++) {
                if (day < nowDateGre && m == nowMonthGre && y == nowYearGre) {
                    text += '<td><div class="next-month">' + day + '</div></td>';
                }
                else if (day > numOfDay) {
                    text += '<td><div class="next-month">' + nextMonth + '</div></td>';
                    nextMonth++;
                }
                else {
                    if ((selectDays[0][0] == y && selectDays[0][1] == m && selectDays[0][2] == day) || (selectDays[1][0] == y && selectDays[1][1] == m && selectDays[1][2] == day))
                        text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv current-day">' + day + '</div></td>';
                    else {
                        if (y > selectDays[0][0] && selectDays[1][0] != null) {
                            if (m < selectDays[1][1] && y <= selectDays[1][0])
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                            else if (m == selectDays[1][1] && day < selectDays[1][2] && y <= selectDays[1][0])
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                            else
                                text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')" ><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv">' + day + '</div></td>';
                        }
                        else if (y < selectDays[1][0] && y == selectDays[0][0] && m > selectDays[0][1]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y < selectDays[1][0] && y == selectDays[0][0] && m == selectDays[0][1] && day > selectDays[0][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m > selectDays[0][1] && m < selectDays[1][1] && selectDays[1][0] != null) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[0][1] && m < selectDays[1][1] && day > selectDays[0][2] && selectDays[1][0] != null) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[1][1] && m > selectDays[0][1] && day < selectDays[1][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else if (y == selectDays[0][0] && m == selectDays[1][1] && m == selectDays[0][1] && day < selectDays[1][2] && day > selectDays[0][2]) {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')"><div id="' + y + '-' + m + '-' + day + '-' + type + '"  class="tableDiv between ">' + day + '</div></td>';
                        }
                        else {
                            text += '<td onclick="selectDay(' + y + ',' + m + ',' + day + ',' + kind + ')" ><div id="' + y + '-' + m + '-' + day + '-' + type + '" class="tableDiv">' + day + '</div></td>';
                        }
                    }
                }
            }
            document.getElementById('row_gre_' + type + '_' + i).innerHTML = text;
        }
    }
}

function selectDay(y, m, d, kind) {
    if (y < 1600) {
        var go = $("#select_month_go").val();
        if (numOfCalendar == 2)
            var back = $("#select_month_back").val();

        var goCal = go.split("-");
        if (numOfCalendar == 2)
            var backCal = back.split("-");
    }
    else {
        var go = $("#select_month_gre_go").val();
        if (numOfCalendar == 2)
            var back = $("#select_month_gre_back").val();

        var goCal = go.split("-");
        if (numOfCalendar == 2)
            var backCal = back.split("-");
    }

    if (kind == 0)
        var type = 'go';
    else
        var type = 'back';

    if (numClick == 0) {
        selectDays[0][0] = y;
        selectDays[0][1] = m;
        selectDays[0][2] = d;

        selectDays[1][0] = null;
        selectDays[1][1] = null;
        selectDays[1][2] = null;

        document.getElementById('backDate').value = '';
        if (y > 1600) {
            var dd = new Date();
            var hell = dd.setFullYear(selectDays[0][0], selectDays[0][1], selectDays[0][2]);
            dd = new Date(hell);

            var year = dd.getJalaliFullYear();
            var month = dd.getJalaliMonth();
            var date = dd.getJalaliDate();

            document.getElementById('goDate').value = year + '/' + (parseInt(month) + 1) + '/' + date;
            getGregorianCalendar(goCal[1], goCal[0], 'go');
            if (numOfCalendar == 2)
                getGregorianCalendar(backCal[1], backCal[0], 'back');
        }
        else {
            document.getElementById('goDate').value = selectDays[0][0] + '/' + (parseInt(selectDays[0][1]) + 1) + '/' + selectDays[0][2];
            getCalendar(goCal[1], goCal[0], 'go');
            if (numOfCalendar == 2)
                getCalendar(backCal[1], backCal[0], 'back');
        }
        if (numOfCalendar == 2)
            numClick = 1;
    }
    else {
        if ((selectDays[0][0] == y && selectDays[0][1] == m && selectDays[0][2] < d) || (selectDays[0][0] == y && selectDays[0][1] < m) || (selectDays[0][0] < y)) {
            selectDays[1][0] = y;
            selectDays[1][1] = m;
            selectDays[1][2] = d;

            if (selectDays[1][0] > 1600) {
                var dd = new Date();
                var hell = dd.setFullYear(selectDays[1][0], selectDays[1][1], selectDays[1][2]);
                dd = new Date(hell);

                var year = dd.getJalaliFullYear();
                var month = dd.getJalaliMonth();
                var date = dd.getJalaliDate();

                document.getElementById('backDate').value = year + '/' + (parseInt(month) + 1) + '/' + date;
                getGregorianCalendar(goCal[1], goCal[0], 'go');
                if (numOfCalendar == 2)
                    getGregorianCalendar(backCal[1], backCal[0], 'back');
            }
            else {
                document.getElementById('backDate').value = y + '/' + (parseInt(m) + 1) + '/' + d;
                getCalendar(goCal[1], goCal[0], 'go');
                if (numOfCalendar == 2)
                    getCalendar(backCal[1], backCal[0], 'back');
            }
            numClick = 0;
        }
        else {
            alert('تاریخ برگشت نمی تواند قبل از رفت باشد.')
        }
    }
}


function changeDateKind(kind) {
    if (kind == 1) {
        document.getElementById('calendarJalali').style.display = 'none';
        document.getElementById('JalaliButton').style.display = 'none';
        document.getElementById('GregorianButton').style.display = '';
        document.getElementById('calendarGregorian').style.display = '';

        var dd = new Date();
        var hell = dd.setJalaliFullYear(selectDays[0][0], selectDays[0][1], selectDays[0][2]);
        dd = new Date(hell);
        selectDays[0][0] = dd.getFullYear();
        selectDays[0][1] = dd.getMonth();
        selectDays[0][2] = dd.getDate();

        if (selectDays[1][0] != null) {
            var hell = dd.setJalaliFullYear(selectDays[1][0], selectDays[1][1], selectDays[1][2]);
            dd = new Date(hell);
            selectDays[1][0] = dd.getFullYear();
            selectDays[1][1] = dd.getMonth();
            selectDays[1][2] = dd.getDate();

            if (selectDays[1][0] == selectDays[0][0] && selectDays[1][1] == selectDays[0][1]) {
                var month = selectDays[0][1];
                var year = selectDays[0][0];
                if (month == 11) {
                    month = 0;
                    year = parseInt(year) + 1;
                }
                getGregorianCalendar(selectDays[0][0], selectDays[0][1], 'go');
                getGregorianCalendar(year, (parseInt(month) + 1), 'back');
            }
            else {
                getGregorianCalendar(selectDays[0][0], selectDays[0][1], 'go');
                getGregorianCalendar(selectDays[1][0], selectDays[1][1], 'back');
            }
        }
        else {
            var month = selectDays[0][1];
            var year = selectDays[0][0];
            if (month == 11) {
                month = 0;
                year = parseInt(year) + 1;
            }
            getGregorianCalendar(selectDays[0][0], selectDays[0][1], 'go');
            getGregorianCalendar(year, (parseInt(month) + 1), 'back');
        }
    }
    else {
        document.getElementById('calendarJalali').style.display = '';
        document.getElementById('JalaliButton').style.display = '';
        document.getElementById('GregorianButton').style.display = 'none';
        document.getElementById('calendarGregorian').style.display = 'none';

        var dd = new Date();
        var hell = dd.setFullYear(selectDays[0][0], selectDays[0][1], selectDays[0][2]);
        dd = new Date(hell);
        selectDays[0][0] = dd.getJalaliFullYear();
        selectDays[0][1] = dd.getJalaliMonth();
        selectDays[0][2] = dd.getJalaliDate();

        if (selectDays[1][0] != null) {
            var hell = dd.setFullYear(selectDays[1][0], selectDays[1][1], selectDays[1][2]);
            dd = new Date(hell);
            selectDays[1][0] = dd.getJalaliFullYear();
            selectDays[1][1] = dd.getJalaliMonth();
            selectDays[1][2] = dd.getJalaliDate();
            if (selectDays[1][0] == selectDays[0][0] && selectDays[1][1] == selectDays[0][1]) {
                var month = selectDays[0][1];
                var year = selectDays[0][0];
                if (month == 11) {
                    month = 0;
                    year = parseInt(year) + 1;
                }
                getCalendar(selectDays[0][0], selectDays[0][1], 'go');
                getCalendar(year, (parseInt(month) + 1), 'back');
            }
            else {
                getCalendar(selectDays[0][0], selectDays[0][1], 'go');
                getCalendar(selectDays[1][0], selectDays[1][1], 'back');
            }
        }
        else {
            var month = selectDays[0][1];
            var year = selectDays[0][0];
            if (month == 11) {
                month = 0;
                year = parseInt(year) + 1;
            }
            getCalendar(selectDays[0][0], selectDays[0][1], 'go');
            getCalendar(year, (parseInt(month) + 1), 'back');
        }
    }
}

function getNewMonthGre(m, type) {
    var select = m.split('-');
    if (type == 'go') {
        if (select[0] == 11)
            firstMonthGreBack = 0 + '-' + (parseInt(select[1]) + 1);
        else
            firstMonthGreBack = (parseInt(select[0]) + 1) + '-' + select[1];
    }
    else {
        if (select[0] == 0)
            lastMonthGre = 11 + '-' + (parseInt(select[1]) - 1);
        else
            lastMonthGre = (parseInt(select[0]) - 1) + '-' + select[1];
    }
    getGregorianCalendar(select[1], select[0], type);
}

function nextMonthGre(type) {
    var val = $("#select_month_gre_" + type).val();
    var select = val.split('-');

    if (type == 'go') {
        if (lastMonthGre != (select[0] + '-' + select[1])) {
            if (select[0] == 11) {
                firstMonthGreBack = 1 + '-' + (parseInt(select[1]) + 1);
                getGregorianCalendar((parseInt(select[1]) + 1), 0, 'go');
            }
            else {
                if (select[0] == 10)
                    firstMonthGreBack = 0 + '-' + (parseInt(select[1]) + 1);
                else
                    firstMonthGreBack = (parseInt(select[0]) + 2) + '-' + select[1];

                getGregorianCalendar(select[1], (parseInt(select[0]) + 1), 'go');
            }
        }
    }
    else {
        if (lastMonthGreBack != (select[0] + '-' + select[1])) {
            lastMonthGre = select[0] + '-' + select[1];
            if (select[0] == 11)
                getGregorianCalendar((parseInt(select[1]) + 1), 0, 'back');
            else
                getGregorianCalendar(select[1], (parseInt(select[0]) + 1), 'back');
        }
    }
}

function prevMonthGre(type) {
    var val = $("#select_month_gre_" + type).val();
    var select = val.split('-');
    if (type == 'go') {
        if (firstMonthGre != (select[0] + '-' + select[1])) {
            firstMonthGreBack = select[0] + '-' + select[1];
            if (select[0] == 0)
                getGregorianCalendar((parseInt(select[1]) - 1), 11, 'go');
            else
                getGregorianCalendar(select[1], (parseInt(select[0]) - 1), 'go');
        }
    }
    else {
        if (firstMonthGreBack != (select[0] + '-' + select[1])) {
            if (select[0] == 0) {
                lastMonthGre = 10 + '-' + (parseInt(select[1]) - 1);
                getGregorianCalendar((parseInt(select[1]) - 1), 11, 'back');
            }
            else {
                if (select[0] == 1)
                    lastMonthGre = 11 + '-' + (parseInt(select[1]) - 1);
                else
                    getGregorianCalendar(select[1], (parseInt(select[0]) - 1), 'back');
            }
        }
    }
}


function nowCalendar() {
    if (checkOpen) {

        text = '';
        text2 = '';
        //********************************************************************************************************************************************************
        // for Jalali Calendar
        //********************************************************************************************************************************************************
        yearGo = selectDays[0][0];
        monthGO = selectDays[0][1];
        yearBack = selectDays[1][0];
        monthBack = selectDays[1][1];

        if (selectDays[1][1] != null && selectDays[1][1] == 0)
            lastMonth = 11 + '-' + (parseInt(selectDays[1][0]) - 1);
        else if (selectDays[1][1] != null)
            lastMonth = (parseInt(selectDays[1][1]) - 1) + '-' + selectDays[1][0];


        if (selectDays[0][1] == 11)
            firstMonthBack = 0 + '-' + (parseInt(selectDays[0][0]) + 1);
        else
            firstMonthBack = (parseInt(selectDays[0][1]) + 1) + '-' + selectDays[0][0];
        var newYear = 0;
        for (var i = nowMonth; i < nowMonth + 12; i++) {
            if (nowMonth == i) {
                text += '<option value="' + i + '-' + nowYear + '" selected>' + date.getmonthName(i) + '-' + nowYear + '</option>';
                firstMonth = i + '-' + nowYear;
            }
            else {
                if (i > 11) {
                    text += '<option value="' + newYear + '-' + (nowYear + 1) + '">' + date.getmonthName(newYear) + '-' + (nowYear + 1) + '</option>';
                    newYear++;
                }
                else
                    text += '<option value="' + i + '-' + nowYear + '">' + date.getmonthName(i) + '-' + nowYear + '</option>';
            }
        }
        newYear = 0;
        for (var i = backMonth; i < backMonth + 12; i++) {
            if (backMonth == i) {
                text2 += '<option value="' + i + '-' + backYear + '" selected>' + date.getmonthName(i) + '-' + backYear + '</option>';
            }
            else {
                if (i == backMonth + 11)
                    lastMonthBack = newYear + '-' + (backYear + 1);

                if (i > 11) {
                    text2 += '<option value="' + newYear + '-' + (backYear + 1) + '">' + date.getmonthName(newYear) + '-' + (backYear + 1) + '</option>';
                    newYear++;
                }
                else
                    text2 += '<option value="' + i + '-' + backYear + '">' + date.getmonthName(i) + '-' + backYear + '</option>';
            }
        }

        document.getElementById('select_month_go').innerHTML = text;
        document.getElementById('select_month_back').innerHTML = text2;

        getCalendar(selectDays[0][0], selectDays[0][1], 'go');
        if (yearBack == null) {
            var mount = selectDays[0][1];
            var year = selectDays[0][0];
            if (mount == 11) {
                mount = 0;
                year = parseInt(year) + 1;
            }
            getCalendar(year, mount + 1, 'back');
        }
        else
            getCalendar(selectDays[1][0], selectDays[1][1], 'back');

        document.getElementById('container1').style.display = 'block';

        //********************************************************************************************************************************************************
        //********************************************************************************************************************************************************
        //********************************************************************************************************************************************************
        text = '';
        text2 = '';
        newYear = 0;

        if (selectDays[0][0] < 1600) {
            var dd = new Date();
            var hell = dd.setJalaliFullYear(selectDays[0][0], selectDays[0][1], selectDays[0][2]);
            dd = new Date(hell);
            var yearGo = dd.getFullYear();
            var monthGo = dd.getMonth();

            var hell = dd.setJalaliFullYear(selectDays[1][0], selectDays[1][1], selectDays[1][2]);
            dd = new Date(hell);
            var yearBack = dd.getFullYear();
            var monthBack = dd.getMonth();

            if (monthBack == 0)
                lastMonthGreBack = 11 + '-' + (parseInt(yearBack) - 1);
            else
                lastMonthGreBack = (parseInt(monthBack) - 1) + '-' + yearBack;


            if (selectDays[0][1] == 11)
                firstMonthGreBack = 0 + '-' + (parseInt(yearGo) + 1);
            else
                firstMonthGreBack = (parseInt(monthGo) + 1) + '-' + yearGo;

        }
        else {
            yearGo = selectDays[0][0];
            monthGO = selectDays[0][1];
            yearBack = selectDays[1][0];
            monthBack = selectDays[1][1];

            if (selectDays[1][1] == 0)
                lastMonthGreBack = 11 + '-' + (parseInt(selectDays[1][0]) - 1);
            else
                lastMonthGreBack = (parseInt(selectDays[1][1]) - 1) + '-' + selectDays[1][0];


            if (selectDays[0][1] == 11)
                firstMonthGreBack = 0 + '-' + (parseInt(selectDays[0][0]) + 1);
            else
                firstMonthGreBack = (parseInt(selectDays[0][1]) + 1) + '-' + selectDays[0][0];
        }

        for (var i = nowMonthGre; i < nowMonthGre + 12; i++) {
            if (nowMonthGre == i) {
                text += '<option value="' + i + '-' + nowYearGre + '" selected>' + date.getMonthNameGregorian(i) + '-' + nowYearGre + '</option>';
                firstMonthGre = i + '-' + nowYearGre;
            }
            else {
                if (i == nowMonthGre + 11)
                    lastMonthGre = newYear + '-' + (nowYearGre + 1);

                if (i > 11) {
                    text += '<option value="' + newYear + '-' + (nowYearGre + 1) + '">' + date.getMonthNameGregorian(newYear) + '-' + (nowYearGre + 1) + '</option>';
                    newYear++;
                }
                else
                    text += '<option value="' + i + '-' + nowYearGre + '">' + date.getMonthNameGregorian(i) + '-' + nowYearGre + '</option>';
            }
        }
        newYear = 0;
        for (var i = backMonthGre; i < backMonthGre + 12; i++) {
            if (backMonthGre == i) {
                text2 += '<option value="' + i + '-' + backYearGre + '" selected>' + date.getMonthNameGregorian(i) + '-' + backYearGre + '</option>';
                firstMonthGreBack = i + '-' + backYearGre;
            }
            else {
                if (i == backMonthGre + 11)
                    lastMonthGreBack = newYear + '-' + (backYearGre + 1);

                if (i > 11) {
                    text2 += '<option value="' + newYear + '-' + (backYearGre + 1) + '">' + date.getMonthNameGregorian(newYear) + '-' + (backYearGre + 1) + '</option>';
                    newYear++;
                }
                else
                    text2 += '<option value="' + i + '-' + backYearGre + '">' + date.getMonthNameGregorian(i) + '-' + backYearGre + '</option>';
            }
        }
        document.getElementById('select_month_gre_go').innerHTML = text;
        document.getElementById('select_month_gre_back').innerHTML = text2;
        getGregorianCalendar(selectDays[0][0], selectDays[0][1], 'go');
        if (monthBack == null) {
            var mount = monthGo;
            var year = yearGo;
            if (mount == 11) {
                mount = 0;
                year = parseInt(year) + 1;
            }
            getGregorianCalendar(year, mount + 1, 'back');
        }
        else
            getGregorianCalendar(selectDays[1][0], selectDays[1][1], 'back');
        checkOpen = false;
    }
    else
        document.getElementById('container1').style.display = 'block';
}

function getNewMonth(m, type) {
    var select = m.split('-');
    if (type == 'go') {
        if (select[0] == 11)
            firstMonthBack = 0 + '-' + (parseInt(select[1]) + 1);
        else
            firstMonthBack = (parseInt(select[0]) + 1) + '-' + select[1];
    }
    else {
        if (select[0] == 0)
            lastMonth = 11 + '-' + (parseInt(select[1]) - 1);
        else
            lastMonth = (parseInt(select[0]) - 1) + '-' + select[1];
    }
    getCalendar(select[1], select[0], type);
}

function nextMonth(type) {
    var val = $("#select_month_" + type).val();
    var select = val.split('-');
    if (type == 'go') {
        if (lastMonth != (select[0] + '-' + select[1])) {
            if (select[0] == 11) {
                firstMonthBack = 1 + '-' + (parseInt(select[1]) + 1);
                getCalendar((parseInt(select[1]) + 1), 0, 'go');
            }
            else {
                if (select[0] == 10)
                    firstMonthBack = 0 + '-' + (parseInt(select[1]) + 1);
                else
                    firstMonthBack = (parseInt(select[0]) + 2) + '-' + select[1];

                getCalendar(select[1], (parseInt(select[0]) + 1), 'go');
            }
        }
    }
    else {
        if (lastMonthBack != (select[0] + '-' + select[1])) {
            lastMonth = select[0] + '-' + select[1];
            if (select[0] == 11)
                getCalendar((parseInt(select[1]) + 1), 0, 'back');
            else
                getCalendar(select[1], (parseInt(select[0]) + 1), 'back');
        }
    }
}

function prevMonth(type) {
    var val = $("#select_month_" + type).val();
    var select = val.split('-');
    if (type == 'go') {
        if (firstMonth != (select[0] + '-' + select[1])) {
            firstMonthBack = select[0] + '-' + select[1];
            if (select[0] == 0)
                getCalendar((parseInt(select[1]) - 1), 11, 'go');
            else
                getCalendar(select[1], (parseInt(select[0]) - 1), 'go');
        }
    }
    else {
        if (firstMonthBack != (select[0] + '-' + select[1])) {
            if (select[0] == 0) {
                lastMonth = 10 + '-' + (parseInt(select[1]) - 1);
                getCalendar((parseInt(select[1]) - 1), 11, 'back');
            }
            else {
                if (select[0] == 1)
                    lastMonth = 11 + '-' + (parseInt(select[1]) - 1);
                else
                    lastMonth = (parseInt(select[0]) - 2) + '-' + select[1];
                getCalendar(select[1], (parseInt(select[0]) - 1), 'back');
            }
        }
    }
}

function closeCalender() {
    document.getElementById('container1').style.display = 'none';
}

