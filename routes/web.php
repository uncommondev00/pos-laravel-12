<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PaymentAccountController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VariationTemplateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\UnitSellController;
use App\Http\Controllers\VoidTransactionController;
use App\Http\Controllers\XZReportController;
use App\Http\Controllers\SellPosController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\GroupTaxController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\InvoiceSchemeController;
use App\Http\Controllers\LabelsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LocationSettingsController;
use App\Http\Controllers\BusinessLocationController;
use App\Http\Controllers\InvoiceLayoutController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TransactionPaymentController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\ImportProductsController;
use App\Http\Controllers\SalesCommissionAgentController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\OpeningStockController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\ImportOpeningStockController;
use App\Http\Controllers\SellReturnController;
use App\Http\Controllers\BackUpController;
use App\Http\Controllers\SellingPriceGroupController;
use App\Http\Controllers\NotificationTemplateController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountReportsController;
use App\Http\Controllers\Restaurant\TableController;
use App\Http\Controllers\Restaurant\ModifierSetsController;
use App\Http\Controllers\Restaurant\ProductModifierSetController;
use App\Http\Controllers\Restaurant\KitchenController;
use App\Http\Controllers\Restaurant\OrderController;
use App\Http\Controllers\Restaurant\DataController;
use App\Http\Controllers\Restaurant\BookingController;
use App\Http\Controllers\DenominationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


include_once('install_r.php');

Route::get('/', function () {
    return view('front_end.index');
});


