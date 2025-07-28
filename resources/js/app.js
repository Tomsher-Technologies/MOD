import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'select2/dist/js/select2.full';
import 'select2/dist/css/select2.min.css';

// Import Flowbite components
import 'flowbite';

import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";
// Bootstrap or custom setup
import './bootstrap';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

$(function () {
    $('.select2').select2({
        minimumResultsForSearch: 5
    });
});

window.toastr = toastr;
window.Swal = Swal;

Fancybox.bind("[data-fancybox]", {
  animated: true,
  dragToClose: false,
  groupAll: true,
});

toastr.options = {
    closeButton: true,
    progressBar: true,
    timeOut: "5000",
    extendedTimeOut: "1000",
    positionClass: "toast-top-right",
    showDuration: "300",
    hideDuration: "1000",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};


