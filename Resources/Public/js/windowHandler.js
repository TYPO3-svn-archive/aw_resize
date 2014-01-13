var WindowHandler =
{
    url : null,
    params : "",
    winObj : null,
    openWindow : function()
    {
        if(this.winObj != null)
            this.winObj.close();

        this.winObj = window.open(this.url,"popup",this.params);
        this.winObj.focus();
    }
};

jQuery(function() {
    $(".filelist")
        .on({
            click:function()
            {
                var self = $(this);
                var width = parseInt(self.attr("data-width")) + 10;
                var height = parseInt(self.attr("data-height")) + 10;

                WindowHandler.url = self.attr("src");
                WindowHandler.params = "width=" + width + ",height=" + height;

                WindowHandler.openWindow();
            }
        },"img")
    ;
});
