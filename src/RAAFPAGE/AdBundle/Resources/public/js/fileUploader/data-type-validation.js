var dataTypeValidation = function () {};

dataTypeValidation.prototype = {
    isNumeric: function (data) {
        console.log('checking numeric data');
        var RE = /^-{0,1}\d*\.{0,1}\d+$/;
        return (RE.test(data));
    },
    isValidDate: function (inputDate) {
        var d = new Date();
        return (d.toDateString() == inputDate.toDateString())
    },
    isEmailValid: function (email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    },
    isPhoneValid: function (input) {
        var re = /^\d{11}$/;

        if (!re.test(input)) {
            console.log('invalid phone num');
            return false
        }

        return true;
    },
    isValidUrl: function (url) {
        var re = /^(ftp|http|https):\/\/[^ "]+$/;

        if (!re.test(url)) {
            return false;
        }

        return true;
    }
};