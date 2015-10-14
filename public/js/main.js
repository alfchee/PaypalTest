$(document).on('ready', function() {

    // to show field to create or edit notes
    $('.notes').on('click', '.add-note',function(e) {
        e.preventDefault();

        // show the field
        $(this).parent().children('.note-edition').show();
        $(this).hide();
    });

    // save notes
    $('.notes').on('click', '.save-note',function(e) {
        e.preventDefault();

        var that = $(this);
        var row = $(this).parent().parent().parent();
        var comment = that.siblings('textarea').val();
        var commentId = row.data('comment-id');

        if(comment) {
            var data = { movieId: row.data('movie-id'), comment: comment };

            if(commentId)
                data.commentId = commentId;

            // send the data
            $.post('processNotes.php',data)
                .done(function(data) {
                    console.log(JSON.stringify(data));
                    that.parent().hide();
                    that.parent().siblings().remove();
                    that.parent().parent().append('<p class="note-text">' + comment + '</p>');
                    row.data('comment-id',data.id);
                })
                .fail(function(data) {
                    alert('An error occurred: ' + JSON.stringify(data));
                });
        } else {
            // delete functions
            var data = { method: 'DELETE', commentId: row.data('comment-id') };
            // delete request
            $.post('processNotes.php',data)
            .done(function(data) {
                console.log(JSON.stringify(data));
                row.data('comment-id',null);
                that.parent().hide();
                that.siblings('textarea').val('');
                that.parent().siblings().remove();
                that.parent().parent().append('<a href="#" class="add-note btn btn-primary">Add Note</a>');
            })
            .fail(function(data) {
                alert('An error occurred: ' + JSON.stringify(data));
            });
        }

    });

    $('.notes').on('dblclick', '.note-text', function(e) {
        $(this).siblings('.note-edition').show();
        $(this).hide();
    });

});