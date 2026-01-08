/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function contentView() {
    var self = this;
    
    this.initRows = function() {
        arikaim.ui.loadComponentButton('.content-action');

        arikaim.ui.button('.edit-content',function(element) {
            var uuid = $(element).attr('uuid');

            arikaim.page.loadContent({
                id: 'content_details',
                component: 'content::admin.content.edit',
                params: { uuid: uuid }
            }); 
        });
        
        $('.status-dropdown').on('change', function() {
            var value = $(this).val();
            var uuid = $(this).attr('uuid');
            
            contentApi.setStatus(uuid,value);               
        });

        arikaim.ui.button('.delete-content',function(element) {
            var uuid = $(element).attr('uuid');
            
            arikaim.ui.getComponent('confirm_delete').open(function() {
                contentApi.delete(uuid,function(result) {
                    $('#' + result.uuid).remove();                
                });
            },'Delete content item');          
        });
    };

    this.init = function() {
        this.loadMessages('content::admin');
        arikaim.ui.loadComponentButton('.create-content');

        this.initRows();        
    };
}

var contentView = new createObject(contentView,ControlPanelView);

arikaim.component.onLoaded(function() {
    contentView.init();
});