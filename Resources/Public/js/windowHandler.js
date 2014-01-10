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
    console.log("loaded");
    $(".filelist")
        .on({
            click:function()
            {
                var self = $(this);
                var width = self.attr("data-width");
                var height = self.attr("data-height");

                WindowHandler.url = self.attr("src");
                WindowHandler.params = "width=" + width + ",height=" + height;

                WindowHandler.openWindow();
            }
        },"img")
    ;
});
