import jQuery from "jquery";
import moment from "moment";

window.$ = window.jQuery = jQuery;
window.moment = moment;
window.jQuery.moment = moment;

import "daterangepicker/daterangepicker.css";

import "jquery-validation";

import select2 from "select2";
import "select2/dist/css/select2.min.css";

import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

select2(window.$);

import "toastr/build/toastr.min.css";
import "sweetalert2/dist/sweetalert2.min.css";
import "@fancyapps/ui/dist/fancybox/fancybox.css";

import 'jquery-datetimepicker/jquery.datetimepicker.css';
import 'jquery-datetimepicker/build/jquery.datetimepicker.full.min.js';

import toastr from "toastr";
import Swal from "sweetalert2";
import { Fancybox } from "@fancyapps/ui";
import "flowbite";
import "./bootstrap";

import Highcharts from "highcharts";
window.Highcharts = Highcharts;

import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

window.toastr = toastr;
window.Swal = Swal;


document.addEventListener("DOMContentLoaded", async () => {
    const { default: daterangepicker } = await import("daterangepicker");

    $(".date-range").each(function () {
        var $this = $(this);

        var ranges = {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        };

        $this.daterangepicker({
            timePicker: false,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear',
                direction: $('html').attr('dir') === 'rtl' ? 'rtl' : 'ltr'
            },
            ranges: ranges,
            opens: $('html').attr('dir') === 'rtl' ? 'left' : 'right',
            drops: 'down',
        });

        $this.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $this.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    });
});


document.addEventListener("DOMContentLoaded", () => {
    Fancybox.bind("[data-fancybox]", {
        animated: true,
        dragToClose: false,
        groupAll: true,
    });

});

window.addEventListener("load", () => {
    setTimeout(() => {

        $(".select2").select2({
            width: '100%',
            placeholder: $(this).data('placeholder'),
            dropdownParent: $(document.body),
        });


    }, 100);
});