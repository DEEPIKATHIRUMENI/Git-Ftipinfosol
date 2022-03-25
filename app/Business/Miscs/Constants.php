<?php

if (!defined('APP_NAME')) {
    define('APP_NAME', env('APP_NAME', 'ZEBRA PRO'));
    define('APP_DOMAIN', env('APP_DOMAIN', 'BusinessDomain'));

    //Cache names
    define('CACHE_DURATION', '300');
    define('CACHE_SETTING', 'setting');
    define('CACHE_THEME', 'theme');
    define('CACHE_BANK', 'bank');
    define('CACHE_BRANCH', 'branch');
    define('CACHE_USER', 'user');
    define('CACHE_ROLE', 'role');
    define('CACHE_PERMISSION', 'permission');
    define('CACHE_EMPLOYEE', 'employee');
    define('CACHE_PARTY', 'party');
    define('CACHE_ITEM', 'item');
    define('CACHE_UNIT', 'unit');
    define('CACHE_EXPENSE', 'expense');
    define('CACHE_INVOICE', 'Invoice');
    define('CACHE_INVOICE_DETAIL', 'InvoiceDetail');
    define('CACHE_CREDITNOTE', 'CreditNote');
    define('CACHE_CREDITNOTE_DETAIL', 'CreditNoteDetail');
    define('CACHE_DEBITNOTE', 'DebitNote');
    define('CACHE_DEBITNOTE_DETAIL', 'DebitNoteDetail');
    define('CACHE_DC', 'Dc');
    define('CACHE_DC_DETAIL', 'DcDetail');
    define('CACHE_PURCHASE', 'Purchase');
    define('CACHE_PURCHASE_DETAIL', 'PurchaseDetail');
    define('CACHE_PURCHASE_ORDER', 'PurchaseOrder');
    define('CACHE_PURCHASE_ORDER_DETAIL', 'PurchaseOrderDetail');
    define('CACHE_INVOICE_PAYMENT', 'InvoicePayment');
    define('CACHE_INVOICE_PAYMENT_DETAILS', 'InvoicePaymentDetail');
    define('CACHE_PURCHASE_PAYMENT', 'PurchasePayment');
    define('CACHE_PURCHASE_PAYMENT_DETAILS', 'PurchasePaymentDetail');
     
    define('RETURN_SUCCESS', 'success');
    define('RETURN_FAILURE', 'failure');
    define('RETURN_VALIDATION', 'validation');
    define('RETURN_DATA', 'data');

    define('RESULT_SUCCESS', 'success');
    define('RESULT_FAILURE', 'failure');

    define('DEFAULT_DATE_FORMAT', 'M j, Y');
    define('DEFAULT_DATE_PICKER_FORMAT', 'M d, yyyy');
    define('DEFAULT_DATETIME_FORMAT', 'F j, Y g:i a');
    define('DEFAULT_DATETIME_MOMENT_FORMAT', 'MMM D, YYYY h:mm:ss a');

    define('SAVE_SUCCESS', 'Saved Successfully');
    define('SAVE_FAILURE', 'Saved Failure');
    define('SAVE_REFUSE', 'Record cannot be saved. Because ');

    define('UPDATE_SUCCESS', 'Updated Successfully');
    define('UPDATE_FAILURE', 'Updated Failure');
    define('UPDATE_REFUSE', 'Record cannot be updated. Because ');
    define('UPDATE_NO_RECORD', 'No record found. Please refresh page and try again');

    define('DELETE_SUCCESS', 'Deleted Successfully');
    define('DELETE_FAILURE', 'Deleted Failure');
    define('DELETE_REFUSE', 'Record cannot be deleted. Because ');
    define('DELETE_NO_RECORD', 'No record found. Please refresh page and try again');

    define('SELECT_NO_RECORD', 'No record found. Please add some records');











// TODO: Modify Later
    
    define('MENU_SETTING', 1);
  
    define('MENU_ROLE', 2);
        define('ACTION_ROLE_CREATE', 1);
        define('ACTION_ROLE_EDIT', 2);
        define('ACTION_ROLE_DELETE', 3);

    define('MENU_PERMISSION', 3);

    define('MENU_USER', 4);
        define('ACTION_USER_CREATE', 4);
        define('ACTION_USER_EDIT', 5);
        define('ACTION_USER_DELETE', 6);
     
    define('MENU_EMPLOYEE', 5);
        define('ACTION_EMPLOYEE_CREATE', 7);
        define('ACTION_EMPLOYEE_EDIT', 8);
        define('ACTION_EMPLOYEE_DELETE', 9);
       

    define('MENU_PARTY', 6);
        define('ACTION_PARTY_CREATE', 10);   
        define('ACTION_PARTY_EDIT', 11);
        define('ACTION_PARTY_DELETE', 12);

    define('MENU_UNIT', 7);
        define('ACTION_UNIT_CREATE', 13);
        define('ACTION_UNIT_EDIT', 14);
        define('ACTION_UNIT_DELETE', 15);

    define('MENU_ITEM', 8);
        define('ACTION_ITEM_CREATE', 16);
        define('ACTION_ITEM_EDIT', 17);
        define('ACTION_ITEM_DELETE', 18);


    define('MENU_PURCHASE', 9);
        define('ACTION_PURCHASE_CREATE', 19);
        define('ACTION_PURCHASE_EDIT', 20);
        define('ACTION_PURCHASE_DELETE', 21);
        define('ACTION_PURCHASE_PRINT', 22);


    define('MENU_DEBITNOTE', 10);
        define('ACTION_DEBITNOTE_CREATE', 23);
        define('ACTION_DEBITNOTE_EDIT', 24);
        define('ACTION_DEBITNOTE_DELETE', 25);
        define('ACTION_DEBITNOTE_PRINT', 26);
        define('ACTION_DEBITNOTE_STATUS_CHANGE', 27);

    define('MENU_PURCHASE_PAYMENT', 11);
        define('ACTION_PURCHASE_PAYMENT_CREATE', 28);
        define('ACTION_PURCHASE_PAYMENT_EDIT', 29);
        define('ACTION_PURCHASE_PAYMENT_DELETE', 30);
        define('ACTION_PURCHASE_PAYMENT_PRINT', 31);

    define('MENU_DC', 12);
        define('ACTION_DC_CREATE', 32);
        define('ACTION_DC_EDIT', 33);
        define('ACTION_DC_DELETE', 34);
        define('ACTION_DC_PRINT', 35);
        define('ACTION_DC_PROCEED_TO', 36);

    define('MENU_INVOICE', 13);
        define('ACTION_INVOICE_CREATE', 37);
        define('ACTION_INVOICE_EDIT', 38);
        define('ACTION_INVOICE_DELETE', 39);
        define('ACTION_INVOICE_PRINT', 40);
        define('ACTION_INVOICE_STATUS_CHANGE', 41);
    
    define('MENU_CREDITNOTE', 14);
        define('ACTION_CREDITNOTE_CREATE', 42);
        define('ACTION_CREDITNOTE_EDIT', 43);
        define('ACTION_CREDITNOTE_DELETE', 44);
        define('ACTION_CREDITNOTE_PRINT', 45);
        define('ACTION_CREDITNOTE_STATUS_CHANGE', 46);
      
    define('MENU_INVOICE_PAYMENT', 15);
        define('ACTION_INVOICE_PAYMENT_CREATE', 47);
        define('ACTION_INVOICE_PAYMENT_EDIT', 48);
        define('ACTION_INVOICE_PAYMENT_DELETE', 49);
        define('ACTION_INVOICE_PAYMENT_PRINT', 50);

    define('MENU_EXPENSE', 16);
        define('ACTION_EXPENSE_CREATE', 51);
        define('ACTION_EXPENSE_EDIT', 52);
        define('ACTION_EXPENSE_DELETE', 53);

    define('MENU_SALES_GST_STATEMENT', 54);
    define('MENU_PURCHASE_GST_STATEMENT', 55);
    define('MENU_CUSTOMER_LEDGER', 56);
    define('MENU_SUPPLIER_LEDGER', 57);
    define('MENU_CUSTOMER_OUTSTANDING', 58);
    define('MENU_SUPPLIER_OUTSTANDING', 59);
    define('MENU_HSN_SUMMARY', 60);
    define('MENU_EXPENSE_REPORT', 61);
    define('MENU_STOCK_STATEMENT', 62);
    define('MENU_STOCK_LEDGER', 63);
    define('MENU_DASHBOARD', 64);

}
