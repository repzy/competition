//CKeditor
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
ClassicEditor
    .create( document.querySelector( '.ckeditor-textarea' ), {
        toolbar: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable' ],
    })
    .catch( error => {
        console.log( error );
    });
