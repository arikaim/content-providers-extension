'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.ui.form.onSubmit("#content_form",function() {  
        return contentApi.add('#content_form');
    },function(result) {
        arikaim.ui.form.clear('#content_form');
        arikaim.ui.form.showMessage(result.message);
    });
});