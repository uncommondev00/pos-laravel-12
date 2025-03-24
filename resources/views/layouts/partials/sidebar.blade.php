@inject('request', 'Illuminate\Http\Request')

<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p> -->
          <!-- Status -->
          <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div> -->

      <!-- search form (Optional) -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">

        <!-- Call superadmin module if defined -->
        {{-- @if(Module::has('Superadmin'))
          @include('superadmin::layouts.partials.sidebar')
        @endif --}}

        <!-- Call ecommerce module if defined -->
        {{-- @if(Module::has('Ecommerce'))
          @include('ecommerce::layouts.partials.sidebar')
        @endif --}}
        <!-- <li class="header">HEADER</li> -->
        <li class="{{ $request->segment(1) == 'home' ? 'active' : '' }}">
          <a href="{{route('home')}}">
            <i class="fa fa-dashboard"></i> <span>
            @lang('home.home')</span>
          </a>
        </li>

        <!--product alert-->
         @can('stock_report.view')
        <li class="{{ $request->segment(1) == 'stock_alert' ? 'active' : '' }} ">
          <a href="{{route('home.stock_alert')}}" class="">
            <i class="fa fa-exclamation-triangle text-yellow"></i> <span>
            @lang('Stock Alert')</span>
          </a>
        </li>
        @endcan

        @if(auth()->user()->can('user.view') || auth()->user()->can('user.create') || auth()->user()->can('roles.view'))
        <li class="treeview {{ in_array($request->segment(1), ['roles', 'users', 'sales-commission-agents']) ? 'active active-sub' : '' }}">
            <a href="#">
                <i class="fa fa-users"></i>
                <span class="title">@lang('user.user_management')</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
              @can( 'user.view' )
                <li class="{{ $request->segment(1) == 'users' ? 'active active-sub' : '' }}">
                  <a href="{{route('users.index')}}">
                      <i class="fa fa-user"></i>
                      <span class="title">
                          @lang('user.users')
                      </span>
                  </a>
                </li>
              @endcan
              @can('roles.view')
                <li class="{{ $request->segment(1) == 'roles' ? 'active active-sub' : '' }}">
                  <a href="{{route('roles.index')}}">
                      <i class="fa fa-briefcase"></i>
                      <span class="title">
                        @lang('user.roles')
                      </span>
                  </a>
                </li>
              @endcan
              @can('user.create')
                <li class="{{ $request->segment(1) == 'sales-commission-agents' ? 'active active-sub' : '' }}">
                  <a href="{{route('sales-commission-agents.index')}}">
                      <i class="fa fa-handshake-o"></i>
                      <span class="title">
                        @lang('lang_v1.sales_commission_agents')
                      </span>
                  </a>
                </li>
              @endcan
            </ul>
        </li>
        @endif
        @if(auth()->user()->can('supplier.view') || auth()->user()->can('customer.view') )
          <li class="treeview {{ in_array($request->segment(1), ['contacts', 'customer-group']) ? 'active active-sub' : '' }}" id="tour_step4">
            <a href="#" id="tour_step4_menu"><i class="fa fa-address-book"></i> <span>@lang('contact.contacts')</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @can('supplier.view')
                <li class="{{ $request->input('type') == 'supplier' ? 'active' : '' }}"><a href="{{route('contacts.index', ['type' => 'supplier'])}}"><i class="fa fa-star"></i> @lang('report.supplier')</a></li>
              @endcan

              @can('customer.view')
                <li class="{{ $request->input('type') == 'customer' ? 'active' : '' }}"><a href="{{route('contacts.index', ['type' => 'customer'])}}"><i class="fa fa-star"></i> @lang('report.customer')</a></li>

                <li class="{{ $request->segment(1) == 'customer-group' ? 'active' : '' }}"><a href="{{route('customer-group.index')}}"><i class="fa fa-users"></i> @lang('lang_v1.customer_groups')</a></li>
              @endcan

              @if(auth()->user()->can('supplier.create') || auth()->user()->can('customer.create') )
                <li class="{{ $request->segment(1) == 'contacts' && $request->segment(2) == 'import' ? 'active' : '' }}"><a href="{{route('contacts.import')}}"><i class="fa fa-download"></i> @lang('lang_v1.import_contacts')</a></li>
              @endcan

            </ul>
          </li>
        @endif

        @if(auth()->user()->can('product.view') || 
        auth()->user()->can('product.create') || 
        auth()->user()->can('brand.view') ||
        auth()->user()->can('unit.view') ||
        auth()->user()->can('category.view') ||
        auth()->user()->can('brand.create') ||
        auth()->user()->can('unit.create') ||
        auth()->user()->can('category.create') )
          <li class="treeview {{ in_array($request->segment(1), ['variation-templates', 'products', 'labels', 'import-products', 'import-opening-stock', 'selling-price-group', 'brands', 'units', 'categories']) ? 'active active-sub' : '' }}" id="tour_step5">
            <a href="#" id="tour_step5_menu"><i class="fa fa-cubes"></i> <span>@lang('sale.products')</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @can('product.view')
                <li class="{{ $request->segment(1) == 'products' && $request->segment(2) == '' ? 'active' : '' }}"><a href="{{route('products.index')}}"><i class="fa fa-list"></i>@lang('lang_v1.list_products')</a></li>
              @endcan
              @can('product.create')
                <li class="{{ $request->segment(1) == 'products' && $request->segment(2) == 'create' ? 'active' : '' }}"><a href="{{route('products.create')}}"><i class="fa fa-plus-circle"></i>@lang('product.add_product')</a></li>
              @endcan
              @can('product.view')
                <li class="{{ $request->segment(1) == 'labels' && $request->segment(2) == 'show' ? 'active' : '' }}"><a href="{{route('labels.show')}}"><i class="fa fa-barcode"></i>@lang('barcode.print_labels')</a></li>
              @endcan
              @can('product.create')
                <li class="{{ $request->segment(1) == 'variation-templates' ? 'active' : '' }}"><a href="{{route('variation-templates.index')}}"><i class="fa fa-circle-o"></i><span>@lang('product.variations')</span></a></li>
              @endcan
              @can('product.create')
                <li class="{{ $request->segment(1) == 'import-products' ? 'active' : '' }}"><a href="{{route('import_products.index')}}"><i class="fa fa-download"></i><span>@lang('product.import_products')</span></a></li>
              @endcan
              @can('product.opening_stock')
                <li class="{{ $request->segment(1) == 'import-opening-stock' ? 'active' : '' }}"><a href="{{route('import_opening_stock.index')}}"><i class="fa fa-download"></i><span>@lang('lang_v1.import_opening_stock')</span></a></li>
              @endcan
              @can('product.create')
                <li class="{{ $request->segment(1) == 'selling-price-group' ? 'active' : '' }}"><a href="{{route('selling-price-group.index')}}"><i class="fa fa-circle-o"></i><span>@lang('lang_v1.selling_price_group')</span></a></li>
              @endcan
              
              @if(auth()->user()->can('unit.view') || auth()->user()->can('unit.create'))
                <li class="{{ $request->segment(1) == 'units' ? 'active' : '' }}">
                  <a href="{{route('units.index')}}"><i class="fa fa-balance-scale"></i> <span>@lang('unit.units')</span></a>
                </li>
              @endif

              @if(auth()->user()->can('category.view') || auth()->user()->can('category.create'))
                <li class="{{ $request->segment(1) == 'categories' ? 'active' : '' }}">
                  <a href="{{route('categories.index')}}"><i class="fa fa-tags"></i> <span>@lang('category.categories') </span></a>
                </li>
              @endif

              @if(auth()->user()->can('brand.view') || auth()->user()->can('brand.create'))
                <li class="{{ $request->segment(1) == 'brands' ? 'active' : '' }}">
                  <a href="{{route('brands.index')}}"><i class="fa fa-diamond"></i> <span>@lang('brand.brands')</span></a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        @if(auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') || auth()->user()->can('purchase.update') )
        <li class="treeview {{in_array($request->segment(1), ['purchases', 'purchase-return']) ? 'active active-sub' : '' }}" id="tour_step6">
          <a href="#" id="tour_step6_menu"><i class="fa fa-arrow-circle-down"></i> <span>@lang('purchase.purchases')</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @can('purchase.view')
              <li class="{{ $request->segment(1) == 'purchases' && $request->segment(2) == null ? 'active' : '' }}"><a href="{{route('purchases.index')}}"><i class="fa fa-list"></i>@lang('purchase.list_purchase')</a></li>
            @endcan
            @can('purchase.create')
              <li class="{{ $request->segment(1) == 'purchases' && $request->segment(2) == 'create' ? 'active' : '' }}"><a href="{{route('purchases.create')}}"><i class="fa fa-plus-circle"></i> @lang('purchase.add_purchase')</a></li>
            @endcan
            @can('purchase.update')
              <li class="{{ $request->segment(1) == 'purchase-return' ? 'active' : '' }}"><a href="{{route('purchase-return.index')}}"><i class="fa fa-undo"></i> @lang('lang_v1.list_purchase_return')</a></li>
            @endcan
          </ul>
        </li>
        @endif

        @if(auth()->user()->can('sell.view') || auth()->user()->can('sell.create') || auth()->user()->can('direct_sell.access') )
          
          <li class="treeview {{  in_array( $request->segment(1), ['sells', 'unit_sells', 'voids', 'pos', 'sell-return', 'ecommerce', 'discount']) ? 'active active-sub' : '' }}" id="tour_step7">
            <a  href="#" id="tour_step7_menu"><i class="fa fa-arrow-circle-up"></i> <span>@lang('sale.sale')</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>


            <ul class="treeview-menu">
              @can('direct_sell.access')
                <li class="{{ $request->segment(1) == 'sells' && $request->segment(2) == null ? 'active' : '' }}" ><a href="{{route('sells.index')}}"><i class="fa fa-list"></i>@lang('lang_v1.all_sales')</a></li>
              @endcan
              <!-- Call superadmin module if defined -->
              {{-- @if(Module::has('Ecommerce'))
                @include('ecommerce::layouts.partials.sell_sidebar')
              @endif --}}
              @can('direct_sell.access')
                <li class="{{ $request->segment(1) == 'sells' && $request->segment(2) == 'create' ? 'active' : '' }}"><a href="{{route('sells.create')}}"><i class="fa fa-plus-circle"></i>@lang('sale.add_sale')</a></li>
              @endcan
              @can('direct_sell.access')
                <li class="{{ $request->segment(1) == 'pos' && $request->segment(2) == null ? 'active' : '' }}" ><a href="{{route('pos.index')}}"><i class="fa fa-list"></i>@lang('sale.list_pos')</a></li>
              @endcan
              @can('sell.create')
                <li class="{{ $request->segment(1) == 'pos' && $request->segment(2) == 'create' ? 'active' : '' }}"><a href="{{route('pos.create')}}"><i class="fa fa-plus-circle"></i>@lang('sale.pos_sale')</a></li>
               
              @endcan
              @can('direct_sell.access')
                <li class="{{ $request->segment(1) == 'sell-return' && $request->segment(2) == null ? 'active' : '' }}" ><a href="{{route('sell-return.index')}}"><i class="fa fa-undo"></i>@lang('lang_v1.list_sell_return')</a></li>
                
                 <li class="{{ $request->segment(1) == 'sells' && $request->segment(2) == 'drafts' ? 'active' : '' }}" ><a href="{{route('sells.getDrafts')}}"><i class="fa fa-pencil-square" aria-hidden="true"></i>@lang('lang_v1.list_drafts')</a></li>

                <li class="{{ $request->segment(1) == 'sells' && $request->segment(2) == 'quotations' ? 'active' : '' }}" ><a href="{{route('sells.getQuotations')}}"><i class="fa fa-pencil-square" aria-hidden="true"></i>@lang('lang_v1.list_quotations')</a></li>
              @endcan
              {{-- 
              @can('discount.access')
                <li class="{{ $request->segment(1) == 'discount' ? 'active' : '' }}" ><a href="{{action('DiscountController@index')}}"><i class="fa fa-percent"></i>@lang('lang_v1.discounts')</a></li>
              @endcan
              --}}

              @if(in_array('subscription', $enabled_modules))
                <li class="{{ $request->segment(1) == 'subscriptions'? 'active' : '' }}" ><a href="{{route('pos.listSubscriptions')}}"><i class="fa fa-recycle"></i>@lang('lang_v1.subscriptions')</a></li>
              @endif

              @can('sell.view')
                <li class="{{ $request->segment(1) == 'unit_sells' && $request->segment(2) == null ? 'active' : '' }}" id="veri"><a href="#"><i class="fa fa-list"></i>Per Unit Sales</a></li>
              @endcan
              
              @can('sell.view')
                <li class="{{ $request->segment(1) == 'voids' && $request->segment(2) == null ? 'active' : '' }}" ><a href="{{route('voids.index')}}"><i class="fa fa-list"></i>Voided Transaction</a></li>
              @endcan

            </ul>
          </li>
        @endif

        {{-- @if(Module::has('Repair'))
          @include('repair::layouts.partials.sidebar')
        @endif --}}

        @if(auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') )

         
          <li class="treeview {{ $request->segment(1) == 'stock-transfers' ? 'active active-sub' : '' }}">
            <a href="#"><i class="fa fa-truck" aria-hidden="true"></i> <span>@lang('lang_v1.stock_transfers')</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @can('purchase.view')
                <li class="{{ $request->segment(1) == 'stock-transfers' && $request->segment(2) == null ? 'active' : '' }}"><a href="{{route('stock-transfers.index')}}"><i class="fa fa-list"></i>@lang('lang_v1.list_stock_transfers')</a></li>
              @endcan

               @if(auth()->user()->roles[0]->id == 1 ||   auth()->user()->roles[0]->id == 3 ||   auth()->user()->roles[0]->id == 9)

              @can('purchase.create')
                <li class="{{ $request->segment(1) == 'stock-transfers' && $request->segment(2) == 'create' ? 'active' : '' }}"><a href="{{route('stock-transfers.create')}}"><i class="fa fa-plus-circle"></i>@lang('lang_v1.add_stock_transfer')</a></li>
              @endcan

              @endif
            </ul>
          </li>
          
        @endif

        @if(auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') )

         
        <li class="treeview {{ $request->segment(1) == 'stock-adjustments' ? 'active active-sub' : '' }}">
          <a href="#"><i class="fa fa-database" aria-hidden="true"></i> <span>@lang('stock_adjustment.stock_adjustment')</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @can('purchase.view')
              <li class="{{ $request->segment(1) == 'stock-adjustments' && $request->segment(2) == null ? 'active' : '' }}"><a href="{{route('stock-adjustments.index')}}"><i class="fa fa-list"></i>@lang('stock_adjustment.list')</a></li>
            @endcan
             @if(auth()->user()->roles[0]->id == 1 || auth()->user()->roles[0]->id == 3)
              @can('purchase.create')
                <li class="{{ $request->segment(1) == 'stock-adjustments' && $request->segment(2) == 'create' ? 'active' : '' }}"><a href="{{route('stock-adjustments.create')}}"><i class="fa fa-plus-circle"></i>@lang('stock_adjustment.add')</a></li>
              @endcan
            @endif
          </ul>
        </li>
          
        @endif

        @if(auth()->user()->can('expense.access'))
        <li class="treeview {{  in_array( $request->segment(1), ['expense-categories', 'expenses']) ? 'active active-sub' : '' }}">
          <a href="#"><i class="fa fa-minus-circle"></i> <span>@lang('expense.expenses')</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $request->segment(1) == 'expenses' && empty($request->segment(2)) ? 'active' : '' }}"><a href="{{route('expenses.index')}}"><i class="fa fa-list"></i>@lang('lang_v1.list_expenses')</a></li>
            <li class="{{ $request->segment(1) == 'expenses' && $request->segment(2) == 'create' ? 'active' : '' }}"><a href="{{route('expenses.create')}}"><i class="fa fa-plus-circle"></i>@lang('messages.add') @lang('expense.expenses')</a></li>
            <li class="{{ $request->segment(1) == 'expense-categories' ? 'active' : '' }}"><a href="{{route('expense-categories.index')}}"><i class="fa fa-circle-o"></i>@lang('expense.expense_categories')</a></li>
          </ul>
        </li>
        @endif

        @can('account.access')
          @if(in_array('account', $enabled_modules))
            <li class="treeview {{ $request->segment(1) == 'account' ? 'active active-sub' : '' }}">
              <a href="#"><i class="fa fa-money" aria-hidden="true"></i> <span>@lang('lang_v1.payment_accounts')</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                  <li class="{{ $request->segment(1) == 'account' && $request->segment(2) == 'account' ? 'active' : '' }}"><a href="{{route('account.index')}}"><i class="fa fa-list"></i>@lang('account.list_accounts')</a></li>

                  <li class="{{ $request->segment(1) == 'account' && $request->segment(2) == 'balance-sheet' ? 'active' : '' }}"><a href="{{route('account-reports.balanceSheet')}}"><i class="fa fa-book"></i>@lang('account.balance_sheet')</a></li>

                  <li class="{{ $request->segment(1) == 'account' && $request->segment(2) == 'trial-balance' ? 'active' : '' }}"><a href="{{route('account-reports.trialBalance')}}"><i class="fa fa-balance-scale"></i>@lang('account.trial_balance')</a></li>

                  <li class="{{ $request->segment(1) == 'account' && $request->segment(2) == 'cash-flow' ? 'active' : '' }}"><a href="{{route('account.cashFlow')}}"><i class="fa fa-exchange"></i>@lang('lang_v1.cash_flow')</a></li>

                  <li class="{{ $request->segment(1) == 'account' && $request->segment(2) == 'payment-account-report' ? 'active' : '' }}"><a href="{{route('account-reports.paymentAccountReport')}}"><i class="fa fa-file-text-o"></i>@lang('account.payment_account_report')</a></li>
              </ul>
            </li>
          @endif
        @endcan

        @if(auth()->user()->can('purchase_n_sell_report.view') 
          || auth()->user()->can('contacts_report.view') 
          || auth()->user()->can('stock_report.view') 
          || auth()->user()->can('tax_report.view') 
          || auth()->user()->can('trending_product_report.view') 
          || auth()->user()->can('sales_representative.view') 
          || auth()->user()->can('register_report.view')
          || auth()->user()->can('expense_report.view')
          )
          @php
            $user_role = explode("#", auth()->user()->getRoleNames()[0], 2)[0];
          @endphp

          @if($user_role == "StockMan" || $user_role == "ENGEL")
            <li class="treeview {{  in_array( $request->segment(1), ['reports']) ? 'active active-sub' : '' }}" id="tour_step8">
            <a href="#" id="tour_step8_menu"><i class="fa fa-bar-chart-o"></i> <span>@lang('report.reports')</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

               <li class="{{ $request->segment(2) == 'product-sell-report' ? 'active' : '' }}" ><a href="{{action('ReportController@getproductSellReport')}}"><i class="fa fa-arrow-circle-up"></i>@lang('lang_v1.product_sell_report')</a></li>

               <li class="{{ $request->segment(2) == 'product-purchase-report' ? 'active' : '' }}" ><a href="{{action('ReportController@getproductPurchaseReport')}}"><i class="fa fa-arrow-circle-down"></i>@lang('lang_v1.product_purchase_report')</a></li>
            </ul>
          </li>
          @else
            <li class="treeview {{  in_array( $request->segment(1), ['reports']) ? 'active active-sub' : '' }}" id="tour_step8">
            <a href="#" id="tour_step8_menu"><i class="fa fa-bar-chart-o"></i> <span>@lang('report.reports')</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @can('purchase_n_sell_report.view')
                <!--x reading-->
                <li class="{{ $request->segment(2) == 'x-reading' ? 'active' : '' }}" ><a href="{{route('reports.xReading')}}"><i class="fa fa-list-alt"></i>@lang('X Reading')</a></li>
                <!--z reading-->
                <li class="{{ $request->segment(2) == 'z-reading' ? 'active' : '' }}" ><a href="{{route('reports.zReading')}}"><i class="fa fa-list-alt"></i>@lang('Z Reading')</a></li>
              @endcan

              @can('profit_loss_report.view')
                <li class="{{ $request->segment(2) == 'profit-loss' ? 'active' : '' }}" ><a href="{{route('reports.getProfitLoss')}}"><i class="fa fa-money"></i>@lang('report.profit_loss')</a></li>
              @endcan

              @can('purchase_n_sell_report.view')
                <li class="{{ $request->segment(2) == 'purchase-sell' ? 'active' : '' }}" ><a href="{{route('reports.getPurchaseSell')}}"><i class="fa fa-exchange"></i>@lang('report.purchase_sell_report')</a></li>
                <li class="{{ $request->segment(2) == 'purchase-report' ? 'active' : '' }}" ><a href="{{route('reports.getPurchaseReport')}}"><i class="fa fa-arrow-circle-down"></i>@lang('Stock Value Report')</a></li>
              @endcan

              @can('tax_report.view')
              <li class="{{ $request->segment(2) == 'product-purchase-report' ? 'active' : '' }}" ><a href="{{route('reports.getproductPurchaseReport')}}"><i class="fa fa-arrow-circle-down"></i>@lang('lang_v1.product_purchase_report')</a></li>
                
              @endcan

              @can('contacts_report.view')
                <li class="{{ $request->segment(2) == 'customer-supplier' ? 'active' : '' }}" ><a href="{{route('reports.getCustomerSuppliers')}}"><i class="fa fa-address-book"></i>@lang('report.contacts')</a></li>

                <li class="{{ $request->segment(2) == 'customer-group' ? 'active' : '' }}" ><a href="{{route('reports.getCustomerGroup')}}"><i class="fa fa-users"></i>@lang('lang_v1.customer_groups_report')</a></li>
              @endcan
              
              @can('stock_report.view')
                <li class="{{ $request->segment(2) == 'stock-report' ? 'active' : '' }}" ><a href="{{route('reports.getStockReport')}}"><i class="fa fa-hourglass-half" aria-hidden="true"></i>@lang('report.stock_report')</a></li>

                <li class="{{ $request->segment(2) == 'opening-stocks-report' ? 'active' : '' }}" ><a href="{{route('reports.getproductOpeningStocksReport')}}"><i class="fa fa-database"></i>@lang('Opening Stocks Report')</a></li>
              @endcan

              @can('stock_report.view')
                @if(session('business.enable_product_expiry') == 1)
                <li class="{{ $request->segment(2) == 'stock-expiry' ? 'active' : '' }}" ><a href="{{route('reports.getStockExpiryReport')}}"><i class="fa fa-calendar-times-o"></i>@lang('report.stock_expiry_report')</a></li>
                @endif
              @endcan

              @can('stock_report.view')
                <li class="{{ $request->segment(2) == 'lot-report' ? 'active' : '' }}" ><a href="{{route('reports.getLotReport')}}"><i class="fa fa-hourglass-half" aria-hidden="true"></i>@lang('lang_v1.lot_report')</a></li>
                
              @endcan

              @can('trending_product_report.view')
                <li class="{{ $request->segment(2) == 'trending-products' ? 'active' : '' }}" ><a href="{{route('reports.getTrendingProducts')}}"><i class="fa fa-line-chart" aria-hidden="true"></i>@lang('report.trending_products')</a></li>
              @endcan

              @can('stock_report.view')
                <li class="{{ $request->segment(2) == 'stock-adjustment-report' ? 'active' : '' }}" ><a href="{{route('reports.getStockAdjustmentReport')}}"><i class="fa fa-sliders"></i>@lang('report.stock_adjustment_report')</a></li>
              @endcan

              @can('purchase_n_sell_report.view')
                
                <!--change-->
                <li class="{{ $request->segment(2) == 'tax-report' ? 'active' : '' }}" ><a href="{{route('reports.getTaxReport')}}"><i class="fa fa-tumblr" aria-hidden="true"></i>@lang('report.tax_report')</a></li>
                

                <li class="{{ $request->segment(2) == 'product-sell-report' ? 'active' : '' }}" ><a href="{{route('reports.getproductSellReport')}}"><i class="fa fa-arrow-circle-up"></i>@lang('lang_v1.product_sell_report')</a></li>

                <li class="{{ $request->segment(2) == 'purchase-payment-report' ? 'active' : '' }}" ><a href="{{route('reports.purchasePaymentReport')}}"><i class="fa fa-money"></i>@lang('lang_v1.purchase_payment_report')</a></li>

                <li class="{{ $request->segment(2) == 'sell-payment-report' ? 'active' : '' }}" ><a href="{{route('reports.sellPaymentReport')}}"><i class="fa fa-money"></i>@lang('lang_v1.sell_payment_report')</a></li>
              @endcan

              @can('expense_report.view')
                <li class="{{ $request->segment(2) == 'expense-report' ? 'active' : '' }}" ><a href="{{route('reports.getExpenseReport')}}"><i class="fa fa-search-minus" aria-hidden="true"></i></i>@lang('report.expense_report')</a></li>
              @endcan

              @can('register_report.view')
                <li class="{{ $request->segment(2) == 'register-report' ? 'active' : '' }}" ><a href="{{route('reports.getRegisterReport')}}"><i class="fa fa-briefcase"></i>@lang('report.register_report')</a></li>
              @endcan

              @can('sales_representative.view')
                <li class="{{ $request->segment(2) == 'sales-representative-report' ? 'active' : '' }}" ><a href="{{route('reports.getSalesRepresentativeReport')}}"><i class="fa fa-user" aria-hidden="true"></i>@lang('report.sales_representative')</a></li>
              @endcan

              @if(in_array('tables', $enabled_modules))
                @can('purchase_n_sell_report.view')
                  <li class="{{ $request->segment(2) == 'table-report' ? 'active' : '' }}" ><a href="{{route('reports.getTableReport')}}"><i class="fa fa-table"></i>@lang('restaurant.table_report')</a></li>
                @endcan
              @endif
              @if(in_array('service_staff', $enabled_modules))
                @can('sales_representative.view')
                <li class="{{ $request->segment(2) == 'service-staff-report' ? 'active' : '' }}" ><a href="{{route('reports.getServiceStaffReport')}}"><i class="fa fa-user-secret"></i>@lang('restaurant.service_staff_report')</a></li>
                @endcan
              @endif

            </ul>
          </li>
          @endif

          
        @endif

        @can('backup')
          <li class="treeview {{  in_array( $request->segment(1), ['backup']) ? 'active active-sub' : '' }}">
              <a href="{{route('backup.index')}}"><i class="fa fa-dropbox"></i> <span>@lang('lang_v1.backup')</span>
              </a>
          </li>
        @endrole

        <!-- Call restaurant module if defined -->
        @if(in_array('tables', $enabled_modules) && in_array('service_staff', $enabled_modules) )
          @if(auth()->user()->can('crud_all_bookings') || auth()->user()->can('crud_own_bookings') )
          <li class="treeview {{ $request->segment(1) == 'bookings'? 'active active-sub' : '' }}">
              <a href="{{route('bookings.index')}}"><i class="fa fa-calendar-check-o"></i> <span>@lang('restaurant.bookings')</span></a>
          </li>
          @endif
        @endif

        @if(in_array('kitchen', $enabled_modules))
          <li class="treeview {{ $request->segment(1) == 'modules' && $request->segment(2) == 'kitchen' ? 'active active-sub' : '' }}">
              <a href="{{route('kitchen.index')}}"><i class="fa fa-fire"></i> <span>@lang('restaurant.kitchen')</span></a>
          </li>
        @endif
        @if(in_array('service_staff', $enabled_modules))
          <li class="treeview {{ $request->segment(1) == 'modules' && $request->segment(2) == 'orders' ? 'active active-sub' : '' }}">
              <a href="{{route('orders.index')}}"><i class="fa fa-list-alt"></i> <span>@lang('restaurant.orders')</span></a>
          </li>
        @endif

        @can('send_notifications')
          <li class="treeview hide {{  $request->segment(1) == 'notification-templates' ? 'active active-sub' : '' }}">
              <a href="{{route('notification-templates.index')}}"><i class="fa fa-envelope"></i> <span>@lang('lang_v1.notification_templates')</span>
              </a>
          </li>
        @endrole
        
        @if(auth()->user()->can('business_settings.access') || 
        auth()->user()->can('barcode_settings.access') ||
        auth()->user()->can('invoice_settings.access') ||
        auth()->user()->can('tax_rate.view') ||
        auth()->user()->can('tax_rate.create'))
        
        
        <li class="treeview @if( in_array($request->segment(1), ['business', 'tax-rates', 'barcodes', 'invoice-schemes', 'business-location', 'invoice-layouts', 'printers', 'subscription']) || in_array($request->segment(2), ['tables', 'modifiers']) ) {{'active active-sub'}} @endif">
        
          <a href="#" id="tour_step2_menu"><i class="fa fa-cog"></i> <span>@lang('business.settings')</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" id="tour_step3">
            @can('business_settings.access')
              <li class="{{ $request->segment(1) == 'business' ? 'active' : '' }}">
                <a href="{{route('business.getBusinessSettings')}}" id="tour_step2"><i class="fa fa-cogs"></i> @lang('business.business_settings')</a>
              </li>
              <li class="{{ $request->segment(1) == 'business-location' ? 'active' : '' }}" >
                <a href="{{route('business-location.index')}}"><i class="fa fa-map-marker"></i> @lang('business.business_locations')</a>
              </li>
            @endcan
            @can('invoice_settings.access')
              <li class="@if( in_array($request->segment(1), ['invoice-schemes', 'invoice-layouts']) ) {{'active'}} @endif">
                <a href="{{route('invoice-schemes.index')}}"><i class="fa fa-file"></i> <span>@lang('invoice.invoice_settings')</span></a>
              </li>
            @endcan
            
            @can('barcode_settings.access')
            <li class="{{ $request->segment(1) == 'barcodes' ? 'active' : '' }}">
              <a href="{{route('barcodes.index')}}"><i class="fa fa-barcode"></i> <span>@lang('barcode.barcode_settings')</span></a>
            </li>
            @endcan

            <li class="{{ $request->segment(1) == 'printers' ? 'active' : '' }}">
              <a href="{{route('printers.index')}}"><i class="fa fa-share-alt"></i> <span>@lang('printer.receipt_printers')</span></a>
            </li>

            @if(auth()->user()->can('tax_rate.view') || auth()->user()->can('tax_rate.create'))
              <li class="{{ $request->segment(1) == 'tax-rates' ? 'active' : '' }}">
                <a href="{{route('tax-rates.index')}}"><i class="fa fa-bolt"></i> <span>@lang('tax_rate.tax_rates')</span></a>
              </li>
            @endif

            <!--add points-->
            <li class="{{ $request->segment(2) == 'business/points' ? 'active' : '' }}">
                <a href="{{route('business.edit_points')}}" id="tour_step2"><i class="fa fa-credit-card"></i> <span>Add Points</span></a>
            </li>

            @if(in_array('tables', $enabled_modules))
               @can('business_settings.access')
                <li class="{{ $request->segment(1) == 'modules' && $request->segment(2) == 'tables' ? 'active' : '' }}">
                  <a href="{{route('tables.index')}}"><i class="fa fa-table"></i> @lang('restaurant.tables')</a>
                </li>
              @endcan
            @endif

             

            @if(in_array('modifiers', $enabled_modules))
              @if(auth()->user()->can('product.view') || auth()->user()->can('product.create') )
                <li class="{{ $request->segment(1) == 'modules' && $request->segment(2) == 'modifiers' ? 'active' : '' }}">
                  <a href="{{route('modifiers.index')}}"><i class="fa fa-delicious"></i> @lang('restaurant.modifiers')</a>
                </li>
              @endif
            @endif

            {{-- @if(Module::has('Superadmin'))
              @include('superadmin::layouts.partials.subscription')
            @endif --}}

          </ul>
        </li>
        @endif
        <!-- call Essentials module if defined -->
        {{-- @if(Module::has('Essentials'))
          @include('essentials::layouts.partials.sidebar')
        @endif --}}
        
        {{-- @if(Module::has('Woocommerce'))
          @include('woocommerce::layouts.partials.sidebar')
        @endif --}}
      </ul>

      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>