<!--
function validate_form ( )
{
    valid = true;

        if ( document.form_question.form__name.value == "" )
        {                                                                        
                alert ( "Пожалуйста заполните поле 'Ваше имя'." );
                valid = false;
        }
        if ( document.form_question.form__email.value == "" )
        {
                alert ( "Пожалуйста заполните поле 'e-mail'." );
                valid = false;
        }
        if ( document.form_question.form__question.value == "" )
        {
                alert ( "Пожалуйста заполните поле 'Вопрос'." );
                valid = false;
        }
//*****************************************************************        
         if ( document.form_feedback.form__name.value == "" )
        {                                                                        
                alert ( "Пожалуйста заполните поле 'Ваша фамилия, имя и отчество'." );
                valid = false;
        }
        return valid;
}
//-->