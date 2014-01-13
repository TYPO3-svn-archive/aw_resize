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

    $(".imageSelection")
        .on({
            click : function()
            {
                var checkboxes = $(".fileList input[type='checkbox']");

                switch ($(this).attr("class"))
                {
                    case "all":
                        checkboxes.prop("checked", true);
                    break;

                    case "none":
                        checkboxes.prop("checked", false);
                    break;

                    case "invert":
                        $.each(checkboxes, function(key, value)
                        {
                            $(value).prop("checked", !$(value).prop("checked"));
                        });
                    break;

                    case "filetype_jpg":
                        $.each($(".fileList input[type='checkbox'][data-extension='jpg']"), function(key, value)
                        {
                            $(value).prop("checked", !$(value).prop("checked"));
                        });
                    break;

                    case "filetype_png":
                        $.each($(".fileList input[type='checkbox'][data-extension='png']"), function(key, value)
                        {
                            $(value).prop("checked", !$(value).prop("checked"));
                        });
                    break;

                    case "filetype_gif":
                        $.each($(".fileList input[type='checkbox'][data-extension='gif']"), function(key, value)
                        {
                            $(value).prop("checked", !$(value).prop("checked"));
                        });
                    break;

                }

                return false;
            }
        },"a");
});
