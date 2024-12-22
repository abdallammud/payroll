<?php 


require('./assets/tcpdf/tcpdf.php');
require('./app/init.php');
if (!authenticate()) {
    header("Location: ".baseUri()."/login ");
    exit; // Important to exit to prevent further execution
}
 
if(isset($_GET['print'])) {
	if($_GET['print'] == 'payslip') {
		require('prints/payslip.php');
	} else if($_GET['print'] == 'customers') {
		require('prints/allCustomers.php');
	} else if($_GET['print'] == 'book_transactions') {
		require('prints/book_transactions.php');
	} else if($_GET['print'] == 'customer_transactions') {
		require('prints/customer_transactions.php');
	} else if($_GET['print'] == 'booksCheckedout') {
		require('prints/books_checked_out.php');
	} else if($_GET['print'] == 'overdueBooks') {
		require('prints/overdue_books.php');
	} else if($_GET['print'] == 'returnedBooks') {
		require('prints/returned_books.php');
	}
} else {header("Location: /");}

?>