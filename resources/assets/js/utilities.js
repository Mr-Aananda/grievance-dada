// Start checkbox all check function ========================> 
function checkAll(event, input) {
    for (var i = 0; i < input.length; i++) {
        if (event.target.checked) {
            input[i].checked = true;
        } else {
            input[i].checked = false;
        }
    }

}
// End checkbox all check function ========================> 


// Print
function printable(area) {
    var printContents = document.getElementById(area).innerHTML, // get printable content 
        originalContents = document.body.innerHTML; // get document content
    
    // make a temporary document for printable content
    document.body.innerHTML = printContents;
    window.print(); // print the current document

    // restore the original document 
    document.body.innerHTML = originalContents;
}