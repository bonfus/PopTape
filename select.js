$('#start').on('change click', function() {
    var stval = parseInt(this.value, 10);
    $("#end").val(0);
    $("#end option").each(function()
    {
        // Add $(this).val() to your list
        if (parseInt($(this).val(),10) > stval && parseInt($(this).val(),10) <= (stval+9*60*60))
        {
            $(this).prop('disabled', false);
        } else {
            $(this).prop('disabled', true);
        }
    });
    $("#end").prop('disabled', false);
})
