$(document).ready(function () {
        // active page shadow
        $("#register").addClass('text_shadow');


        // Initialize form validation on the registration form.
        // It has the name attribute "registration"


        // $('#register_submit').click(function (e) {
        //     e.preventDefault();
        //     if ($("#registration").valid()) {
        //         alert('valid!');
        //     } else {
        //         alert('invalid!');
        //     }
        // });


        // Custom Method for pass validation, only number of chars
        $.validator.addMethod('strongPass', function(value, element){
            return this.optional(element) || value.length >= 8 && value.length <= 15;
        }, 'Your password must be between 8 and 15 characters');

        // Custom method for phone validation
        $.validator.addMethod('telephone', function(value, element){
            return this.optional(element) || value.length === 10;
        }, 'Your phone number must be 10 digits');

        //
        $.validator.setDefaults({
            errorClass: 'help-block',
            highlight: function (element) {
                $(element).closest('.input-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.input-group').removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                if (element.prop('type')=== 'checkbox'){
                    error.insertAfter(element.parent());
                }else {
                    error.insertAfter(element);
                }
                
            }

        });

        $.validator.addMethod('mailRegEx', function(value, element){
            return this.optional(element) || /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/.test(value);
        }, 'Please enter a <i>valid email</i> address');

        $('#registration').validate({
            rules: {
                username: {
                    required: true,
                    alphanumeric: true
                },

                password: {
                    required: true,
                    strongPass: true
                },

                confirmPass: {
                    required: true,
                    equalTo: "#password"
                },

                firstName: {
                    required: true,
                    nowhitespace: true,
                    lettersonly: true
                },

                lastName: {
                    required: true,
                    nowhitespace: true,
                    lettersonly: true
                },

                email: {
                    required: true,
                    mailRegEx: true
                },

                phone: {
                    required: true,
                    telephone: true
                },

                address: {
                    required: true
                },

                age: {
                    required: true
                }
            },

            messages: {
                username: {
                    alphanumeric: "Cannot contain special symbols"
                },
                // password: {
                //     required: "Please provide a password"
                // },
                address: "Please enter your address",
                email:{
                    required: "Please enter an email address"
                },
                confirmPass:{
                    equalTo: "Enter same password again"
                },
                age: "Your age must be 18+"
            }

        });

    $('#updateUser').validate({
        rules: {
            username: {
                required: true,
                alphanumeric: true
            },

            firstName: {
                required: true,
                nowhitespace: true,
                lettersonly: true
            },

            lastName: {
                required: true,
                nowhitespace: true,
                lettersonly: true
            },

            email: {
                required: true,
                mailRegEx: true
            },

            phone: {
                required: true,
                telephone: true
            },

            address: {
                required: true
            },

            age: {
                required: true
            }
        },

        messages: {
            username: {
                alphanumeric: "Cannot contain special symbols"
            },
            address: "Please enter your address",
            email:{
                required: "Please enter an email address"
            },
            age: "Your age must be 18+"
        }

    });


});
