const validation = new JustValidate("#signup");

validation
.addField("#name", [
    {
        rule: "required",
        errorMessage: "Imię jest wymagane"
    }
])

.addField("#PESEL", [
    {
        rule: "required",
        errorMessage: "Numer PESEL nie może być pusty"
        
    },

    {
        rule: "number",
        errorMessage: "Numer PESEL musi zawierać tylko liczby"
    },

    {
        rule: "minLength",
        value: 11,
        errorMessage: "Numer PESEL musi mieć 11 cyfr"
    },

    {
        rule: "maxLength",
        value: 11,
        errorMessage: "Numer PESEL musi mieć 11 cyfr"
    },

    {
        validator: (value) => () => {
            if (value.trim() === "") {
                return Promise.resolve(true); 
            }
            return fetch("validate-number.php?PESEL=" + encodeURIComponent(value))
                   .then(function(response) {
                       return response.json();
                   })
                   .then(function(json) {
                       return json.available;
                   });
        },
        errorMessage: "Numer PESEL jest już zajęty"
    }
])


.addField("#city", [
    {
        rule: "required",
        errorMessage: "Miasto nie może być puste"
    }
])

.addField("#street", [
    {
        rule: "required",
        errorMessage: "Adres nie może być pusty"
    }
])



    .addField("#email", [
        {
            rule: "required",
            errorMessage: "Adres e-mail nie może być pusty"

        },
        {
            rule: "email",
            errorMessage: "Niepoprawny format adresu e-mail"

        },
        {
            validator: (value) => () => {
                if (value.trim() === "") {
                    return Promise.resolve(true);
                }
                return fetch("validate-email.php?email=" + encodeURIComponent(value))
                       .then(function(response) {
                           return response.json();
                       })
                       .then(function(json) {
                           return json.available;
                       });
            },
            errorMessage: "E-mail jest już zajęty"
        }
    ])

    
    

    .addField("#password", [
        {
            rule: "required",
            errorMessage: "Hasło nie może być puste"

        },
        {
            rule: "password"
        }
    ])
    .addField("#password_confirmation", [
        {
            validator: (value, fields) => {
                return value === fields["#password"].elem.value;
            },
            errorMessage: "Hasło muszą być takie same"
        }
    ])


    .addField("#phone_number", [
        {
            rule: "required",
            errorMessage: "Numer telefonu nie może być pusty"
        }, 

        {
            rule: 'number',
            errorMessage: "Numer telefonu może zawierać tylko cyfry"

        },
            
        {
            rule: 'minLength',
            value: 9,
            errorMessage: "Numer telefonu musi mieć minimum 9 cyfr"

        },

       
    ])



    .onSuccess((event) => {
        document.getElementById("signup").submit();
    });
    


    
    
    
    
    
    
    
    