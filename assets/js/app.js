/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Pickaday
import Pikaday from 'pikaday';
let picker = new Pikaday({
    field: document.getElementsByClassName('pickaday-datepicker')[0],
    format: 'DD.MM.YYYY',
    firstDay: 1,
    i18n: {
        previousMonth : 'Попередній місяць',
        nextMonth     : 'Наступний місяць',
        months        : ['Січень','Лютий','Березень','Квітень','Травень','Червень','Липень','Серпень','Вересень','Жовтень','Листопад','Грудень'],
        weekdays      : ['Неділя','Понеділок','Вівторок','Середа','Четвер','П\'ятниця','Субота'],
        weekdaysShort : ['Нд','Пн','Вт','Ср','Чт','Пт','Сб']
    },
    toString: function(date, format) {
        return ('0' + date.getDate()).slice(-2) + '.' + ('0' + (date.getMonth()+1)).slice(-2) + '.' + date.getFullYear();
    }
});

//CKeditor
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
ClassicEditor
    .create( document.querySelector( '.ckeditor-textarea' ), {
        toolbar: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable' ],
    })
    .catch( error => {
        console.log( error );
    });

// Bootstrap
require('bootstrap/js/dist/collapse.js');
require('bootstrap/js/dist/dropdown.js');
