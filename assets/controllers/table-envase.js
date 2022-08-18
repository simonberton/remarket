import $ from 'jquery';
global.$ = global.jQuery = $;
import '../styles/admin.css';

// start the Stimulus application
import '../bootstrap';

import { Grid, html } from "gridjs";
import "gridjs/dist/theme/mermaid.css";
import { esES } from "gridjs/l10n";

const grid = new Grid({
    columns: [ {
        name: 'Actions',
        formatter: (_, row) => html(`<a href='${row.cells[0].data}'>Editar</a>`)
    },'Nombre', 'Precio', 'Cantidad'],
    search: true,
    sort: true,
    resizable: true,
    pagination: {
        enabled: true,
    },
    language: esES,
    data: eval($('.jsColumnsData').data('data')),

}).render(document.getElementById("table"));

$('.gridjs-tbody .gridjs-tr').find("[data-column-id='editar']").on('click', function() {
    let url = $('.jsEditLink').data('link').replace('id', $(this).val());
    console.log(url);
});
$( document ).ready(function() {
    console.log( "ready!" );
    $('.gridjs-tbody .gridjs-tr').find("[data-column-id='editar']").on('click', function() {
        let url = $('.jsEditLink').data('link').replace('id', $(this).val());
        console.log(url);
    });
});