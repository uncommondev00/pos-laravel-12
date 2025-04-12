<div class="btn-group">
    <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
            data-toggle="dropdown" aria-expanded="false">
        @lang("messages.actions")
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" role="menu">
        @can('sell.view')
            <li>
                <a href="#" 
                   class="btn-modal" 
                   data-container=".view_modal" 
                   data-href="{{ route('sell-return.show', $row->parent_sale_id) }}">
                    <i class="fa fa-external-link"></i> @lang("messages.view")
                </a>
            </li>
            <li>
                <a href="{{ route('sell-return.add', $row->parent_sale_id) }}">
                    <i class="fa fa-edit"></i> @lang("messages.edit")
                </a>
            </li>
            <li>
                <a href="#" 
                   class="print-invoice" 
                   data-href="{{ route('sell-return.printInvoice', $row->id) }}">
                    <i class="fa fa-print"></i> @lang("messages.print")
                </a>
            </li>
        @endcan
    </ul>
</div>