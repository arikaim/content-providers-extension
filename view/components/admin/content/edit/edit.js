'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit("#content_form",function() {  
        return contentApi.update('#content_form');
    },function(result) {       
        arikaim.ui.form.showMessage(result.message);
    });   
});