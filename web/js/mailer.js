$(document).ready(function() {
    setInterval(reloadContent, 60000);
    reloadContent();
});

function reloadContent() {
    $.ajax({
        url: 'api.php?module=mailer',
        type: 'GET',
        dataType: 'json'
    })
    .done(function(data) {
        $('#page-content').html(generateTable(data));
    })
    .fail(function(data) {
        console.log('error ajax mailer');
    });
}

function generateTable(data) {
    var table;
    var tts = "";
    if (data.length > 0) {
        table = '<table class="table table-bordered table-hover">';

        for (var i = 0; i < data.length; ++i) {
            table += '<tr>';
            table += '<td>#'+ data[i].msgno +'</td>';
            table += '<td>'+ data[i].subject +'</td>';
            table += '<td>'+ data[i].from +'</td>';
            table += '<td>'+ data[i].date +'</td>';
            table += '</tr>';

            tts += data[i].from +'.';
        }

        table += '</table>';
    }

    speak(tts);
    return table;
}

function speak(text) {
    $.ajax({
        url: 'api.php?module=vocalizer',
        type: 'GET',
        dataType: 'json',
        data: {
            'tts': text,
            'lang': 'fr'
        }
    })
    .done(function(data) {
        $('#speaker').attr('src', data.filename);
    })
    .fail(function(data) {
        console.log('error ajax vocalizer');
    });
}
