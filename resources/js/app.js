// Importing necessary libraries
import './bootstrap';
import 'bootstrap'; // Bootstrap JS load
import * as bootstrap from 'bootstrap'; // Import Bootstrap object
import 'livewire-sortable';
import jQuery from 'jquery';
import Swal from "sweetalert2";
window.Swal = Swal;
import print from "print-js";
window.print = print;

import QRCode from 'qrcode';
window.QRCode = QRCode;

// Import ApexCharts and ApexTree
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

import 'bootstrap-icons/font/bootstrap-icons.css';
// Make libraries available globally
window.bootstrap = bootstrap;
window.$ = window.jQuery = jQuery;

import "daterangepicker/daterangepicker.css";
import "daterangepicker/daterangepicker.js";

// Import JSZip for handling zip files
import JSZip from 'jszip';
window.JSZip = JSZip;

// Import pdfmake and related files
import pdfmake from 'pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
pdfmake.addVirtualFileSystem(pdfFonts);
// pdfmake.vfs = pdfFonts.pdfMake.vfs;
window.pdfmake = pdfmake;

// DataTables imports
import DataTable from 'datatables.net-bs5';
window.DataTable = DataTable;
import 'datatables.net-autofill-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-colreorder-bs5';
import 'datatables.net-fixedcolumns-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-keytable-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-rowgroup-bs5';
import 'datatables.net-rowreorder-bs5';
import 'datatables.net-scroller-bs5';
import 'datatables.net-searchbuilder-bs5';
import 'datatables.net-searchpanes-bs5';
import 'datatables.net-select-bs5';
import 'datatables.net-staterestore-bs5';

// Custom JS file
import CP from './cp';

document.addEventListener("DOMContentLoaded", function() {
    /* -------------------------------------------------------------------------- */
    /*                                   Popover                                  */
    /* -------------------------------------------------------------------------- */
    const popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Initialize CP
    CP.init();
    window.CP = CP;
});
