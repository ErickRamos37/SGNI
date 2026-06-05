import './bootstrap';
import 'bootstrap';     // Importacion del framework de diseño Bootstrap

// Importar jQuery y hacerlo global
import $ from 'jquery';
window.$ = window.jQuery = $;

// Importamos DataTables y su integración con Bootstrap 5
import DataTable from 'datatables.net-bs5';
window.DataTable = DataTable;

// Configuración global por defecto para DataTables
if (window.DataTable) {
    Object.assign(window.DataTable.defaults, {
        processing: true, // Activado globalmente para todas las tablas
        language: {
            // Apunta directamente a la carpeta public/js/
            url: '/js/datatables-es.json' 
        }
    });
}