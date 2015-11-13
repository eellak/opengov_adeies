    $(document).ready(function() {
    $('#dataTables-example').DataTable( {
        language: {
            lengthMenu: "Εμφάνιση _MENU_ καταχωρήσεων ανά σελίδα",
            zeroRecords: "Κανένα Αποτέλεσμα",
            info: "Σελίδα _PAGE_ από _PAGES_",
            infoEmpty: "Δεν υπάρχουν διαθέσιμα δεδομένα",
            infoFiltered: "(φιλτραρίστηκαν _MAX_ συνολικές καταχωρήσεις)",
            loadingRecords: "Φόρτωση...",
            processing:     "Επεξεργασία...",
    search:         "Αναζήτηση:",
    paginate: {
        first:      "Πρώτη",
        last:       "Τελευταία",
        next:       "Επόμενη",
        previous:   "Προηγούμενη"
    },
    aria: {
        sortAscending:  ": αύξουσα ταξινόμηση",
        sortDescending: ": φθίνουσα ταξινόμηση"
    }
        }
    } );
} );