import 'moment';
import 'moment/locale/uk';

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
