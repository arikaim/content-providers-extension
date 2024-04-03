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
        
        $('.status-dropdown').dropdown({
            onChange: function(value) {
                var uuid = $(this).attr('uuid');
                content.setStatus(uuid,value);               
            }
        });

        arikaim.ui.button('.delete-content',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });
            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
                description: message
            },function() {
                contentApi.delete(uuid,function(result) {
                    $('#' + uuid).remove();                
                });
            });
        });
    };

    this.init = function() {
        this.loadMessages('content::admin');

        paginator.init('items_list',{
            name: 'content::admin.content.view.rows',
            params: {
                namespace: 'content'
            }
        }); 

        contentView.initRows();
        arikaim.ui.loadComponentButton('.create-content');
    };
}

var contentView = new createObject(contentView,ControlPanelView);

arikaim.component.onLoaded(function() {
    contentView.init();
});