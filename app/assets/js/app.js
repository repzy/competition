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
    }
});

// TinyMCE
import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/silver';
import 'tinymce/plugins/table';
tinymce.init({
    selector: '.tinymce-textarea',
    skin: false,
    height: 500,
    plugins: [
        'table'
    ],
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | fontsizeselect'
});