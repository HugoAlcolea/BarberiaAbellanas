$(document).ready(function () {
    $('#downloadButton').click(function () {
        var doc = new jsPDF();

        doc.text('Prueba OK', 10, 10);

        doc.save('ticket.pdf');
    });
});