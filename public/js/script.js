$(document).ready(function () {
    var $TABLE = $('#table');

    $.get("/notes/all", function(data) {
        data.forEach(function(item, index) {
            var $clone = $TABLE.find('tr.hide').clone(true).removeClass('hide');
            
            $clone.find("#id").attr({value: item.id});
            $clone.find("#name").attr({value: item.name});
            $clone.find("#company").attr({value: item.company});
            $clone.find("#message").val(item.message);
            
            $TABLE.find('table').append($clone);
        });
    });
    
    $('.table-remove').click(function () {
        var $parent = $(this).parents('tr');
        var $id = $parent.find("#id").val();
        
        $.ajax({
            url: '/notes/' + $id,
            type: 'DELETE',
            success: function(result) {
                $parent.detach('tr');
            },
            error: function (result) {
                alert("An error occurred while deleting record.");
            }
        });
    });
    
    $('.table-update').click(function() {
        var $parent = $(this).parents('tr');
        var $id = $parent.find('#id').val();
        var data = {
            name: $parent.find("#name").val(),
            company: $parent.find("#company").val(),
            message: $parent.find('#message').val()
        };
        
        $.ajax({
            url: '/notes/' + $id,
            type: 'PUT',
            data: data,
            error: function (result) {
                location.reload();
            }
        });
    });
});