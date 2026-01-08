'use strict';

arikaim.component.onLoaded(function() {
    $('.content-type-dropdown').on('change', function() {
        var value = $(this).val();

        return arikaim.page.loadContent({
            id: 'content_provider_selector',           
            component: 'content::selector.provider',
            params: { 
                type: value,
                class: 'fluid basic button selection' 
            }            
        },function(result) {
            $('#content_provider_dropdown').on('change', function() {
                var value = $(this).val();
                var type = $('.content-type-dropdown').val();
                    
                return arikaim.page.loadContent({
                    id: 'search_content',           
                    component: 'content::selector.search',
                    params: { 
                        provider: value,
                        type: type                               
                    }            
                });                
            });
        });        
    });
});