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