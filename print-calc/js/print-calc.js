$(document).ready(function(){
    CalculationRequestForm.init();
});

var CalculationRequestForm = {
   init: function(){
       var formReload = ['BindingID','BookColorTypeID','CoverDecorationTypeID'];
        $('form[id$="CalculationRequestForm"] *[name]').change(function(){
            var $this = $(this);
            if (formReload.indexOf($this.attr('name'))>=0) {
                CalculationRequestForm.reloadForm();
            }
        });
   },
    reloadForm : function(params) {
        var link = window.location.href.replace(/\/$/,'') + '/reloadForm',
            rForm = $('form[id$="CalculationRequestForm"]');
        $.getJSON(link, rForm.serialize(), function(json){
            if (json && json.form) {
                rForm.replaceWith(json.form);
                CalculationRequestForm.init();
            }
        });
    }
};