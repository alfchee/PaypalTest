
/**
 * round decimals in some cool way
 * @param  {float} value    value to be rounded
 * @param  {integer} decimals number of decimals to show
 * @return {float}          value rounded
 */
function round(value, decimals) {
    if(value == 0)
        return 0;
    
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

$(document).on('ready',function() {

    var urlSelected = [];

    $('#result').on('change','.check-url', function(e) {
        var url = $(this).parent().parent().data('url'); // we obtain the url checked by user

        if($(this).is(':checked')) {
            urlSelected.push(url);
        } else {
            var index = urlSelected.indexOf(url);
            if(index > -1)
                urlSelected.splice(index,1);
        }
        console.log(urlSelected);
    });

    $('body').on('click','#add-site-manager', function(e) {
        e.preventDefault();

        $.post('/crawler/site-manager', { urls: urlSelected })
            .done(function(data) {
                alert(JSON.stringify(data));
                console.log(JSON.stringify(data));
            })
            .fail(function(message) {
                alert('An error occurred: ' + JSON.stringify(message) );
            });
    });

    $('#result').tablesorter();

    $('#search-submit').on('click', function(e) {
        e.preventDefault();

        var that = $(this);
        var url = that.parent().attr('action');
        var search = $('#url').val();
        var table = $('#result tbody');

        var pattern = /^https?:/i;
        if(!pattern.test(search))
            search = 'http://' + search;

        pattern = /\/$/i;
        if(!pattern.test(search))
            search += '/';

        $('#result').show();
        $('#add-site-manager').show();
        table.empty();
        var spinner = '<tr><td class="text-center" colspan="8"><i class="fa fa-refresh icon-spinner"></i></td></tr>';

        table.append(spinner);

        $.post(url,{ url: search })
            .done(function(data) {
                table.empty();
                console.log(JSON.stringify(data));
                $.each(data, function(index, value) {
                    var content = '<tr data-url="' + value.url + '">';
                        content += '<td class="text-center"><input type="checkbox" class="check-url" /></<d>';
                        content += '<td><span class="info-url">' + value.url + '</span><span class="info-title">' + value.data.uu + '</td>';
                        content += '<td class="text-center">' + round(value.data.ueid,2) + '</td>';
                        content += '<td class="text-center">' + round(value.data.uid, 2) + '</td>';
                        content += '<td class="text-center">' + round(value.data.umrp, 2) + '</td>';
                        content += '<td class="text-center">' + round(value.data.us, 2) + '</td>';
                        content += '<td class="text-center">' + round(value.data.upa, 2) + '</td>';
                        content += '<td class="text-center">' + round(value.data.pda, 2) + '</td></tr>';
                    table.append(content);
                    // let the plugin know that we made a update 
                    $('#result').trigger("update"); 
                });
            })
            .fail(function(data) {
                alert('An error occurred: ' + JSON.stringify(data));
            });
    });
});