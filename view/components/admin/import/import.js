'use strict';

arikaim.component.onLoaded(function() {  
    var fileUpload = new FileUpload('#file_form',{
        url: '/api/admin/content/import',
        maxFiles: 1,
        allowMultiple: false,       
        formFields: {                     
        },
        onSuccess: function(result) {                            
            arikaim.ui.form.showMessage(result.message);
        }
    });     
});