Route::middleware(['IsInstalled'])->group(function () {


    //clear cache if uploaded to NAS
    Route::get('/clear', function () {
        $exitCode = Artisan::call('config:clear');
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('config:cache');
        $exitCode = Artisan::call('view:clear');
        $exitCode = Artisan::call('route:clear');
        return 'DONE'; //Return anything
    });


    Route::get('invalid_mac', function () {
        return view('invalid_mac');
    });

    Route::get('login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/business/register', [BusinessController::class, 'getRegister'])->name('business.getRegister');
    Route::post('/business/register', [BusinessController::class, 'postRegister'])->name('business.postRegister');
    Route::post('/business/register/check-username', [BusinessController::class, 'postCheckUsername'])->name('business.postCheckUsername');
    Route::post('/business/register/check-email', [BusinessController::class, 'postCheckEmail'])->name('business.postCheckEmail');

    Route::get('/invoice/{token}', [SellPosController::class, 'showInvoice'])
        ->name('show_invoice');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['IsInstalled', 'auth', 'SetSessionData', 'language', 'timezone'])->group(function () {

    Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/business/points', [BusinessController::class, 'edit_points'])->name('business.edit_points');
    Route::post('/business/points/', [BusinessController::class, 'update_points'])->name('business.update_points');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/stock_alert', [HomeController::class, 'stockAlert'])->name('home.stock_alert');
    Route::get('/home/get-totals', [HomeController::class, 'getTotals'])->name('home.getTotals');
    Route::get('/home/get-totals1', [HomeController::class, 'getTotals'])->name('home.getTotals1');
    Route::get('/home/product-stock-alert', [HomeController::class, 'getProductStockAlert'])->name('home.getProductStockAlert');
    Route::get('/home/purchase-payment-dues', [HomeController::class, 'getPurchasePaymentDues'])->name('home.getPurchasePaymentDues');
    Route::get('/home/sales-payment-dues', [HomeController::class, 'getSalesPaymentDues'])->name('home.getSalesPaymentDues');

    Route::get('/load-more-notifications', [HomeController::class, 'loadMoreNotifications'])->name('home.loadMoreNotifications');

    Route::get('/business/settings', [BusinessController::class, 'getBusinessSettings'])->name('business.getBusinessSettings');
    Route::post('/business/update', [BusinessController::class, 'postBusinessSettings'])->name('business.postBusinessSettings');
    Route::get('/user/profile', [UserController::class, 'getProfile'])->name('user.getProfile');
    Route::post('/user/update', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::post('/user/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');

    Route::resource('brands', BrandController::class);
    //Route::resource('payment-account', PaymentAccountController::class);
    Route::resource('tax-rates', TaxRateController::class);
    Route::resource('units', UnitController::class);

    Route::get('/contacts/import', [ContactController::class, 'getImportContacts'])->name('contacts.import');
    Route::post('/contacts/import', [ContactController::class, 'postImportContacts'])->name('contacts.postImportContacts');
    Route::post('/contacts/check-contact-id', [ContactController::class, 'checkContactId'])->name('contacts.checkContactId');
    Route::get('/contacts/customers', [ContactController::class, 'getCustomers'])->name('contacts.getCustomers');
    Route::resource('contacts', ContactController::class);

    Route::resource('categories', CategoryController::class);
    Route::resource('variation-templates', VariationTemplateController::class);

    Route::post('/products/mass-deactivate', [ProductController::class, 'massDeactivate'])->name('products.massDeactivate');
    Route::get('/products/activate/{id}', [ProductController::class, 'activate'])->name('products.activate');
    Route::get('/products/view-product-group-price/{id}', [ProductController::class, 'viewGroupPrice'])->name('products.viewGroupPrice');
    Route::get('/products/add-selling-prices/{id}', [ProductController::class, 'addSellingPrices'])->name('products.addSellingPrices');
    Route::post('/products/save-selling-prices', [ProductController::class, 'saveSellingPrices'])->name('products.saveSellingPrices');
    Route::post('/products/mass-delete', [ProductController::class, 'massDestroy'])->name('products.massDestroy');
    Route::get('/products/view/{id}', [ProductController::class, 'view'])->name('products.view');
    Route::get('/products/list', [ProductController::class, 'getProducts'])->name('products.getProducts');
    Route::get('/products/list-no-variation', [ProductController::class, 'getProductsWithoutVariations'])->name('products.getProductsWithoutVariations');
    Route::post('/products/get_sub_categories', [ProductController::class, 'getSubCategories'])->name('products.getSubCategories');
    Route::post('/products/product_form_part', [ProductController::class, 'getProductVariationFormPart'])->name('products.getProductVariationFormPart');
    Route::post('/products/get_product_variation_row', [ProductController::class, 'getProductVariationRow'])->name('products.getProductVariationRow');
    Route::post('/products/get_variation_template', [ProductController::class, 'getVariationTemplate'])->name('products.getVariationTemplate');
    Route::get('/products/get_variation_value_row', [ProductController::class, 'getVariationValueRow'])->name('products.getVariationValueRow');
    Route::post('/products/check_product_sku', [ProductController::class, 'checkProductSku'])->name('products.checkProductSku');
    Route::get('/products/quick_add', [ProductController::class, 'quickAdd'])->name('products.quickAdd');
    Route::post('/products/save_quick_product', [ProductController::class, 'saveQuickProduct'])->name('products.saveQuickProduct');
    Route::resource('products', ProductController::class);

    Route::get('/purchases/get_products', [PurchaseController::class, 'getProducts'])->name('purchases.getProducts');
    Route::get('/purchases/get_suppliers', [PurchaseController::class, 'getSuppliers'])->name('purchases.getSuppliers');
    Route::post('/purchases/get_purchase_entry_row', [PurchaseController::class, 'getPurchaseEntryRow'])->name('purchases.getPurchaseEntryRow');
    Route::post('/purchases/check_ref_number', [PurchaseController::class, 'checkRefNumber'])->name('purchases.checkRefNumber');
    Route::get('/purchases/print/{id}', [PurchaseController::class, 'printInvoice'])->name('purchases.printInvoice');
    Route::get('/purchases/{id}/get_pp_logs', [PurchaseController::class, 'getPurchasePriceLogs'])->name('purchases.getPurchasePriceLogs');
    Route::resource('purchases', PurchaseController::class);

    Route::get('/toggle-subscription/{id}', [SellPosController::class, 'toggleRecurringInvoices'])->name('pos.toggleRecurringInvoices');
    Route::get('/sells/subscriptions', [SellPosController::class, 'listSubscriptions'])->name('pos.listSubscriptions');
    Route::get('/sells/invoice-url/{id}', [SellPosController::class, 'showInvoiceUrl'])->name('pos.showInvoiceUrl');
    Route::get('/sells/duplicate/{id}', [SellController::class, 'duplicateSell'])->name('sells.duplicateSell');
    Route::get('/sells/drafts', [SellController::class, 'getDrafts'])->name('sells.getDrafts');
    Route::get('/sells/quotations', [SellController::class, 'getQuotations'])->name('sells.getQuotations');
    Route::get('/sells/draft-dt', [SellController::class, 'getDraftDatables'])->name('sells.getDraftDatables');
    Route::resource('sells', SellController::class);

    // Per Unit Sells
    Route::resource('unit-sells', UnitSellController::class);

    // Voided Transaction
    Route::resource('voids', VoidTransactionController::class);

    // Z and X Reading Reports
    Route::get('/z_reading', [XZReportController::class, 'create'])->name('z_reading.create');
    Route::post('/z_reading/post', [XZReportController::class, 'store'])->name('z_reading.store');
    Route::get('/z_print', [XZReportController::class, 'print_this'])->name('z_reading.print');

    Route::get('/x_reading', [XZReportController::class, 'Xcreate'])->name('x_reading.create');
    Route::post('/x_reading/post', [XZReportController::class, 'store2'])->name('x_reading.store');
    Route::get('/x_print', [XZReportController::class, 'print_this2'])->name('x_reading.print');
    Route::get('/reports/xreading_print/{id}', [XZReportController::class, 'print_again'])->name('reports.xreading_print');
    Route::get('/reports/zreading_print/{id}', [XZReportController::class, 'print_again2'])->name('reports.zreading_print');
    // Note: Duplicate route '/reports/xreading_print' was removed to avoid conflicts

    Route::get('/sells/pos/get_product_row/{variation_id}/{location_id}', [SellPosController::class, 'getProductRow'])->name('pos.getProductRow');
    Route::post('/sells/pos/get_payment_row', [SellPosController::class, 'getPaymentRow'])->name('pos.getPaymentRow');
    Route::get('/sells/pos/get-recent-transactions', [SellPosController::class, 'getRecentTransactions'])->name('pos.getRecentTransactions');
    Route::get('/sells/{transaction_id}/print', [SellPosController::class, 'printInvoice'])->name('pos.printInvoice');
    Route::get('/sells/{transaction_id}/print2', [SellPosController::class, 'invoicePrint'])->name('pos.invoicePrint');
    Route::get('/sells/pos/get-product-suggestion', [SellPosController::class, 'getProductSuggestion'])->name('pos.getProductSuggestion');
    Route::get('/pos/price-checking', [SellPosController::class, 'price_checking'])->name('pos.price-checking');
    Route::resource('pos', SellPosController::class);

    Route::resource('roles', RoleController::class);
    Route::resource('users', ManageUserController::class);
    Route::resource('group-taxes', GroupTaxController::class);

    Route::get('/barcodes/set_default/{id}', [BarcodeController::class, 'setDefault'])->name('barcodes.setDefault');
    Route::resource('barcodes', BarcodeController::class);

    // Invoice schemes
    Route::get('/invoice-schemes/set_default/{id}', [InvoiceSchemeController::class, 'setDefault'])->name('invoice-schemes.setDefault');
    Route::resource('invoice-schemes', InvoiceSchemeController::class);

    // Print Labels
    Route::get('/labels/show', [LabelsController::class, 'show'])->name('labels.show');
    Route::get('/labels/add-product-row', [LabelsController::class, 'addProductRow'])->name('labels.addProductRow');
    Route::post('/labels/preview', [LabelsController::class, 'preview'])->name('labels.preview');

    // Reports
    Route::get('/reports/service-staff-report', [ReportController::class, 'getServiceStaffReport'])->name('reports.getServiceStaffReport');
    Route::get('/reports/service-staff-line-orders', [ReportController::class, 'serviceStaffLineOrders'])->name('reports.serviceStaffLineOrders');
    Route::get('/reports/table-report', [ReportController::class, 'getTableReport'])->name('reports.getTableReport');
    Route::get('/reports/profit-loss', [ReportController::class, 'getProfitLoss'])->name('reports.getProfitLoss');
    Route::get('/reports/get-opening-stock', [ReportController::class, 'getOpeningStock'])->name('reports.getOpeningStock');
    Route::get('/reports/purchase-sell', [ReportController::class, 'getPurchaseSell'])->name('reports.getPurchaseSell');
    Route::get('/reports/purchase-report', [ReportController::class, 'getPurchaseReport'])->name('reports.getPurchaseReport');
    Route::get('/reports/customer-supplier', [ReportController::class, 'getCustomerSuppliers'])->name('reports.getCustomerSuppliers');
    Route::get('/reports/stock-report', [ReportController::class, 'getStockReport'])->name('reports.getStockReport');
    Route::get('/reports/stock-details', [ReportController::class, 'getStockDetails'])->name('reports.getStockDetails');
    Route::get('/reports/tax-report', [ReportController::class, 'getTaxReport'])->name('reports.getTaxReport');
    Route::get('/reports/trending-products', [ReportController::class, 'getTrendingProducts'])->name('reports.getTrendingProducts');
    Route::get('/reports/expense-report', [ReportController::class, 'getExpenseReport'])->name('reports.getExpenseReport');
    Route::get('/reports/stock-adjustment-report', [ReportController::class, 'getStockAdjustmentReport'])->name('reports.getStockAdjustmentReport');
    Route::get('/reports/register-report', [ReportController::class, 'getRegisterReport'])->name('reports.getRegisterReport');
    Route::get('/reports/sales-representative-report', [ReportController::class, 'getSalesRepresentativeReport'])->name('reports.getSalesRepresentativeReport');
    Route::get('/reports/sales-representative-total-expense', [ReportController::class, 'getSalesRepresentativeTotalExpense'])->name('reports.getSalesRepresentativeTotalExpense');
    Route::get('/reports/sales-representative-total-sell', [ReportController::class, 'getSalesRepresentativeTotalSell'])->name('reports.getSalesRepresentativeTotalSell');
    Route::get('/reports/sales-representative-total-commission', [ReportController::class, 'getSalesRepresentativeTotalCommission'])->name('reports.getSalesRepresentativeTotalCommission');
    Route::get('/reports/stock-expiry', [ReportController::class, 'getStockExpiryReport'])->name('reports.getStockExpiryReport');
    Route::get('/reports/stock-expiry-edit-modal/{purchase_line_id}', [ReportController::class, 'getStockExpiryReportEditModal'])->name('reports.getStockExpiryReportEditModal');
    Route::post('/reports/stock-expiry-update', [ReportController::class, 'updateStockExpiryReport'])->name('reports.updateStockExpiryReport');
    Route::get('/reports/customer-group', [ReportController::class, 'getCustomerGroup'])->name('reports.getCustomerGroup');
    Route::get('/reports/product-purchase-report', [ReportController::class, 'getproductPurchaseReport'])->name('reports.getproductPurchaseReport');
    Route::get('/reports/product-sell-report', [ReportController::class, 'getproductSellReport'])->name('reports.getproductSellReport');
    Route::get('/reports/product-sell-grouped-report', [ReportController::class, 'getproductSellGroupedReport'])->name('reports.getproductSellGroupedReport');
    Route::get('/reports/lot-report', [ReportController::class, 'getLotReport'])->name('reports.getLotReport');
    Route::get('/reports/purchase-payment-report', [ReportController::class, 'purchasePaymentReport'])->name('reports.purchasePaymentReport');
    Route::get('/reports/sell-payment-report', [ReportController::class, 'sellPaymentReport'])->name('reports.sellPaymentReport');
    Route::get('/reports/product-stock-details', [ReportController::class, 'productStockDetails'])->name('reports.productStockDetails');
    Route::get('/reports/adjust-product-stock', [ReportController::class, 'adjustProductStock'])->name('reports.adjustProductStock');
    Route::get('/reports/x-reading', [ReportController::class, 'xReading'])->name('reports.xReading');
    Route::get('/reports/z-reading', [ReportController::class, 'zReading'])->name('reports.zReading');
    Route::get('/reports/opening-stocks-report', [ReportController::class, 'getproductOpeningStocksReport'])->name('reports.getproductOpeningStocksReport');
    Route::get('/reports/print_stock', [ReportController::class, 'printStockreport'])->name('reports.printStockreport');

    // Business Location Settings
    Route::prefix('business-location/{location_id}')->name('location.')->group(function () {
        Route::get('settings', [LocationSettingsController::class, 'index'])->name('settings');
        Route::post('settings', [LocationSettingsController::class, 'updateSettings'])->name('settings_update');
    });

    // Business Locations
    Route::post('business-location/check-location-id', [BusinessLocationController::class, 'checkLocationId'])->name('business-location.checkLocationId');
    Route::resource('business-location', BusinessLocationController::class);

    // Invoice layouts
    Route::resource('invoice-layouts', InvoiceLayoutController::class);

    // Expense Categories
    Route::resource('expense-categories', ExpenseCategoryController::class);

    // Expenses
    Route::resource('expenses', ExpenseController::class);

    // Transaction payments
    Route::get('/payments/opening-balance/{contact_id}', [TransactionPaymentController::class, 'getOpeningBalancePayments'])->name('payments.getOpeningBalancePayments');
    Route::get('/payments/show-child-payments/{payment_id}', [TransactionPaymentController::class, 'showChildPayments'])->name('payments.showChildPayments');
    Route::get('/payments/view-payment/{payment_id}', [TransactionPaymentController::class, 'viewPayment'])->name('payments.viewPayment');
    Route::get('/payments/add_payment/{transaction_id}', [TransactionPaymentController::class, 'addPayment'])->name('payments.addPayment');
    Route::get('/payments/pay-contact-due/{contact_id}', [TransactionPaymentController::class, 'getPayContactDue'])->name('payments.getPayContactDue');
    Route::post('/payments/pay-contact-due', [TransactionPaymentController::class, 'postPayContactDue'])->name('payments.postPayContactDue');
    Route::resource('payments', TransactionPaymentController::class);

    // Printers
    Route::resource('printers', PrinterController::class);

    Route::get('/stock-adjustments/remove-expired-stock/{purchase_line_id}', [StockAdjustmentController::class, 'removeExpiredStock'])->name('stock-adjustments.removeExpiredStock');
    Route::post('/stock-adjustments/get_product_row', [StockAdjustmentController::class, 'getProductRow'])->name('stock-adjustments.getProductRow');
    Route::resource('stock-adjustments', StockAdjustmentController::class);

    Route::get('/cash-register/register-details', [CashRegisterController::class, 'getRegisterDetails'])->name('cash-register.getRegisterDetails');
    Route::get('/cash-register/close-register', [CashRegisterController::class, 'getCloseRegister'])->name('cash-register.getCloseRegister');
    Route::post('/cash-register/close-register', [CashRegisterController::class, 'postCloseRegister'])->name('cash-register.postCloseRegister');
    Route::resource('cash-register', CashRegisterController::class);

    // Import products
    Route::get('/import-products', [ImportProductsController::class, 'index'])->name('import-products.index');
    Route::post('/import-products/store', [ImportProductsController::class, 'store'])->name('import-products.store');

    // Sales Commission Agent
    Route::resource('sales-commission-agents', SalesCommissionAgentController::class);

    // Stock Transfer
    Route::get('stock-transfers/print/{id}', [StockTransferController::class, 'printInvoice'])->name('stock-transfers.printInvoice');
    Route::resource('stock-transfers', StockTransferController::class);

    Route::get('/opening-stocks/add/{product_id}', [OpeningStockController::class, 'add'])->name('opening-stocks.add');
    Route::post('/opening-stock/save', [OpeningStockController::class, 'save'])->name('opening-stocks.save');

    // Customer Groups
    Route::resource('customer-group', CustomerGroupController::class);

    // Import opening stock
    Route::get('/import-opening-stock', [ImportOpeningStockController::class, 'index'])->name('import-opening-stock.index');
    Route::post('/import-opening-stock/store', [ImportOpeningStockController::class, 'store'])->name('import-opening-stock.store');

    // Sell return
    Route::resource('sell-return', SellReturnController::class);
    Route::get('sell-return/get-product-row', [SellReturnController::class, 'getProductRow'])->name('sell-return.getProductRow');
    Route::get('/sell-return/print/{id}', [SellReturnController::class, 'printInvoice'])->name('sell-return.printInvoice');
    Route::get('/sell-return/add/{id}', [SellReturnController::class, 'add'])->name('sell-return.add');

    // Backup
    Route::get('backup/download/{file_name}', [BackUpController::class, 'download'])->name('backup.download');
    Route::get('backup/delete/{file_name}', [BackUpController::class, 'delete'])->name('backup.delete');
    Route::resource('backup', BackUpController::class)->only(['index', 'create', 'store']);

    Route::resource('selling-price-group', SellingPriceGroupController::class);

    Route::resource('notification-templates', NotificationTemplateController::class)->only(['index', 'store']);
    Route::get('notification/get-template/{transaction_id}/{template_for}', [NotificationController::class, 'getTemplate'])->name('notification.getTemplate');
    Route::post('notification/send', [NotificationController::class, 'send'])->name('notification.send');

    Route::get('/purchase-return/add/{id}', [PurchaseReturnController::class, 'add'])->name('purchase-return.add');
    Route::resource('/purchase-return', PurchaseReturnController::class);

    Route::get('/discount/activate/{id}', [DiscountController::class, 'activate'])->name('discount.activate');
    Route::post('/discount/mass-deactivate', [DiscountController::class, 'massDeactivate'])->name('discount.massDeactivate');
    Route::resource('discount', DiscountController::class);

    Route::group(['prefix' => 'account'], function () {
        Route::resource('/account', AccountController::class);
        Route::get('/fund-transfer/{id}', [AccountController::class, 'getFundTransfer'])->name('account.getFundTransfer');
        Route::post('/fund-transfer', [AccountController::class, 'postFundTransfer'])->name('account.postFundTransfer');
        Route::get('/deposit/{id}', [AccountController::class, 'getDeposit'])->name('account.getDeposit');
        Route::post('/deposit', [AccountController::class, 'postDeposit'])->name('account.postDeposit');
        Route::get('/close/{id}', [AccountController::class, 'close'])->name('account.close');
        Route::get('/delete-account-transaction/{id}', [AccountController::class, 'destroyAccountTransaction'])->name('account.destroyAccountTransaction');
        Route::get('/get-account-balance/{id}', [AccountController::class, 'getAccountBalance'])->name('account.getAccountBalance');
        Route::get('/balance-sheet', [AccountReportsController::class, 'balanceSheet'])->name('account.balanceSheet');
        Route::get('/trial-balance', [AccountReportsController::class, 'trialBalance'])->name('account.trialBalance');
        Route::get('/payment-account-report', [AccountReportsController::class, 'paymentAccountReport'])->name('account.paymentAccountReport');
        Route::get('/link-account/{id}', [AccountReportsController::class, 'getLinkAccount'])->name('account.getLinkAccount');
        Route::post('/link-account', [AccountReportsController::class, 'postLinkAccount'])->name('account.postLinkAccount');
        Route::get('/cash-flow', [AccountController::class, 'cashFlow'])->name('account.cashFlow');
    });

    // Restaurant module
    Route::group(['prefix' => 'modules'], function () {
        Route::resource('tables', TableController::class);
        Route::resource('modifiers', ModifierSetsController::class);

        // Map modifier to products
        Route::get('/product-modifiers/{id}/edit', [ProductModifierSetController::class, 'edit'])->name('product-modifiers.edit');
        Route::post('/product-modifiers/{id}/update', [ProductModifierSetController::class, 'update'])->name('product-modifiers.update');
        Route::get('/product-modifiers/product-row/{product_id}', [ProductModifierSetController::class, 'product_row'])->name('product-modifiers.product_row');
        Route::get('/add-selected-modifiers', [ProductModifierSetController::class, 'add_selected_modifiers'])->name('product-modifiers.add_selected_modifiers');

        Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
        Route::get('/kitchen/mark-as-cooked/{id}', [KitchenController::class, 'markAsCooked'])->name('kitchen.markAsCooked');
        Route::post('/refresh-orders-list', [KitchenController::class, 'refreshOrdersList'])->name('kitchen.refreshOrdersList');
        Route::post('/refresh-line-orders-list', [KitchenController::class, 'refreshLineOrdersList'])->name('kitchen.refreshLineOrdersList');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/mark-as-served/{id}', [OrderController::class, 'markAsServed'])->name('orders.markAsServed');
        Route::get('/data/get-pos-details', [DataController::class, 'getPosDetails'])->name('data.getPosDetails');
        Route::get('/orders/mark-line-order-as-served/{id}', [OrderController::class, 'markLineOrderAsServed'])->name('orders.markLineOrderAsServed');
    });

    Route::get('bookings/get-todays-bookings', [BookingController::class, 'getTodaysBookings'])->name('bookings.getTodaysBookings');
    Route::resource('bookings', BookingController::class);

    // for denomination in close register
    Route::get('/denomination', [DenominationController::class, 'getDenonimation'])->name('denomination.getDenonimation');
    Route::get('/open_drawer', [DenominationController::class, 'openDrawer'])->name('denomination.openDrawer');
});

//require __DIR__.'/auth.php';
