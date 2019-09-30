/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything

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