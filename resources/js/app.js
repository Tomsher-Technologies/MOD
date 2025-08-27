import jQuery from "jquery";
window.$ = window.jQuery = jQuery;

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

import toastr from "toastr";
import Swal from "sweetalert2";
import { Fancybox } from "@fancyapps/ui";
import "flowbite";
import "./bootstrap";

window.toastr = toastr;
window.Swal = Swal;

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
            dropdownParent: $(document.body),
        });

        // $(".repeater").repeater({
        //     initEmpty: false,
        //     show: function () {
        //         $(this).slideDown();
        //     },
        //     hide: function (deleteElement) {
        //         if (confirm("Are you sure you want to delete this element?")) {
        //             $(this).slideUp(deleteElement);
        //         }
        //     },
        // });
    }, 100);
});
